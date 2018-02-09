<?php
/***************************************************************************
*
*	AuctionTheme - copyright (c) - sitemile.com
*	The most popular auction theme for wordress on the internet. Launch your
*	auction site in minutes from purchasing. Turn-key solution.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/p/auctionTheme
*	since v4.4.7.1
*
***************************************************************************/
 


if(!function_exists('AuctionTheme_post_new_area_function'))
{
function AuctionTheme_post_new_area_function()
{

	$new_auction_step =  $_GET['step'];
	if(empty($new_auction_step)) $new_auction_step = 1;

	$pid = $_GET['auction_id'];
	global $error, $current_user;
		$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$uploaders = auctiontheme_get_uploaders_tp();

?>

	<div class="my_box3 breadcrumb-wrap" style="margin-top:5px;">
    <div class="box_title"><?php echo __("Post new Auction", 'AuctionTheme'); ?></div>
    <div class="box_content">

<div class="clear25"></div>

    <div id="steps">
        <ul>
            <li <?php if($new_auction_step >= 1): ?> class="active" <?php endif; ?>> <?php echo __('Write Auction','AuctionTheme') ?> </li>
            <li <?php if($new_auction_step >= 2): ?> class="active" <?php endif; ?>> <?php echo __('Add Photos','AuctionTheme') ?></li>
            <li <?php if($new_auction_step >= 3): ?> class="active" <?php endif; ?>> <?php echo __('Review & Publish','AuctionTheme') ?> </li>
 		</ul>
    </div>

<script src="<?php echo esc_url( get_template_directory_uri() ) ?>/js/prefixfree.js" type="text/javascript" type="text/javascript"></script>

     <div class="clear25"></div>





<!-- ####################################### Step 1 ######################################### -->
<?php

if($new_auction_step == "1")
{
	//-----------------
	$post 		= get_post($pid);
	$cat 		= wp_get_object_terms($pid, 'auction_cat', array('order' => 'ASC', 'orderby' => 'term_id' ));
 	$location 	= wp_get_object_terms($pid, 'auction_location', array('order' => 'ASC', 'orderby' => 'term_id' ));

	if(is_array($error))
	if($auctionOK == 0)
	{

		echo '<div class="errrs">';

			//foreach($error as $e)
			echo '<div class="newad_error">'.__('You have errors in your new item form. Please see below and correct them.','AuctionTheme'). '</div>';

		echo '</div>';

	}

	do_action('AuctionTheme_step1_before');

	?>
    <script type="text/javascript">

		function check_quant()
		{
			jQuery('#quantity_li').toggle('slow');
			jQuery('#start_prc').toggle('slow');
			jQuery('#res_prc').toggle('slow');
			
			jQuery("#prvbds").toggle('slow');
			
			<?php
					$AuctionTheme_no_time_on_buy_now = get_option('AuctionTheme_no_time_on_buy_now');
					if($AuctionTheme_no_time_on_buy_now == "yes"):
			?>
					jQuery('#ending_li').toggle('slow');
			<?php endif; ?>
		}

	</script>
    <script>

									function display_subcat(vals)
									{
										$.post("<?php bloginfo('siteurl'); ?>/?get_subcats_for_me=1", {queryString: ""+vals+""}, function(data){
											if(data.length >0) {

												$('#sub_cats').html(data);
												$('#sub_cats2').html("");
												$('#sub_cats3').html("");

											}
										});

									}

									function display_subcat_cat2(vals)
									{
										$.post("<?php bloginfo('siteurl'); ?>/?get_subcats_for_me2=1", {queryString: ""+vals+""}, function(data){
											if(data.length >0) {

												$('#sub_cats2').html(data);

											}
										});

									}

									function display_subcat_cat3(vals)
									{
										$.post("<?php bloginfo('siteurl'); ?>/?get_subcats_for_me3=1", {queryString: ""+vals+""}, function(data){
											if(data.length >0) {

												$('#sub_cats3').html(data);

											}
										});

									}


									function display_subcat2(vals)
									{
										jQuery.post("<?php bloginfo('siteurl'); ?>/?get_locscats_for_me=1", {queryString: ""+vals+""}, function(data){
											if(data.length >0) {

												jQuery('#sub_locs').html(data);
												jQuery('#sub_locs2').html("&nbsp;");

											}
											else
											{
												jQuery('#sub_locs').html("&nbsp;");
												jQuery('#sub_locs2').html("&nbsp;");
											}
										});

									}

									function display_subcat3(vals)
									{
										jQuery.post("<?php bloginfo('siteurl'); ?>/?get_locscats_for_me2=1", {queryString: ""+vals+""}, function(data){
											if(data.length >0) {

												jQuery('#sub_locs2').html(data);

											}
										});

									}




									</script>


 	<form method="post" action="<?php echo AuctionTheme_post_new_with_pid_stuff_thg($pid, $new_auction_step);?>">
    <?php wp_nonce_field( 'form_step_1' ); ?>

    <ul class="post-new">
        <li class="<?php echo AuctionTheme_show_error_class($error,'title'); ?>">
        <?php echo AuctionTheme_show_error_content_m($error,'title'); ?>

        	<h2><?php echo __('Your item title:', 'AuctionTheme'); ?></h2>
        	<p><input type="text" size="50" class="do_input form-control" name="auction_title"
            value="<?php echo (empty($_POST['auction_title']) ?
			($post->post_title == "Auto Draft" ? "" : $post->post_title) : $_POST['auction_title']); ?>" /> <?php do_action('AuctionTheme_step1_after_title_field');  ?></p>
        </li>

        <?php do_action('ActionTheme_after_title_li'); ?>

		<?php

			$AuctionTheme_currency_position = get_option('AuctionTheme_currency_position');


			$AuctionTheme_enable_locations = get_option('AuctionTheme_enable_locations');
			if($AuctionTheme_enable_locations != 'no'):

		?>
        <li class="<?php echo AuctionTheme_show_error_class($error,'location'); ?>">
        <?php echo AuctionTheme_show_error_content_m($error,'location'); ?>
        	<h2><?php echo __('Item Location:', 'AuctionTheme'); ?></h2>
        <p>




         <?php

			 	echo AuctionTheme_get_categories_clck("auction_location",
                                !isset($_POST['auction_location_cat']) ? (is_array($location) ? $location[0]->term_id : "") : htmlspecialchars($_POST['auction_location_cat'])
                                , __('Select Location','AuctionTheme'), "do_input form-control", 'onchange="display_subcat2(this.value)"' );


								echo '<br/><span id="sub_locs">';


											if(!empty($location[1]->term_id))
											{
												$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$location[0]->term_id;
												$sub_terms2 = get_terms( 'auction_location', $args2 );

												$ret = '<select class="do_input form-control" name="subloc">';
												$ret .= '<option value="">'.__('Select SubLocation','AuctionTheme'). '</option>';
												$selected1 = $location[1]->term_id;

												if(is_array($sub_terms2))
												foreach ( $sub_terms2 as $sub_term2 )
												{
													$sub_id2 = $sub_term2->term_id;
													$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

												}
												$ret .= "</select>";
												echo $ret;


											}

										echo '</span>';


										echo '<br/><span id="sub_locs2">';


											if(!empty($location[2]->term_id))
											{
												$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$location[1]->term_id;
												$sub_terms2 = get_terms( 'auction_location', $args2 );

												$ret = '<select class="do_input form-control" name="subloc2">';
												$ret .= '<option value="">'.__('Select SubLocation','AuctionTheme'). '</option>';
												$selected1 = $location[2]->term_id;

												if(is_array($sub_terms2))
												foreach ( $sub_terms2 as $sub_term2 )
												{
													$sub_id2 = $sub_term2->term_id;
													$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

												}
												$ret .= "</select>";
												echo $ret;


											}

										echo '</span>';




			 ?>




        </p>
        </li>
        <?php endif; ?>
        <?php do_action('ActionTheme_after_location_li'); ?>



        <li class="<?php echo AuctionTheme_show_error_class($error,'category'); ?>">
        <?php echo AuctionTheme_show_error_content_m($error,'category'); ?>
        <h2><?php echo __('Category', 'AuctionTheme'); ?>:</h2>
        	<p>
			<?php if(get_option('AuctionTheme_enable_multi_cats') == "yes"): ?>
			<div class="multi_cat_placeholder_thing">

            	<?php

					$selected_arr = AuctionTheme_build_my_cat_arr($pid);
					echo AuctionTheme_get_categories_multiple('auction_cat', $selected_arr);

				?>

            </div>

            <?php else: ?>

			<?php

			 	echo AuctionTheme_get_categories_clck("auction_cat",
                                !isset($_POST['auction_cat_cat']) ? (is_array($cat) ? $cat[0]->term_id : "") : htmlspecialchars($_POST['auction_cat_cat'])
                                , __('Select Category','AuctionTheme'), "do_input form-control", 'onchange="display_subcat(this.value)"' );


								echo '<br/><span id="sub_cats">';


											if(!empty($cat[1]->term_id))
											{
												$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat[0]->term_id;
												$sub_terms2 = get_terms( 'auction_cat', $args2 );

												$ret = '<select class="do_input form-control" name="subcat" onchange="display_subcat_cat2(this.value)" >';
												$ret .= '<option value="">'.__('Select Subcategory','AuctionTheme'). '</option>';
												$selected1 = $cat[1]->term_id;

												foreach ( $sub_terms2 as $sub_term2 )
												{
													$sub_id2 = $sub_term2->term_id;
													$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

												}
												$ret .= "</select>";
												echo $ret;


											}

										echo '</span>';



										echo '<br/><span id="sub_cats2">';


											if(!empty($cat[2]->term_id))
											{
												$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat[1]->term_id;
												$sub_terms2 = get_terms( 'auction_cat', $args2 );

												$ret = '<select class="do_input form-control" name="subcat2"  onchange="display_subcat_cat3(this.value)">';
												$ret .= '<option value="">'.__('Select Subcategory','AuctionTheme'). '</option>';
												$selected1 = $cat[2]->term_id;

												foreach ( $sub_terms2 as $sub_term2 )
												{
													$sub_id2 = $sub_term2->term_id;
													$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

												}
												$ret .= "</select>";
												echo $ret;


											}

										echo '</span>';


										echo '<br/><span id="sub_cats3">';


											if(!empty($cat[3]->term_id))
											{
												$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat[2]->term_id;
												$sub_terms2 = get_terms( 'auction_cat', $args2 );

												$ret = '<select class="do_input form-control" name="subcat3">';
												$ret .= '<option value="">'.__('Select Subcategory','AuctionTheme'). '</option>';
												$selected1 = $cat[3]->term_id;

												foreach ( $sub_terms2 as $sub_term2 )
												{
													$sub_id2 = $sub_term2->term_id;
													$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

												}
												$ret .= "</select>";
												echo $ret;


											}

										echo '</span>';

			 ?>
            <?php endif; ?>

            </p>
        </li>

<?php $AuctionTheme_enable_it_cond = get_option('AuctionTheme_enable_it_cond');

	if($AuctionTheme_enable_it_cond == "yes"):


 	$item_condition 	= wp_get_object_terms($pid, 'item_condition', array('order' => 'ASC', 'orderby' => 'term_id' ));
?>
          <li class="<?php echo AuctionTheme_show_error_class($error,'item_condition'); ?>">
        <?php echo AuctionTheme_show_error_content_m($error,'item_condition'); ?>
        <h2><?php echo __('Item Condition', 'AuctionTheme'); ?>:</h2>
        	<p>
			<?php

			 	echo AuctionTheme_get_categories_clck("item_condition",    $item_condition[0]->term_id  , __('Select Item Condition','AuctionTheme'), "do_input form-control", '' );

			 ?>
            </p>
        </li>

    <?php endif; ?>


        <?php do_action('ActionTheme_after_category_li'); ?>
		<?php

			$only_buy_now = 1; //get_post_meta($pid, 'only_buy_now', true);

		?>




        <li id='start_prc' class="<?php echo AuctionTheme_show_error_class($error,'start_price'); ?>" style="<?php echo ($only_buy_now == "1" ? 'display:none' : '');  ?>">
        <?php echo AuctionTheme_show_error_content_m($error,'start_price'); ?>
        	<h2><?php echo __('Start Price', 'AuctionTheme'); ?>:</h2>
        <p><input type="text" size="16" name="start_price" class="do_input input-sm" placeholder="<?php echo auctiontheme_get_currency_USD() ?>"
        	value="<?php echo  auctionTheme_show_sum_of_cash_m( get_post_meta($pid, 'start_price', true) ); ?>" />  <?php do_action('AuctionTheme_step1_after_start_rpice_field');  ?></p>
        </li>


        <?php do_action('ActionTheme_after_start_price_li'); ?>

         <li id="res_prc" class="<?php echo AuctionTheme_show_error_class($error,'reserve'); ?>" style="<?php echo ($only_buy_now == "1" ? 'display:none' : '');  ?>">
         <?php echo AuctionTheme_show_error_content_m($error,'reserve'); ?>
        	<h2><?php echo __('Reserve Price', 'AuctionTheme'); ?>:</h2>
        <p>
        <input type="text" size="16" name="reserve" class="do_input input-sm" placeholder="<?php echo auctiontheme_get_currency_USD() ?>"
        	value="<?php echo auctionTheme_show_sum_of_cash_m( get_post_meta($pid, 'reserve', true) ); ?>" />

            <?php do_action('AuctionTheme_step1_after_reserve_price_field');  ?></p>
        </li>

        <?php do_action('ActionTheme_after_reserve_price_li'); ?>

         <li class="<?php echo AuctionTheme_show_error_class($error,'buy_now'); ?>">
         <?php echo AuctionTheme_show_error_content_m($error,'buy_now'); ?>
        	<h2><?php echo __('Buy Now Price', 'AuctionTheme'); ?>:</h2>
        <p>
        <input type="text" size="16" name="buy_now" class="do_input input-sm" placeholder="<?php echo auctiontheme_get_currency_USD() ?>"
        	value="<?php echo auctionTheme_show_sum_of_cash_m(get_post_meta($pid, 'buy_now', true) ); ?>" />
             <input onchange="check_quant();"  name="only_buy_now" value="1" type="checkbox" <?php if($only_buy_now == "1") echo 'checked="checked"'; ?> /> <?php _e('Only buy now auction','AuctionTheme'); ?>
             <?php do_action('AuctionTheme_step1_after_buy_now_field');  ?>
             </p>
        </li>

        <?php do_action('ActionTheme_after_buy_now_li'); ?>


         <li>
        <h2><?php _e("Allow Offers?",'AuctionTheme');  ?>:</h2>
        <p><input type="checkbox" class="do_input input-sm" name="allow_offers" <?php echo (get_post_meta($pid,'allow_offers', true) == "1" ? 'checked="checked"' : ''); ?> value="1" />
        <?php do_action('AuctionTheme_step1_after_allow_offers_field');  ?>

       </p>
        </li>


        <li id="quantity_li" style="<?php echo ($only_buy_now != "1" ? 'display:none' : '');  ?>">
        	<h2><?php echo __('Quantity', 'AuctionTheme'); ?>:</h2>
        <p><input type="text" size="10" name="quant" class="do_input input-sm"
        	value="<?php echo (empty($_POST['quant']) ? get_post_meta($pid, 'quant', true) : $_POST['quant']); ?>" />
            <?php do_action('AuctionTheme_step1_after_quantity_field');  ?></p>
        </li>

        <script>

		function turn_me_off_on()
		{
			var op = jQuery(".shipping_component").css("opacity");
			if(op == 1)
			{
				jQuery(".shipping_component").fadeTo( "fast", 0.33 );
				jQuery(".shipping_component input").attr('disabled','disabled');
			}
			else
			{
				jQuery(".shipping_component").fadeTo( "fast", 1 );
				jQuery(".shipping_component input").removeAttr('disabled');
			}

		}

		function change_shipping_mode()
		{

			var selected = jQuery("input[type='radio'][name='shipping_type']:checked");
			if(selected.val() == "variable")
			{
				jQuery('#postage_courier_options').css('display','inline-block');
			}
			else
			{
				jQuery('#postage_courier_options').hide();
			}
		}

		</script>
        <?php

			$shipping_type = get_post_meta($pid, 'shipping_type', true);
			if(empty($shipping_type)) $shipping_type = "flat";

			$do_not_require_shipping = get_post_meta($pid,'do_not_require_shipping',true);
			if($do_not_require_shipping == 1)
			{
				$chk1 = "checked='checked'";
				echo '<script>

				jQuery(function() {
					jQuery(".shipping_component").fadeTo( "fast", 0.33 );
					jQuery(".shipping_component input").attr(\'disabled\',\'disabled\');
				});

				</script>';
			}
			else
			{
				$chk1 = '';
			}



		?>

        <?php do_action('ActionTheme_after_quantity_li'); ?>
        <li class="my_hr_shipping"></li>
		<li class="my_sub_title">
        	<h3><?php _e('Item Shipping','AuctionTheme'); ?></h3>
            <p class="class_p"><input type="checkbox" value="1" onchange="turn_me_off_on()" <?php echo $chk1 ?> name="do_not_require_shipping" /> <?php _e('This item does not require shipping.','AuctionTheme'); ?></p>

        </li>



        <li class="shipping_component">
            <h2 class="h2_shipping"><input type="radio" name="shipping_type" onclick="change_shipping_mode()"
            value="flat" <?php if($shipping_type == "flat") echo 'checked="checked"'; ?> /> <?php echo __('Flat Shipping:', 'AuctionTheme'); ?></h2>

            <p class="p_shipping">
            <input type="text" size="10" name="shipping" class="do_input input-sm"  placeholder="<?php echo auctiontheme_get_currency_USD(); ?>	"
                value="<?php echo auctionTheme_show_sum_of_cash_m(get_post_meta($pid, 'shipping', true)); ?>" />
                <?php do_action('AuctionTheme_step1_after_shipping_field');  ?></p>
        </li>



        <li class="shipping_component <?php echo AuctionTheme_show_error_class($error,'shipping'); ?>">
        <?php echo AuctionTheme_show_error_content_m($error,'shipping'); ?>

        <h2 class="h2_shipping"><input type="radio" name="shipping_type" onclick="change_shipping_mode()"
        value="variable" <?php if($shipping_type == "variable") echo 'checked="checked"'; ?> /> <?php echo __('Variable Shipping:', 'AuctionTheme'); ?></h2>
        </li>

        <style>
			#postage_courier_options
			{
				<?php if($shipping_type == 'variable') echo 'display:inline-block'; else echo 'display:none'; ?>
			}

		</style>

        <li class="shipping_component">
        	<div  id="postage_courier_options">
            	<div class="padd10">

                    <table width="100%">
                    <?php

						$args = "orderby=id&order=ASC&hide_empty=0&parent=0";
						$terms = get_terms( 'auction_shipping', $args );

						if(is_array($terms))
						foreach($terms as $term):

					?>
                    	<tr>
                        	<td><?php printf($term->name); ?></td>
                        	<td>


                            <input type="text" class="do_input input-sm" placeholder="<?php echo auctiontheme_get_currency_USD(); ?>"
                            name="shipping_value_<?php echo $term->term_id ?>" size="15" value="<?php echo auctiontheme_get_shipping_charge($pid, $term->term_id) ?>" />


                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </table>

                </div>
            </div>
        </li>


        <li class="my_hr_shipping"></li>
        <?php do_action('ActionTheme_after_shipping_li'); ?>


        <?php

			$auto_renew_item = get_post_meta($pid,'auto_renew_item',true);
			if($auto_renew_item == 1)
			{
				$chk2 = "checked='checked'";
			}
			else
			{
				$chk2 = '';
			}


			if(get_option('AuctionTheme_enable_auto_renew') != "no"):

		?>

		<li class="my_sub_title">
        	<h3><?php _e('Auto Renew','AuctionTheme'); ?></h3>
            <p class="class_p"><input type="checkbox" value="1" <?php echo $chk2 ?> name="auto_renew_item" /> <?php _e('Auto renew this item if it doesnt sell.','AuctionTheme'); ?></p>

        </li>

        <?php

			$amount_times = get_post_meta($pid,'amount_times',true);

		?>

         <li>
            <h2 class="h2_shipping"><?php echo __('AutoRenew Interval:', 'AuctionTheme'); ?></h2>
            <p class="p_shipping">
            	<select name="amount_times" class="do_input input-sm">
            		<option value="5" <?php echo ($amount_times == 5 ? 'selected="selected"' : '') ?>>5</option>
                    <option value="4" <?php echo ($amount_times == 4 ? 'selected="selected"' : '') ?>>4</option>
                    <option value="3" <?php echo ($amount_times == 3 ? 'selected="selected"' : '') ?>>3</option>
                    <option value="2" <?php echo ($amount_times == 2 ? 'selected="selected"' : '') ?>>2</option>
                    <option value="1" <?php echo ($amount_times == 1 ? 'selected="selected"' : '') ?>>1</option>
                </select>

                x

                <select class="do_input" name="amount_days input-sm">
            		<option value="30" <?php echo ($amount_days == 30 ? 'selected="selected"' : '') ?>>30</option>
                    <option value="12" <?php echo ($amount_days == 12 ? 'selected="selected"' : '') ?>>12</option>
                    <option value="7" <?php echo ($amount_days == 7 ? 'selected="selected"' : '') ?>>7</option>
                    <option value="5" <?php echo ($amount_days == 5 ? 'selected="selected"' : '') ?>>5</option>
                </select>

                <?php _e('days','AuctionTheme') ?>

            </p>
        </li>


        <li class="my_hr_shipping"></li>

        <?php endif; ?>

                 <li id="ending_li">
        <h2>



        <link rel="stylesheet" media="all" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ui-thing.css" />
		<script type="text/javascript" language="javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery-ui-timepicker-addon.js"></script>

       <?php _e("Auction Ending On:",'AuctionTheme'); ?></h2>
        <p><input type="text" name="ending" id="ending" class="do_input input-sm" value='<?php

		$dts = get_post_meta($pid, 'ending', true);
		if(!empty($dts))
		echo date_i18n('m/d/Y H:i:s',$dts);


		?>' />
        <?php do_action('AuctionTheme_step1_after_ending_field');  ?>
        </p>
        </li> <?php do_action('ActionTheme_after_ending_li'); ?>

		 <script>

        jQuery(document).ready(function() {
             jQuery('#ending').datetimepicker({
            showSecond: true,
			dateFormat: 'dd-mm-yy',
            timeFormat: 'HH:mm:ss'
        });});

         </script>




        <li id='prvbds' style="<?php echo ($only_buy_now == "1" ? 'display:none' : '');  ?>">
        	<h2><?php echo __('Private Bids:', 'AuctionTheme'); ?></h2>
        <p><select name="private_bids" class="do_input input-sm">
        <option value="no" <?php if(get_post_meta($pid,'private_bids',true) == "no") echo 'selected="selected"'; ?>><?php _e("No",'AuctionTheme'); ?></option>
        <option value="yes" <?php if(get_post_meta($pid,'private_bids',true) == "yes") echo 'selected="selected"'; ?>><?php _e("Yes",'AuctionTheme'); ?></option>
        </select>
        <?php do_action('AuctionTheme_step1_after_private_bids_field');  ?>
        </p>
        </li>

        <?php do_action('ActionTheme_after_private_bids_li'); ?>


        <li>
        	<h2><?php echo __('Address:','AuctionTheme'); ?></h2>
        <p><input type="text" size="50" class="do_input form-control"  name="auction_location_addr" value="<?php echo !isset($_POST['auction_location_addr']) ?
		get_post_meta($pid, 'Location', true) : $_POST['auction_location_addr']; ?>" />
        <?php do_action('AuctionTheme_step1_after_address_field');  ?>
        </p>
        </li>

        <?php do_action('ActionTheme_after_address_li'); ?>



		<?php

			$AuctionTheme_enable_html_description = get_option('AuctionTheme_enable_html_description');

		?>
        <li class="<?php echo AuctionTheme_show_error_class($error,'description'); ?>">
        <?php echo AuctionTheme_show_error_content_m($error,'description'); ?>
        <h2><?php echo __('Description:', 'AuctionTheme'); ?></h2>
        <p>
        <?php if($AuctionTheme_enable_html_description == 'yes'): ?>

        <div class="description_html_box_placeholder">
        <?php wp_editor( $post->post_content, 'auction_description', array('media_buttons' => false) ); ?>
        </div>
        <?php else: ?>

         <textarea rows="6" cols="60" class="do_input form-control" id="textareaID"  name="auction_description"><?php
		echo empty($_POST['auction_description']) ? trim($post->post_content) : $_POST['auction_description']; ?></textarea>

        <?php endif; ?>
       </p>


        <?php do_action('AuctionTheme_step1_after_description_field');  ?>

        </li>


		<?php do_action('ActionTheme_after_description_li'); ?>

	 <li>
        <h2><?php _e("Feature item?",'AuctionTheme');  ?>:</h2>
        <p><input type="checkbox" class="do_input" name="featured" <?php echo (get_post_meta($pid,'featured', true) == "1" ? 'checked="checked"' : ''); ?> value="1" />
        <?php do_action('AuctionTheme_step1_after_featured_field');  ?>

       </p>
        </li>

        <?php do_action('ActionTheme_after_feature_li'); ?>

       <!--
        <li>
        <h2><?php _e("Coupon", "AuctionTheme"); ?>:</h2>
        <p><input type="text" class="do_input" name="coupon" size="30" /> <?php if($false_cup == 0 && isset($_POST['auction_submit2'])) _e('The coupon code you used is wrong.','AuctionTheme'); ?>
        <?php do_action('AuctionTheme_step1_after_coupon_field');  ?>
        </p>
        </li>-->

		<?php do_action('ActionTheme_after_coupon_li'); ?>


       <?php

		$auction_tags = '';

	   	$t = wp_get_post_tags($pid);
		foreach($t as $tg)
		{
			$auction_tags .= $tg->name . ', ';
		}

	   ?>

		<li>
        	<h2><?php echo __('Tags', 'AuctionTheme'); ?>:</h2>
        <p><input type="text" size="50" class="do_input form-control"  name="auction_tags" value="<?php echo $auction_tags; ?>" />
        <?php do_action('AuctionTheme_step1_after_tags_field');  ?> </p>
        </li>


     	<?php do_action('ActionTheme_after_tags_li'); ?>

        <li>
        <h2>&nbsp;</h2>
        <p>
        <?php

		//echo '<a class="goback-link" href="'.AuctionTheme_post_new_link().'/step/1/?substep='.(count($_SESSION['AuctionTheme_stored_categories'])).'&post_new_auction_id='.  $pid.'">
		//'.__('Go Back','AuctionTheme').'</a>';

		 ?>
        <input type="submit" name="auction_submit_1" class="submit_bottom" value="<?php _e("Next Step", 'AuctionTheme'); ?> >>" /></p>
        </li>

    	<?php do_action('ActionTheme_after_submit_li'); ?>

    </ul>
    </form>

  <?php } ?>

 <!-- ####################################### Step 2 ######################################### -->






<?php

if($new_auction_step == 2)
{

	$img_nr = get_option("ad_theme_pic_nr");
	$catid 	= $_SESSION['posted_thing_cat'];
	$wii = get_option('ad_uploaded_image_width');

	if(empty($img_nr)) $img_nr = 5;

	global $current_user;
		$current_user = wp_get_current_user();
	$cid = $current_user->ID;


	if($uploaders == "html") $enc = 'enctype="multipart/form-data"';

	?>
    <!-- ###########################  -->
    <?php

		if($uploaders == "jquery"):

	?>

    <ul class="post-new">
    <li>
                            <h2><?php echo __('Images', 'AuctionTheme'); ?>:</h2>
                            <p>

	<div id="mcont">
    <form id="fileupload" action="<?php bloginfo('siteurl'); ?>/?uploady_thing=1&pid=<?php echo $pid; ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="pid" value="<?php echo $pid; ?>">
    <input type="hidden" name="cid" value="<?php echo $cid; ?>">

        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="http://blueimp.github.com/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="span7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php _e('Add files...','AuctionTheme') ?></span>
                    <input type="file" name="files[]" multiple>
                </span>

                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i>
                    <span><?php _e('Cancel upload','AuctionTheme') ?></span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php _e('Delete','AuctionTheme') ?></span>
                </button>
                <input type="checkbox" class="toggle">
            </div>
            <!-- The global progress information -->
            <div class="span5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
    </form>


<!-- modal-gallery is the modal dialog used for the image gallery -->
<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd" tabindex="-1">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3 class="modal-title"></h3>
    </div>
    <div class="modal-body"><div class="modal-image"></div></div>
    <div class="modal-footer">
        <a class="btn modal-download" target="_blank">
            <i class="icon-download"></i>
            <span>Download</span>
        </a>
        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
            <i class="icon-play icon-white"></i>
            <span>Slideshow</span>
        </a>
        <a class="btn btn-info modal-prev">
            <i class="icon-arrow-left icon-white"></i>
            <span>Previous</span>
        </a>
        <a class="btn btn-primary modal-next">
            <span>Next</span>
            <i class="icon-arrow-right icon-white"></i>
        </a>
    </div>
</div>


<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span><?php _e('Start','AuctionTheme') ?></span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span><?php _e('Cancel','AuctionTheme') ?></span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                <i class="icon-trash icon-white"></i>
                <span><?php _e('Delete','AuctionTheme') ?></span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
    </tr>
{% } %}
</script>

</div>


    						</p>
    						</li>

    </ul>


    <?php endif; //endif jquery uploads ?>

    <!-- ########################## -->

    <form method="post" <?php echo $enc; ?>  action="<?php echo AuctionTheme_post_new_with_pid_stuff_thg($pid, $new_auction_step);?>" >
      <ul class="post-new">

      <?php
	  	if($uploaders == "html"):
	  ?>

 <li>
                            <h2><?php echo __('Images', 'AuctionTheme'); ?>:</h2>
                            <p>
          <?php

		  		$args = array(
				'order'          => 'ASC',
				'orderby'        => 'post_date',
				'post_type'      => 'attachment',
				'post_parent'    => $pid,
				'post_mime_type' => 'image',
				'numberposts'    => -1,
				); $i = 0;

				$attachments = get_posts($args);

				$default_nr = get_option('AuctionTheme_nr_max_of_images');
		  		if(empty($default_nr)) $default_nr = 5;

				$actual_nr = count($attachments);
				$dis = $default_nr - $actual_nr;

		  		for($i=1;$i<=$dis;$i++):
				?>

                	<input type="file" class="do_input file_inpt" name="file_<?php echo $i; ?>" />

				<?php	endfor; ?>

                          </p>
                            </li>

                           <li>

                            <div id="thumbnails" style="overflow:hidden;">

                                          <script type="text/javascript">

	function delete_this3(id)
	{
		 jQuery.ajax({
						method: 'get',
						url : '<?php echo get_bloginfo('siteurl');?>/?_ad_delete_pid='+id,
						dataType : 'text',
						success: function (text) {   jQuery('#image_ss'+id).remove(); window.location.reload();  }
					 });
		  //alert("a");

	}

</script>


    <?php



	if($pid > 0)
	if ($attachments) {
	    foreach ($attachments as $attachment) {
		$url = wp_get_attachment_url($attachment->ID);

			echo '<div class="div_div2"  id="image_ss'.$attachment->ID.'"><img width="70" class="image_class" height="70" src="' .
			AuctionTheme_wp_get_attachment_image($attachment->ID, array(70, 70)). '" />
			<a href="javascript: void(0)" onclick="delete_this3(\''.$attachment->ID.'\')"><img border="0" src="'.get_template_directory_uri().'/images/delete_icon.png" /></a>
			</div>';

	}
	}


	?>

    </div>

                           </li>

 	<?php endif; //image uploaders html ?>


 		<?php /*-------  custom fields  -------- */ ?>
        <?php



		$auction_cat = wp_get_object_terms($pid, 'auction_cat');
		foreach($auction_cat as $acs)
		{
			$rrt[] = $acs->term_id;
		}

		$cate = $rrt;

		$arr 	= get_auction_category_fields($cate, $pid);

		for($i=0;$i<count($arr);$i++)
		{

			        echo '<li>';
					echo '<h2>'.$arr[$i]['field_name'].$arr[$i]['id'].':</h2>';
					echo '<p>'.$arr[$i]['value'];
					do_action('AuctionTheme_step2_after_custom_field_'.$arr[$i]['id'].'_field');
					echo '</p>';
					echo '</li>';

					do_action('ActionTheme_after_field_'.$arr[$i]['id'].'_li');


		}


		do_action('AuctionTheme_step2_form_thing', $pid);


		?>


        <li>
        <h2>&nbsp;</h2>
        <p>
        <?php

		echo '<a class="submit_bottom" href="'.AuctionTheme_post_new_with_pid_stuff_thg($pid, ($new_auction_step-1)).'">'.__('Go Back','AuctionTheme').'</a>';

		?>
        <input type="submit" name="auction_submit_photos" class="submit_bottom" value="<?php _e("Next Step", 'AuctionTheme'); ?> >>" /></p>
        </li>


    </ul>
    </form>

  <?php } //end step2 ?>


<?php


if($new_auction_step == "3")
{
	if($_GET['finalise'] == "yes") $finalise = true;
	else $finalise = false;

	echo '<div class="padd10">';
	//-----------------------

	$AuctionTheme_new_auction_listing_fee = get_option('AuctionTheme_new_auction_listing_fee');
	if(empty($AuctionTheme_new_auction_listing_fee)) $AuctionTheme_new_auction_listing_fee = 0;

	$AuctionTheme_new_auction_feat_listing_fee = get_option('AuctionTheme_new_auction_feat_listing_fee');
	if(empty($AuctionTheme_new_auction_feat_listing_fee)) $AuctionTheme_new_auction_feat_listing_fee = 0;

	$AuctionTheme_new_auction_sealed_bidding_fee = get_option('AuctionTheme_new_auction_sealed_bidding_fee');
	if(empty($AuctionTheme_new_auction_sealed_bidding_fee)) $AuctionTheme_new_auction_sealed_bidding_fee = 0;

	$AuctionTheme_get_images_cost_extra = AuctionTheme_get_images_cost_extra($pid);
	$catid 								= AuctionTheme_get_item_primary_cat($pid);

	//---------------------------------

	$custom_set = get_option('auctionTheme_enable_custom_posting');
	if($custom_set == 'yes')
	{
		$posting_fee = get_option('auctionTheme_theme_custom_cat_'.$catid);
		if(empty($posting_fee)) $posting_fee = 0;
	}
	else
	{
		$posting_fee = $AuctionTheme_new_auction_listing_fee;
	}


	//---------------------------------

	$payment_arr = array();
	$AuctionTheme_enable_membership = get_option('AuctionTheme_enable_membership');

	if($posting_fee > 0 and $AuctionTheme_enable_membership != "yes")
	{

		$my_small_arr = array();
		$my_small_arr['fee_code'] 		= 'base_fee';
		$my_small_arr['show_me'] 		= true;
		$my_small_arr['amount'] 		= $posting_fee;
		$my_small_arr['description'] 	= __('Base Listing Fee','AuctionTheme');
		array_push($payment_arr, $my_small_arr);

	}
	//--------------------------------

	$featured = get_post_meta($pid, 'featured', true);

	if($featured == "1"):
		$my_small_arr = array();
		$my_small_arr['fee_code'] 		= 'feat_fee';
		$my_small_arr['show_me'] 		= true;
		$my_small_arr['amount'] 		= $AuctionTheme_new_auction_feat_listing_fee;
		$my_small_arr['description'] 	= __('Featured Listing Fee','AuctionTheme');
		array_push($payment_arr, $my_small_arr);
	endif;

	//--------------------------------

	$private_bids = get_post_meta($pid, 'private_bids', true);

	if($private_bids == "yes"):
		$my_small_arr = array();
		$my_small_arr['fee_code'] 		= 'sealed_fee';
		$my_small_arr['show_me'] 		= true;
		$my_small_arr['amount'] 		= $AuctionTheme_new_auction_sealed_bidding_fee;
		$my_small_arr['description'] 	= __('Sealed Bidding Fee','AuctionTheme');
		array_push($payment_arr, $my_small_arr);
	endif;


		$my_small_arr = array();
		$my_small_arr['fee_code'] 		= 'extra_img';
		$my_small_arr['show_me'] 		= true;
		$my_small_arr['amount'] 		= $AuctionTheme_get_images_cost_extra;
		$my_small_arr['description'] 	= __('Extra Images Fee','AuctionTheme');
		array_push($payment_arr, $my_small_arr);

	//--------------------------------

	$post 			= get_post($pid);

	//---------------------------------------------

	$new_total 		= 0;

	foreach($payment_arr as $payment_item):
		if($payment_item['amount'] > 0):
			$new_total += $payment_item['amount'];
		endif;
	endforeach;

	//----------------------------------------

	$total 			= $new_total;
	$total 			= apply_filters('AuctioTheme_total_price_to_pay' , 			$total, $pid);

	$opt = get_option('AuctionTheme_admin_approve_auction');
	if($opt == "yes") $admin_must_approve = true;
	else $admin_must_approve = false;

	//-----------------------------------------

	do_action('AuctionTheme_action_when_posting_auction', $pid);
	do_action('AuctionTheme_action_when_posting_auction_payment_arr', $payment_arr, $new_total);

	if($total == 0)
	{
			global $current_user;
				$current_user = wp_get_current_user();

			echo '<div >';
			echo __('Thank you for posting your item with us.','AuctionTheme');
			update_post_meta($pid, "paid", "1");


			if($finalise):
				if($admin_must_approve)
				{
					$my_post = array();
					$my_post['ID'] = $pid;
					$my_post['post_status'] = 'draft';

					wp_update_post( $my_post );

					AuctionTheme_send_email_posted_item_not_approved($pid);
					AuctionTheme_send_email_posted_item_approved_admin($pid);

					echo '<br/>'.__("Your auction isn't yet live, the admin needs to approve it.", "AuctionTheme");



				}
				else
				{
					$my_post = array();
					$my_post['ID'] = $pid;
					$my_post['post_status'] = 'publish';

					wp_update_post( $my_post );

					AuctionTheme_send_email_posted_item_approved($pid);
					AuctionTheme_send_email_posted_item_not_approved_admin($pid);

				}

			endif;
			echo '</div>';


	}
	else
	{
			update_post_meta($pid, "paid", "0");

			echo '<div >';
			echo __('Thank you for posting your auction with us. Below is the total price that you need to pay in order to put your auction live.<br/>
			Click the pay button and you will be redirected...', 'AuctionTheme');
			echo '</div>';



	}

	//----------------------------------------


	echo '<table style="margin-top:25px">';

	if($total > 0) :
	foreach($payment_arr as $payment_item):

			if($payment_item['amount'] > 0):

				echo '<tr>';
				echo '<td>'.$payment_item['description'].'&nbsp; &nbsp;</td>';
				echo '<td>'.auctionTheme_get_show_price($payment_item['amount'],2).'</td>';
				echo '</tr>';

			endif;

		endforeach;


				echo '<tr>';
	echo '<td>&nbsp;</td>';
	echo '<td></td>';
	echo '<tr>';

	echo '<tr>';
	echo '<td><strong>'.__('Total to Pay','AuctionTheme').'</strong></td>';
	echo '<td><strong>'.auctionTheme_get_show_price($total,2).'</strong></td>';
	echo '<tr>';


	echo '<tr>';
	echo '<td>&nbsp;<br/>&nbsp;</td>';
	echo '<td></td>';
	echo '<tr>';

	endif;

	if($total == 0)
	{
		if(!$admin_must_approve && $finalise):

			echo '<tr>';
			echo '<td></td>';
			echo '<td><a href="'.get_permalink($pid).'" class="pay_now">'.__('See your auction','AuctionTheme') .'</a></td>';
			echo '<tr>';

		endif;

	}
	else
	{
		update_post_meta($pid,'unpaid','1');

		$AuctionTheme_enable_pay_credits = get_option('AuctionTheme_enable_pay_credits');
		if($AuctionTheme_enable_pay_credits != 'no'):


			echo '<tr>';
			echo '<td><strong>'.__('Your Total Credits','AuctionTheme').'</strong></td>';
			echo '<td><strong>'.auctionTheme_get_show_price(auctionTheme_get_credits($uid),2).'</strong></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td>'.__('Pay by Credits','AuctionTheme').'</td>';
			echo '<td><a href="'.get_bloginfo('siteurl').'/?tms='.current_time('timestamp',0).'&a_action=credits_listing&pid='.$pid.'" class="post_bid_btn">'.__('Pay Now','AuctionTheme').'</a></td>';
			echo '<tr>';

		endif;

						echo '<tr>';
						echo '<td></td><td>';

						$AuctionTheme_paypal_enable 		= get_option('AuctionTheme_paypal_enable');
						$AuctionTheme_alertpay_enable 		= get_option('AuctionTheme_alertpay_enable');
						$AuctionTheme_moneybookers_enable 	= get_option('AuctionTheme_moneybookers_enable');


						if($AuctionTheme_paypal_enable == "yes")
						{
							echo '<a href="'.get_bloginfo('siteurl').'/?a_action=paypal_listing&pid='.$pid.'" class="post_bid_btn">'.__('Pay by PayPal','AuctionTheme').'</a>';

							$AuctionTheme_paypal_enable2 		= get_option('AuctionTheme_paypal_enable2');
						if($AuctionTheme_paypal_enable == "yes")
						echo '<a href="'.get_bloginfo('siteurl').'/?a_action=paypal_cc&pid='.$pid.'" class="post_bid_btn">'.__('Pay by Credit Card','AuctionTheme').'</a>';

						}

						if($AuctionTheme_moneybookers_enable == "yes")
							echo '<a href="'.get_bloginfo('siteurl').'/?a_action=mb_listing&pid='.$pid.'" class="post_bid_btn">'.__('Pay by MoneyBookers/Skrill','AuctionTheme').'</a>';

						if($AuctionTheme_alertpay_enable == "yes")
							echo '<a href="'.get_bloginfo('siteurl').'/?a_action=payza_listing&pid='.$pid.'" class="post_bid_btn">'.__('Pay by Payza','AuctionTheme').'</a>';

						do_action('AuctionTheme_add_payment_options_to_post_new_project', $pid);

						echo '</td></tr>';



	}


	echo '<tr>';
	echo '<td>&nbsp;<br/>&nbsp;</td>';
	echo '<td></td>';
	echo '<tr>';

	echo '</table>';




	echo '<div class="clear10"></div>';
	echo '<div class="clear10"></div>';

	if(!$finalise)
	echo '<a href="'. AuctionTheme_post_new_with_pid_stuff_thg($pid, '2') .'" class="submit_bottom" >'.__('Go Back','AuctionTheme').'</a>';

	if($total == 0 && !$finalise)
	echo '<a href="'. AuctionTheme_post_new_with_pid_stuff_thg($pid, '3', 'finalise').'"
	class="submit_bottom" >'.__('Finalize and Publish Item','AuctionTheme').'</a>';

	echo '<div class="clear10"></div>';

	//*****************************************************************************

	$reverse    = get_post_meta($pid, "reverse", true);
	$location   = get_post_meta($pid, "Location", true);
	$ending     = get_post_meta($pid, "ending", true);
	$post_au = get_post($pid);

	?>

       <!-- end -->

    </div>
    </div>
    </div>

    <div class="main_thing_pst_new">

    <div class="col-xs-12 col-sm-8 col-lg-8">





<?php

	$reverse    = get_post_meta($pid, "reverse", true);
	$location   = get_post_meta($pid, "Location", true);
	$ending     = get_post_meta($pid, "ending", true);

?>






 			<div class="my_box3">
            	<div class="box_title auction_page_title"><h1><?php echo $post_au->post_title ?></h1></div>
					<div class="col-xs-12 col-sm-4 col-lg-4 bb-image">
						<?php echo AuctionTheme_get_first_post_image($pid, 250, 170, 'img_class img-responsive'); ?>

						<?php

				$arr = AuctionTheme_get_post_images($pid, 4);

				if($arr)
				{


				echo '<ul class="image-gallery" style="padding-top:10px">';
				foreach($arr as $image)
				{
					echo '<li><a href="'.AuctionTheme_wp_get_attachment_image($image, array(900, 700)).'" rel="image_gal1">'.wp_get_attachment_image( $image, array(50, 50) ).'</a></li>';
				}
				echo '</ul>';


				}
				//else { echo __('No images.') ;}

				?>

					</div>

                <?php

				$start_price = auctionTheme_get_start_price($pid);
				$current	 = auctionTheme_get_current_price($pid);
				$buy_now	 = auctionTheme_get_buy_it_now_price($pid);
				$closed 	 = get_post_meta($pid,'closed',true);

				?>

				<div class="col-xs-12 col-sm-8 col-lg-8">



                <?php



				if($closed == "0") :


				$only_buy_now = get_post_meta($pid, 'only_buy_now', true);


				if($only_buy_now != '1'):

				?>
                <!-- ######### -->

                <div class="bid_panel">
                <div class="padd10">
                <form method="post" id="my_bid_form_1" onsubmit="return confirm_my_bid();">
                <input type="hidden" value="1" name="bid_now_cc" />
                	<ul class="auction-details">
							<li>
								<h3><?php echo __("Current Bid","AuctionTheme"); ?>:</h3>
								<p><?php echo auctionTheme_get_show_price(auctionTheme_get_current_price($pid)); ?></p>
							</li>

                            <li>
								<h3>&nbsp;</h3>
								<p><input type="text" name="bid" id="bids_val" size="10" />
                                <input type="hidden" name="control_id" value="<?php echo  ($pid); ?>" />
                                <input class="submit_bottom" type="submit" id="place_bid" name="bid_now" value="<?php _e("Place Bid","AuctionTheme"); ?>" /></p>
							</li>

                	</ul>
                   </form>
                </div>
                </div>

                <?php endif; ?>

                <!-- ######### -->
                <?php

				$buynow = get_post_meta($pid,'buy_now',true);
				if(!empty($buynow)):

				?>

                <div class="bid_panel">
                <div class="padd10">
                <form method="post"> <input type="hidden" name="control_id" value="<?php echo  ($pid); ?>" />
                	<ul class="auction-details ">
                    <?php if($only_buy_now == '1'): ?>

							<li>
								<h3><?php echo __("Quantity","AuctionTheme"); ?>:</h3>
								<p><?php echo get_post_meta($pid, 'quant', true); ?> <?php echo __("items", 'AuctionTheme'); ?></p>
							</li>

                   <?php endif; ?>

                            <li>
								<h3><?php echo __("Buy It Now","AuctionTheme"); ?>:</h3>
								<p><?php echo auctionTheme_get_show_price(auctionTheme_get_buy_it_now_price($pid)); ?></p>
							</li>

                            <li>
								<h3>&nbsp;</h3>
								<p><input type="submit" class="submit_bottom" name="buy_now" value="<?php _e("Buy Now!","AuctionTheme"); ?>" /></p>
							</li>

                	</ul>
                </form>
                </div>
                </div>

                <?php endif; ?>



                <?php

				$allow_offers = get_post_meta($pid,'allow_offers',true);
				if(!auctiontheme_see_if_offer_posted($pid, $uid) and $allow_offers == "1"):

					$AuctionTheme_currency_position = get_option('AuctionTheme_currency_position');

				?>

                <div class="bid_panel">
                <div class="padd10">
                <form method="post"> <input type="hidden" name="control_id" value="<?php echo  ($pid); ?>" />
                	<ul class="auction-details">

                            <li>
								<h3><?php echo __("Make an Offer","AuctionTheme"); ?>:</h3>
								<p>
                                <?php if($AuctionTheme_currency_position == "front") echo auctiontheme_get_currency_USD(); ?>
                                <input type="text" size="5" name="offered_price" /> <?php if($AuctionTheme_currency_position != "front") echo auctiontheme_get_currency_USD(); ?></p>
							</li>

                            <li>
								<h3>&nbsp;</h3>
								<p><input type="submit" class="my-buttons2" name="make_offer" value="<?php _e("Submit Offer","AuctionTheme"); ?>" /></p>
							</li>

                	</ul>
                </form>
                </div>
                </div>

                <?php endif; ?>
                <?php

					$offr = auctiontheme_waiting_to_answer_offer($pid, $uid);

					if( $offr != false):

				?>

                <div class="bid_panel">
                <div class="padd10">

                	<ul class="auction-details">

                            <li>
								<?php

								if($offr->counteroffer_sent == 0 and $offr->counteroffer_accepted == 0 and $offr->counteroffer_rejected == 0)
								 echo sprintf(__("Your offer of %s was submitted. Waiting the seller's answer.","AuctionTheme"), auctiontheme_get_sumitted_offer_price($pid,$uid)); ?>
                                <?php

									if($offr->counteroffer_sent == 1 and $offr->counteroffer_accepted == 0 and $offr->counteroffer_rejected == 0)
									{
										if(get_post_meta($pid, 'closed', true) == 0)
										{
											echo '<br/>' . sprintf(__('Seller sent counter offer of: %s','AuctionTheme'), auctiontheme_get_show_price($offr->counteroffer_price));
											echo '<br/>' . '<a href="'.get_bloginfo('siteurl').'/?a_action=accept_counter_offer&pid='.$pid.'&ids='.$offr->id.'">'.__('Accept Offer','AuctionTheme').'</a> |
											 <a href="'.get_bloginfo('siteurl').'/?a_action=reject_counter_offer&pid='.$pid.'&ids='.$offr->id.'">'.__('Reject Offer','AuctionTheme').'</a> ';
										}
									}

								?>
							</li>



                	</ul>

                </div>
                </div>


               <?php endif; ?>

              <?php

               else:
				// auction closed
				?>

                <div class="bid_panel">
                <div class="padd10">

                	<?php

					$pid = $pid;
					$winner = get_post_meta($pid, 'winner', true);
					if(!empty($winner))
					{

						global $wpdb;
						$q = "select bid from ".$wpdb->prefix."auction_bids where pid='$pid' and winner='1'";
						$r = $wpdb->get_results($q);
						$r = $r[0];

						_e("Auction closed for price: ","AuctionTheme");
						echo auctionTheme_get_show_price($r->bid);

					}

					?>

                </div>
                </div>

                <?php endif; ?>
                <div class="clear10"></div>




                        <div class="row border-bottom">
								<div class="col-xs-5 col-sm-5 col-lg-5 font-weight-bold"><?php echo __("Auction ID","AuctionTheme"); ?>:</div>
								<div class="col-xs-7 col-sm-7 col-lg-7">#<?php the_ID(); ?></div>
							</div>

				         <?php

						 $reserve = get_post_meta($pid,'reserve',true);

						 if(!empty($reserve)):
						 ?>
                            <div class="row border-bottom">

								<div class="col-xs-5 col-sm-5 col-lg-5 font-weight-bold"><?php echo __("Reserve","AuctionTheme"); ?>:</div>
								<div class="col-xs-7 col-sm-7 col-lg-7"><?php

								$prc = auctionTheme_get_current_price($pid);
								if($prc >= $reserve) echo __('Reserve price met.',"AuctionTheme");
								else echo __('Reserve price not met.',"AuctionTheme");
								 ?></div>
							</div>
                          <?php endif; ?>

							<div class="row border-bottom">

								<div class="col-xs-5 col-sm-5 col-lg-5 font-weight-bold"><?php echo __("Location","AuctionTheme"); ?>:</div>
								<div class="col-xs-7 col-sm-7 col-lg-7"><?php echo get_the_term_list( $pid, 'auction_location', '', ', ', '' ); ?></div>
							</div>

							<div class="row border-bottom">
								<?php $cvs = current_time('timestamp', 0); ?>
								<div class="col-xs-5 col-sm-5 col-lg-5 font-weight-bold"><?php echo __("Posted on","AuctionTheme"); ?>:</div>
								<div class="col-xs-7 col-sm-7 col-lg-7"><?php echo date_i18n("jS \o\\f F Y \a\\t g:i A", $cvs); ?></div>
							</div>

							<?php

								if($closed == "0"):
								$AuctionTheme_no_time_on_buy_now = get_option('AuctionTheme_no_time_on_buy_now');
							if($only_buy_now == "1" and $AuctionTheme_no_time_on_buy_now == "yes"):
							//asd
							else:
							?>
							<div class="row border-bottom">

								<div class="col-xs-5 col-sm-5 col-lg-5 font-weight-bold"><?php echo __("Time Left","AuctionTheme"); ?>:</div>

                                <div class="col-xs-7 col-sm-7 col-lg-7">
                                <p class="expiration_auction_p"><?php echo ($closed == "0" ? ($ending - current_time('timestamp',0))
								: __("Expired/Closed","AuctionTheme")); ?></p></div>

							<!--	<p><?php echo ($closed == "0" ? AuctionTheme_prepare_seconds_to_words($ending - current_time('timestamp',0))
								: __("Expired/Closed","AuctionTheme")); ?></p> -->
							</div>
                            <?php endif; endif; ?>

                            <div class="row border-bottom">

								<div class="col-xs-5 col-sm-5 col-lg-5 font-weight-bold"><?php echo __("Watch List","AuctionTheme"); ?>:</div>
								<div class="col-xs-7 col-sm-7 col-lg-7"><div class="watch-list txt-align-lft"><?php



				if(AuctionTheme_check_if_pid_is_in_watchlist($pid, $uid) == true):
				?>

                 <a class="rem-to-watchlist" rel="<?php the_ID(); ?>"  href="#"><?php _e('- watchlist','AuctionTheme'); ?></a>

                <?php else: ?>

                <a class="add-to-watchlist" rel="<?php the_ID(); ?>" href="#"><?php _e('+ watchlist','AuctionTheme'); ?></a>

                <?php endif; ?>


								</div></div>
							</div>




			</div>
			</div>
			<?php

				$arrms = get_auction_fields_values($post_au->ID);
			if(count($arrms) > 0)
							{
			?>
            	<div class="clear10"></div>


            <div class="my_box3">


            	<div class="box_title"><?php echo __("Item Specifics","AuctionTheme"); ?></div>
                <div class="box_content b1b1">


                        <?php


							if(count($arrms) > 0)
							{
								?>

                                <table width="100%">

                                <?php
								for($i=0;$i<count($arrms);$i++)
								{

							?>
							<tr>

								<th class="gold_thing_th"><?php echo $arrms[$i]['field_name'];?></th>
								<th><?php


								if(is_array($arrms[$i]['field_value'][0]))
								{

									foreach($arrms[$i]['field_value'][0] as $vl)
									{

										echo $vl	.'<br/>';
									}
								}
								else echo $arrms[$i]['field_value'][0];
								?></th>
							</tr>
							<?php } ?>
                        </table>
                        <?php } ?>

				</div>
			</div>
            <?php } ?>

			<div class="clear10"></div>
				<?php do_action('AuctionTheme_auction_page_before_description_div'); ?>


			<!-- ####################### -->

			<div class="my_box3">


            	<div class="box_title"><?php echo __("Description","AuctionTheme"); ?></div>
                <div class="padd10">
					<p><?php echo $post_au->post_content; ?></p>
				</div>
			</div>


			<div class="clear10"></div>

            	<?php do_action('AuctionTheme_auction_page_before_bids_div');

				$post_au = get_post($pid);
				$allow_offers = get_post_meta($pid,'allow_offers',true);
				global $wpdb;

				if($allow_offers == "1" and $uid == $post_au->post_author):

				?>
            <!-- ####################### -->

            <div class="my_box3">


            	<div class="box_title"><?php echo __("Received Offers",'AuctionTheme'); ?></div>
                <div class="box_content">
            		<?php

					$s = "select * from ".$wpdb->prefix."auction_offers where pid='".$pid."'";
					$r = $wpdb->get_results($s);

					if(count($r) > 0)
					{
						echo '<table class="table">';
						echo '<thead><tr>';
							echo '<th>'.__('Username','AuctionTheme').'</th>';
							echo '<th>'.__('Offer Price','AuctionTheme').'</th>';
							echo '<th>'.__('Date Made','AuctionTheme').'</th>';
							echo '<th>'.__('Options','AuctionTheme').'</th>';
						echo '</tr></thead><tbody>';



						foreach($r as $row)
						{
							$user = $row->uid;
							$user = get_userdata($user);

							echo '<tr>';
							echo '<th>'.$user->user_login.'</th>';
							echo '<th>'.auctiontheme_get_show_price($row->price).'</th>';
							echo '<th>'.date_i18n("d-m-Y H:i:s", $row->datemade).'</th>';

							$off_prc = sprintf(__('Counter Offer of %s sent', 'AuctionTheme'), auctiontheme_get_show_price($row->counteroffer_price));

							echo '<th>'.(($row->approved == 0 and $row->rejected == 0) ? ( $row->counteroffer_sent == 1 ? (

							($row->counteroffer_accepted == 0 and $row->counteroffer_rejected == 0) ? $off_prc :
							($row->counteroffer_accepted == 1 ? __('Counter Offer Accepted','AuctionTheme') : __('Counter Offer Rejected','AuctionTheme'))

							) : (
							'<a href="'.get_bloginfo('siteurl').'/?a_action=accept_offer&pid='.$pid.'&ids='.$row->id.'">'.__('Accept Offer','AuctionTheme').'</a> |
							<a href="'.get_bloginfo('siteurl').'/?a_action=reject_offer&pid='.$pid.'&ids='.$row->id.'">'.__('Reject Offer','AuctionTheme').'</a> | '.
							'<a href="'.get_bloginfo('siteurl').'/?a_action=counter_offer&pid='.$pid.'&ids='.$row->id.'">'.__('CounterOffer','AuctionTheme').'</a>' )) :
							auctiontheme_show_status_offer($row->approved, $row->rejected)		 ).'</th>';
							echo '</tr>	';

						}

						echo '</tbody></table>';


					}
					else _e('You have not received any offers yet.','AuctionTheme');


					?>


                </div>
            </div>
            <div class="clear10"></div>

			<?php

			endif;

			$private_bids = get_post_meta($pid, 'private_bids', true);
			$only_buy_now = get_post_meta($pid, 'only_buy_now', true);

			if($only_buy_now != "1"):

			?>
			<div class="my_box3">

            	<div class="box_title"><?php echo __("Posted Bids",'AuctionTheme'); ?> <?php

				if($private_bids == 'yes') _e('[auction has private bids]','AuctionTheme');

				 ?></div>
                <div class="box_content">
				<?php




				$pid = $pid;

				$bids = "select * from ".$wpdb->prefix."auction_bids where pid='$pid' order by id DESC";
				$res  = $wpdb->get_results($bids);

				if($post_au->post_author == $uid) $owner = 1; else $owner = 0;

				if(count($res) > 0)
				{

					if($private_bids == 'yes')
					{
						if ($owner == 1) $show_stuff = 1;
						else if(auctionTheme_current_user_has_bid($uid, $res)) $show_stuff = 1;
						else $show_stuff = 0;
					}
					else $show_stuff = 1;

					//------------

					if($show_stuff == 1):

						echo '<table class="table">';
						echo '<thead><tr>';
							echo '<th>'.__('Username','AuctionTheme').'</th>';
							echo '<th>'.__('Bid Amount','AuctionTheme').'</th>';
							echo '<th>'.__('Date Made','AuctionTheme').'</th>';
							if ($owner == 1):
								if($reverse == 'yes' || $reverse == '1')
								echo '<th>'.__('Choose Winner','AuctionTheme').'</th>';
							echo '<th>'.__('Messaging','AuctionTheme').'</th>';
							endif;

							if($closed == "1") echo '<th>'.__('Winner','AuctionTheme').'</th>';

						echo '</tr></thead><tbody>';

					endif;

					//-------------

					$using_perm = AuctionTheme_using_permalinks();

							if($using_perm)	$privurl_m = get_permalink(get_option('AuctionTheme_my_account_priv_mess_page_id')). "?";
							else $privurl_m = get_bloginfo('siteurl'). "/?page_id=". get_option('AuctionTheme_my_account_priv_mess_page_id'). "&";

					foreach($res as $row)
					{

						if ($owner == 1) $show_this_around = 1;
						else
						{
							if($private_bids == 'yes')
							{
								if($uid == $row->uid) 	$show_this_around = 1;
								else $show_this_around = 0;
							}
							else
							$show_this_around = 1;

						}

						if($show_this_around == 1):

						$user = get_userdata($row->uid);
						echo '<tr>';
						echo '<th>'.$user->user_login.'</th>';
						echo '<th>'.auctionTheme_get_show_price($row->bid).'</th>';
						echo '<th>'.date_i18n("d-M-Y H:i:s", $row->date_made).'</th>';
						if ($owner == 1 ) {
							//if($reverse == 'yes' || $reverse == '1')
							//echo '<th><a href="'.get_bloginfo('siteurl').'/choose-winner/'.$pid.'/'.$row->id.'">'.__('Select','AuctionTheme').'</a></th>';

							echo '<th><a href="'.$privurl_m.'priv_act=send&uid='.$row->uid.'&pid='.$pid.'">'.__('Send Message','AuctionTheme').'</a></th>';
						}

						if($closed == "1") { if($row->winner == 1) echo '<th>'.__('Yes','AuctionTheme').'</th>'; else echo '<th>&nbsp;</th>'; }

						echo '</tr>';


						endif;
					}

					echo '</tbody></table>';
				}
				else _e("No bids placed yet.",'AuctionTheme');
				?>

			</div>
			</div>

			<div class="clear10"></div> <?php endif; ?>

			<!-- ####################### -->
				<?php do_action('AuctionTheme_auction_page_before_image_gallery_div'); ?>
			<div class="my_box3">


            	<div class="box_title"><?php echo __("Image Gallery",'AuctionTheme'); ?></div>
                <div class="box_content">
				<?php

				$arr = AuctionTheme_get_post_images($pid);

				if($arr)
				{


				echo '<ul class="image-gallery">';
				foreach($arr as $image)
				{
					echo '<li><a href="'.AuctionTheme_wp_get_attachment_image($image, array(900, 700)).'" rel="image_gal2">'.wp_get_attachment_image( $image, array(100, 80) ).'</a></li>';
				}
				echo '</ul>';

				}
				else { echo __('No images.','AuctionTheme') ;}

				?>



			</div>
			</div>

			<div class="clear10"></div>

			<!-- ####################### -->
				<?php do_action('AuctionTheme_auction_page_before_map_div'); ?>

                <?php

					$AuctionTheme_enable_locations = get_option('AuctionTheme_enable_locations');
					if($AuctionTheme_enable_locations != 'no'):

				?>

			<div class="my_box3">


            	<div class="box_title"><?php echo __("Map Location",'AuctionTheme'); ?></div>
                <div class="padd10">

				<div id="map" style=" height: 300px;border:2px solid #ccc;float:left" class="col-xs-12 col-sm-12 col-lg-12"></div>

                <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>

            <script type="text/javascript"
            src="<?php echo get_template_directory_uri(); ?>/js/mk.js"></script>
                                                <script type="text/javascript">

	  var geocoder;
  var map;
  function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-34.397, 150.644);
    var myOptions = {
      zoom: 13,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map"), myOptions);
  }

  function codeAddress(address) {

    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new MarkerWithLabel({

            position: results[0].geometry.location,
			map: map,
       labelContent: address,
       labelAnchor: new google.maps.Point(22, 0),
       labelClass: "labels", // the CSS class for the label
       labelStyle: {opacity: 1.0}

        });
      } else {
        //alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }

initialize();

codeAddress("<?php

	global $post_au;
	$pid = $post_au->ID;

	$terms = wp_get_post_terms($pid,'auction_location');
	foreach($terms as $term)
	{
		echo $term->name.",";
	}

	$location = get_post_meta($pid, "Location", true);
	echo $location;

 ?>");

    </script>


			</div>
			</div>

			<!-- ####################### -->
			<?php

				endif;

				global $post_au;
				$pid = $post_au->ID;

			 ?>




</div>

<?php

	echo '<div id="right-sidebar" class="page-sidebar col-xs-12 col-sm-4 col-lg-4">';
	echo '<ul class="xoxo">';

	?>

    	<li class="widget-container widget_text" id="ad-other-details">
		<h3 class="widget-title"><?php _e("Seller Details",'AuctionTheme'); ?></h3>
        <div class="my-only-widget-content">
		<p>

        <ul class="other-dets5">
				<li>


					<h3><?php _e("Posted by",'AuctionTheme');?>:</h3>
					<p><a href="<?php echo AuctionTheme_get_user_profile_link($post_au->post_author);?>"><?php the_author() ?></a></p>
				</li>
				<?php _e('Feedback','AuctionTheme'); ?>: <?php echo auction_get_star_rating($post_au->post_author); ?><br/><br/>
               <a href="<?php echo AuctionTheme_get_user_profile_link($post_au->post_author);?>"><?php _e('See More Auctions by this user','AuctionTheme'); ?></a><br/>
               <a href="<?php echo AuctionTheme_get_user_feedback_link($post_au->post_author);?>"><?php _e('User Feedback','AuctionTheme'); ?></a><br/>

			</ul>
   		</p>
        </div>
   </li>
 <?php

 $do_not_require_shipping = get_post_meta($pid,'do_not_require_shipping',true);
			if($do_not_require_shipping == 1)
			{

			}
			else
			{
				?>

    	<li class="widget-container widget_text" id="ad-other-details">
		<h3 class="widget-title"><?php _e("Shipping Charges",'AuctionTheme'); ?></h3>
        <div class="my-only-widget-content">
		<p>

        <?php

			$shipping_type = get_post_meta($post_au->ID,'shipping_type',true);

			if($shipping_type == "flat")
			{
				$shipping = get_post_meta($pid,'shipping',true);
				echo sprintf(__('Flat Shipping: %s','AuctionTheme'), auctiontheme_get_show_price($shipping) );
			}
			else
			{
		?>


        <?php

			$args = "orderby=id&order=ASC&hide_empty=0&parent=0";
						$terms = get_terms( 'auction_shipping', $args );

						foreach($terms as $term):

							$auctiontheme_get_shipping_charge = auctiontheme_get_shipping_charge($pid, $term->term_id);
							if(!empty($auctiontheme_get_shipping_charge) or $auctiontheme_get_shipping_charge == "0")
							{


								?>


									<div class="row border-bottom">
                                        <div class="col-xs-6 col-sm-6 col-lg-6 font-weight-bold"><?php echo $term->name;?>:</div>
                                        <div class="col-xs-6 col-sm-6 col-lg-6"><?php echo $auctiontheme_get_shipping_charge == "0" ? __('FREE','AuctionTheme') : auctiontheme_get_show_price($auctiontheme_get_shipping_charge) ?></div>
                                     </div>


                                <?php

							}

						endforeach;

		?>


			  <?php } ?>
   		</p>
        </div>
   </li>

<?php } ?>

	<li class="widget-container widget_text" id="ad-other-details">
		<h3 class="widget-title"><?php _e("Other Details",'AuctionTheme'); ?></h3>
        <div class="my-only-widget-content">
		<p>


                <?php

				if($only_buy_now != "1"):

				?>

                <div class="row border-bottom">
 					<div class="col-xs-5 col-sm-4 col-lg-4 font-weight-bold"><?php _e("Bids",'AuctionTheme');?>:</div>
					<div class="col-xs-7 col-sm-8 col-lg-8"><?php echo auctionTheme_number_of_bid($post_au->ID); ?></div>
				</div>

                <?php endif; ?>

				<div class="row border-bottom">

					<div class="col-xs-5 col-sm-4 col-lg-4 font-weight-bold"><?php _e("Category",'AuctionTheme');?>:</div>
					<div class="col-xs-7 col-sm-8 col-lg-8"><?php echo get_the_term_list( $post_au->ID, 'auction_cat', '', ', ', '' ); ?></div>
				</div>

                <?php do_action('AuctionTheme_small_thing_after_categories_single_page'); ?>

				<div class="row border-bottom">

					<div class="col-xs-5 col-sm-4 col-lg-4 font-weight-bold"><?php _e("Address",'AuctionTheme');?>:</div>
					<div class="col-xs-7 col-sm-8 col-lg-8"><?php echo $location; ?></div>
				</div>

                <?php

				$rt = get_option('auctionTheme_show_auction_views');

				if($rt != 'no'):
				?>

				<div class="row border-bottom">
					<div class="col-xs-5 col-sm-4 col-lg-4 font-weight-bold"><?php _e("Viewed",'AuctionTheme');?>:</div>
					<div class="col-xs-7 col-sm-8 col-lg-8"><?php echo $views; ?> <?php _e("times",'AuctionTheme');?></div>
				</div>
				<?php endif; ?>







			<?php

				 if(!AuctionTheme_is_owner_of_post()) {?>


            <a href="<?php

			$using_perm = AuctionTheme_using_permalinks();

			if($using_perm)	$privurl_m = get_permalink(get_option('AuctionTheme_my_account_priv_mess_page_id')). "?";
			else $privurl_m = get_bloginfo('siteurl'). "/?page_id=". get_option('AuctionTheme_my_account_priv_mess_page_id'). "&";

			echo $privurl_m."priv_act=send&";
            $post_au = get_post($pid);
			echo 'pid='.$pid.'&uid='.$post_au->post_author;

			?>" class="submit_bottom2"><?php _e("Contact Seller",'AuctionTheme'); ?></a>

                <?php } ?>


                   <a href="" class="submit_bottom2" id="report-this-link"><?php _e("Report Auction",'AuctionTheme'); ?></a>
		</p>
        </li>
	</li>


	<?php

						dynamic_sidebar( 'auction-widget-area' );
	echo '</ul>';
	echo '</div>';
 ?>



    <?php


	//*****************************************************************************



}


 ?>





</div>







<?php

}}

?>
