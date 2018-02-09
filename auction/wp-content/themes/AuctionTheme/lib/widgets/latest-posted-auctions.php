<?php

add_action('widgets_init', 'register_latest_posted_auctions_widget');
function register_latest_posted_auctions_widget() {
	register_widget('auctionTheme_latest_posted_auctions');
}

class auctionTheme_latest_posted_auctions extends WP_Widget {

	function auctionTheme_latest_posted_auctions() {
		$widget_ops = array( 'classname' => 'latest-posted-auctions', 'description' => __('Show latest posted auctions','AuctionTheme') );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'latest-posted-auctions' );
		parent::__construct( 'latest-posted-auctions', __('AuctionTheme - Latest auctions','AuctionTheme'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);

		echo $before_widget;

		if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		$limit = $instance['show_auctions_limit'];
		echo '<div class="my-only-widget-content">';
		if(empty($limit) || !is_numeric($limit)) $limit = 5;

				 global $wpdb;
				 $querystr = "
					SELECT distinct wposts.*
					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
					WHERE wposts.ID = wpostmeta.post_id
					AND wpostmeta.meta_key = 'closed'
					AND wpostmeta.meta_value = '0' AND
					wposts.post_status = 'publish'
					AND wposts.post_type = 'auction'
					ORDER BY wposts.post_date DESC LIMIT ".$limit;

				 $pageposts = $wpdb->get_results($querystr, OBJECT);

				 ?>

					 <?php $i = 0; if ($pageposts): ?>
					 <?php global $post; ?>
                     <?php foreach ($pageposts as $post): ?>
                     <?php setup_postdata($post); ?>


                     <?php auctionTheme_small_post(); ?>


                     <?php endforeach; ?>
                     <?php else : ?> <?php $no_p = 1; ?>
                       <div class="padd100"><p class="center"><?php _e("Sorry, there are no posted auctions yet.",'AuctionTheme'); ?></p></div>

                     <?php endif; ?>

                     <?php
		echo '</div>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) { ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','AuctionTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
			value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:95%;" />
		</p>


		<p>
			<label for="<?php echo $this->get_field_id('show_auctions_limit'); ?>"><?php _e('Show auctions limit','AuctionTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('show_auctions_limit'); ?>" name="<?php echo $this->get_field_name('show_auctions_limit'); ?>"
			value="<?php echo esc_attr( $instance['show_auctions_limit'] ); ?>" style="width:20%;" />
		</p>


	<?php
	}
}



?>
