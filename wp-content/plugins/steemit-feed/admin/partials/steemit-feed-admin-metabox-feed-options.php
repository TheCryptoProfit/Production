<?php
/**
 * Provide the view for a metabox
 *
 * @since 		1.1.0
 * @package 	Steemit-Feed
 * @subpackage 	Steemit-Feed/admin/partials
 */

wp_nonce_field( $this->plugin_name, 'feed_options' );

$post_id = get_the_ID();

?>
<div id="steemitfeed-admin-metabox-options">
<?php

	// General settings ?>
	<div id="steemitfeed-admin-metabox-general-settings" class="inside">
	<table class="form-table">
	<tbody>
	<?php
	
		// Grid class - General settings
		$atts 					= array();
		$atts['description'] 	= 'An optional class to be applied to the feed container.';
		$atts['id'] 			= 'feed-class';
		$atts['label'] 			= 'Feed class';
		$atts['name'] 			= 'feed-class';
		$atts['placeholder'] 	= '';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
		
		// Referral username - General settings
		$atts 					= array();
		$atts['description'] 	= '(Beta) Adds a referral username to posts urls.';
		$atts['id'] 			= 'feed-referral';
		$atts['label'] 			= 'Referral username';
		$atts['name'] 			= 'feed-referral';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
		
		// Load asynchronously - General settings
		$atts 					= array();
		$atts['description'] 	= 'Loads feed asynchronously after the page has loaded.';
		$atts['id'] 			= 'feed-load-asynch';
		$atts['label'] 			= 'Load asynchronously';
		$atts['name'] 			= 'feed-load-asynch';
		$atts['type'] 			= 'radio';
		$atts['value'] 			= 'no';
		$atts['selections']     = array(
			array(
			  'value'       => 'yes',
			  'label'       => 'Yes'
			),
			array(
			  'value'       => 'no',
			  'label'       => 'No'
			)
		);
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
		?></tr><?php
				
		// Load FontAwesome - General settings
		$atts 					= array();
		$atts['description'] 	= 'Disable if you are already using the FontAwesome library in your template.';
		$atts['id'] 			= 'feed-font-awesome';
		$atts['label'] 			= 'Load FontAwesome';
		$atts['name'] 			= 'feed-font-awesome';
		$atts['type'] 			= 'radio';
		$atts['value'] 			= 'yes';
		$atts['selections']     = array(
			array(
			  'value'       => 'yes',
			  'label'       => 'Yes'
			),
			array(
			  'value'       => 'no',
			  'label'       => 'No'
			)
		);
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
		?></tr><?php
			
	?>
	</tbody>
	</table>
	</div>
	<?php 

	// Data source ?>
	<div id="steemitfeed-admin-metabox-data-source" class="inside hidden">
			
		<?php
		// Dynamic source ?>
		<div id="steemitfeed-admin-metabox-data-source-dynamic" class="">
		
			<?php
			// Items type: Posts ?>
			<div id="steemitfeed-admin-metabox-data-source-posts" class="">
			<table class="form-table">
			<tbody>
			<?php
				
				/*?><tr><th colspan="2"><h4><?php esc_html_e( 'Author', 'steemit-feed' ); ?></h4></th></tr><?php*/
					
				// Authors - Data source
				$atts 					= array();
				$atts['description'] 	= 'Enter a Steem author username.';
				$atts['id'] 			= 'feed-author';
				$atts['label'] 			= 'Author';
				$atts['name'] 			= 'feed-author'; // [] for array
				$atts['type'] 			= 'text';
				$atts['value'] 			= '';
				
				if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
					$atts['value'] = get_post_meta($post_id, $atts['id'], true);
				}
				
				apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
				
				?><tr><?php
				include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
				?></tr><?php								
				
				/*?><tr><th colspan="2"><h4><?php esc_html_e( 'Tags', 'steemit-feed' ); ?></h4></th></tr><?php*/
				
				// Include Tags - Data source
				$atts 					= array();
				$atts['description'] 	= 'Separate tags with commas.';
				$atts['id'] 			= 'feed-tags-include';
				$atts['label'] 			= 'Include tags';
				$atts['name'] 			= 'feed-tags-include';
				$atts['type'] 			= 'textarea';
				$atts['cols'] 			= '50';
				$atts['rows'] 			= '4';
				$atts['value'] 			= '';
				
				if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
					$atts['value'] = get_post_meta($post_id, $atts['id'], true);
				}
				
				apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
				
				?><tr><?php
				include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-textarea.php' );
				?></tr><?php
				
				// Exclude Tags - Data source
				$atts 					= array();
				$atts['description'] 	= 'Separate tags with commas.';
				$atts['id'] 			= 'feed-tags-exclude';
				$atts['label'] 			= 'Exclude tags';
				$atts['name'] 			= 'feed-tags-exclude';
				$atts['type'] 			= 'textarea';
				$atts['cols'] 			= '50';
				$atts['rows'] 			= '4';
				$atts['value'] 			= '';
				
				if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
					$atts['value'] = get_post_meta($post_id, $atts['id'], true);
				}
				
				apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
				
				?><tr><?php
				include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-textarea.php' );
				?></tr><?php	
				
				// Exclude Posts - Data source
				$atts 					= array();
				$atts['description'] 	= 'Enter each post permlink on a new line.';
				$atts['id'] 			= 'feed-posts-exclude';
				$atts['label'] 			= 'Exclude posts';
				$atts['name'] 			= 'feed-posts-exclude';
				$atts['type'] 			= 'textarea';
				$atts['cols'] 			= '50';
				$atts['rows'] 			= '4';
				$atts['value'] 			= '';
				
				if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
					$atts['value'] = get_post_meta($post_id, $atts['id'], true);
				}
				
				apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
				
				?><tr><?php
				include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-textarea.php' );
				?></tr><?php	
								
			?>
			</tbody>
			</table>
			</div>
											
		</div>
	
	</div>
	<?php
	
	// Layout ?>
	<div id="steemitfeed-admin-metabox-layout" class="inside hidden">
	<table class="form-table">
	<tbody>
	<?php
		
		// Preset layouts
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-layout';
		$atts['label'] 			= 'Preset layouts';
		$atts['name'] 			= 'feed-layout';
		$atts['type'] 			= 'radio';
		$atts['value'] 			= 'list1';
		$atts['selections']     = array(
			array(
			  'value'       => 'list1',
			  'label'       => 'List 1',
			  'image' 		=> 'feed/list1.jpg',
			),
			array(
			  'value'       => 'list2',
			  'label'       => 'List 2',
			  'image' 		=> 'feed/list2.jpg',
			)
		);
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-feed-layouts.php' );
		?></tr><?php
		
		?><tr><th colspan="2"><hr /></th></tr><?php
		
		// Enable scrollbar - Layout settings
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-scrollbar';
		$atts['label'] 			= 'Enable scrollbar';
		$atts['name'] 			= 'feed-scrollbar';
		$atts['type'] 			= 'radio';
		$atts['value'] 			= 'no';
		$atts['selections']     = array(
			array(
			  'value'       => 'yes',
			  'label'       => 'Yes'
			),
			array(
			  'value'       => 'no',
			  'label'       => 'No'
			)
		);
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
		?></tr><?php
		
		// Max height - Layout settings
		$atts 					= array();
		$atts['description'] 	= 'Maximum height for feed box. Scrollbar must be enabled.';
		$atts['id'] 			= 'feed-maxheight';
		$atts['label'] 			= 'Max height';
		$atts['name'] 			= 'feed-maxheight';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '400';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
		
		// Responsive breakpoint - Layout settings
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-breakpoint';
		$atts['label'] 			= 'Responsive breakpoint';
		$atts['name'] 			= 'feed-breakpoint';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '400';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
		
		// Title font size - Layout settings
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-title-font-size';
		$atts['label'] 			= 'Title font size';
		$atts['name'] 			= 'feed-title-font-size';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '18';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
		
		// Body font size - Layout settings
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-body-font-size';
		$atts['label'] 			= 'Body font size';
		$atts['name'] 			= 'feed-body-font-size';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '15';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
		
		// Tags font size - Layout settings
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-tags-font-size';
		$atts['label'] 			= 'Tags font size';
		$atts['name'] 			= 'feed-tags-font-size';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '14';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
		
	?>
	</tbody>
	</table>
	</div>
	<?php

	// Image settings ?>
	<div id="steemitfeed-admin-metabox-image-settings" class="inside hidden">
	<table class="form-table">
	<tbody>
	<?php
	
		// Show images - Image settings
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-show-images';
		$atts['label'] 			= 'Show images';
		$atts['name'] 			= 'feed-show-images';
		$atts['type'] 			= 'radio';
		$atts['value'] 			= 'yes';
		$atts['selections']     = array(
			array(
			  'value'       => 'yes',
			  'label'       => 'Yes'
			),
			array(
			  'value'       => 'no',
			  'label'       => 'No'
			)
		);
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
		?></tr><?php
		
		// Image size
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-image-size';
		$atts['label'] 			= 'Image size';
		$atts['name'] 			= 'feed-image-size';
		$atts['type'] 			= 'radio';
		$atts['value'] 			= '0x0';
		$atts['selections']     = array(
			array(
			  'value'       => '256x512',
			  'label'       => 'Small',			
			),
			array(
			  'value'       => '0x0',
			  'label'       => 'Large'
			)
		);
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
		?></tr><?php
					
		// Fallback image - Image settings
		$atts 					= array();
		$atts['description'] 	= 'Absolute url. The fallback image will be displayed if an item does not have an image.';
		$atts['id'] 			= 'feed-fallback-image';
		$atts['label'] 			= 'Fallback image';
		$atts['name'] 			= 'feed-fallback-image';
		$atts['placeholder'] 	= 'Path to image';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
	
	?>
	</tbody>
	</table>
	</div>
	<?php
	
	// Detail box settings ?>
	<div id="steemitfeed-admin-metabox-detail-box" class="inside hidden">
	
		<div id="steemitfeed-admin-metabox-detail-box-general">
		<table class="form-table">
		<tbody>
		<?php
			
			// Title
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-title';
			$atts['label'] 			= 'Show title';
			$atts['name'] 			= 'feed-item-title';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'yes';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
			
			// Title limit - Item settings
			$atts 					= array();
			$atts['description'] 	= 'Word limit for title.';
			$atts['id'] 			= 'feed-title-limit';
			$atts['label'] 			= 'Title limit';
			$atts['name'] 			= 'feed-title-limit';
			$atts['placeholder'] 	= '';
			$atts['type'] 			= 'text';
			$atts['value'] 			= '8';
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
			?></tr><?php
		
			// Body text
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-introtext';
			$atts['label'] 			= 'Show body text';
			$atts['name'] 			= 'feed-item-introtext';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'yes';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
			
			// Body text limit - Item settings
			$atts 					= array();
			$atts['description'] 	= 'Word limit for body text.';
			$atts['id'] 			= 'feed-introtext-limit';
			$atts['label'] 			= 'Body text limit';
			$atts['name'] 			= 'feed-introtext-limit';
			$atts['placeholder'] 	= '';
			$atts['type'] 			= 'text';
			$atts['value'] 			= '15';
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
			?></tr><?php
					
			// Date
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-date';
			$atts['label'] 			= 'Show date';
			$atts['name'] 			= 'feed-item-date';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'yes';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
									
			// Category
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-category';
			$atts['label'] 			= 'Show category';
			$atts['name'] 			= 'feed-item-category';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'yes';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
			
			// Tags
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-tags';
			$atts['label'] 			= 'Show tags';
			$atts['name'] 			= 'feed-item-tags';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'yes';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
						
			// Author
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-author';
			$atts['label'] 			= 'Show author';
			$atts['name'] 			= 'feed-item-author';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'yes';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
			
			// Author reputation
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-author-rep';
			$atts['label'] 			= 'Show author reputation';
			$atts['name'] 			= 'feed-item-author-rep';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'yes';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
			
			// Reward
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-reward';
			$atts['label'] 			= 'Show reward';
			$atts['name'] 			= 'feed-item-reward';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'no';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
			
			// Votes
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-votes';
			$atts['label'] 			= 'Show votes';
			$atts['name'] 			= 'feed-item-votes';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'no';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
			
			// Comments count
			$atts 					= array();
			$atts['description'] 	= '';
			$atts['id'] 			= 'feed-item-comments-count';
			$atts['label'] 			= 'Show comments count';
			$atts['name'] 			= 'feed-item-comments-count';
			$atts['type'] 			= 'radio';
			$atts['value'] 			= 'yes';
			$atts['selections']     = array(
				array(
				  'value'       => 'yes',
				  'label'       => 'Yes'
				),
				array(
				  'value'       => 'no',
				  'label'       => 'No'
				)
			);
			
			if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
				$atts['value'] = get_post_meta($post_id, $atts['id'], true);
			}
			
			apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
			
			?><tr><?php
			include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-radio.php' );
			?></tr><?php
						
		?>
		</tbody>
		</table>
		</div>
		
	</div>
	<?php 
		
	// Pagination ?>
	<div id="steemitfeed-admin-metabox-pagination" class="inside hidden">
	<table class="form-table">
	<tbody>
	<?php
		/*
		// Pagination type - Pagination
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-pagination-type';
		$atts['label'] 			= 'Pagination type';
		$atts['name'] 			= 'feed-pagination-type';
		$atts['type'] 			= 'select';
		$atts['value'] 			= '';
		$atts['selections']     = array(
			array(
			  'value'       => '0',
			  'label'       => 'None'
			),
			array(
			  'value'       => '2',
			  'label'       => 'Load more button'
			)
		);
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-select.php' );
		?></tr><?php
	
		?><tr><th colspan="2"><hr /></th></tr><?php
		*/			
		// Initial Items - Pagination
		$atts 					= array();
		$atts['description'] 	= 'The amount of posts to be initially loaded in the feed.';
		$atts['id'] 			= 'feed-initial-items';
		$atts['label'] 			= 'Initial posts';
		$atts['name'] 			= 'feed-initial-items';
		$atts['placeholder'] 	= '';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '5';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
		
		// Items per page - Pagination
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-items-per-page';
		$atts['label'] 			= 'Items per page';
		$atts['name'] 			= 'feed-items-per-page';
		$atts['placeholder'] 	= '';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '5';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
				
		// Additional pages - Pagination
		$atts 					= array();
		$atts['description'] 	= 'Additional pages after initial posts. Enter -1 for no limit.';
		$atts['id'] 			= 'feed-additional-pages';
		$atts['label'] 			= 'Additional pages';
		$atts['name'] 			= 'feed-additional-pages';
		$atts['placeholder'] 	= '';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '0';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php		
		/*				
		?><tr><th colspan="2"><hr /></th></tr><?php
		
		// Theme color - Pagination
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-pagination-color';
		$atts['label'] 			= 'Theme color';
		$atts['name'] 			= 'feed-pagination-color';
		$atts['placeholder'] 	= '';
		$atts['type'] 			= 'color';
		$atts['value'] 			= '#e96d51';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-color.php' );
		?></tr><?php
		
		// Text color - Pagination
		$atts 					= array();
		$atts['description'] 	= '';
		$atts['id'] 			= 'feed-pagination-text-color';
		$atts['label'] 			= 'Text color';
		$atts['name'] 			= 'feed-pagination-text-color';
		$atts['placeholder'] 	= '';
		$atts['type'] 			= 'color';
		$atts['value'] 			= '#ffffff';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-color.php' );
		?></tr><?php
		
		// Border radius - Pagination
		$atts 					= array();
		$atts['description'] 	= 'In pixels.';
		$atts['id'] 			= 'feed-pagination-border-radius';
		$atts['label'] 			= 'Border radius';
		$atts['name'] 			= 'feed-pagination-border-radius';
		$atts['placeholder'] 	= '';
		$atts['type'] 			= 'text';
		$atts['value'] 			= '0';
		
		if ( ! empty( get_post_meta($post_id, $atts['id'], true) ) ) {
			$atts['value'] = get_post_meta($post_id, $atts['id'], true);
		}
		
		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );
		
		?><tr><?php
		include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );
		?></tr><?php
		*/
	
	?>
	</tbody>
	</table>
	</div>
	<?php

	/*// Header ?>
	<div id="steemitfeed-admin-metabox-header" class="inside hidden">
	<table class="form-table">
	<tbody>
	<?php
		
	?>
	</tbody>
	</table>
	</div>
	<?php */
	?>

</div>


