<?php

add_action('widgets_init', 'register_adv_search_AT_widget');
function register_adv_search_AT_widget() {
	register_widget('auctionTheme_search_widget_auctions');
}

class auctionTheme_search_widget_auctions extends WP_Widget {

	function auctionTheme_search_widget_auctions() {
		$widget_ops = array( 'classname' => 'adv-search-widget', 'description' => __('Show advanced search widget.','AuctionTheme') );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'adv-search-widget' );
		parent::__construct( 'adv-search-widget', __('AuctionTheme - Search Widget','AuctionTheme'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);

		echo $before_widget;

		if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;


		?>

         <table class="table">


                <form method="get" action="<?php echo AuctionTheme_advanced_search_link(); ?>">

                <?php

							if(AuctionTheme_using_permalinks() == false)
							echo '<input type="hidden" value="'.get_option('AuctionTheme_adv_search_id').'" name="page_id" />';

							?>

                 <tr><td><?php _e('Auction ID#',"AuctionTheme"); ?>: </td><td>
                   <input class="form-control" size="10" value="<?php echo $_GET['auction_ID']; ?>" name="auction_ID" />
                   </td></tr>

                   <tr><td><?php _e('Keyword',"AuctionTheme"); ?>: </td><td>
                   <input class="form-control" size="10" value="<?php echo $_GET['term']; ?>" name="term" />
                   </td></tr>

                   <tr><td><?php _e('Min Price',"AuctionTheme"); ?>:</td><td>
                    <input class="form-control" size="10" value="<?php echo $_GET['price_min']; ?>" placeholder="<?php echo auctiontheme_get_currency() ?>" name="price_min" /></td></tr>

                   <tr><td><?php _e('Max Price',"AuctionTheme"); ?>:</td><td>
                   <input class="form-control" size="10" value="<?php echo $_GET['price_max']; ?>" placeholder="<?php echo auctiontheme_get_currency() ?>" name="price_max" /></td></tr>
          			<?php

					$AuctionTheme_enable_locations = get_option('AuctionTheme_enable_locations');
					if($AuctionTheme_enable_locations != "no"):

					?>
                   <tr><td><?php _e('Zip Code',"AuctionTheme"); ?>:</td><td>
                   <input class="form-control" size="10" value="<?php echo $_GET['zip_code']; ?>" name="zip_code" /></td></tr>

                   <tr><td><?php _e('Radius',"AuctionTheme"); ?>: </td><td>
                   <input class="form-control" size="10" value="<?php echo $_GET['radius']; ?>" placeholder="<?php   _e('miles','AuctionTheme' ) ?>" name="radius" />
                    </td></tr>

                   <tr><td><?php _e('Filter by Location',"AuctionTheme"); ?>:</td><td>
				   <?php	echo AuctionTheme_get_categories_slug("auction_location", $_GET['auction_location_cat'], __("Select Location","AuctionTheme"), "form-control"); ?></td></tr>

                   <?php endif; ?>

                   <tr><td><?php _e('Filter by Category',"AuctionTheme"); ?>: </td><td>
				   <?php	echo AuctionTheme_get_categories_slug("auction_cat", $_GET['auction_cat_cat'], __("Select Category","AuctionTheme"), "form-control"); ?></td></tr>

                        <?php

		$get_catID = AuctionTheme_get_CATID($_GET['auction_cat_cat']);

		if(empty($get_catID)) $get_catID = 0;

		$get_catID = array($get_catID);
		$arr = AuctionTheme_get_auction_category_fields_without_vals($get_catID, 'no');

		for($i=0;$i<count($arr);$i++)
		{

			        echo '<tr>';
					echo '<td>'.$arr[$i]['field_name'].$arr[$i]['id'].':</td>';
					echo '<td>'.$arr[$i]['value'].'</td>';
					echo '</tr>';


		}


		?>

                </div>

                   <tr><td></td><td>
                   <input type="submit" class="submit_bottom2" value="<?php _e("Refine Search","AuctionTheme"); ?>" name="ref-search" class="big-search-submit2" /></td></tr>
                   </form>
</table>

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



	<?php
	}
}



?>
