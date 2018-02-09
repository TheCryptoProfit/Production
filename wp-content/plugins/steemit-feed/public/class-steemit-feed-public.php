<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since		1.1.0
 * @package    	Steemit-Feed
 * @subpackage 	Steemit-Feed/public
 */
 
class Steemit_Feed_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.0
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version; 
		
		// Add shortcode
		add_shortcode( 'steemitfeed', array( $this, 'steemitfeed_display' ) );
	
	}
	
	/**
	 * Display the feed.
	 *
	 * @since    1.1.0
	 */
	public function steemitfeed_display($atts, $content = null ) {
		
		// Get feed data
		$atts = shortcode_atts(array('id' => ""), $atts, 'steemitfeed');
		$feed_id = $atts['id'];
		$feed_options = get_post_meta( $feed_id, '', false );
		$steemitfeed_id = 'steemitfeed-'.$feed_id;
		$steemitfeed_wrapper_id = 'steemitfeed-wrapper-'.$feed_id;
		$dynamicCss = new Steemit_Feed_CSS();
		
		// Start inline css
		$custom_css = '';
					
		// Font awesome
		if ($feed_options['feed-font-awesome'][0] == 'yes')
		{
			wp_enqueue_style( $this->plugin_name.'_fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', array(), $this->version, 'all' );
		}
							
		// Check if we have a username
		if (!isset($feed_options['feed-author'][0]) || !$feed_options['feed-author'][0])
		{
			$html = '<div class="mn_steem_feed_error"><p>'.__('Please enter a username on the Steemit Feed plugin Settings page.', 'steemit-feed' ).'</p></div>';
		}
		else
		{
			// Start html
			$html = '<div id="'.$steemitfeed_wrapper_id.'" class="steemitfeed-wrapper '.$feed_options['feed-class'][0].'">';
				
				// Loader
				if ($feed_options['feed-load-asynch'][0] == 'yes')
				{
					$html .= '<div id="steemitfeed-loader-'.$feed_id.'" class="steemit-feed-loader"></div>';
				}
				
				// Header
				/*if ($feed_options['feed-header'][0] == 'yes') 			
				{ 
					$html .= '<div class="steemitfeed_header">';
						
						$html .= $this->feed_create_header($atts);
										
					$html .= '</div>';
				}*/
						
				// Feed display
				$html .= $this->feed_create_items_wrapper($atts);
				
				// Pagination display
				if (isset($feed_options['feed-additional-pages'][0]) 
				&& ((int)$feed_options['feed-additional-pages'][0] > 0
				|| $feed_options['feed-additional-pages'][0] === '-1'))
				{
					$style = '';
					if ($feed_options['feed-load-asynch'][0] == 'yes')
					{
						$style = 'style="display: none;"';	
					}
					$html .= '<div id="sf-pagination-'.$feed_id.'" class="sf-pagination" '.$style.'>';
					$html .= '<a href="#" class="sf-btn" data-page="1">';
					$html .= '<span class="sf-page-text">'. __('Load more', 'steemit-feed' ).'</span>';
					$html .= '<span class="sf-page-end">'. __('No more posts', 'steemit-feed' ).'</span>';
					$html .= '<span id="steemitfeed-page-loader-'.$feed_id.'" class="steemit-feed-page-loader"></span>';
					$html .= '</a>';
					$html .= '</div>';
					
					$sf_ajaxurl = admin_url( 'admin-ajax.php' );

					$pagination_script = "
						<script type='text/javascript'>
						(function ($) {
							$(document).ready(function() 
							{
								// Options
								var feedId = ".$feed_id.";
								var sf_ajaxurl = '".$sf_ajaxurl."';
								
								// Initialize spinner
								var createPaginationSpinner = function()
								{ 
									var spinner_options = {
									  lines: 8,
									  length: 3,
									  width: 3,
									  radius: 3,
									  corners: 1,
									  rotate: 0,
									  direction: 1,
									  color: '#333',
									  speed: 1,
									  trail: 52,
									  shadow: false,
									  hwaccel: false,
									  className: 'spinner',
									  zIndex: 2e9,
									  top: '50%',
									  left: '50%'
									};
									$('#steemitfeed-page-loader-'+feedId+'').append(new Spinner(spinner_options).spin().el);
									
									return;
								}
								
								// Pagination button click
								$('#sf-pagination-'+feedId+'').on('click', 'a.sf-btn', function(e) {
									e.preventDefault();
									
									if (!$(this).hasClass('sf-loading') && !$(this).hasClass('sf-disabled'))
									{
										$(this).addClass('sf-loading');
										
										// Get page number
										var page = $('#sf-pagination-'+feedId+' .sf-btn').attr('data-page');
										page = parseInt(page, 10);
										
										// Get last permlink
										var permlink = $('#".$steemitfeed_id." .sf-li:last').data('permlink');
									
										// Check if spinner has initialized
										if (!$('#steemitfeed-page-loader-'+feedId+' .spinner').length)
											createPaginationSpinner();
										
										// Show loader
										$('#sf-pagination-'+feedId+' .sf-page-text').hide();
										$('#steemitfeed-page-loader-'+feedId+'').css('display', 'inline-block');
									
										// Load feed										
										$.ajax({
											type : 'post',
											url : sf_ajaxurl,
											data : {
												action			: 'feed_create_items', 
												feedid 			: ''+feedId+'',
												'ajax'			: '1',
												'permlink'		: ''+permlink+'',
												'page'			: ''+page+''
											}
										}).done(function (response) 
										{
											$('#sf-pagination-'+feedId+' .sf-page-text').css('display', 'inline-block');
											$('#steemitfeed-page-loader-'+feedId+'').hide();
											var next_page = page + 1;
											next_page = parseInt(next_page, 10);
											$('#sf-pagination-'+feedId+' .sf-btn').attr('data-page', next_page);
											$('#sf-pagination-'+feedId+' .sf-btn').removeClass('sf-loading');
											if (!$.trim(response))
											{
												$('#sf-pagination-'+feedId+' .sf-btn').addClass('sf-disabled');
												$('#sf-pagination-'+feedId+' .sf-page-text').hide();
												$('#sf-pagination-'+feedId+' .sf-page-end').css('display', 'inline-block');
											} 
											else
											{
												$('#".$steemitfeed_id."').append(response);
											}
											
										}).fail(function (jqXHR, exception) 
										{
											var msg = '';
											if (jqXHR.status === 0) {
												msg = 'No connection. Verify Network.';
											} else if (jqXHR.status == 404) {
												msg = 'Requested page not found. [404]';
											} else if (jqXHR.status == 500) {
												msg = 'Internal Server Error [500].';
											} else if (exception === 'parsererror') {
												msg = 'Requested JSON parse failed.';
											} else if (exception === 'timeout') {
												msg = 'Time out error.';
											} else if (exception === 'abort') {
												msg = 'Ajax request aborted.';
											} else {
												msg = 'Uncaught Error.' + jqXHR.responseText;
											}
											$('#sf-pagination-'+feedId+' .sf-page-text').css('display', 'inline-block');
											$('#steemitfeed-page-loader-'+feedId+'').hide();
											$('#sf-pagination-'+feedId+' .sf-btn').removeClass('sf-loading');
											$('#".$steemitfeed_id."').append(response);
										});
									}
								});
							});
							})(jQuery)
						</script>
					";	
					
					$html .= $pagination_script;
				}
			
			$html .= '</div>';
		
			// Responsive layout		
			$custom_css .= $dynamicCss->getResponsiveCss($feed_options, $feed_id);
		
			// Add inline css
			wp_enqueue_style( $this->plugin_name.'_custom_style', plugin_dir_url( __FILE__ ) . 'css/steemit-feed-public-custom.css', array(), $this->version, 'all' );
			wp_add_inline_style( $this->plugin_name.'_custom_style', $custom_css );
							
			// Javascript operations
			if ($feed_options['feed-load-asynch'][0] == 'yes')
			{
				$javascript = new Steemit_Feed_Javascript();
				$html .= $javascript->getMainScript($feed_id);
			}
		}
				
		return $html;
	}
		
	/**
	 * Creates the header.
	 *
	 * @since    1.1.0
	 */
	/*public function feed_create_header($atts) {
		
		$atts = shortcode_atts(array('id' => ""), $atts, 'steemitfeed');
			
		// Get header from file
		ob_start();
			include( plugin_dir_path( __FILE__ ) . 'partials/steemit-feed-public-header.php' );		
			$header = ob_get_clean();
			
		return $header;	
	}*/
		
	/**
	 * Creates the items wrapper.
	 *
	 * @since    1.1.0
	 */
	public function feed_create_items_wrapper($atts) {

		// Get feed data
		$atts = shortcode_atts(array('id' => ""), $atts, 'steemitfeed');
		$feed_id = $atts['id'];
		$feed_options = get_post_meta( $feed_id, '', false );
		$steemitfeed_id = 'steemitfeed-'.$feed_id;

		// Create items wrapper
		$html = '<div id="'.$steemitfeed_id.'" class="sf-list sf-'.$feed_options['feed-layout'][0].'">';
		
			// Load items
			if (!isset($feed_options['feed-load-asynch'][0]) || $feed_options['feed-load-asynch'][0] == 'no')
			{
				// Synchronous display
				$html .= $this->feed_create_items($atts);
			}
			else
			{
				// Asynchronous display
				$sf_ajaxurl = admin_url( 'admin-ajax.php' );
	
				$asynch_script = "
				<script type='text/javascript'>
					(function ($) {
						$(document).ready(function() 
						{
							var sf_ajaxurl = '".$sf_ajaxurl."';
							var feedId = ".$feed_id.";
							
							$('#sf-pagination-'+feedId+' .sf-btn').addClass('sf-loading');

							// Load feed										
							$.ajax({
								type : 'post',
								url : sf_ajaxurl,
								data : {
									action			: 'feed_create_items', 
									feedid 			: ''+feedId+'',
									'ajax'			: '1' 
								}
							}).done(function (response) 
							{
								$('#steemitfeed-loader-'+feedId+'').hide();
								$('#sf-pagination-'+feedId+'').show();
								$('#sf-pagination-'+feedId+' .sf-btn').removeClass('sf-loading');
								$('#".$steemitfeed_id."').html(response);
								
							}).fail(function (jqXHR, exception) 
							{
								var msg = '';
								if (jqXHR.status === 0) {
									msg = 'No connection. Verify Network.';
								} else if (jqXHR.status == 404) {
									msg = 'Requested page not found. [404]';
								} else if (jqXHR.status == 500) {
									msg = 'Internal Server Error [500].';
								} else if (exception === 'parsererror') {
									msg = 'Requested JSON parse failed.';
								} else if (exception === 'timeout') {
									msg = 'Time out error.';
								} else if (exception === 'abort') {
									msg = 'Ajax request aborted.';
								} else {
									msg = 'Uncaught Error.' + jqXHR.responseText;
								}
								$('#steemitfeed-loader-'+feedId+'').hide();
								$('#sf-pagination-'+feedId+' .sf-btn').removeClass('sf-loading');
								$('#".$steemitfeed_id."').html(response);
							});
							
						});
					})(jQuery)
				</script>
				";
			}
		
		$html .= '</div>';
		
		$html .= $asynch_script;
		
		return $html;
	}
	
	/**
	 * Creates the items.
	 *
	 * @since    1.1.0
	 */
	public function feed_create_items($atts) {
	
		// Get ajax variables
		$safe_ajax = (int)$_POST['ajax'];
		if ($safe_ajax == '1')
		{
			$ajax = true;	
		}
		else
		{
			$ajax = false;	
		}

		if ($ajax)
		{
			$feed_id = (int)$_POST['feedid'];
			$permlink = $_POST['permlink'];
			$page = (int)$_POST['page'];
		}
		else
		{
			$atts = shortcode_atts(array('id' => ""), $atts, 'steemitfeed');
			$feed_id = $atts['id'];
			$permlink = false;
			$page = false;
		}
		$feed_options = get_post_meta( $feed_id, '', false );
		$utilities = new Steemit_Feed_Utilities();
		$dataSource = new Steemit_Feed_Data();
	
		// Referral code
		$referral_code = $feed_options['feed-referral'][0] ? '?r='.$feed_options['feed-referral'][0] : '';
			
		// Images
		$feed_show_images = $feed_options['feed-show-images'][0];
		$feed_image_size = $feed_options['feed-image-size'][0];
		$feed_fallback_image = $feed_options['feed-fallback-image'][0];	
		
		// Detail box options
		$feed_title_limit = $feed_options['feed-title-limit'][0];
		$feed_introtext_limit = $feed_options['feed-introtext-limit'][0];
		$feed_strip_html = $feed_options['feed-strip-html'][0];
		$feed_date_format = $feed_options['feed-date-format'][0];

		$this->detailBox = $feed_options['feed-detail-box'][0];
		$this->detailBoxTitle = $feed_options['feed-item-title'][0];
		$this->detailBoxIntrotext = $feed_options['feed-item-introtext'][0];
		$this->detailBoxDate = $feed_options['feed-item-date'][0];
		$this->detailBoxCategory = $feed_options['feed-item-category'][0];
		$this->detailBoxTags = $feed_options['feed-item-tags'][0];
		$this->detailBoxAuthor = $feed_options['feed-item-author'][0];
		$this->detailBoxAuthorRep = $feed_options['feed-item-author-rep'][0];
		$this->detailBoxReward = $feed_options['feed-item-reward'][0];
		$this->detailBoxVotes = $feed_options['feed-item-votes'][0];
		$this->detailBoxComments = $feed_options['feed-item-comments-count'][0];
	
		// Query items
		$queryItems = $dataSource->feed_query_items($feed_options, $ajax, $permlink, $page);

		foreach ($queryItems as $key => $item) 
		{
			// Strip slashes		
			$item->title = trim(stripslashes( $item->title ));
			$item->body  = trim(stripslashes( $item->body ));
			
			// Trim title
			$item->short_title = wp_trim_words( $item->title, (int)$feed_title_limit);
			
			// Trim body
			$item->short_body = wp_trim_words( $item->body, (int)$feed_introtext_limit);
			
			// Format date
			$item->formatted_date = $utilities->sf_time_since($item->created);
			
			// Author reputation
			$item->author_reputation = $utilities->sf_format_reputation($item->author_reputation);
			
			// Reward
			$total_payout_value = round((float)$item->total_payout_value, 2);
			$curator_payout_value = round((float)$item->curator_payout_value, 2);
			$pending_payout_value = round((float)$item->pending_payout_value, 2);
			$total_pending_payout_value = round((float)$item->total_pending_payout_value, 2);
			$item->total_reward = number_format(round(($total_payout_value + $curator_payout_value + $pending_payout_value + $total_pending_payout_value), 2), 2);
			
			// Votes
			$item->votes = $item->net_votes;
			
			// Replies count
			$item->replies_count = $utilities->sf_replies_count($item->author, $item->permlink);
			
			// Metadata
			$metadata = json_decode($item->json_metadata, false);
		
			// Tags
			$item->tags = $metadata->tags;
			array_shift($item->tags);
			
			// Image
			if (isset($metadata->image))
			{
				$raw_image = $metadata->image;
				if (array_key_exists('0', $raw_image))
				{
					$item->image = 'https://steemitimages.com/'.$feed_image_size.'/'.$raw_image[0];
				}
			}
			else
			{
				if ($feed_fallback_image)
				{
					$item->image = $feed_fallback_image;
				}
			}
												
			$items[] = $item;
		}

		// Get feed from file
		ob_start();
			include( plugin_dir_path( __FILE__ ) . 'partials/steemit-feed-public-items.php' );		
			$items = ob_get_clean();		
		
		if ($ajax) {
			echo $items;
			wp_die();
		} else {
			return $items;	
		}
	}
				
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/steemit-feed-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_scripts() {
		
		// Spinner
		wp_enqueue_script( $this->plugin_name.'_spin', plugin_dir_url( __FILE__ ) . 'js/spin.min.js', array( 'jquery' ), $this->version, false );
		
	}

}
