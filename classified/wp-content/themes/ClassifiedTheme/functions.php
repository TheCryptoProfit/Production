<?php
/********************************************************************************
*
*	ClassifiedTheme - copyright (c) - sitemile.com - Details
*	http://sitemile.com/p/classifiedTheme
*	Code written by_________Saioc Dragos Andrei
*	email___________________andreisaioc@gmail.com
*	since v6.2.1
*
*********************************************************************************/

	load_theme_textdomain( 'ClassifiedTheme', TEMPLATEPATH . '/languages' );

	DEFINE("CLASSIFIEDTHEME_VERSION", "7.1.1");
	DEFINE("CLASSIFIEDTHEME_RELEASE", "22 march 2017");

	//----------------------------------------

	get_template_part( 'lib/first_run');
	get_template_part(  'lib/first_run_emails');
	get_template_part( 'lib/cronjob');
	get_template_part( 'lib/login_register/custom2');
	get_template_part( 'lib/post_new');
	get_template_part( 'lib/admin_menu');
	get_template_part( 'lib/blog_page');

	get_template_part( 'lib/my_account/my_account_home');
	get_template_part( 'lib/my_account/expired_listings');
	get_template_part( 'lib/my_account/unpublished_listings');
	get_template_part( 'lib/my_account/active_listings');
	get_template_part( 'lib/my_account/personal_info');
	get_template_part( 'lib/my_account/mem_packs');
	get_template_part( 'lib/my_account/purchase_mem');
	get_template_part( 'lib/my_account/messages');


	add_action('init', 	'classifiedTheme_myStartSession', 1);
	add_action('wp_logout', 'classifiedTheme_myEndSession');
	add_action('wp_login', 	'classifiedTheme_myEndSession');

	function classifiedTheme_myStartSession() {
		if (!session_id())
			session_start();
	}

	function classifiedTheme_myEndSession() {
	    session_destroy ();
	}



	get_template_part( 'lib/widgets/browse-by-location');
	get_template_part( 'lib/widgets/browse-by-category');

	get_template_part( 'lib/widgets/featured-ads');
	get_template_part( 'lib/widgets/category-with-images');
	get_template_part( 'lib/widgets/latest-posted-ads');

	get_template_part( 'lib/all_locations');
	get_template_part( 'lib/all_categories');
	get_template_part( 'lib/advanced_search');

	get_template_part( 'autosuggest');
	get_template_part( 'my-upload');

	//------------------------

	global $current_theme_locale_name, $ads_thing_list;
	$current_theme_locale_name = 'ClassifiedTheme';

	global $default_search;
	$default_search = __("Begin to search by typing here...", "ClassifiedTheme");

	global $category_url_link, $location_url_link, $ads_url_thing, $ads_thing_list;
	$category_url_link 		= get_option("ClassifiedTheme_category_permalink");
	$location_url_link 		= get_option("ClassifiedTheme_location_permalink");
	$listings_url_thing 	= get_option("ClassifiedTheme_listing_permalink");

	if(empty($listings_url_thing)) 	$listings_url_thing = 'listings';
	if(empty($ads_url_thing)) 	$ads_url_thing = 'listings';

	$ads_thing_list		= "item-list";

	if(empty($category_url_link)) 	$category_url_link = 'section';
	if(empty($location_url_link)) 	$location_url_link = 'location';
	if(empty($ads_thing_list)) 		$ads_thing_list = 'listings';


//--------------------------------------------------------------

	add_filter('the_content',					'ClassifiedTheme_display_my_account_home_disp_page');
	add_action('init', 							'classifiedTheme_create_post_type' );
	add_action( 'admin_head', 					'ClassifiedTheme_my_admin_head' );
	add_action('wp_enqueue_scripts', 			'classifiedTheme_add_theme_styles');
	add_action('widgets_init',	 				'ClassifiedTheme_framework_init_widgets' );
	add_action('generate_rewrite_rules', 		'ClassifiedTheme_rewrite_rules' );

	add_action("manage_posts_custom_column", 	"classifiedTheme_my_custom_columns");
	add_filter("manage_edit-ad_columns", 		"classifiedTheme_my_ads_columns");

	add_action('the_content', 					'ClassifiedTheme_display_my_post_new_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_my_all_cats_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_my_all_locs_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_my_adv_search_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_my_expired_list_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_my_unpub_list_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_my_active_list_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_my_pers_inf_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_my_account_mem_pks_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_blog_posts_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_purchase_mem_disp_page' );
	add_action('the_content', 					'ClassifiedTheme_display_messages_disp_page' );

	add_action('draft_to_publish', 				'ClassifiedTheme_run_when_post_published',10,1);

	add_action('admin_menu', 					'ClassifiedTheme_admin_main_menu_scr');
	add_action('save_post',						'classifiedTheme_save_custom_fields');
	add_action('query_vars', 					'ClassifiedTheme_add_query_vars');

	add_action("template_redirect", 				'classifiedTheme_template_redirect');
	add_filter('wp_head',							'ClassifiedTheme_add_max_nr_of_images');
	add_action( 'init', 										'classifiedTheme_register_my_menus' );

	global $width_widget_categories, $height_widget_categories;
	$width_widget_categories = 190;
	$height_widget_categories = 95;
	add_image_size( 'ra_my_category_image_thing', $width_widget_categories, $height_widget_categories, true ); //category images in the widget


/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/

function classifiedTheme_get_userid_from_username($user)
{
	//$user = get_user_by('login', $user);
	global $wpdb; $user = trim($user);

	$usrs = $wpdb->users;

	$s = "select * from ".$usrs." where user_login='$user'";
	$r = $wpdb->get_results($s);
	$row = $r[0];

	//if(empty($row->ID)) return false;

	return $row->ID;
}

function ClassifiedTheme_my_admin_notice() {

	if(!function_exists('siteorigin_panels_init'))
	{

    ?>
    <div class="updated">
        <p><?php


		$action = 'install-plugin';
		$slug = 'siteorigin-panels';
		$ss = wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'plugin' => $slug
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$slug
		);

		echo sprintf(__( 'In order to benefit of the full experience of our theme, we recommend installing <strong><a href="%s">this plugin</a></strong>. It will give you the "Page Builder" feature for your pages, and configure homepage as well.', 'ClassifiedTheme' ), $ss); ?></p>
    </div>
    <?php
}

	if(!function_exists('wp_pagenavi'))
	{

    ?>
    <div class="updated">
        <p><?php


		$action = 'install-plugin';
		$slug = 'wp-pagenavi';
		$ss = wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'plugin' => $slug
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$slug
		);

		echo sprintf(__( 'In order to benefit of the full experience of our theme, we recommend installing <strong><a href="%s">WP PageNavi Plugin</a></strong>. You will need it for pagination.', 'ClassifiedTheme' ), $ss); ?></p>
    </div>
    <?php
}


	if(!function_exists('bcn_display'))
	{

    ?>
    <div class="updated">
        <p><?php


		$action = 'install-plugin';
		$slug = 'breadcrumb-navxt';
		$ss = wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'plugin' => $slug
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$slug
		);

		echo sprintf(__( 'In order to benefit of the full experience of our theme, we recommend installing <strong><a href="%s">the Breadcrumb Plugin</a></strong>.', 'ClassifiedTheme' ), $ss); ?></p>
    </div>
    <?php
}


}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
add_action( 'admin_notices', 'ClassifiedTheme_my_admin_notice' );

if(!function_exists('ClassifiedTheme_get_categories_clck'))
{
function ClassifiedTheme_get_categories_clck($taxo, $selected = "", $include_empty_option = "", $ccc = "" , $xx = "")
{
	$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
	$terms = get_terms( $taxo, $args );

	$ret = '<select name="'.$taxo.'_cat" class="'.$ccc.'" id="'.$ccc.'" '.$xx.'>';
	if(!empty($include_empty_option)) $ret .= "<option value=''>".$include_empty_option."</option>";

	if(empty($selected)) $selected = -1;

	foreach ( $terms as $term )
	{
		$id = $term->term_id;
		$ret .= '<option '.($selected == $id ? "selected='selected'" : " " ).' value="'.$id.'">'.$term->name.'</option>';

	}

	$ret .= '</select>';

	return $ret;

}
}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function classifiedtheme_generate_thumb3($img_ID, $size_string)
{
	return classifiedtheme_wp_get_attachment_image($img_ID, $size_string);
}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function classifiedtheme_wp_get_attachment_image($attachment_id, $size = 'thumbnail', $icon = false, $attr = '') {

	$html = '';
	$image = wp_get_attachment_image_src($attachment_id, $size, $icon);
	if ( $image ) {
		list($src, $width, $height) = $image;
		$hwstring = image_hwstring($width, $height);
		if ( is_array($size) )
			$size = join('x', $size);
		$attachment =& get_post($attachment_id);
		$default_attr = array(
			'src'	=> $src,
			'class'	=> "attachment-$size",
			'alt'	=> trim(strip_tags( get_post_meta($attachment_id, '_wp_attachment_image_alt', true) )), // Use Alt field first
			'title'	=> trim(strip_tags( $attachment->post_title )),
		);
		if ( empty($default_attr['alt']) )
			$default_attr['alt'] = trim(strip_tags( $attachment->post_excerpt )); // If not, Use the Caption
		if ( empty($default_attr['alt']) )
			$default_attr['alt'] = trim(strip_tags( $attachment->post_title )); // Finally, use the title

		$attr = wp_parse_args($attr, $default_attr);
		$attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment );
		$attr = array_map( 'esc_attr', $attr );
		$html = rtrim("<img $hwstring");

		$html = $attr['src'];
	}

	return $html;
}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function classifiedtheme_get_view_grd()
{
	if(isset($_SESSION['view_tp']))
	{
		if(	$_SESSION['view_tp'] == "grid") return "grid"; else return "normal";
	}
	return "normal";

}


/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function classifiedTheme_curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function classifiedTheme_get_CATID($slug)
{
	$c = get_term_by('slug', $slug, 'ad_cat');
	return $c->term_id;
}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function ClassifiedTheme_get_auction_category_fields_without_vals($catid, $clas_op = '')
{
	global $wpdb;
	$s = "select * from ".$wpdb->prefix."ad_custom_fields order by ordr asc";
	$r = $wpdb->get_results($s);

	$arr1 = array(); $i = 0;

	if($clas_op != "no") $clas_op = 'do_input';

	foreach($r as $row)
	{
		$ims 	= $row->id;
		$name 	= $row->name;
		$tp 	= $row->tp;

		if($row->cate == 'all')
		{
			$arr1[$i]['id'] 	= $ims;
			$arr1[$i]['name'] 	= $name;
			$arr1[$i]['tp'] 	= $tp;
			$i++;

		}
		else
		{
			$se = "select * from ".$wpdb->prefix."ad_custom_relations where custid='$ims'";
			$re = $wpdb->get_results($se);

			if(count($re) > 0)
			foreach($re as $rowe) // = mysql_fetch_object($re))
			{
				if(count($catid) > 0)
				foreach($catid as $id_of_cat)
				{

					if($rowe->catid == $id_of_cat)
					{
						$flag_me = 1;
						for($k=0;$k<count($arr1);$k++)
						{
							if(	$arr1[$k]['id'] 	== $ims	) {  $flag_me = 0; break; }
						}

						if($flag_me == 1)
						{
							$arr1[$i]['id'] 	= $ims;
							$arr1[$i]['name'] 	= $name;
							$arr1[$i]['tp'] 	= $tp;
							$i++;
						}
					}
				}
			}
		}
	}

	$arr = array();
	$i = 0;

	for($j=0;$j<count($arr1);$j++)
	{
		$ids 	= $arr1[$j]['id'];
		$tp 	= $arr1[$j]['tp'];

		$arr[$i]['field_name']  = $arr1[$j]['name'];
		$arr[$i]['id']  = '<input type="hidden" value="'.$ids.'" name="custom_field_id[]" />';

		if($tp == 1)
		{

		$teka = ''; //!empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;

		$arr[$i]['value']  = '<input class="'.$clas_op.'" type="text" name="custom_field_value_'.$ids.'"
		value="'.(isset($_GET['custom_field_value_'.$ids]) ? $_GET['custom_field_value_'.$ids] : $teka ).'" />';

		}

		if($tp == 5)
		{

			$teka 	= ''; //!empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;
			$value 	= isset($_GET['custom_field_value_'.$ids]) ? $_GET['custom_field_value_'.$ids] : $teka;

			$arr[$i]['value']  = '<textarea rows="5" cols="40" class="'.$clas_op.'" name="custom_field_value_'.$ids.'">'.$value.'</textarea>';

		}

		if($tp == 3) //radio
		{
			$arr[$i]['value']  = '';

				$s2 = "select * from ".$wpdb->prefix."ad_custom_options where custid='$ids' order by ordr ASC ";
				$r2 = $wpdb->get_results($s2);

				if(count($r2) > 0)
				foreach($r2 as $row2) // = mysql_fetch_object($r2))
				{
					$teka 	= ''; //!empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;
					if(isset($_GET['custom_field_value_'.$ids]))
					{
						if($_GET['custom_field_value_'.$ids] == $row2->valval) $value = 'checked="checked"';
						else $value = '';
					}
					elseif(!empty($pid))
					{
						$v = ''; //get_post_meta($pid, 'custom_field_ID_'.$ids, true);
						if($v == $row2->valval) $value = 'checked="checked"';
						else $value = '';

					}
					else $value = '';

					$arr[$i]['value']  .= '<input type="radio" '.$value.' value="'.$row2->valval.'" name="custom_field_value_'.$ids.'"> '.$row2->valval.'<br/>';
				}
		}


		if($tp == 4) //checkbox
		{
			$arr[$i]['value']  = '';

				$s2 = "select * from ".$wpdb->prefix."ad_custom_options where custid='$ids' order by ordr ASC ";
				$r2 = $wpdb->get_results($s2);


				if(count($r2) > 0)
				foreach($r2 as $row2) // = mysql_fetch_object($r2))
				{
					$teka 	= $_GET['custom_field_value_'.$ids]; //!empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids) : "" ;


					if(is_array($teka))
					{
						$tekam = '';

						foreach($teka as $te)
						{

							if($te == $row2->valval) { $tekam = "checked='checked'"; break; }
						}


					}
					else $tekam = '';



					$arr[$i]['value']  .= '<input '.$tekam.' type="checkbox" value="'.$row2->valval.'" name="custom_field_value_'.$ids.'[]"> '.$row2->valval.'<br/>';
				}

		}

		if($tp == 2) //select
		{
			$arr[$i]['value']  = '<select class="'.$clas_op.'" name="custom_field_value_'.$ids.'" /><option value="">'.__('Select','ClassifiedTheme').'</option>';

				$s2 = "select * from ".$wpdb->prefix."ad_custom_options where custid='$ids' order by ordr ASC ";
				$r2 = $wpdb->get_results($s2);

				if(count($r2) > 0)
				foreach($r2 as $row2) // = mysql_fetch_object($r2))
				{
					$teka 	= ''; //!empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids) : "" ;

					if(!empty($teka))
					{
						foreach($teka as $te)
						{
							if($te == $row2->valval) { $teka = "selected='selected'"; break; }
						}

						$teka = '';
					}
					else $teka = '';

					if(isset($_GET['custom_field_value_'.$ids]) && $_GET['custom_field_value_'.$ids] == $row2->valval)
					$value = "selected='selected'" ;
					else $value = $teka;


					$arr[$i]['value']  .= '<option '.$value.' value="'.$row2->valval.'">'.$row2->valval.'</option>';

				}
			$arr[$i]['value']  .= '</select>';
		}

		$i++;
	}

	return $arr;
}
/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function ClassifiedTheme_clear_table($colspan = '')
{
	return '        <tr>
             <td colspan="'.$colspan.'">&nbsp;</td>
        </tr>';
}


function ClassifiedTheme_run_when_post_published($post)
{

	if($post->post_type == 'ad'):

		$ClassifiedTheme_listing_featured_time_listing = get_option('ClassifiedTheme_listing_featured_time_listing');
		if(empty($ClassifiedTheme_listing_featured_time_listing)) $ClassifiedTheme_listing_featured_time_listing = 30;

		$ClassifiedTheme_listing_time_listing = get_option('ClassifiedTheme_listing_time_listing');
		if(empty($ClassifiedTheme_listing_time_listing)) $ClassifiedTheme_listing_time_listing = 30;

		$is_ad_featured = get_post_meta($pid, 'featured', true);
		if($is_ad_featured == "1") $time_ending = $nowtm + $ClassifiedTheme_listing_featured_time_listing *3600*24;
		else $time_ending = $nowtm + $ClassifiedTheme_listing_time_listing *3600*24;

		update_post_meta($pid, "ending", 		$ending);

	endif;
}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/

function classifiedTheme_register_my_menus() {
		register_nav_menu( 'primary-classifiedtheme-header', 'ClassifiedTheme Top Menu' );
		register_nav_menu( 'primary-classified-big-menu', 'ClassifiedTheme Main Menu' );

}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/

function ClassifiedTheme_add_max_nr_of_images()
{
	?>

    <script type="text/javascript">
		<?php
		$ClassifiedTheme_enable_max_images_limit = get_option('ClassifiedTheme_enable_max_images_limit');
		if($ClassifiedTheme_enable_max_images_limit == "yes")
		{
			$classifiedTheme_nr_max_of_images = get_option('ClassifiedTheme_nr_max_of_images');
			if(empty($classifiedTheme_nr_max_of_images))	 $classifiedTheme_nr_max_of_images = 10;
		}
		else $ClassifiedTheme_enable_max_images_limit = 1000;

		if(empty($classifiedTheme_nr_max_of_images)) $classifiedTheme_nr_max_of_images = 100;

		?>



		var maxNrImages_PT = <?php echo $classifiedTheme_nr_max_of_images; ?>;



						jQuery(document).ready(function() {

 		jQuery('.parent_taxe').click(function () {

			var rels = jQuery(this).attr('rel');
			jQuery("#" + rels).toggle();
			jQuery("#img_" + rels).attr("src","<?php bloginfo('template_url'); ?>/images/posted1.png");

			return false;
		});


});

	</script>

    <?php

}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/

function classifiedTheme_my_ads_columns($columns) //this function display the columns headings
{
		$columns["price"] =  __("Price",'ClassifiedTheme');
		$columns["featured"] =  __("Featured",'ClassifiedTheme');
		$columns["exp"] =  __("Expires in",'ClassifiedTheme');
		$columns["thumbnail"] =  __("Thumbnail",'ClassifiedTheme');
		$columns["options"] =  __("Options",'ClassifiedTheme');

	return $columns;
}
/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function ClassifiedTheme_listing_clear_table($spm = '')
{
	return '<tr><td colspan="'.$spm.'"></td></tr>';
}
/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function ClassifiedTheme_get_post_nr_of_images($pid)
{

		//---------------------
		// build the exclude list
		$exclude = array();

		$args = array(
		'order'          => 'ASC',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'meta_key'		 => 'another_reserved1',
		'meta_value'	 => '1',
		'numberposts'    => -1,
		'post_status'    => null,
		);
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ($attachments as $attachment) {
			$url = $attachment->ID;
			array_push($exclude, $url);
		}
		}

		//-----------------


		$arr = array();

		$args = array(
		'order'          => 'ASC',
		'orderby'        => 'post_date',
		'post_type'      => 'attachment',
		'post_parent'    => $pid,
		'exclude'    		=> $exclude,
		'post_mime_type' => 'image',
		'numberposts'    => -1,
		); $i = 0;

		$attachments = get_posts($args);
		if ($attachments) {

			foreach ($attachments as $attachment) {

				$url = wp_get_attachment_url($attachment->ID);
				array_push($arr, $url);

		}
			return count($arr);
		}
		return 0;
}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function classifiedTheme_my_custom_columns($column)
{
	global $post;
	if ("ID" == $column) echo $post->ID; //displays title
	elseif ("description" == $column) echo $post->ID; //displays the content excerpt
	elseif ("thumbnail" == $column)
	{
		echo '<a href="'.get_bloginfo('siteurl').'/wp-admin/post.php?post='.$post->ID.'&action=edit"><img class="image_class"
	src="'.classifiedtheme_get_first_post_image($post->ID,40,30).'" width="40" height="30" /></a>'; //shows up our post thumbnail that we previously created.
	}

	elseif ("author" == $column)
	{
		echo $post->post_author;
	}

	elseif ("featured" == $column)
	{
		$uu = get_post_meta($post->ID, 'featured', true);
		if($uu == "1") echo __('Yes','ClassifiedTheme');
		else echo __('No','ClassifiedTheme');
	}

	elseif ("price" == $column)
	{
		$price = get_post_meta($post->ID, 'price', true);
		echo classifiedtheme_formats($price,2)." ".classifiedTheme_currency();
	}

	elseif ("exp" == $column)
	{
		$ending = get_post_meta($post->ID, 'ending', true);
		echo ClassifiedTheme_prepare_seconds_to_words($ending - current_time('timestamp',0));
	}

	elseif ("options" == $column)
	{
		echo '<div style="padding-top:20px">';
		echo '<a class="awesome" href="'.get_bloginfo('siteurl').'/wp-admin/post.php?post='.$post->ID.'&action=edit">'.__('Edit','ClassifiedTheme').'</a> ';
		echo '<a class="awesome" href="'.get_permalink($post->ID).'" target="_blank">'.__('View','ClassifiedTheme').'</a> ';
		echo '<a class="trash" href="'.get_delete_post_link($post->ID).'">'.__('Trash','ClassifiedTheme').'</a> ';
		echo '</div>';
	}

}
/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/

function ClassifiedTheme_get_total_nr_of_listings()
{
	$query = new WP_Query( "post_type=ad&order=DESC&orderby=id&posts_per_page=-1&paged=1" );
	return $query->post_count;
}
/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function ClassifiedTheme_get_total_nr_of_open_listings()
{
	$query = new WP_Query( "meta_key=closed&meta_value=0&post_type=ad&order=DESC&orderby=id&posts_per_page=-1&paged=1" );
	return $query->post_count;
}
/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function ClassifiedTheme_get_total_nr_of_closed_listings()
{
	$query = new WP_Query( "meta_key=closed&meta_value=1&post_type=ad&order=DESC&orderby=id&posts_per_page=-1&paged=1" );
	return $query->post_count;
}
/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function classifiedtheme_get_field_tp($nr)
{
		if($nr == "1") return "Text field";
		if($nr == "2") return "Select box";
		if($nr == "3") return "Radio Buttons";
		if($nr == "4") return "Check-box";
		if($nr == "5") return "Large text-area";


}
/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function classifiedTheme_search_into($custid, $val)
	{
		global $wpdb;
		$s = "select * from ".$wpdb->prefix."ad_custom_relations where custid='$custid'";
		$r = $wpdb->get_results($s);

		if(count($r) == 0) return 0;
		else
		foreach($r as $row) // = mysql_fetch_object($r))
		{
			if($row->catid == $val) return 1;
		}

		return 0;
	}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function CT_get_super_site_link_taxe($tx)
{
	$trm = get_term_by( 'slug', $tx, 'ad_location');
	$termchildren = get_term_children( $trm->term_id, 'ad_location' );

	if(count($termchildren) == 0) return get_term_link($trm->term_id, 'ad_location');
	return get_bloginfo('siteurl') . "/ads-locations/" . $tx;
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_sanitize_string($ss)
{

	 $ss = esc_sql($ss);
	 return $ss;
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function ClassifiedTheme_get_my_pagination_main($url, $current_page, $field_page, $total_pages, $other = '')
{
	$s = '';
	$s .= '<div class="wp-pagenavi"> <span class="pages">'.sprintf(__('Page %s of %s','ClassifiedTheme'), $current_page, $total_pages).'</span>';

	$batch 	= 5;
	$raport = ceil($current_page/$batch) - 1; if ($raport < 0) $raport = 0;
	$start 		= $raport * $batch + 1;
	$end		= $start + $batch - 1;

	if($end > $total_pages) $end = $total_pages;

	$previous_pg = $current_page - 1;


	$next_pg = $current_page + 1;
	if($next_pg > $total_pages) $next_pg = 1;

	//----------------------

	if($current_page > 1)
	$s .= '<a href="'.$url.'&'.$field_page.'=1'.$other.'">&laquo; '.__('First','Walleto').'</a>';

	if($previous_pg > 0)
	$s.= '<a href="'.$url.'&'.$field_page.'='.$previous_pg.$other.'">&laquo;</a>';


	for($i = $start; $i <= $end; $i ++) {
			if ($i == $current_page) {
				$s .= '<span class="current">'.$i.'</span>';
			} else {

				$s .= '<a class="page larger" href="'.$url.'&'.$field_page.'='.$i.$other.'">'.$i.'</a>';

			}
		}

	//extend
	if($end < $total_pages) $s .= '<span class="extend">...</span>';

	$next_pg = $current_page + 1;
	if($next_pg > $total_pages) $next_pg = 1;

	if($total_pages > $current_page)
	$s .= '<a href="'.$url.'&'.$field_page.'='.$next_pg.$other.'" class="page larger">&raquo;</a>';

	if($total_pages > $current_page)
	$s .= '<a href="'.$url.'&'.$field_page.'='.$total_pages.$other.'" class="page larger">'.__('Last','ClassifiedTheme').' &raquo;</a>';

	return $s.'</div>';

}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_send_email_on_priv_mess_received($sender_uid, $receiver_uid)
{
	$enable 	= get_option('ClassifiedTheme_priv_mess_received_email_enable');
	$subject 	= get_option('ClassifiedTheme_priv_mess_received_email_subject');
	$message 	= get_option('ClassifiedTheme_priv_mess_received_email_message');

	if($enable != "no"):

		$user 			= get_userdata($receiver_uid);
		$site_login_url = ClassifiedTheme_login_url();
		$site_name 		= get_bloginfo('name');
		$account_url 	= get_permalink(get_option('ClassifiedTheme_my_account_page_id'));
		$sndr			= get_userdata($sender_uid);

		$find 		= array('##sender_username##', '##receiver_username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##');
   		$replace 	= array($sndr->user_login, $user->user_login, $site_login_url, $site_name, get_bloginfo('url'), $account_url);

		$tag		= 'ClassifiedTheme_send_email_on_priv_mess_received';
		$find 		= apply_filters( $tag . '_find', 	$find );
		$replace 	= apply_filters( $tag . '_replace', $replace );

		$message 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $message);
		$subject 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $subject);

		//---------------------------------------------

		ClassifiedTheme_send_email($user->user_email, $subject, $message);

	endif;
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_add_query_vars($public_query_vars)
{
    	$public_query_vars[] = 'jb_action';
		$public_query_vars[] = 'a_action';
		$public_query_vars[] = 'orderid';
		$public_query_vars[] = 'bid_id';

				$public_query_vars[] = 'pg';
						$public_query_vars[] = 'step';
		$public_query_vars[] = 'my_second_page';
		$public_query_vars[] = 'third_page';
		$public_query_vars[] = 'username';
		$public_query_vars[] = 'pid';
		$public_query_vars[] = 'term_search';		//job_sort, job_category, page
		$public_query_vars[] = 'method';
		$public_query_vars[] = 'post_author';
		$public_query_vars[] = 'page';
		$public_query_vars[] = 'rid';
		$public_query_vars[] = 'current_taxo';

    	return $public_query_vars;
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_get_post_blog()
{

						 $arrImages =& get_children('post_type=attachment&post_mime_type=image&post_parent=' . get_the_ID());

						 if($arrImages)
						 {
							$arrKeys 	= array_keys($arrImages);
							$iNum 		= $arrKeys[0];
					        $sThumbUrl 	= wp_get_attachment_thumb_url($iNum);
					        $sImgString = '<a href="' . get_permalink() . '">' .
	                          '<img class="image_class" src="' . $sThumbUrl . '" width="100" height="100" />' .
                      		'</a>';

						 }
						 else
						 {
								$sImgString = '<a href="' . get_permalink() . '">' .
	                          '<img class="image_class" src="' . get_bloginfo('template_url') . '/images/nopic.png" width="100" height="100" />' .
                      			'</a>';

						 }
			global $post;
			$author = get_userdata($post->post_author);
?>
				<div class="post vc_POST" id="post-<?php the_ID(); ?>">
                <div class="padd10">
                <div class="image_holder" style="width:120px">
                <?php echo $sImgString; ?>
                </div>
                <div  class="title_holder" style="width:510px" >
                     <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
                        <?php the_title(); ?></a></h2>
                        <p class="mypostedon"><?php _e('Posted on','ClassifiedTheme'); ?> <?php the_time('F jS, Y') ?>  <?php _e('by','ClassifiedTheme'); ?>
                       <a href="<?php echo ClassifiedTheme_get_user_profile_link($author->ID); ?>"><?php echo $author->user_login; ?></a>
                  </p>
                       <p class="blog_post_preview"> <?php the_excerpt(); ?></p>


                        <a href="<?php the_permalink() ?>" class="my_button_m"><?php _e('Read More','ClassifiedTheme'); ?></a>

                     </div></div>



                     </div>
<?php
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_get_item_primary_cat($pid)
{
	$ad_cat = wp_get_object_terms($pid, 'ad_cat');
	if(is_array($ad_cat))
	{
		return 	$ad_cat[0]->term_id;
	}

	return 0;
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_save_custom_fields($pid)
{
	$classifiedTheme_ad_period = get_option('classifiedTheme_ad_period');
	if(is_numeric($classifiedTheme_ad_period)) $adper = $classifiedTheme_ad_period;
	else $adper = 30;

	if($_POST['featureds'] == '1')
	{
		$classifiedTheme_ad_period_featured = get_option('classifiedTheme_ad_period_featured');
		if(is_numeric($classifiedTheme_ad_period_featured)) $adper = $classifiedTheme_ad_period_featured;
	}


	if(isset($_POST['fromadmin']))
	{

	$ending = get_post_meta($pid,"ending",true);
	$views = get_post_meta($pid,"views",true);
	$closed = get_post_meta($pid,"closed",true);

	$ending = $_POST['ending'];


	update_post_meta($pid,"ending", strtotime($ending ." 00:00:00"));

	if(empty($views)) update_post_meta($pid,"views",0);


	if($_POST['featureds'] == '1')
	update_post_meta($pid,"featured",'1');
	else
	update_post_meta($pid,"featured",'0');


	if($_POST['paid'] == '1')
	update_post_meta($pid,"paid",'1');
	else
	update_post_meta($pid,"paid",'0');


	//---------------

	/*
	for($i=0;$i<count($_POST['custom_field_id']);$i++)
	{
		$id = $_POST['custom_field_id'][$i];
		$valval = $_POST['custom_field_value_'.$id];

		if(is_array($valval))
				update_post_meta($pid, 'custom_field_ID_'.$id, $valval);
		else
			update_post_meta($pid, 'custom_field_ID_'.$id, strip_tags($valval));
	}

	*/

	//---------------

	if($_POST['closed'] == '1')
		{

			update_post_meta($pid,"closed",'1');
		}
	else
	{

		update_post_meta($pid,"closed",'0');

	}



	update_post_meta($pid,"price",str_replace(",","",trim($_POST['price'])));
	update_post_meta($pid,"Location",trim($_POST['Location']));

	for($i=0;$i<count($_POST['custom_field_id']);$i++)
	{
		$id = $_POST['custom_field_id'][$i];
		$valval = $_POST['custom_field_value_'.$id];

		if(is_array($valval))
		{
			delete_post_meta($pid, 'custom_field_ID_'.$id);

			for($k=0;$k<count($valval);$k++)
				add_post_meta($pid, 'custom_field_ID_'.$id, $valval[$k]);
		}
		else
		update_post_meta($pid, 'custom_field_ID_'.$id, $valval);
	}
	}
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_small_post()
{
			$ending 		= get_post_meta(get_the_ID(), 'ending', true);
			$sec 			= $ending - current_time('timestamp',0);
			$location 		= get_post_meta(get_the_ID(), 'Location', true);


			$price 			= get_post_meta(get_the_ID(), 'price', true);
			$closed 		= get_post_meta(get_the_ID(), 'closed', true);
			$featured 		= get_post_meta(get_the_ID(), 'featured', true);

?>
				<div class="post" id="post-<?php the_ID(); ?>"  <?php if($featured == "1"): ?> class="me_featured_sk" <?php endif; ?>>

                <?php if($featured == "1"): ?>
                <div class="featured-two"></div>
                <?php endif; ?>

                <div class="image_holder2">
                <a href="<?php the_permalink(); ?>"><img width="50" height="50" class="image_class"
                src="<?php echo ClassifiedTheme_get_first_post_image(get_the_ID(),75,65); ?>" /></a>
                </div>
                <div  class="title_holder2" >
                     <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
                        <?php the_title();


                        ?></a></h2>

                        <p class="mypostedon2">
                        <?php _e("Posted in", "ClassifiedTheme");?> <?php echo get_the_term_list( get_the_ID(), 'ad_cat', '', ', ', '' ); ?><br/>
                       <?php _e("Location", "ClassifiedTheme");?>: <?php

					   $lc = get_the_term_list( get_the_ID(), 'ad_location', '', ', ', '' );
					   echo (empty($lc) ? __("not defined", "ClassifiedTheme") : $lc ); ?> </p>




                     </div></div> <?php
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_get_avatar($uid, $w = 25, $h = 25)
{
	$av = get_user_meta($uid, 'avatar', true);
	if(empty($av)) return get_bloginfo('template_url')."/images/noav.jpg";
	else return ClassifiedTheme_generate_thumb($av, $w, $h);
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_is_owner_of_post()
{
	return false;
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_get_post_images($pid, $limit = -1)
{

		//---------------------
		// build the exclude list
		$exclude = array();

		$args = array(
		'order'          => 'ASC',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'meta_key'		 => 'another_reserved1',
		'meta_value'	 => '1',
		'numberposts'    => -1,
		'post_status'    => null,
		);
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ($attachments as $attachment) {
			$url = $attachment->ID;
			array_push($exclude, $url);
		}
		}

		//-----------------


		$arr = array();

		$args = array(
		'order'          => 'ASC',
		'orderby'        => 'post_date',
		'post_type'      => 'attachment',
		'post_parent'    => $pid,
		'exclude'    		=> $exclude,
		'post_mime_type' => 'image',
		'numberposts'    => $limit,
		); $i = 0;

		$attachments = get_posts($args);
		if ($attachments) {

			foreach ($attachments as $attachment) {

				$url = wp_get_attachment_url($attachment->ID);
				array_push($arr, $url);

		}
			return $arr;
		}
		return false;
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function classifiedTheme_add_theme_styles()
{
		global $wp_query;
		$new_advert_step 	= $wp_query->query_vars['step'];
		$a_action			= $wp_query->query_vars['a_action'];

		wp_register_style( 'bx_styles', get_bloginfo('template_url').'/css/bx_styles.css', array(), '20120822', 'all' );
		wp_register_script( 'social_pr', get_bloginfo('template_url').'/js/connect.js');

		wp_register_script( 'easing', get_bloginfo('template_url').'/js/jquery.easing.1.3.js');
		wp_register_script( 'bx_slider', get_bloginfo('template_url').'/js/jquery.bxSlider.min.js');


	wp_register_style( 'bootstrap_style1', get_bloginfo('template_url').'/css/bootstrap_min.css', array(), '20120822', 'all' );
  	wp_register_style( 'bootstrap_style2', get_bloginfo('template_url').'/css/css.css', array(), '20120822', 'all' );
	wp_register_style( 'bootstrap_style3', get_bloginfo('template_url').'/css/bootstrap_responsive.css', array(), '20120822', 'all' );
	wp_register_style( 'bootstrap_ie6', 	get_bloginfo('template_url').'/css/bootstrap_ie6.css', array(), '20120822', 'all' );
	wp_register_style( 'bootstrap_gal', 	get_bloginfo('template_url').'/css/bootstrap_gal.css', array(), '20120822', 'all' );
	wp_register_style( 'fileupload_ui', 	get_bloginfo('template_url').'/css/fileupload_ui.css', array(), '20120822', 'all' );
	wp_register_style( 'uploadify_css', 	get_bloginfo('template_url').'/lib/uploadify/uploadify.css', array(), '20120822', 'all' );

		wp_register_script( 'html5_js', get_bloginfo('template_url').'/js/html5.js');
	wp_register_script( 'jquery_ui', get_bloginfo('template_url').'/js/vendor/jquery.ui.widget.js');
	wp_register_script( 'templ_min', get_bloginfo('template_url').'/js/templ.min.js');
	wp_register_script( 'load_image', get_bloginfo('template_url').'/js/load_image.min.js');
	wp_register_script( 'canvas_to_blob', get_bloginfo('template_url').'/js/canvas_to_blob.js');
	wp_register_script( 'iframe_transport', get_bloginfo('template_url').'/js/jquery.iframe-transport.js');


	wp_register_style( 'fileupload_ui', 	get_bloginfo('template_url').'/css/fileupload_ui.css', array(), '20120822', 'all' );
	wp_register_script( 'fileupload_main', get_bloginfo('template_url').'/js/jquery.fileupload.js');
	wp_register_script( 'fileupload_fp', get_bloginfo('template_url').'/js/jquery.fileupload-fp.js');
	wp_register_script( 'fileupload_ui', get_bloginfo('template_url').'/js/jquery.fileupload-ui.js');
	wp_register_script( 'main_thing', get_bloginfo('template_url').'/js/main.js');
	wp_register_script( 'locale_thing', get_bloginfo('template_url').'/js/locale.js');

	wp_register_style( 'mega_menu_thing', 	get_bloginfo('template_url').'/css/menu.css', array(), '20120822', 'all' );
	wp_enqueue_script( 'dcjqmegamenu', get_bloginfo('template_url') . '/js/jquery.dcmegamenu.1.3.4.min.js', array('jquery') );
	wp_enqueue_script( 'jqueryhoverintent', get_bloginfo('template_url') . '/js/jquery.hoverIntent.minified.js', array('jquery') );

		global $wp_styles, $wp_scripts;
		// enqueing:

		 wp_enqueue_style( 'bx_styles' );
		 wp_enqueue_script( 'social_pr' );
		 wp_enqueue_script( 'easing' );
		 wp_enqueue_script( 'bx_slider' );
		 wp_enqueue_script( 'dcjqmegamenu' );
		 wp_enqueue_script( 'jqueryhoverintent' );
		 wp_enqueue_style( 'mega_menu_thing' );

		 if($new_advert_step == "2" or $a_action == "edit_ad" or $a_action == "repost_ad"):

		 	  	// enqueing:
	  	wp_enqueue_style( 'bootstrap_style1' );
	 	//wp_enqueue_style( 'bootstrap_style2' );
		//wp_enqueue_style( 'bootstrap_style3' );
		//wp_enqueue_style( 'bootstrap_ie6' );
		//wp_enqueue_style( 'bootstrap_gal' );
		wp_enqueue_style( 'fileupload_ui' );
		wp_enqueue_style( 'uploadify_css' );

		 wp_enqueue_script( 'html5_js' );
		 wp_enqueue_script( 'jquery_ui' );
		 wp_enqueue_script( 'templ_min' );
		 wp_enqueue_script( 'load_image' );
		 wp_enqueue_script( 'canvas_to_blob' );
		 wp_enqueue_script( 'iframe_transport' );

		 wp_enqueue_script( 'fileupload_main' );
		 wp_enqueue_script( 'fileupload_fp' );
		 wp_enqueue_script( 'fileupload_ui' );
		 wp_enqueue_script( 'locale_thing' );
		 wp_enqueue_script( 'main_thing' );
		 wp_enqueue_script( 'uploadify_js' );

	//$wp_styles->add_data('bootstrap_ie6', 'conditional', 'lte IE 7');

		endif;


}

function ClassifiedTheme_get_images_cost_extra($pid)
{
	$ClassifiedTheme_charge_fees_for_images 	= get_option('ClassifiedTheme_charge_fees_for_images');
	$ClassifiedTheme_extra_image_charge		= get_option('ClassifiedTheme_extra_image_charge');


	if($ClassifiedTheme_charge_fees_for_images == "yes")
	{
		$ClassifiedTheme_nr_of_free_images = get_option('ClassifiedTheme_nr_of_free_images');
		if(empty($ClassifiedTheme_nr_of_free_images)) $ClassifiedTheme_nr_of_free_images = 1;

		$ClassifiedTheme_get_post_nr_of_images = ClassifiedTheme_get_post_nr_of_images($pid);

		$nr_imgs = $ClassifiedTheme_get_post_nr_of_images - $ClassifiedTheme_nr_of_free_images;
		if($nr_imgs > 0)
		{
			return $nr_imgs*	$ClassifiedTheme_extra_image_charge;
		}

	}

	return 0;

}

function ClassifiedTheme_get_listing_primary_cat($pid)
{
	$ad_terms = wp_get_object_terms($pid, 'ad_cat');
	if(is_array($ad_terms))
	{
		return 	$ad_terms[0]->term_id;
	}

	return 0;
}


/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_slider_post()
{
	do_action('classifiedTheme_slider_post');
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_price($pid)
{
	return get_post_meta($pid, 'price', true);
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function get_listing_fields_values($pid)
	{
		$cat = wp_get_object_terms($pid, 'ad_cat');

		$catid = $cat[0]->term_id ;

		global $wpdb;
		$s = "select * from ".$wpdb->prefix."ad_custom_fields order by ordr asc "; //where cate='all' OR cate like '%|$catid|%' order by ordr asc";
		$r = $wpdb->get_results($s);



		$arr = array();
		$i = 0;

		foreach($r as $row) // = mysql_fetch_object($r))
		{

			$pmeta = get_post_meta($pid, "custom_field_ID_".$row->id);

			if(!empty($pmeta) && count($pmeta) > 0)
			{
			 	$arr[$i]['field_name']  = $row->name;

				if(!empty($pmeta))
				{
					$arr[$i]['field_name']  = $row->name;
					$arr[$i]['field_value'] = $pmeta;
					$i++;
				}


			}
		}

		return $arr;
	}

function ClassifiedTheme_generate_thumb($img_url, $width, $height, $cut = true)
{


	require_once(ABSPATH . '/wp-admin/includes/image.php');
	$uploads = wp_upload_dir();
	$basedir = $uploads['basedir'].'/';
	$exp = explode('/',$img_url);

	$nr = count($exp);
	$pic = $exp[$nr-1];
	$year = $exp[$nr-3];
	$month = $exp[$nr-2];

	if($uploads['basedir'] == $uploads['path'])
	{
		$img_url = $basedir.'/'.$pic;
		$ba = $basedir.'/';
		$iii = $uploads['url'];
	}
	else
	{
		$img_url = $basedir.$year.'/'.$month.'/'.$pic;
		$ba = $basedir.$year.'/'.$month.'/';
		$iii = $uploads['baseurl']."/".$year."/".$month;
	}
	list($width1, $height1, $type1, $attr1) = getimagesize($img_url);

	//return $height;
	$a = false;
	if($width == -1)
	{
		$a = true;

	}


	if($width > $width1) $width = $width1-1;
	if($height > $height1) $height = $height1-1;

	if($a == true)
	{
		$prop = $width1 / $height1;
		$width = round($prop * $height);
	}

		$width = $width-1;
	$height = $height-1;

	//-------------



	//-------------

	$xxo = "-".$width."x".$height;
	$exp = explode(".", $pic);

	$exps = preg_replace ( '/[^a-zA-Z0-9]/u', '_', $exp[0] );

	$new_name = $exps.$xxo.".".$exp[count($exp) - 1];

	$tgh = str_replace("//","/",$ba.$new_name);

	if(file_exists($tgh)) return $iii."/".$new_name;



	$thumb = image_resize($img_url,$width,$height,$cut);

	if(is_wp_error($thumb)) return "is-wp-error";

	$exp = explode($basedir, $thumb);
    return $uploads['baseurl']."/".$exp[1];
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_get_first_post_image($pid, $w = 100, $h = 100)
{

	//---------------------
	// build the exclude list
	$exclude = array();

	$args = array(
	'order'          => 'ASC',
	'post_type'      => 'attachment',
	'post_parent'    => get_the_ID(),
	'meta_key'		 => 'another_reserved1',
	'meta_value'	 => '1',
	'numberposts'    => -1,
	'post_status'    => null,
	);
	$attachments = get_posts($args);
	if ($attachments) {
	    foreach ($attachments as $attachment) {
		$url = $attachment->ID;
		array_push($exclude, $url);
	}
	}

	//-----------------

	$args = array(
	'order'          => 'ASC',
	'orderby'        => 'post_date',
	'post_type'      => 'attachment',
	'post_parent'    => $pid,
	'exclude'    		=> $exclude,
	'post_mime_type' => 'image',
	'post_status'    => null,
	'numberposts'    => 1,
	);
	$attachments = get_posts($args);
	if ($attachments) {
	    foreach ($attachments as $attachment)
	    {
			$url = wp_get_attachment_url($attachment->ID);
			return ClassifiedTheme_generate_thumb($url, $w, $h);
		}
	}
	else{
			return get_bloginfo('template_url').'/images/nopic.png';

	}
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function classifiedTheme_set_metaboxes()
{

	    add_meta_box( 'custom_ad_fields', 	'Listing Custom Fields',			'classifiedTheme_custom_fields_html', 	'ad', 'advanced',	'high' );
		add_meta_box( 'ad_images', 			'Listing Images',					'classifiedTheme_theme_ad_images', 		'ad', 'advanced',	'high' );
		add_meta_box( 'ad_dets', 			'Listing Details',					'classifiedTheme_theme_ad_dts', 		'ad', 'side',		'high' );


}


/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_my_admin_head() {
 ?>
 <script type="text/javascript" language="javascript">

 var $ = jQuery;

 </script>

 <?php
    }
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_theme_ad_dts()
{

	global $post;
	$pid = $post->ID;
	$price = get_post_meta($pid, "price", true);
	$location = get_post_meta($pid, "Location", true);
	$f = get_post_meta($pid, "featured", true);
	$t = get_post_meta($pid, "closed", true);
	$paid = get_post_meta($pid, "paid", true);

	?>
	<table> <input type="hidden" name="fromadmin" value="1" />

          <tr><td>










       <?php _e("Ad Ending On",'ClassifiedTheme'); ?>:</td>
        <td><input type="text" name="ending" id="ending" value="<?php

		$d = get_post_meta($pid,'ending',true);

		if(!empty($d)) {
		$r = date_i18n('m/d/Y', $d);
		echo $r;
		}
		 ?>" class="do_input"  /></td>
        </tr>

 <script>

jQuery(document).ready(function() {
	 jQuery('#ending').datepicker({

});});

 </script>



	<tr>
	<td><?php _e("Price", "ClassifiedTheme");?>: </td>
	<td> <input type="text" size="8" value="<?php echo $price; ?>" name="price" /> <?php echo classifiedTheme_currency(); ?>
	</td>
	</tr>

	<tr>
	<td> <?php _e("Address", "ClassifiedTheme");?>: </td>
	<td> <input type="text" size="8" value="<?php echo $location; ?>" name="Location" />
	</td>
	</tr>
	<tr>
	<td> <?php _e("Feature this ad", "ClassifiedTheme");?>: </td>
	<td> <input type="checkbox" value="1" name="featureds"
	<?php

	if($f == '1')
	{
		echo ' checked="checked" ';

	}
	?>
	 />
	</td>
	</tr>


	<tr>
	<td>Closed?: </td>
	<td> <input type="checkbox" value="1" name="closed"
	<?php

	if($t == '1')
	{
		echo ' checked="checked" ';

	}
	?>
	 />
	</td>
	</tr>


    <tr>
	<td>Paid?: </td>
	<td> <input type="checkbox" value="1" name="paid"
	<?php

	if($paid == '1')
	{
		echo ' checked="checked" ';

	}
	?>
	 />
	</td>
	</tr>

	</table>

	<?php

}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_custom_fields_html()
{

	global $post, $wpdb;
	$pid = $post->ID;
	?>
    <table width="100%">
    <input type="hidden" value="1" name="fromadmin" />
	<?php
		$cat 		  	= wp_get_object_terms($pid, 'ad_cat');
		$catidarr 		= array();

		foreach($cat as $catids)
		{
			$catidarr[] = $catids->term_id;
		}

		$arr 	= get_listing_category_fields($catidarr, $pid);

		for($i=0;$i<count($arr);$i++)
		{

			        echo '<tr>';
					echo '<td>'.$arr[$i]['field_name'].$arr[$i]['id'].':</td>';
					echo '<td>'.$arr[$i]['value'];
					do_action('ClassifiedTheme_step3_after_custom_field_'.$arr[$i]['id'].'_field');
					echo '</td>';
					echo '</tr>';


		}

	?>


    </table>
    <?php
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_theme_ad_images()
{

	global $current_user;
	get_currentuserinfo();
	$cid = $current_user->ID;

	global $post;
	$pid = $post->ID;


?>


    <script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/lib/uploadify/jquery.uploadify-3.1.js"></script>
	<link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/lib/uploadify/uploadify.css" type="text/css" />

    <script type="text/javascript">

	function delete_this(id)
	{
		 jQuery.ajax({
						method: 'get',
						url : '<?php echo get_bloginfo('siteurl');?>/index.php/?_ad_delete_pid='+id,
						dataType : 'text',
						success: function (text) {   jQuery('#image_ss'+id).remove();  }
					 });
		  //alert("a");

	}



	jQuery(function() {

		jQuery("#fileUpload3").uploadify({
			height        : 30,
			auto:			true,
			swf           : '<?php echo get_bloginfo('template_url'); ?>/lib/uploadify/uploadify.swf',
			uploader      : '<?php echo get_bloginfo('template_url'); ?>/lib/uploadify/uploady.php',
			width         : 120,
			fileTypeExts  : '*.jpg;*.jpeg;*.gif;*.png',
			formData    : {'ID':<?php echo $pid; ?>,'author':<?php echo $cid; ?>},
			onUploadSuccess : function(file, data, response) {

			//alert(data);
			var bar = data.split("|");

jQuery('#thumbnails').append('<div class="div_div" id="image_ss'+bar[1]+'" ><img width="70" class="image_class" height="70" src="' + bar[0] + '" /><a href="javascript: void(0)" onclick="delete_this('+ bar[1] +')"><img border="0" src="<?php echo get_bloginfo('template_url'); ?>/images/delete_icon.png" border="0" /></a></div>');
}



    	});


	});


	</script>

    <style type="text/css">
	.div_div
	{
		margin-left:5px; float:left;
		width:110px;margin-top:10px;
	}

	</style>

    <div id="fileUpload3">You have a problem with your javascript</div>
    <div id="thumbnails" style="overflow:hidden;margin-top:20px">

    <?php

		$args = array(
		'order'          => 'ASC',
		'orderby'        => 'post_date',
		'post_type'      => 'attachment',
		'post_parent'    => $post->ID,
		'post_mime_type' => 'image',
		'numberposts'    => -1,
		); $i = 0;

		$attachments = get_posts($args);



	if ($attachments) {
	    foreach ($attachments as $attachment) {
		$url = wp_get_attachment_url($attachment->ID);

			echo '<div class="div_div"  id="image_ss'.$attachment->ID.'"><img width="70" class="image_class" height="70" src="' .
			ClassifiedTheme_generate_thumb($url, 70, 70). '" />
			<a href="javascript: void(0)" onclick="delete_this(\''.$attachment->ID.'\')"><img border="0" src="'.get_bloginfo('template_url').'/images/delete_icon.png" /></a>
			</div>';

	}
	}


	?>

    </div>

<?php

}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function ClassifiedTheme_get_auto_draft($uid)
{
	global $wpdb;
	$querystr = "
		SELECT distinct wposts.*
		FROM $wpdb->posts wposts where
		wposts.post_author = '$uid' AND wposts.post_status = 'auto-draft'
		AND wposts.post_type = 'ad'
		ORDER BY wposts.ID DESC LIMIT 1 ";

	$row = $wpdb->get_results($querystr, OBJECT);
	if(count($row) > 0)
	{
		$row = $row[0];
		return $row->ID;
	}

	return ClassifiedTheme_create_auto_draft($uid);
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_create_auto_draft($uid)
{
		$my_post = array();
		$my_post['post_title'] 		= 'Auto Draft';
		$my_post['post_type'] 		= 'ad';
		$my_post['post_status'] 	= 'auto-draft';
		$my_post['post_author'] 	= $uid;
		$pid =  wp_insert_post( $my_post, true );

		update_post_meta($pid,'base_fee_paid', 		"0");
		update_post_meta($pid,'featured_paid', 	"0");


		return $pid;

}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
 add_action( 'admin_init', 'CLASS_THM_my_plugin_admin_init' );

 function CLASS_THM_my_plugin_admin_init() {

	    wp_enqueue_style('thickbox'); // call to media files in wp
		wp_enqueue_script('thickbox');
		wp_enqueue_script( 'media-upload');

    }

function classifiedTheme_th_send_to_editor( $html, $id ) {
    return str_replace( '<a href', '<a data-id="' . $id . '" href', $html );
}

add_filter( 'image_send_to_editor', 'classifiedTheme_th_send_to_editor', 10, 2 );

function classifiedTheme_get_users_links()
{
		global $current_user, $wpdb;
		get_currentuserinfo();

		?>

        <div id="right-sidebar" style="float:left">
			<ul class="xoxo">

            <li class="widget-container widget_text"><h3 class="widget-title"><?php _e("My Account Menu",'ClassifiedTheme'); ?></h3>
			<p>

        	<ul id="my-account-admin-menu">
		<li><a href="<?php echo classifiedTheme_my_account_link(); ?>"><?php _e("MyAccount Home", "ClassifiedTheme");?></a></li>
		<li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_post_new_page_id')); ?>"><?php _e("Post New Listing", "ClassifiedTheme");?></a></li>
		<li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_my_account_messages_id')); ?>"><?php _e("Messages", "ClassifiedTheme");?></a></li>
        <li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_my_account_personal_info_id')); ?>"><?php _e("Personal Info", "ClassifiedTheme");?></a></li>
        <li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_my_account_mem_pks_id')); ?>"><?php _e("Membership Packages", "ClassifiedTheme");?></a></li>
		<li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_my_account_active_ads_id')); ?>"><?php _e("My Active Listings", "ClassifiedTheme");?></a></li>
		<li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_my_account_expired_ads_id')); ?>"><?php _e("Expired Listings", "ClassifiedTheme");?></a></li>
		<li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_my_account_unpub_ads_id')); ?>"><?php _e("Unpublished Listings", "ClassifiedTheme");?></a></li>

	<?php do_action('ClassifiedTheme_add_new_items_my_account_menu'); ?>

		<li><a href="<?php echo wp_logout_url(); ?>"><?php _e("Log Out", "ClassifiedTheme");?></a></li>
	</ul>

       </p></li>


    </ul>

    </div>

        <?php


}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_clear_sums_of_cash($cash)
{
	$cash = str_replace(" ","",$cash);
	$cash = str_replace(",","",$cash);
	//$cash = str_replace(".","",$cash);

	return strip_tags($cash);
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_template_redirect()
{
	global $wp;
	global $wp_query, $wp_rewrite, $post;
	$paagee 	=  $wp_query->query_vars['my_custom_page_type'];
	$a_action 	=  $wp_query->query_vars['a_action'];

	$my_pid = $post->ID;
	$parent = $post->post_parent;

	$ClassifiedTheme_post_new_page_id 					= get_option('ClassifiedTheme_post_new_page_id');
	$ClassifiedTheme_my_account_page_id					= get_option('ClassifiedTheme_my_account_page_id');

	//---------------------------------------
	if(isset($_GET['_delete_custom_id']))
	{
		global $wpdb;
		$ids = $_GET['_delete_custom_id'];
		$s2 = "delete from ".$wpdb->prefix."ad_custom_options where id='$ids'";
		$wpdb->query($s2);

		die();
	}


	if(isset($_GET['update_custom_id']))
	{
		global $wpdb;
		$ids = $_GET['update_custom_id'];
		$ord = $_GET['order'];

		$s2 = "update ".$wpdb->prefix."ad_custom_options set ordr='$ord' where id='$ids'";
		$wpdb->query($s2);

		echo 'done';
		die();
	}



	if(isset($_GET['get_subcats_for_me']))
		{
			$cat_id = $_POST['queryString'];
			if(empty($cat_id) ) { echo " "; }
			else
			{

				$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat_id;
				$sub_terms2 = get_terms( 'ad_cat', $args2 );

				if(count($sub_terms2) > 0)
				{

					$ret = '<select class="do_input" name="subcat" onChange="display_subcat_a2(this.value)">';
					$ret .= '<option value="">'.__('Select Subcategory','ClassifiedTheme'). '</option>';

					foreach ( $sub_terms2 as $sub_term2 )
					{
						$sub_id2 = $sub_term2->term_id;
						$ret .= '<option '.($selected == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

					}
					$ret .= "</select>";
					echo $ret;

				}
			}

			die();
		}




	if(isset($_GET['get_subcats_for_me2a']))
		{
			$cat_id = $_POST['queryString'];
			if(empty($cat_id) ) { echo " "; }
			else
			{

				$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat_id;
				$sub_terms2 = get_terms( 'ad_cat', $args2 );

				if(count($sub_terms2) > 0)
				{

					$ret = '<select class="do_input" name="subcat2">';
					$ret .= '<option value="">'.__('Select Subcategory','ClassifiedTheme'). '</option>';

					foreach ( $sub_terms2 as $sub_term2 )
					{
						$sub_id2 = $sub_term2->term_id;
						$ret .= '<option '.($selected == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

					}
					$ret .= "</select>";
					echo $ret;

				}
			}

			die();
		}




		if(isset($_GET['get_locscats_for_me']))
		{
			$cat_id = $_POST['queryString'];
			if(empty($cat_id) ) { echo " "; }
			else
			{

				$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat_id;
				$sub_terms2 = get_terms( 'ad_location', $args2 );

				if(count($sub_terms2) > 0)
				{

					$ret = '<select class="do_input" name="subloc" onchange="display_subcat3(this.value)">';
					$ret .= '<option value="">'.__('Select Sublocation','ClassifiedTheme'). '</option>';

					foreach ( $sub_terms2 as $sub_term2 )
					{
						$sub_id2 = $sub_term2->term_id;
						$ret .= '<option '.($selected == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

					}
					$ret .= "</select>";
					echo $ret;

				}
			}

			die();
		}

		if(isset($_GET['get_locscats_for_me2']))
		{
			$cat_id = $_POST['queryString'];
			if(empty($cat_id) ) { echo " "; }
			else
			{

				$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat_id;
				$sub_terms2 = get_terms( 'ad_location', $args2 );

				if(count($sub_terms2) > 0)
				{

					$ret = '<select class="do_input" name="subloc2" >';
					$ret .= '<option value="">'.__('Select Sublocation','ClassifiedTheme'). '</option>';

					foreach ( $sub_terms2 as $sub_term2 )
					{
						$sub_id2 = $sub_term2->term_id;
						$ret .= '<option '.($selected == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

					}
					$ret .= "</select>";
					echo $ret;

				}
			}

			die();
		}

	if(isset($_GET['set_image_for_term']))
	{
		if(is_user_logged_in())
		{
			$term_id = $_GET['term_id'];
			$attachment_id = $_GET['att_id'];
			update_post_meta($attachment_id, 'category_image', $term_id);
		}

		die();
	}

	if(isset($_GET['set_image_for_term2']))
	{
		if(is_user_logged_in())
		{
			$term_id = $_GET['term_id'];
			$attachment_id = $_GET['att_id'];
			update_post_meta($attachment_id, 'category_icon', $term_id);
		}

		die();
	}

	if(isset($_GET['switch_to_view']))
	{
		//classifiedTheme_get_view_grd

		$_SESSION['view_tp'] = $_GET['switch_to_view'];
		wp_redirect($_GET['ret_u']);
		exit;
	}

	if($parent == $ClassifiedTheme_my_account_page_id or $ClassifiedTheme_my_account_page_id == $my_pid )
	{

		if(!is_user_logged_in())	{ wp_redirect(ClassifiedTheme_login_url()); exit; }
	}

	if($my_pid == $ClassifiedTheme_post_new_page_id)
	{
		if(!is_user_logged_in())	{ wp_redirect(ClassifiedTheme_login_url()); exit; }

		if(!isset($_GET['ad_id'])) $set_ad = 1; else $set_ad = 0;
		global $current_user;
		get_currentuserinfo();

		if(!empty($_GET['ad_id']))
		{
			$my_main_post = get_post($_GET['ad_id']);
			if($my_main_post->post_author != $current_user->ID)
			{
				wp_redirect(get_bloginfo('siteurl')); exit;
			}

		}

		if($set_ad == 1)
		{
			$pid 		= ClassifiedTheme_get_auto_draft($current_user->ID);
			wp_redirect(ClassifiedTheme_post_new_with_pid_stuff_thg($pid));
		}

		include 'lib/post_new_post.php';
	}

	if($a_action == 'cl_style_location')
	{
		include 'lib/cl_style_location.php';
		die();
	}

	if($a_action == 'user_profile')
	{
		include 'lib/user_profile.php';
		die();
	}

	if($a_action == 'edit_ad')
	{
		include 'lib/my_account/edit_ad.php';
		die();
	}

	if($a_action == 'close_ad')
	{
		include 'lib/my_account/close_ad.php';
		die();
	}

	if($a_action == 'delete_ad')
	{
		include 'lib/my_account/delete_ad.php';
		die();
	}

	if($a_action == 'paypal_listing')
	{
		include 'lib/gateways/paypal_listing.php';
		die();
	}

	if($a_action == 'paypal_mem')
	{
		include 'lib/gateways/paypal_mem.php';
		die();
	}

	if($a_action == 'mb_listing')
	{
		include 'lib/gateways/moneybookers_listing.php';
		die();
	}

	if($a_action == 'payza_listing_response')
	{
		include 'lib/gateways/payza_listing_response.php';
		die();
	}



	if($a_action == 'payza_listing')
	{
		include 'lib/gateways/payza_listing.php';
		die();
	}



	if($a_action == 'mb_mem_response')
	{
		include 'lib/gateways/mb_mem_response.php';
		die();
	}


	if($a_action == 'mb_listing_response')
	{
		include 'lib/gateways/moneybookers_listing_response.php';
		die();
	}


	if(isset($_GET['_ad_delete_pid']))
	{
		if(is_user_logged_in())
		{
			$pid	= $_GET['_ad_delete_pid'];
			$pstpst = get_post($pid);
			global $current_user;
			get_currentuserinfo();

			if($pstpst->post_author == $current_user->ID)
			{
				wp_delete_post($_GET['_ad_delete_pid']);
				echo "done";
			}
		}
		exit;
	}

}

if(!function_exists('classifiedTheme_get_post_function_grid'))
{
function classifiedTheme_get_post_function_grid( $arr = '')
{

			if($arr[0] == "winner") $pay_this_me = 1;
			if($arr[0] == "unpaid") $unpaid = 1;

			$paid = get_post_meta(get_the_ID(),'paid',true);

			$ending 		= get_post_meta(get_the_ID(), 'ending', true);
			$sec 			= $ending - current_time('timestamp',0);
			$location 		= get_post_meta(get_the_ID(), 'Location', true);
			$closed 		= get_post_meta(get_the_ID(), 'closed', true);
			$post 			= get_post(get_the_ID());
			$only_buy_now 	= get_post_meta(get_the_ID(), 'only_buy_now', true);
			$buy_now 		= get_post_meta(get_the_ID(), 'buy_now', true);
			$featured 		= get_post_meta(get_the_ID(), 'featured', true);
			//$current_bid 		= get_post_meta(get_the_ID(), 'current_bid', true);

			$post = get_post(get_the_ID());

			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;

			$pid = get_the_ID();

?>
				<div class="post_grid" id="post-ID-<?php the_ID(); ?>">


                <?php if($featured == "1"): ?>
                <div class="featured-two"></div>
                <?php endif; ?>



                <div class="padd10_a">
                <div class="image_holder_grid">
                <a href="<?php the_permalink(); ?>"><img src="<?php echo ClassifiedTheme_get_first_post_image(get_the_ID(),125,85); ?>" class="img_class" /></a>


                </div>
                <div  class="title_holder_grid" >
                     <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
                        <?php


                        the_title();


                        ?></a></h2>


               </div>

                    <div class="details_holder_grid">


                  <ul class="ad-details1">
							<li>

								<p><?php echo classifiedTheme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
                                <?php if($only_buy_now == '1') : ?>

                                [<?php _e("Read More",'ClassifiedTheme'); ?>]
                                <?php endif; ?>
                                </p>
							</li>



						</ul>


                  </div>

                     </div></div>
<?php
} }

function classifiedtheme_formats_special($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always

	$dec_sep = '.';
	$tho_sep = ',';

  //dec,thou

  if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $money = ($cents == 2 ? '0'.$dec_sep.'00' : '0'); // output zero
    } else { // value
      if (floor($number) == $number) { // whole number
        $money = number_format($number, ($cents == 2 ? 2 : 0), $dec_sep, '' ); // format
      } else { // cents
        $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2), $dec_sep, '' ); // format
      } // integer or decimal
    } // value
    return $money;
  } // numeric
} // formatMoney


function ClassifiedTheme_login_url()
{
	return get_bloginfo('siteurl') . "/wp-login.php";
}

function ClassifiedTheme_replace_stuff_for_me($find, $replace, $subject)
{
	$i = 0;
	foreach($find as $item)
	{
		$replace_with = $replace[$i];
		$subject = str_replace($item, $replace_with, $subject);
		$i++;
	}

	return $subject;
}

function ClassifiedTheme_send_email($recipients, $subject = '', $message = '') {

	$ClassifiedTheme_email_addr_from 	= get_option('ClassifiedTheme_email_addr_from');
	$ClassifiedTheme_email_name_from  	= get_option('ClassifiedTheme_email_name_from');
	$ClassifiedTheme_use_no_personal_headers  	= get_option('ClassifiedTheme_use_no_personal_headers');

	$message = stripslashes($message);
	$subject = stripslashes($subject);

	if(empty($ClassifiedTheme_email_name_from)) $ClassifiedTheme_email_name_from  = "Classified Theme";
	if(empty($ClassifiedTheme_email_addr_from)) $ClassifiedTheme_email_addr_from  = "ClassifiedTheme@wordpress.org";

	$headers = 'From: '. $ClassifiedTheme_email_name_from .' <'. $ClassifiedTheme_email_addr_from .'>' . PHP_EOL;
	$ClassifiedTheme_allow_html_emails = get_option('ClassifiedTheme_allow_html_emails');
	if($ClassifiedTheme_allow_html_emails != "yes") $html = false;
	else $html = true;

	$ok_send_email = true;
	$ok_send_email = apply_filters('ClassifiedTheme_ok_to_send_emails', $ok_send_email);

	if($ok_send_email == true)
	{
		if ($html) {
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: " . get_bloginfo('html_type') . "; charset=\"". get_bloginfo('charset') . "\"\n";
			$mailtext = "<html><head><title>" . $subject . "</title></head><body>" . nl2br($message) . "</body></html>";
			$mailtext2 =  str_replace("<br />",'',$message);

			if($ClassifiedTheme_use_no_personal_headers == "yes")
				return wp_mail($recipients, $subject, $mailtext2);
			else
				return wp_mail($recipients, $subject, $mailtext, $headers);

		} else {
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: text/plain; charset=\"". get_bloginfo('charset') . "\"\n";
			$message = preg_replace('|&[^a][^m][^p].{0,3};|', '', $message);
			$message = preg_replace('|&amp;|', '&', $message);
			$mailtext = wordwrap(strip_tags($message), 80, "\n");

			if($ClassifiedTheme_use_no_personal_headers == "yes")
				return wp_mail($recipients, stripslashes($subject), stripslashes($mailtext));
			else
				return wp_mail($recipients, stripslashes($subject), stripslashes($mailtext), $headers);
		}

	}

}


function ClassifiedTheme_send_email_expiry_advert_notice($pid)
{
	$enable 	= get_option('ClassifiedTheme_send_expire_notice_enable');
	$subject 	= get_option('ClassifiedTheme_send_expire_notice_subject');
	$message 	= get_option('ClassifiedTheme_send_expire_notice_message');

	if($enable != "no"):

		$post 			= get_post($pid);
		$user 			= get_userdata($post->post_author);
		$site_login_url = ClassifiedTheme_login_url();
		$site_name 		= get_bloginfo('name');
		$account_url 	= get_permalink(get_option('ClassifiedTheme_my_account_page_id'));


		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##item_name##', '##item_link##');
   		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid));

		$tag		= 'ClassifiedTheme_send_email_expiry_advert_notice';
		$find 		= apply_filters( $tag . '_find', 	$find );
		$replace 	= apply_filters( $tag . '_replace', $replace );

		$message 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $message);
		$subject 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $subject);

		//---------------------------------------------

		ClassifiedTheme_send_email($user->user_email, $subject, $message);

	endif;

}


function ClassifiedTheme_send_email_posted_item_not_approved($pid)
{
	$enable 	= get_option('ClassifiedTheme_new_item_email_not_approved_enable');
	$subject 	= get_option('ClassifiedTheme_new_item_email_not_approved_subject');
	$message 	= get_option('ClassifiedTheme_new_item_email_not_approved_message');

	if($enable != "no"):

		$post 			= get_post($pid);
		$user 			= get_userdata($post->post_author);
		$site_login_url = ClassifiedTheme_login_url();
		$site_name 		= get_bloginfo('name');
		$account_url 	= get_permalink(get_option('ClassifiedTheme_my_account_page_id'));


		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##item_name##', '##item_link##');
   		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid));

		$tag		= 'ClassifiedTheme_send_email_posted_item_not_approved_admin';
		$find 		= apply_filters( $tag . '_find', 	$find );
		$replace 	= apply_filters( $tag . '_replace', $replace );

		$message 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $message);
		$subject 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $subject);

		//---------------------------------------------

		ClassifiedTheme_send_email($user->user_email, $subject, $message);

	endif;

}

function ClassifiedTheme_send_email_posted_item_not_approved_admin($pid)
{
	$enable 	= get_option('ClassifiedTheme_new_item_email_not_approve_admin_enable');
	$subject 	= get_option('ClassifiedTheme_new_item_email_not_approve_admin_subject');
	$message 	= get_option('ClassifiedTheme_new_item_email_not_approve_admin_message');

	if($enable != "no"):

		$post 			= get_post($pid);
		$user 			= get_userdata($post->post_author);
		$site_login_url = ClassifiedTheme_login_url();
		$site_name 		= get_bloginfo('name');
		$account_url 	= get_permalink(get_option('ClassifiedTheme_my_account_page_id'));


		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##item_name##', '##item_link##');
   		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid));

		$tag		= 'ClassifiedTheme_send_email_posted_item_not_approved_admin';
		$find 		= apply_filters( $tag . '_find', 	$find );
		$replace 	= apply_filters( $tag . '_replace', $replace );

		$message 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $message);
		$subject 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $subject);

		//---------------------------------------------

		$email = get_bloginfo('admin_email');
		ClassifiedTheme_send_email($email, $subject, $message);

	endif;

}

function ClassifiedTheme_send_email_posted_item_approved($pid)
{
	$enable 	= get_option('ClassifiedTheme_new_item_email_approved_enable');
	$subject 	= get_option('ClassifiedTheme_new_item_email_approved_subject');
	$message 	= get_option('ClassifiedTheme_new_item_email_approved_message');

	if($enable != "no"):

		$post 			= get_post($pid);
		$user 			= get_userdata($post->post_author);
		$site_login_url = ClassifiedTheme_login_url();
		$site_name 		= get_bloginfo('name');
		$account_url 	= get_permalink(get_option('ClassifiedTheme_my_account_page_id'));

		$post 		= get_post($pid);
		$item_name 	= $post->post_title;
		$item_link 	= get_permalink($pid);

		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##item_name##', '##item_link##');
   		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $item_name, $item_link);

		$tag		= 'ClassifiedTheme_send_email_posted_item_approved';
		$find 		= apply_filters( $tag . '_find', 	$find );
		$replace 	= apply_filters( $tag . '_replace', $replace );

		$message 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $message);
		$subject 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $subject);

		//---------------------------------------------

		$email = $user->user_email;
		ClassifiedTheme_send_email($email, $subject, $message);

	endif;

}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_send_email_posted_item_approved_admin($pid)
{
	$enable 	= get_option('ClassifiedTheme_new_item_email_approve_admin_enable');
	$subject 	= get_option('ClassifiedTheme_new_item_email_approve_admin_subject');
	$message 	= get_option('ClassifiedTheme_new_item_email_approve_admin_message');

	if($enable != "no"):

		$post 			= get_post($pid);
		$user 			= get_userdata($post->post_author);
		$site_login_url = ClassifiedTheme_login_url();
		$site_name 		= get_bloginfo('name');
		$account_url 	= get_permalink(get_option('ClassifiedTheme_my_account_page_id'));


		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##item_name##', '##item_link##');
   		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid));

		$tag		= 'ClassifiedTheme_send_email_posted_item_approved_admin';
		$find 		= apply_filters( $tag . '_find', 	$find );
		$replace 	= apply_filters( $tag . '_replace', $replace );

		$message 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $message);
		$subject 	= ClassifiedTheme_replace_stuff_for_me($find, $replace, $subject);

		//---------------------------------------------

		$email = get_bloginfo('admin_email');
		ClassifiedTheme_send_email($email, $subject, $message);

	endif;
}


/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function ClassifiedTheme_prepare_seconds_to_words($seconds)
	{
		$res = ClassifiedTheme_seconds_to_words_new($seconds);
		if($res == "Expired") return __('Expired','ClassifiedTheme');

		if($res[0] == 0) return sprintf(__("%s hours, %s min, %s sec",'ClassifiedTheme'), $res[1], $res[2], $res[3]);
		if($res[0] == 1){

			$plural = $res[1] > 1 ? __('days','ClassifiedTheme') : __('day','ClassifiedTheme');
			return sprintf(__("%s %s, %s hours, %s min",'ClassifiedTheme'), $res[1], $plural , $res[2], $res[3]);
		}
	}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function ClassifiedTheme_seconds_to_words_new($seconds)
{
		if($seconds < 0 ) return 'Expired';

        /*** number of days ***/
        $days=(int)($seconds/86400);
        /*** if more than one day ***/
        $plural = $days > 1 ? 'days' : 'day';
        /*** number of hours ***/
        $hours = (int)(($seconds-($days*86400))/3600);
        /*** number of mins ***/
        $mins = (int)(($seconds-$days*86400-$hours*3600)/60);
        /*** number of seconds ***/
        $secs = (int)($seconds - ($days*86400)-($hours*3600)-($mins*60));
        /*** return the string ***/
                if($days == 0 || $days < 0)
				{
					$arr[0] = 0;
					$arr[1] = $hours;
					$arr[2] = $mins;
					$arr[3] = $secs;
					return $arr;//sprintf("%d hours, %d min, %d sec", $hours, $mins, $secs);
				}
				else
				{
					$arr[0] = 1;
					$arr[1] = $days;
					$arr[2] = $hours;
					$arr[3] = $mins;

					return $arr; //sprintf("%d $plural, %d hours, %d min", $days, $hours, $mins);
        		}

}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_get_user_profile_link($uid)
{
	return get_bloginfo('siteurl'). '/?a_action=user_profile&post_author='. $uid;
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function classifiedTheme_get_post($param = '')
{
	do_action('classifiedTheme_get_post', $param);
}

add_action('classifiedTheme_get_post','classifiedTheme_get_post_function',0,1);
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_get_post_function($param = '')
{

			if($param) {

				if($param[0] == 'closed')	 $closed = 1;

			}

			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
			$post = get_post(get_the_ID());
			$post_status = $post->post_status;


			$ending 	= get_post_meta(get_the_ID(), 'ending', true);
			$sec 		= $ending - current_time('timestamp',0);
			$location 	= get_post_meta(get_the_ID(), 'Location', true);
			$price 		= get_post_meta(get_the_ID(), 'price', true);
			$closed 	= get_post_meta(get_the_ID(), 'closed', true);
			$featured 	= get_post_meta(get_the_ID(), 'featured', true);
			$author = get_userdata($post->post_author);

?>
				<div class="post <?php if($featured == "1"){ ?>  me_featured_sk  <?php } ?>" id="post-<?php the_ID(); ?>"   >

                <div class="price_tag_main">
                <div class="price_tag_1"></div>
                <div class="price_tag_2">
                	<div class="paddspec"><?php echo classifiedTheme_get_price(get_the_ID()); ?></div>
                </div>


                </div>

                <?php if($featured == "1"): ?>
                <div class="featured-one"></div>
                <?php endif; ?>

                <div class="padd10">
                <div class="image_holder">
                <a href="<?php the_permalink(); ?>"><img width="75" height="65" class="image_class"
                src="<?php echo ClassifiedTheme_get_first_post_image(get_the_ID(),75,65); ?>" /></a>
                </div>
                <div  class="title_holder" >
                     <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
                        <?php the_title(); ?></a></h2>


                  <?php if(1) { ?>

                        <p class="mypostedon">
						<ul class="can_be_main_details">

                        <li>
                        <img src="<?php echo get_template_directory_uri() ?>/images/clock.png" width="16" height="16" alt="clock" /> <p> <?php the_time('F jS, Y') ?></p>
                        </li>


                        <li>
                        <img src="<?php echo get_template_directory_uri() ?>/images/user.png" width="16" height="16" alt="user" /> <p><a href="<?php echo ClassifiedTheme_get_user_profile_link($author->ID); ?>"><?php echo $author->user_login; ?></a></p>
                        </li>

                        </ul>

                   		<ul class="can_be_main_details">
                         <?php

					   $lc = get_the_term_list( get_the_ID(), 'ad_cat', '', ', ', '' );
					   if(!empty($lc) )
					   {
							echo '<li>';
								?>
                                	<img src="<?php echo get_template_directory_uri() ?>/images/folder.png" width="16" height="16" alt="folder" />
                                <?php
								echo '<p>'.$lc.'</p>';
							echo '</li>';
					   }

					   $lc = get_the_term_list( get_the_ID(), 'ad_location', '', ', ', '' );

					   if(!empty($lc) )
					   {
							echo '<li>';
								?>
                                	<img src="<?php echo get_template_directory_uri() ?>/images/location_icon.png" width="16" height="16" alt="location" />
                                <?php
								echo '<p>'.$lc.'</p>';
							echo '</li>';
					   }



					  ?>
                      </ul>

                       </p>



               <?php if(1) { ?>
                  <p class="rdmore">
                  <ul class="post-ul-more">

                  <li><a href="<?php the_permalink() ?>" ><?php echo __("Read More", "ClassifiedTheme");?></a></li>

				  <?php if($post->post_author == $uid) { ?>
                  <li><a href="<?php echo get_bloginfo('siteurl') ?>/?a_action=edit_ad&pid=<?php the_ID(); ?>"><?php echo __("Edit Ad", "ClassifiedTheme");?></a></li>
                  <?php }   ?>

                  <?php if($post->post_author == $uid) //$closed == 1)
				  {

				  	$paid = get_post_meta($pid, 'paid',true);
					if($paid == "0" and $post_status == "draft"):

				  ?>

                 	<li><a href="<?php echo ClassifiedTheme_post_new_with_pid_stuff_thg(get_the_ID(), 3); ?>" ><?php echo __("Publish", "ClassifiedTheme");?></a></li>
                    <?php endif;


                    if($closed == "1"):

				  ?>

                 	<li><a href="<?php echo ClassifiedTheme_post_new_with_pid_stuff_thg(get_the_ID(), 1); ?>"><?php echo __("Republish", "ClassifiedTheme");?></a> </li>
                    <?php else: ?>

                    <li><a href="<?php echo get_bloginfo('siteurl') ?>/?a_action=close_ad&pid=<?php the_ID(); ?>" ><?php echo __("Close Ad", "ClassifiedTheme");?></a></li>

					<?php endif; ?>

                   <li> <a href="<?php echo get_bloginfo('siteurl') ?>/?a_action=delete_ad&pid=<?php the_ID(); ?>"><?php echo __("Delete Ad", "ClassifiedTheme");?></a></li>

                  <?php } ?>

                  </ul>
                  </p>
                  <?php } ?>

                     </div>

                    <?php } ?>








                     </div></div>
<?php
}


function get_listing_category_fields($catid, $pid = '')
{
	global $wpdb;
	$s = "select * from ".$wpdb->prefix."ad_custom_fields order by ordr asc";
	$r = $wpdb->get_results($s);

	$arr1 = array(); $i = 0;

	foreach($r as $row)
	{
		$ims 	= $row->id;
		$name 	= $row->name;
		$tp 	= $row->tp;

		if($row->cate == 'all')
		{
			$arr1[$i]['id'] 	= $ims;
			$arr1[$i]['name'] 	= $name;
			$arr1[$i]['tp'] 	= $tp;
			$i++;

		}
		else
		{
			$se = "select * from ".$wpdb->prefix."ad_custom_relations where custid='$ims'";
			$re = $wpdb->get_results($se);


			if(count($re) > 0)
			foreach($re as $rowe) // = mysql_fetch_object($re))
			{
				if(count($catid) > 0)
				foreach($catid as $id_of_cat)
				{

					if($rowe->catid == $id_of_cat)
					{
						$flag_me = 1;
						for($k=0;$k<count($arr1);$k++)
						{
							if(	$arr1[$k]['id'] 	== $ims	) {  $flag_me = 0; break; }
						}

						if($flag_me == 1)
						{
							$arr1[$i]['id'] 	= $ims;
							$arr1[$i]['name'] 	= $name;
							$arr1[$i]['tp'] 	= $tp;
							$i++;
						}
					}
				}
			}
		}
	}

	$arr = array();
	$i = 0;

	for($j=0;$j<count($arr1);$j++)
	{
		$ids 	= $arr1[$j]['id'];
		$tp 	= $arr1[$j]['tp'];

		$arr[$i]['field_name']  = $arr1[$j]['name'];
		$arr[$i]['id']  = '<input type="hidden" value="'.$ids.'" name="custom_field_id[]" />';

		if($tp == 1)
		{

		$teka 	= !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;

		$arr[$i]['value']  = '<input class="do_input" type="text" size="30" name="custom_field_value_'.$ids.'"
		value="'.(isset($_POST['custom_field_value_'.$ids]) ? $_POST['custom_field_value_'.$ids] : $teka ).'" />';

		}

		if($tp == 5)
		{

			$teka 	= !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;
			$teka 	= $teka[0];
			$value 	= isset($_POST['custom_field_value_'.$ids]) ? $_POST['custom_field_value_'.$ids] : $teka;

			$arr[$i]['value']  = '<textarea rows="5" cols="40" name="custom_field_value_'.$ids.'">'.$value.'</textarea>';

		}

		if($tp == 3) //radio
		{
			$arr[$i]['value']  = '';

				$s2 = "select * from ".$wpdb->prefix."ad_custom_options where custid='$ids' order by ordr ASC ";
				$r2 = $wpdb->get_results($s2);

				if(count($r2) > 0)
				foreach($r2 as $row2) // = mysql_fetch_object($r2))
				{
					$teka 	= !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;
					$teka 	= $teka[0];

					if(isset($_POST['custom_field_value_'.$ids]))
					{
						if($_POST['custom_field_value_'.$ids] == $row2->valval) $value = 'checked="checked"';
						else $value = '';
					}
					elseif(!empty($pid))
					{
						$v = get_post_meta($pid, 'custom_field_ID_'.$ids, true);
						if($v == $row2->valval) $value = 'checked="checked"';
						else $value = '';

					}
					else $value = '';

					$arr[$i]['value']  .= '<input type="radio" '.$value.' value="'.$row2->valval.'" name="custom_field_value_'.$ids.'"> '.$row2->valval.'<br/>';
				}
		}


		if($tp == 4) //checkbox
		{
			$arr[$i]['value']  = '';

				$s2 = "select * from ".$wpdb->prefix."ad_custom_options where custid='$ids' order by ordr ASC ";
				$r2 = $wpdb->get_results($s2);

				if(count($r2) > 0)
				foreach($r2 as $row2) // = mysql_fetch_object($r2))
				{
					$teka 		= !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids) : "" ;
					//$teka 		= $teka[0];
					$teka_ch 	= '';

					if(is_array($teka))
					{
						foreach($teka as $te)
						{

							if(trim($te) == trim($row2->valval)) { $teka_ch = "checked='checked'";  break; }
						}

					}
					elseif($row2->valval == $teka) $teka_ch = "checked='checked'";
					else $teka_ch = '';

					$teka_ch 	= isset($_POST['custom_field_value_'.$ids]) ? "checked='checked'" : $teka_ch;

					$arr[$i]['value']  .= '<input type="checkbox" '.$teka_ch.' value="'.$row2->valval.'" name="custom_field_value_'.$ids.'[]"> '.$row2->valval.'<br/>';
				}
		}

		if($tp == 2) //select
		{
			$arr[$i]['value']  = '<select class="do_input" name="custom_field_value_'.$ids.'" /><option value="">'.__('Select','ClassifiedTheme').'</option>';

				$s2 = "select * from ".$wpdb->prefix."ad_custom_options where custid='$ids' order by ordr ASC ";
				$r2 = $wpdb->get_results($s2);

				if(count($r2) > 0)
				foreach($r2 as $row2) // = mysql_fetch_object($r2))
				{
					$teka 	= !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids) : "" ;

					if(!empty($teka))
					{
						foreach($teka as $te)
						{
							if(trim($te) == trim($row2->valval)) { $teka = "selected='selected'"; break; }
						}


					}
					else $teka = '';

					if(isset($_POST['custom_field_value_'.$ids]) && $_POST['custom_field_value_'.$ids] == $row2->valval)
					$value = "selected='selected'" ;
					else $value = $teka;


					$arr[$i]['value']  .= '<option '.$value.' value="'.$row2->valval.'">'.$row2->valval.'</option>';

				}
			$arr[$i]['value']  .= '</select>';
		}

		$i++;
	}

	return $arr;
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_get_price($pid = '')
{
	if(empty($pid)) $pid = get_the_ID();
	$price = get_post_meta($pid, 'price', true);

	return ClassifiedTheme_get_show_price($price);

}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_using_permalinks()
{
	global $wp_rewrite;
	if($wp_rewrite->using_permalinks()) return true;
	else return false;
}

function ClassifiedTheme_get_priv_mess_page_url($subpage = '', $id = '', $addon = '')
{
	$opt = get_option('ClassifiedTheme_my_account_messages_id');
	if(empty($subpage)) $subpage = "home";

	if($subpage == "delete-message")
	{
		if(!empty($_GET['rdr'])) $rdr = urlencode($_GET['rdr']);
		else $rdr = urlencode(classifiedTheme_curPageURL());
	}

	$perm = ClassifiedTheme_using_permalinks();

	if($perm) return get_permalink($opt). "?rdr=".$rdr."&pg=".$subpage.(!empty($id) ? "&id=".$id : '').$addon;

	return get_permalink($opt). "&rdr=".$rdr."&pg=".$subpage.(!empty($id) ? "&id=".$id : '').$addon;
}

function classifiedTheme_get_unread_number_messages($uid)
{
	global $wpdb;
	$s = "select * from ".$wpdb->prefix."ad_pm where user='$uid' and rd='0' AND show_to_destination='1'";
				$r = $wpdb->get_results($s);
				return count($r);

}


/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedtheme_get_purchase_mem_lnk($id)
{
	$perm = ClassifiedTheme_using_permalinks();
	$pid = get_option('ClassifiedTheme_my_account_purchase_mem_id');

	if($perm)
	{
		return get_permalink($pid) . "?id=" . $id;
	}
	else
	return get_permalink($pid) . "&id=" . $id;
}

add_action('admin_head', 						'ClassifiedTheme_admin_main_head_scr');
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_admin_main_head_scr()
{

		wp_enqueue_script("jquery-ui-widget");
	wp_enqueue_script("jquery-ui-mouse");
	wp_enqueue_script("jquery-ui-tabs");
	wp_enqueue_script("jquery-ui-datepicker");
	if($_GET['post_type'] == "ad"):

?>

	<!-- <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script> -->
<?php endif; ?>


    <link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/css/admin.css" type="text/css" />
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/colorpicker.css" type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="<?php bloginfo('template_url'); ?>/css/layout.css" />
	<link type="text/css" href="<?php bloginfo('template_url'); ?>/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" />

	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/idtabs.js"></script>


		<script type="text/javascript">


		jQuery(document).ready(function() {
  jQuery("#usual2 ul").idTabs("tabs1");
		});
		</script>



<?php
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_get_option_drop_down($arr, $name)
{
	$opts = get_option($name);
	$r = '<select name="'.$name.'">';
	foreach ($arr as $key => $value)
	{
		$r .= '<option value="'.$key.'" '.($opts == $key ? ' selected="selected" ' : "" ).'>'.$value.'</option>';

	}
    return $r.'</select>';
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_create_post_type() {

	global $ad_list_thing, $listings_url_thing;

	$ad_list_thing = "ads-list";

  $icn = get_bloginfo('template_url')."/images/adicon.gif";
  register_post_type( 'ad',
    array(
      'labels' => array(
        'name' 			=> __( 'Classified Ads' ,'ClassifiedTheme'),
        'singular_name' => __( 'Ad'  ,'ClassifiedTheme'),
		'add_new' 		=> __('Add New Ad' ,'ClassifiedTheme'),
		'new_item' 		=> __('New Classified Ad' ,'ClassifiedTheme'),
		'edit_item'		=> __('Edit Classified Ad' ,'ClassifiedTheme'),
		'add_new_item' 	=> __('Add New Classified Ad' ,'ClassifiedTheme'),
		'search_items' 	=> __('Search Ads' ,'ClassifiedTheme'),


      ),
      'public' => true,
	  'menu_position' => 5,
	  'register_meta_box_cb' => 'classifiedTheme_set_metaboxes',
	  'has_archive' => $ad_list_thing,
     	'rewrite' => array('slug'=> $listings_url_thing.'/%ad_cat%', 'with_front' => false),
	  '_builtin' => false,
	  'menu_icon' => $icn,
	  'publicly_queryable' => true,
	  'hierarchical' => false

    )
  );

	global $category_url_link, $location_url_link;

	register_taxonomy( 'ad_cat', 'ad', array( 'hierarchical' => true, 'label' => __('Advert Categories','ClassifiedTheme'),
	'rewrite'                  =>    true
	 )

	 );
	register_taxonomy( 'ad_location', 'ad', array( 'hierarchical' => true, 'label' => __('Locations','ClassifiedTheme'),
	'rewrite'                       => array('slug'=>$location_url_link,'with_front'=>false)
	 ) );


	add_post_type_support( 'ad', 'author' );
	add_post_type_support( 'ad', 'comments' );
	add_post_type_support( 'ad', 'trackbacks' );
	//add_post_type_support( 'ad', 'custom-fields' );
	register_taxonomy_for_object_type('post_tag', 'ad');

	flush_rewrite_rules();

}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
add_filter('post_type_link', 'ClassifiedTheme_post_type_link_filter_function', 1, 3);

 function ClassifiedTheme_post_type_link_filter_function( $post_link, $id = 0, $leavename = FALSE ) {

	global $category_url_link;

    if ( strpos('%ad_cat%', $post_link) === 'FALSE' ) {
      return $post_link;
    }
    $post = get_post($id);
    if ( !is_object($post) || $post->post_type != 'ad' ) {

		if(ClassifiedTheme_using_permalinks())
      return str_replace("ad_cat", $category_url_link ,$post_link);
	  else return $post_link;
    }
    $terms = wp_get_object_terms($post->ID, 'ad_cat');
    if ( !$terms ) {
      return str_replace('%ad_cat%', 'uncategorized', $post_link);
    }
    return str_replace('%ad_cat%', $terms[0]->slug, $post_link);
  }

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
add_filter('term_link', 'ClassifiedTheme_post_tax_link_filter_function', 1, 3);

 function ClassifiedTheme_post_tax_link_filter_function( $post_link, $id = 0, $leavename = FALSE ) {
	global $category_url_link;

	if(!ClassifiedTheme_using_permalinks())	 return $post_link;
	return str_replace("ad_cat",$category_url_link ,$post_link);
  }
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function ClassifiedTheme_rewrite_rules( $wp_rewrite )
{
//cl_style_location
		global $category_url_link, $location_url_link;
		$new_rules = array(


		$category_url_link.'/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?ad_cat='.$wp_rewrite->preg_index(1)."&feed=".$wp_rewrite->preg_index(2),
        $category_url_link.'/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?ad_cat='.$wp_rewrite->preg_index(1)."&feed=".$wp_rewrite->preg_index(2),
        $category_url_link.'/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?ad_cat='.$wp_rewrite->preg_index(1)."&paged=".$wp_rewrite->preg_index(2),
        $category_url_link.'/([^/]+)/?$' => 'index.php?ad_cat='.$wp_rewrite->preg_index(1),
		'ads-locations'.'/?$' => 'index.php?a_action=cl_style_location',
		'ads-locations'.'/([^/]+)/?$' => 'index.php?a_action=cl_style_location&current_taxo=' . $wp_rewrite->preg_index(1),

		);

		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function classifiedTheme_is_home()
{
	global $current_user, $wp_query;
	$a_action 	=  $wp_query->query_vars['a_action'];

	if(!empty($a_action)) return false;
	if(is_home()) return true;
	return false;

}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function classifiedTheme_slider_post_function()
{

	?>


	<div class="slider-post">
		<a href="<?php the_permalink(); ?>"><img width="150" height="110" class="image_class"
                src="<?php echo ClassifiedTheme_get_first_post_image(get_the_ID(),150,110); ?>" alt="<?php the_title(); ?>" /></a>
                <br/>

                 <p><b><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
                        <?php

                        the_title();


                        ?></a></b><br/>
                        <?php echo get_the_term_list( get_the_ID(), 'ad_location', '', ', ', '' );   ?><br/>
                        <?php //echo classifiedTheme_get_show_price(classifiedTheme_price(get_the_ID())); ?>

                        <?php $price = classifiedTheme_get_price(get_the_ID()); ?>
                        <?php echo sprintf(__('Price: %s','ClassifiedTheme'), $price); ?>

                       </p>

	</div>

	<?php
}

add_action('classifiedTheme_slider_post',	'classifiedTheme_slider_post_function',0,1);


/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_get_show_price($price, $cents = 2)
{
	$classifiedTheme_currency_position = get_option('classifiedTheme_currency_position');
	if($classifiedTheme_currency_position == "front") return classifiedTheme_get_currency()."".classifiedTheme_formats($price, $cents);
	return classifiedTheme_formats($price,$cents)."".classifiedTheme_get_currency();

}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_formats($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always

  $dec_sep = get_option('ClassifiedTheme_decimal_sum_separator');
  if(empty($dec_sep)) $dec_sep = '.';

  $tho_sep = get_option('ClassifiedTheme_thousands_sum_separator');
  if(empty($tho_sep)) $tho_sep = ',';

  //dec,thou

  if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $money = ($cents == 2 ? '0'.$dec_sep.'00' : '0'); // output zero
    } else { // value
      if (floor($number) == $number) { // whole number
        $money = number_format($number, ($cents == 2 ? 2 : 0), $dec_sep, $tho_sep ); // format
      } else { // cents
        $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2), $dec_sep, $tho_sep ); // format
      } // integer or decimal
    } // value
    return $money;
  } // numeric
} // formatMoney

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_get_currency()
{
	$c = trim(get_option('ClassifiedTheme_currency_symbol'));
	if(empty($c)) return get_option('ClassifiedTheme_currency');
	return $c;

}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function classifiedTheme_currency()
{
	return ClassifiedTheme_get_currency();
}


/****************************************************************
*
*	ClassifiedTheme - function -
*
****************************************************************/

function ClassifiedTheme_display_my_adv_search_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_adv_search\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_show_me_page_adv_search();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_adv_search\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_display_my_pers_inf_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_my_account_personal_info\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_my_account_pers_inf_area_function();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_my_account_personal_info\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function ClassifiedTheme_display_my_unpub_list_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_my_account_unpub_listings\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_my_account_unpublished_listings_area_function();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_my_account_unpub_listings\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function ClassifiedTheme_display_my_active_list_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_my_account_act_listings\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_my_account_active_listings_area_function();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_my_account_act_listings\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_display_my_expired_list_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_my_account_expired_listings\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_my_account_expired_listings_area_function();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_my_account_expired_listings\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function ClassifiedTheme_display_my_all_cats_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_all_categories\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_show_me_page_all_categories();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_all_categories\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_display_my_all_locs_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_all_locations\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_show_me_page_all_locations();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_all_locations\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/

function ClassifiedTheme_display_my_post_new_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_post_new\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_post_new_area_main_function();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_post_new\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}
/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_display_my_account_home_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_my_account_home\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_my_account_home_area_main_function();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_my_account_home\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_display_my_account_mem_pks_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_my_account_mem_pks\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_my_account_mem_packs_area_function();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_my_account_mem_pks\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_display_blog_posts_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_all_blog_posts\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_display_blog_page_disp();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_all_blog_posts\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_display_purchase_mem_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_my_account_purchase_mem\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_purchase_mem_page_disp();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_my_account_purchase_mem\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}



function ClassifiedTheme_display_messages_disp_page($content = '')
{
	if ( preg_match( "/\[classified_theme_my_account_messages\]/", $content ) )
	{
		ob_start();
		ClassifiedTheme_messages_page_disp();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[classified_theme_my_account_messages\](<\/p>)*/", $output, $content );

	}
	else {
		return $content;
	}
}



/****************************************************************
*
*	ClassifiedTheme - function -
*
****************************************************************/

function classifiedTheme_post_new_link()
{
	return get_permalink(get_option('ClassifiedTheme_post_new_page_id'));
}

/****************************************************************
*
*	ClassifiedTheme - function -
*
****************************************************************/
function classifiedTheme_blog_link()
{
	return get_permalink(get_option('ClassifiedTheme_all_blog_posts_page_id'));
}
/****************************************************************
*
*	ClassifiedTheme - function -
*
****************************************************************/
function classifiedTheme_my_account_link()
{

	return get_permalink(get_option('ClassifiedTheme_my_account_page_id'));
}

function ClassifiedTheme_post_new_with_pid_stuff_thg($pid, $step = 1, $fin = '')
{
	$using_perm = ClassifiedTheme_using_permalinks();
	if($using_perm)	return get_permalink(get_option('ClassifiedTheme_post_new_page_id')). "?ad_id=" . $pid."&step=".$step.(!empty($fin) ? "&finalise=yes" : '');
			else return get_bloginfo('siteurl'). "/?page_id=". get_option('ClassifiedTheme_post_new_page_id'). "&ad_id=" . $pid."&step=".$step.(!empty($fin) ? "&finalise=yes" : '');
}


/****************************************************************
*
*	ClassifiedTheme - function -
*
****************************************************************/
function ClassifiedTheme_get_categories($taxo, $selected = "", $include_empty_option = "", $ccc = "")
{
	$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
	$terms = get_terms( $taxo, $args );

	$ret = '<select name="'.$taxo.'_cat" class="'.$ccc.'" id="'.$ccc.'">';
	if(!empty($include_empty_option)) $ret .= "<option value=''>".$include_empty_option."</option>";

	if(empty($selected)) $selected = -1;

	foreach ( $terms as $term )
	{
		$id = $term->term_id;

		$ret .= '<option '.($selected == $id ? "selected='selected'" : " " ).' value="'.$id.'">'.$term->name.'</option>';

		$args = "orderby=name&order=ASC&hide_empty=0&parent=".$id;
		$sub_terms = get_terms( $taxo, $args );

		if($sub_terms)
		foreach ( $sub_terms as $sub_term )
		{
			$sub_id = $sub_term->term_id;
			$ret .= '<option '.($selected == $sub_id ? "selected='selected'" : " " ).' value="'.$sub_id.'">&nbsp; &nbsp;|&nbsp;  '.$sub_term->name.'</option>';

			$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$sub_id;
			$sub_terms2 = get_terms( $taxo, $args2 );

			if($sub_terms2)
			foreach ( $sub_terms2 as $sub_term2 )
			{
				$sub_id2 = $sub_term2->term_id;
				$ret .= '<option '.($selected == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">&nbsp; &nbsp; &nbsp; &nbsp;|&nbsp;
				 '.$sub_term2->name.'</option>';

				 $args3 = "orderby=name&order=ASC&hide_empty=0&parent=".$sub_id2;
				 $sub_terms3 = get_terms( $taxo, $args3 );

				 if($sub_terms3)
				 foreach ( $sub_terms3 as $sub_term3 )
				{
					$sub_id3 = $sub_term3->term_id;
					$ret .= '<option '.($selected == $sub_id3 ? "selected='selected'" : " " ).' value="'.$sub_id3.'">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;|&nbsp;
					 '.$sub_term3->name.'</option>';
				}
			}
		}

	}

	$ret .= '</select>';

	return $ret;

}

/*****************************************************************************
*
*	Function - ClassifiedTheme -
*
*****************************************************************************/
function ClassifiedTheme_get_categories_slug($taxo, $selected = "", $include_empty_option = "", $ccc = "")
{
	$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
	$terms = get_terms( $taxo, $args );

	$ret = '<select name="'.$taxo.'_cat" class="'.$ccc.'" id="'.$ccc.'">';
	if(!empty($include_empty_option)){

		if($include_empty_option == "1") $include_empty_option = "Select";
	 	$ret .= "<option value=''>".$include_empty_option."</option>";
	 }

	if(empty($selected)) $selected = -1;

	foreach ( $terms as $term )
	{
		$id = $term->slug;
		$ide = $term->term_id;

		$ret .= '<option '.($selected == $id ? "selected='selected'" : " " ).' value="'.$id.'">'.$term->name.'</option>';

		$args = "orderby=name&order=ASC&hide_empty=0&parent=".$ide;
		$sub_terms = get_terms( $taxo, $args );

		if($sub_terms)
		foreach ( $sub_terms as $sub_term )
		{
			$sub_id = $sub_term->slug;
			$ret .= '<option '.($selected == $sub_id ? "selected='selected'" : " " ).' value="'.$sub_id.'">&nbsp; &nbsp;|&nbsp;  '.$sub_term->name.'</option>';

			$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$sub_term->term_id;
			$sub_terms2 = get_terms( $taxo, $args2 );

			if($sub_terms2)
			foreach ( $sub_terms2 as $sub_term2 )
			{
				$sub_id2 = $sub_term2->slug;
				$ret .= '<option '.($selected == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">&nbsp; &nbsp; &nbsp; &nbsp;|&nbsp;
				'.$sub_term2->name.'</option>';

				$args3 = "orderby=name&order=ASC&hide_empty=0&parent=".$sub_term2->term_id;
				$sub_terms3 = get_terms( $taxo, $args3 );

				if($sub_terms3)
				foreach ( $sub_terms3 as $sub_term3 )
				{
					$sub_id3 = $sub_term3->slug;
					$ret .= '<option '.($selected == $sub_id3 ? "selected='selected'" : " " ).' value="'.$sub_id3.'">&nbsp; &nbsp; &nbsp; &nbsp;|&nbsp;
					'.$sub_term3->name.'</option>';

				}

			}

		}

	}

	$ret .= '</select>';

	return $ret;

}

/****************************************************************
*
*	ClassifiedTheme - function -
*
****************************************************************/

function classifiedTheme_advanced_search_link()
{
	return get_permalink(get_option('ClassifiedTheme_adv_search_page_id'));
}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/
function ClassifiedTheme_check_if_page_existed($pid)
{
	global $wpdb;
	$s = "select * from ".$wpdb->prefix."posts where post_type='page' AND post_status='publish' AND ID='$pid'";
	$r = $wpdb->get_results($s);

	if(count($r) > 0) return true;
	return false;

}

/*************************************************************
*
*	ClassifiedTheme (c) sitemile.com - function
*
**************************************************************/

function ClassifiedTheme_insert_pages($page_ids, $page_title, $page_tag, $parent_pg = 0 )
{

		$opt = get_option($page_ids);
		if(!ClassifiedTheme_check_if_page_existed($opt))
		{

			$post = array(
			'post_title' 	=> $page_title,
			'post_content' 	=> $page_tag,
			'post_status' 	=> 'publish',
			'post_type' 	=> 'page',
			'post_author' 	=> 1,
			'ping_status' 	=> 'closed',
			'post_parent' 	=> $parent_pg);

			$post_id = wp_insert_post($post);

			update_post_meta($post_id, '_wp_page_template', 'ad-special-page-template.php');
			update_option($page_ids, $post_id);

		}


}
/****************************************************************
*
*	ClassifiedTheme - function -
*
****************************************************************/

function ClassifiedTheme_framework_init_widgets()
{
	register_sidebar( array(
		'name' => __( 'Single Page Sidebar', 'ClassifiedTheme' ),
		'id' => 'single-widget-area',
		'description' => __( 'The sidebar area of the single blog post', 'ClassifiedTheme' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

		register_sidebar( array(
		'name' => __( 'Other Page Sidebar', 'ClassifiedTheme' ),
		'id' => 'other-page-area',
		'description' => __( 'The sidebar area of any other page than the defined ones', 'ClassifiedTheme' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );




	register_sidebar( array(
		'name' => __( 'Home Page Sidebar - Right', 'ClassifiedTheme' ),
		'id' => 'home-right-widget-area',
		'description' => __( 'The right sidebar area of the homepage', 'ClassifiedTheme' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );




	register_sidebar( array(
		'name' => __( 'Home Page Sidebar - Left', 'ClassifiedTheme' ),
		'id' => 'home-left-widget-area',
		'description' => __( 'The left sidebar area of the homepage', 'ClassifiedTheme' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );



	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'ClassifiedTheme' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'ClassifiedTheme' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'ClassifiedTheme' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'ClassifiedTheme' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'ClassifiedTheme' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'ClassifiedTheme' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'ClassifiedTheme' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'ClassifiedTheme' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );



			register_sidebar( array(
			'name' => __( 'ClassifiedTheme - Listing Single Sidebar', 'ClassifiedTheme' ),
			'id' => 'listing-widget-area',
			'description' => __( 'The sidebar of the single listing page', 'ClassifiedTheme' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );


			register_sidebar( array(
			'name' => __( 'ClassifiedTheme - HomePage Area','ClassifiedTheme' ),
			'id' => 'main-page-widget-area',
			'description' => __( 'The sidebar for the main page, just under the slider.', 'ClassifiedTheme' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );


			register_sidebar( array(
			'name' => __( 'ClassifiedTheme - Stretch Wide Sidebar','ClassifiedTheme' ),
			'id' => 'main-stretch-area',
			'description' => __( 'The sidebar sidewide stretched, just under the slider.', 'ClassifiedTheme' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );


			register_sidebar( array(
			'name' => __( 'ClassifiedTheme - Under search index','ClassifiedTheme' ),
			'id' => 'main-stretch-area2',
			'description' => __( 'The sidebar located under the searchbar on homepage.', 'ClassifiedTheme' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );


}

/****************************************************************
*
*	ClassifiedTheme - function -
*
****************************************************************/

/****************************************************************
*
*	ClassifiedTheme - function -
*
****************************************************************/

global $wpdb;

		if(isset($_GET['confirm_message_deletion']))
		{
			$return 	= $_GET['return'];
			$messid 	= $_GET['id'];

			global $wpdb, $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;

			if(empty($messid))
			{

				foreach($_GET['message_id'] as $messid)
				{

					$s = "select * from ".$wpdb->prefix."ad_pm where id='$messid' AND (user='$uid' OR initiator='$uid')";
					$r = $wpdb->get_results($s);

					if(count($r) > 0)
					{
						$row = $r[0];

						if($row->initiator == $uid)
						{
							$s = "update ".$wpdb->prefix."ad_pm set show_to_source='0' where id='$messid'";
							$wpdb->query($s);

						}
						else
						{
							$s = "update ".$wpdb->prefix."ad_pm set show_to_destination='0' where id='$messid'";
							$wpdb->query($s);
						}

						$using_perm = ClassifiedTheme_using_permalinks();

						if($using_perm)	$privurl_m = get_permalink(get_option('ClassifiedTheme_my_account_messages_id')). "/?";
						else $privurl_m = get_bloginfo('url'). "/?page_id=". get_option('ClassifiedTheme_my_account_messages_id'). "&";




					}
					else if(!empty($_GET['rdr'])) { wp_redirect($_GET['rdr']); exit; }
					else { wp_redirect(get_permalink(get_option('ClassifiedTheme_my_account_page_id'))); exit; }
				}

				if($return == "inbox") wp_redirect($privurl_m . "pg=inbox");
				else if($return == "outbox") wp_redirect($privurl_m . "pg=sent-items");
				else if($return == "home") wp_redirect($privurl_m);
				else if(!empty($_GET['rdr'])) wp_redirect($_GET['rdr']);
				else wp_redirect(get_permalink(get_option('ClassifiedTheme_my_account_page_id')));

			}
			else
			{

				$s = "select * from ".$wpdb->prefix."ad_pm where id='$messid' AND (user='$uid' OR initiator='$uid')";
				$r = $wpdb->get_results($s);

				if(count($r) > 0)
				{
					$row = $r[0];

					if($row->initiator == $uid)
					{
						$s = "update ".$wpdb->prefix."ad_pm set show_to_source='0' where id='$messid'";
						$wpdb->query($s);

					}
					else
					{
						$s = "update ".$wpdb->prefix."ad_pm set show_to_destination='0' where id='$messid'";
						$wpdb->query($s);
					}

					$using_perm = ClassifiedTheme_using_permalinks();

					if($using_perm)	$privurl_m = get_permalink(get_option('ClassifiedTheme_my_account_messages_id')). "/?";
					else $privurl_m = get_bloginfo('url'). "/?page_id=". get_option('ClassifiedTheme_my_account_messages_id'). "&";


					if($return == "inbox") wp_redirect($privurl_m . "pg=inbox");
					else if($return == "outbox") wp_redirect($privurl_m . "pg=sent-items");
					else if($return == "home") wp_redirect($privurl_m);
					else if(!empty($_GET['return'])) { wp_redirect($_GET['return']); exit; }
					else { wp_redirect(get_permalink(get_option('ClassifiedTheme_my_account_page_id'))); exit; }

				}
				else if(!empty($_GET['return'])) { wp_redirect($_GET['return']); exit; }
				else wp_redirect(get_permalink(get_option('ClassifiedTheme_my_account_page_id')));

			}
		}

?>
