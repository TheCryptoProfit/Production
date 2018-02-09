<?php

add_action('widgets_init', 'auction_register_browse_by_category_widget_images');
function auction_register_browse_by_category_widget_images() {
	register_widget('auctiontheme_browse_by_category_with_images');
}

class auctiontheme_browse_by_category_with_images extends WP_Widget {

	function auctiontheme_browse_by_category_with_images() {
		$widget_ops = array( 'classname' => 'browse-by-category-thumbs', 'description' => 'Show all categories and browse by category with thumbnails' );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'browse-by-category-thumbs' );
		parent::__construct( 'browse-by-category-thumbs', 'AuctionTheme - Browse by Category Thumbs', $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);

		echo $before_widget;

		if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		global $width_widget_categories, $height_widget_categories;

		$widget_id 		= $args['widget_id'];
		$width 			= $width_widget_categories;
		$height 		= $height_widget_categories;
		$only_these 	= $instance['only_these'];

		$size_string = 'my_size_widget';

		//--------------------------------------------------

		$terms_k 		= get_terms("auction_cat","parent=0&hide_empty=0");


		global $wpdb;
		$arr = array();

		if($only_these == "1")
		{
			$terms = array();

			foreach($terms_k as $trm)
			{
				if($instance['term_' . $trm->term_id] == $trm->term_id)
					array_push($terms, $trm);
			}

		}

		//-----------------------------

		if(count($terms) < count($terms_k)) $disp_btn = 1;
		else $disp_btn = 0;


		$count = count($terms);
		$i = 0;

		if ( $count > 0 )
		{

			// echo '<style>#'.$widget_id.' .my_image_div_cat_name { width: '.round(100/$nr).'%}</style>';

			 foreach ( $terms as $term )
			 {
					$auctiontheme_get_cat_pic_attached = auctiontheme_get_cat_pic_attached($term->term_id);
					$link 		= get_term_link($term->slug,	"auction_cat");
					$image 		= auctiontheme_generate_thumb3($auctiontheme_get_cat_pic_attached, 'my_category_image_thing');


					echo '<div class="my_category_image_holder"><div class="my_image_div">';
					echo '<a href="'.$link.'">';
					echo '<img src="'.$image.'" width="'.$width.'" height="'.$height.'" />';
					echo '</a></div>';

					echo '<div class="my_image_div_cat_name"><div class="padd10">';
					echo '<a href="'.$link.'">'.$term->name.'</a>';
					echo '</div></div>';


					echo '</div>';

			 }

			//=========================================================================

			if($disp_btn == 1)
			{
					echo '<div class="see-more-tax"><b><a href="'.get_permalink(get_option('AuctionTheme_all_cats_id')) .'">'.__('See More Categories','AuctionTheme').'</a></b></div>';
			}
		}
		else { _e('There are no categories defined.','AuctionTheme'); }

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
			<label for="<?php echo $this->get_field_id('nr_rows'); ?>"><?php _e('Only show categories below','AuctionTheme'); ?>:</label>
			<?php echo '<input type="checkbox" name="'.$this->get_field_name('only_these').'"  value="1" '.(
	 $instance['only_these'] == "1" ? ' checked="checked" ' : "" ).' /> '; ?>
		</p>

        <p>
			<label for="<?php echo $this->get_field_id('nr_rows'); ?>"><?php _e('Categories to show','AuctionTheme'); ?>:</label>

                <div style=" width:220px;
    height:180px;
    background-color:#ffffff;
    overflow:auto;border:1px solid #ccc">
     <?php

	 $terms = get_terms("auction_cat","parent=0&hide_empty=0");
	 foreach ( $terms as $term ) {

	 echo '<input type="checkbox" name="'.$this->get_field_name('term_'.$term->term_id).'"  value="'.$term->term_id.'" '.(
	 $instance['term_'.$term->term_id] == $term->term_id ? ' checked="checked" ' : "" ).' /> ';
	 echo $term->name.'<br/>';

	 }

	 ?>

    </div>



		</p>


	<?php
	}
}




?>
