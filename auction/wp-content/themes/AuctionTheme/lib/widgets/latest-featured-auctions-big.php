<?php

add_action('widgets_init', 'register_latest_posted_auctions_big_widget2');
function register_latest_posted_auctions_big_widget2() {
	register_widget('AuctionTheme_latest_posted_ads_big2');
}

class AuctionTheme_latest_posted_ads_big2 extends WP_Widget {

	function AuctionTheme_latest_posted_ads_big2() {
		$widget_ops = array( 'classname' => 'latest-posted-auctions-big2', 'description' => 'Show latest featured auctions (big)' );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'latest-posted-auctions-big2' );
		parent::__construct( 'latest-posted-auctions-big2', 'AuctionTheme - Featured auctions (big)', $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);

		echo $before_widget;

		if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		$limit = $instance['show_auctions_limit'];
		echo '<div class="my-only-widget-content">';

		if(empty($limit) || !is_numeric($limit)) $limit = 5;


		if($instance['order_me'] == "latest") $ord = "wposts.post_date";
		else $ord = "RAND()";


				 global $wpdb;
				 $querystr = "
					SELECT distinct wposts.*
					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta , $wpdb->postmeta wpostmeta2
					WHERE wposts.ID = wpostmeta.post_id AND wposts.ID = wpostmeta2.post_id
					AND wpostmeta.meta_key = 'closed'
					AND wpostmeta.meta_value = '0' AND

					wpostmeta2.meta_key = 'featured'
					AND wpostmeta2.meta_value = '1' AND

					wposts.post_status = 'publish'
					AND wposts.post_type = 'auction'
					ORDER BY ".$ord." DESC LIMIT ".$limit;


				 $pageposts = $wpdb->get_results($querystr, OBJECT);

				 ?>

					 <?php $i = 0; if ($pageposts): ?>
					 <?php global $post; ?>
                     <?php foreach ($pageposts as $post): ?>
                     <?php setup_postdata($post); ?>


                     <?php auctionTheme_get_post(); ?>


                     <?php endforeach; ?>
                     <?php else : ?> <?php $no_p = 1; ?>
                       <div class="padd100"><p class="center"><?php _e("Sorry, there are no posted auctions yet",'AuctionTheme'); ?>.</p></div>

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
			<label for="<?php echo $this->get_field_id('order_me2'); ?>"><?php _e('Order','AuctionTheme'); ?>:</label>
			<?php

				$vl = esc_attr( $instance['order_me'] );
				if(empty($vl)) $vl = "latest";
			?>
            <select name="<?php echo $this->get_field_name('order_me'); ?>" id="<?php echo $this->get_field_id('order_me'); ?>">
            <option value="latest" <?php if($vl == "latest") echo "selected='selected'"; ?>>Latest Posted</option>
            <option value="rand" <?php if($vl == "rand") echo "selected='selected'"; ?>>Random Order</option>

            </select>

		</p>


		<p>
			<label for="<?php echo $this->get_field_id('show_auctions_limit'); ?>"><?php _e('Show auction limit','AuctionTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('show_auctions_limit'); ?>" name="<?php echo $this->get_field_name('show_auctions_limit'); ?>"
			value="<?php echo esc_attr( $instance['show_auctions_limit'] ); ?>" style="width:20%;" />
		</p>


	<?php
	}
}



?>
