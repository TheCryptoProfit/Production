<?php

add_action('widgets_init', 'register_ending_soonest_auctions_widget');
function register_ending_soonest_auctions_widget() {
	register_widget('auctionTheme_ending_sonnest_auctions');
}

class auctionTheme_ending_sonnest_auctions extends WP_Widget {

	function auctionTheme_ending_sonnest_auctions() {
		$widget_ops = array( 'classname' => 'ending-soonest-auctions', 'description' => __('Show ending soonest auctions','AuctionTheme') );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'ending-soonest-auctions' );
		parent::__construct( 'ending-soonest-auctions', __('AuctionTheme - Ending soonest','AuctionTheme'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);

		echo $before_widget;

		if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		$limit = $instance['show_auctions_limit'];



		if(empty($limit) || !is_numeric($limit)) $limit = 5;

				 global $wpdb;
				 $querystr = "
					SELECT distinct wposts.*
					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta, $wpdb->postmeta wpostmeta2
					WHERE wposts.ID = wpostmeta.post_id
					AND wpostmeta.meta_key = 'closed'
					AND wpostmeta.meta_value = '0' AND wposts.ID = wpostmeta2.post_id
					AND wpostmeta2.meta_key = 'ending'
					AND	wposts.post_status = 'publish'
					AND wposts.post_type = 'auction'
					ORDER BY wpostmeta2.meta_value+0 asc LIMIT ".$limit;

				 $pageposts = $wpdb->get_results($querystr, OBJECT);

				 ?>

					 <?php $i = 0; if ($pageposts): ?>
					 <?php global $post; ?>
                     <?php foreach ($pageposts as $post): ?>
                     <?php setup_postdata($post); ?>


                     <?php auctionTheme_small_post('ending'); ?>


                     <?php endforeach; ?>
                     <?php else : ?> <?php $no_p = 1; ?>
                       <div class="padd100"><p class="center"><?php _e("Sorry, there are no posted auctions yet.",'AuctionTheme'); ?></p></div>

                     <?php endif; ?>

                     <?php

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
