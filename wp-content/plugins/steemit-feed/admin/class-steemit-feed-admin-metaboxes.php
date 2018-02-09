<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @since      	1.1.0
 * @package 	Steemit-Feed
 * @subpackage 	Steemit-Feed/admin
 */
 
class Steemit_Feed_Admin_Metaboxes {
		
	/**
	 * The ID of this plugin.
	 *
	 * @since 		1.1.0
	 * @access 		private
	 * @var 		string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;
	
	/**
	 * The version of this plugin.
	 *
	 * @since 		1.1.0
	 * @access 		private
	 * @var 		string 			$version 			The current version of this plugin.
	 */
	private $version;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		1.1.0
	 * @param 		string 			$plugin_name 		The name of this plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

	}
	
	/**
	 * Includes the JavaScript necessary to control the toggling of the tabs in the
	 * meta box that's represented by this class.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_admin_scripts() {
	 
		if ( 'steemitfeed' === get_current_screen()->id ) {
	 
			wp_enqueue_script(
				$this->plugin_name . '-tabs',
				plugins_url( 'steemit-feed/admin/js/steemit-feed-admin-tabs.js' ),
				array( 'jquery' ),
				$this->version
			);
	 
		}
	 
	}
	
	/**
	 * Meta box setup function for Feed.
	 *
	 * @since 	1.1.0
	 */
	public function steemitfeed_post_meta_boxes_setup() {
	
		/* Add meta boxes on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', array( $this, 'steemitfeed_add_feed_options' ) );
		
		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', array( $this, 'steemitfeed_save_post_meta' ) );

	}	
		
	/**
	 * Create one or more meta boxes to be displayed on the post editor screen - Feed
	 *
	 * @since 	1.1.0
	 */
	public function steemitfeed_add_feed_options() {
	
		add_meta_box(
			'steemit_feed_feed_options', 
			apply_filters( $this->plugin_name . '-metabox-title-feed-options', esc_html__( 'Feed Options', 'steemit-feed' ) ),
			array( $this, 'steemitfeed_metabox'),						
			'steemitfeed', // post type												
			'normal',											
			'default',
			array(
				'file' => 'feed-options'
			)								
		);
		
	}
	
	/**
	 * Calls a metabox file specified in the add_meta_box args - Feed
	 *
	 * @since 	1.1.0
	 * @return 	void
	 */
	public function steemitfeed_metabox( $post, $params ) {

		if ( ! is_admin() ) { return; }
		if ( 'steemitfeed' !== $post->post_type ) { return; }

		if ( ! empty( $params['args']['classes'] ) ) {

			$classes = 'repeater ' . $params['args']['classes'];

		}
		
		include( plugin_dir_path( __FILE__ ) . 'partials/steemit-feed-admin-metabox-' . $params['args']['file'] . '-navigation.php' );
		include( plugin_dir_path( __FILE__ ) . 'partials/steemit-feed-admin-metabox-' . $params['args']['file'] . '.php' );

	}
	
	/**
	 * Check each nonce. If any don't verify, $nonce_check is increased.
	 * If all nonces verify, returns 0.
	 *
	 * @since 		1.1.0
	 * @access 		public
	 * @return 		int 		The value of $nonce_check
	 */
	private function check_nonces( $posted, $feed_type ) {

		$nonces 		= array();
		$nonce_check 	= 0;

		$nonces[] 		= $feed_type.'_options';

		foreach ( $nonces as $nonce ) {

			if ( ! isset( $posted[$nonce] ) ) { $nonce_check++; }
			if ( isset( $posted[$nonce] ) && ! wp_verify_nonce( $posted[$nonce], $this->plugin_name ) ) { $nonce_check++; }

		}

		return $nonce_check;

	}

	/**
	 * Returns an array of the all the metabox fields and their respective types - Feed
	 *
	 * @since 		1.1.0
	 * @access 		public
	 * @return 		array 		Metabox fields and types
	 */
	private function get_feed_metabox_fields() {

		$fields = array();
		
		// General settings
		$fields[] = array( 'feed-class', 'text' );
		$fields[] = array( 'feed-referral', 'text' );
		$fields[] = array( 'feed-load-asynch', 'radio' );
		$fields[] = array( 'feed-font-awesome', 'select' );
		
		// Data source
		$fields[] = array( 'feed-author', 'text' );
		$fields[] = array( 'feed-tags-include', 'textarea' );
		$fields[] = array( 'feed-tags-exclude', 'textarea' );
		$fields[] = array( 'feed-posts-exclude', 'textarea' );
		
		// Layout
		$fields[] = array( 'feed-layout', 'radio' );
		$fields[] = array( 'feed-scrollbar', 'radio' );
		$fields[] = array( 'feed-maxheight', 'text' );
		$fields[] = array( 'feed-breakpoint', 'text' );
		$fields[] = array( 'feed-title-font-size', 'text' );
		$fields[] = array( 'feed-body-font-size', 'text' );
		$fields[] = array( 'feed-tags-font-size', 'text' );
		
		// Image settings
		$fields[] = array( 'feed-show-images', 'radio' );
		$fields[] = array( 'feed-image-size', 'radio' );
		$fields[] = array( 'feed-fallback-image', 'text' );	
		
		// Detail box
		$fields[] = array( 'feed-item-title', 'radio' );
		$fields[] = array( 'feed-title-limit', 'text' );
		$fields[] = array( 'feed-item-introtext', 'radio' );
		$fields[] = array( 'feed-introtext-limit', 'text' );
		$fields[] = array( 'feed-item-date', 'radio' );
		$fields[] = array( 'feed-item-category', 'radio' );
		$fields[] = array( 'feed-item-tags', 'radio' );
		$fields[] = array( 'feed-item-author', 'radio' );
		$fields[] = array( 'feed-item-author-rep', 'radio' );
		$fields[] = array( 'feed-item-reward', 'radio' );
		$fields[] = array( 'feed-item-votes', 'radio' );
		$fields[] = array( 'feed-item-comments-count', 'radio' );
										
		// Pagination
		//$fields[] = array( 'feed-pagination-type', 'select' );
		$fields[] = array( 'feed-initial-items', 'text' );
		$fields[] = array( 'feed-items-per-page', 'text' );
		$fields[] = array( 'feed-additional-pages', 'text' );
		//$fields[] = array( 'feed-pagination-color', 'color' );
		//$fields[] = array( 'feed-pagination-text-color', 'color' );
		//$fields[] = array( 'feed-pagination-border-radius', 'text' );	
						
		return $fields;

	}
	
	/**
	 * Sanitizes input.
	 *
	 * @since 		1.1.0
	 * @access 		public
	 * @return 		array 		Metabox fields and types
	 */
	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }

		$return 	= '';
		$sanitizer 	= new Steemit_Feed_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	}
	
	/**
	 * Saves metabox data - Feed
	 *
	 * @since 	1.1.0
	 * @access 	public
	 * @param 	int 		$post_id 		The post ID
	 * @param 	object 		$object 		The post object
	 * @return 	void
	 */
	public function steemitfeed_save_post_meta( $post_id, $object = null) {

		//wp_die( '<pre>' . print_r( $_POST ) . '</pre>' );

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return $post_id; }
		if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		
		$safe_post_type = $this->sanitizer( 'text', $_POST['post_type'] );
		if ( $safe_post_type !== 'steemitfeed' ) { return $post_id; }

		$nonce_check = $this->check_nonces( $_POST, $feed_type = 'feed' );

		if ( 0 < $nonce_check ) { return $post_id; }

		$metas = $this->get_feed_metabox_fields();

		foreach ( $metas as $meta ) {

			$name = $this->sanitizer( 'text', $meta[0] );
			$type = $meta[1];
			
			if (is_array($_POST[$name])) {
				$new_value = $this->sanitizer( 'array', $_POST[$name] );
			} else {
				$new_value = $this->sanitizer( $type, $_POST[$name] );
			}

			update_post_meta( $post_id, $name, $new_value );

		} // foreach

	}
	
}
