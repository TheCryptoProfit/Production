<?php


	global $wp_query;
	$current_user = wp_get_current_user();
	$pid 	=  $wp_query->query_vars['pid'];

	if(auctionTheme_number_of_bid_see_and_buy_now($pid) != false) { $mms = 1; }
	else {

	if(	get_option('AuctionTheme_enable_editing_when_bid_placed') == "yes")
	{

	} else { wp_redirect(get_bloginfo('siteurl')); die(); } }


	global $post_au;
	$post_au = get_post($pid);
	$uploaders = auctiontheme_get_uploaders_tp();

	function AuctionTheme_filter_ttl($title){ global $post_au; return __("Edit Auction",'AuctionTheme')." - ".$post_au->post_title;}
	add_filter( 'wp_title', 'AuctionTheme_filter_ttl', 10, 3 );

	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }


	$current_user = wp_get_current_user();

	$auctionTheme_admin_approves_each_auction = get_option('AuctionTheme_admin_approve_auction');

	$uid 	= $current_user->ID;
	$title 	= $post_au->post_title;
	$cid 	= $current_user->ID;

	if($uid != $post_au->post_author) { echo 'Not your post. Sorry!'; exit; }

	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

			global $wpdb,$wp_rewrite,$wp_query;


		$post_au = get_post($pid);

		if(isset($_POST['save-auction']))
		{


			//---------------------------------------
		// pictures
		if($uploaders  == "html"):

		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');

		$default_nr = get_option('AuctionTheme_nr_max_of_images');
		if(empty($default_nr)) $default_nr = 5;

		for($j=1;$j<=	$default_nr; $j++)
		{

			if(!empty($_FILES['file_' . $j]['name'])):

	  			$upload_overrides 	= array( 'test_form' => false );
                $uploaded_file 		= wp_handle_upload($_FILES['file_' . $j], $upload_overrides);

				$file_name_and_location = $uploaded_file['file'];
                $file_title_for_media_library = $_FILES['file_' . $j]['name'];

				$arr_file_type 		= wp_check_filetype(basename($_FILES['file_' . $j]['name']));
                $uploaded_file_type = $arr_file_type['type'];

				if($uploaded_file_type == "image/png" or $uploaded_file_type == "image/jpg" or $uploaded_file_type == "image/jpeg" or $uploaded_file_type == "image/gif" )
				{

					$attachment = array(
									'post_mime_type' => $uploaded_file_type,
									'post_title' => 'Uploaded image ' . addslashes($file_title_for_media_library),
									'post_content' => '',
									'post_status' => 'inherit',
									'post_parent' =>  $pid,

									'post_author' => $current_user->ID,
								);

					$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $pid );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
					wp_update_attachment_metadata($attach_id,  $attach_data);

				}

			endif;
		}

		endif;
		//------------

			$auction_title = trim($_POST['auction_title']);


			$AuctionTheme_enable_html_description = get_option('AuctionTheme_enable_html_description');

			if($AuctionTheme_enable_html_description == "yes")
			{
				$auction_description 	= $_POST['auction_description'];
			}
			else
			{
				$auction_description 	= nl2br(strip_tags($_POST['auction_description']));
			}


			  $features_not_paid = array();
			  $AuctionTheme_get_images_cost_extra = AuctionTheme_get_images_cost_extra($pid);

			  if(!empty($_POST['auction_location_addr']))
			  update_post_meta($pid, "Location", $_POST['auction_location_addr']);

			 do_action('AuctionTheme_step2_form_thing_post', $pid);
			 do_action('AuctionTheme_save_on_edit_auction_item');



			  $my_post = array();
			  $my_post['ID'] = $pid;
			  $my_post['post_content'] = $auction_description;
			  $my_post['post_title']   = $auction_title;

			  wp_update_post( $my_post );

			//******************************************************************************

			if(get_option('AuctionTheme_enable_multi_cats') == "yes")
			{
				$slg_arr = array();
				foreach($_POST['auction_cat_cat_multi'] as $ct)
				{
					$term = get_term( $ct, 'auction_cat' );
					$auction_category = $term->slug;
					$slg_arr[] = $auction_category;
				}

				wp_set_object_terms($pid, $slg_arr, 'auction_cat');
			}
			else
			{
				$auction_category = $_POST['auction_cat_cat'];


				$term 					= get_term( $auction_category, 'auction_cat' );
				$auction_category 		= $term->slug;
				$arr_cats 				= array();
				$arr_cats[] 			= $auction_category;


				if(!empty($_POST['subcat']))
				{
					$term = get_term( $_POST['subcat'], 'auction_cat' );
					$jb_category2 = $term->slug;
					$arr_cats[] = $jb_category2;

				}

				if(!empty($_POST['subcat2']))
				{
					$term = get_term( $_POST['subcat2'], 'auction_cat' );
					$jb_category2 = $term->slug;
					$arr_cats[] = $jb_category2;

				}

				if(!empty($_POST['subcat3']))
				{
					$term = get_term( $_POST['subcat3'], 'auction_cat' );
					$jb_category2 = $term->slug;
					$arr_cats[] = $jb_category2;

				}


				wp_set_object_terms($pid, $arr_cats ,'auction_cat');

			}


			//******************************************************************************



				$term = get_term( $_POST['auction_location_cat'], 'auction_location' );
				$auction_location = $term->slug;
 				wp_set_object_terms($pid, array($auction_location),'auction_location');


			$not_OK_to_just_publish = 2;

			//-----------------------------------
			// see if the auction was featured

			if(isset($_POST['featured'])) update_post_meta($pid, "featured", "1");
			else update_post_meta($pid, "featured", "0");

			//-----------------------------------
			// mark the auction for private bids if selected
			$private_bids = $_POST['private_bids'];
			update_post_meta($pid, "private_bids", $_POST['private_bids']);

			//-------------------------------------------------------------

			$base_fee_paid 	= get_post_meta($pid, 'base_fee_paid', true);
			$base_fee 		= get_option('AuctionTheme_new_auction_listing_fee');

			//--------------------------------------------

			$catid = AuctionTheme_get_auction_primary_cat($pid);

			$custom_set = get_option('auctionTheme_enable_custom_posting');
			if($custom_set == 'yes')
			{
				$base_fee = get_option('auctionTheme_theme_custom_cat_'.$catid);
				if(empty($base_fee)) $base_fee = 0;
			}

			//--------------------------------------------
			$payment_arr = array();


			if($base_fee_paid != "1" && $base_fee > 0)
			{

				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'base_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $base_fee;
				$my_small_arr['description'] 	= __('Base Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
				$not_OK_to_just_publish = 1;
			}

			if($AuctionTheme_get_images_cost_extra > 0)
			{
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'extra_img';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $AuctionTheme_get_images_cost_extra;
				$my_small_arr['description'] 	= __('Extra Images Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
				$not_OK_to_just_publish = 1;
			}

			//-------- Featured auction Check --------------------------

			if(isset($_POST['allow_offers'])) update_post_meta($pid, "allow_offers", "1");
			else update_post_meta($pid, "allow_offers", "0");


			$featured 		= get_post_meta($pid, 'featured', true);
			$featured_paid 	= get_post_meta($pid, 'featured_paid', true);
			$feat_charge 	= get_option('AuctionTheme_new_auction_feat_listing_fee');

			if($featured == "1" && $featured_paid != "1" && $feat_charge > 0)
			{
				$not_OK_to_just_publish = 1;

				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'feat_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $feat_charge;
				$my_small_arr['description'] 	= __('Featured Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
			}

			//---------- Private Bids Check -----------------------------

			$private_bids 		= get_post_meta($pid, 'private_bids', true);
			$private_bids_paid 	= get_post_meta($pid, 'private_bids_paid', true);



			$auctionTheme_sealed_bidding_fee = get_option('AuctionTheme_new_auction_sealed_bidding_fee');

			if(!empty($auctionTheme_sealed_bidding_fee))
			{
				$opt = get_post_meta($pid,'private_bids',true);
				if($opt == "no") $auctionTheme_sealed_bidding_fee = 0;

			}



			if($private_bids == "yes" && $private_bids_paid != "1" && $auctionTheme_sealed_bidding_fee > 0)
			{
				$not_OK_to_just_publish = 1;

				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'sealed_auction';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $auctionTheme_sealed_bidding_fee;
				$my_small_arr['description'] 	= __('Sealed Bidding Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
			}


			//---------------------------

			$payment_arr = apply_filters('AuctionTheme_filter_payment_array', $payment_arr, $pid);

			$my_total = 0;
			if(count($payment_arr) > 0)
			foreach($payment_arr as $payment_item):
				if($payment_item['amount'] > 0):
					$my_total += $payment_item['amount'];
				endif;
			endforeach;

			$new_total = $my_total;
			$my_total 				= apply_filters('AuctionTheme_filter_payment_total', $my_total, $pid);
			$not_OK_to_just_publish = apply_filters('AuctionTheme_filter_not_OK_to_just_publish', $not_OK_to_just_publish);

			//---------------------

			$shipping 	= auctionTheme_clear_sums_of_cash(trim($_POST['shipping']));
			$buy_now 	= auctionTheme_clear_sums_of_cash(trim($_POST['buy_now']));
			$reserve 	= auctionTheme_clear_sums_of_cash(trim($_POST['reserve']));
			$start_price 	= auctionTheme_clear_sums_of_cash(trim($_POST['start_price']));
			$auction_location_addr = trim($_POST['auction_location_addr']);



			update_post_meta($pid, "shipping", 		empty($shipping) ? 0 : $shipping);
			update_post_meta($pid, "start_price", 	$start_price);
			update_post_meta($pid, "reserve", 		$reserve);
			update_post_meta($pid, "buy_now", 		$buy_now);
			update_post_meta($pid, "Location", $auction_location_addr);

			//--------------------

			//----- for shipping opt --------------------

			update_post_meta($pid, "shipping_type", 		$_POST['shipping_type']);

			global $wpdb;
			$is_counts = 0;
			$ok_counts = 0;

			$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
			$terms = get_terms( 'auction_shipping', $args );
			foreach($terms as $term):

					$location_id 		= $term->term_id;
					$xf = trim($_POST['shipping_value_' . $location_id]);
					$shipping_charge 	= auctionTheme_clear_sums_of_cash($xf);

					if(!empty($shipping_charge) and is_numeric($shipping_charge))
					{
						$ok_counts++;
					}

					if(is_string($shipping_charge) and !is_numeric($shipping_charge))  $shipping_charge = '';

					$sp = "select * from ".$wpdb->prefix."auction_shipping_values where location_id='$location_id' AND pid='$pid'";
					$rp = $wpdb->get_results($sp);
					if(count($rp) > 0)
					{
						$wpdb->query("update ".$wpdb->prefix."auction_shipping_values set shipping_charge='$shipping_charge' where pid='$pid' AND location_id='$location_id'");
					}
					else
					{
						$wpdb->query("insert into ".$wpdb->prefix."auction_shipping_values (pid, location_id, shipping_charge) values('$pid','$location_id','$shipping_charge')");
					}

					$is_counts++;

			endforeach;

			//--------------------------

			if($_POST['shipping_type'] == "variable" and !isset($_POST['do_not_require_shipping']))
			{
				if($is_counts == 0)
				{
					$auctionOK 				= 0;
					$error['shipping'] 		= __('You need to add locations in backend. You have no locations defined.','AuctionTheme');
				}
				/*elseif($ok_counts != $is_counts)
				{
					$auctionOK 				= 0;
					$error['shipping'] 		= __('You need to fill in the shipping charge to all locations, and make sure they are numeric.','AuctionTheme');
				}*/
			}

			//------------------------------------

			$only_buy_now = get_post_meta($pid, 'only_buy_now', true);

			if($only_buy_now == 1)
			{
				$qq = trim($_POST['quant']);
				$quant = abs($qq);
				update_post_meta($pid, "quant", $quant);
			}

			//--------------------

				for($i=0;$i<count($_POST['custom_field_id']);$i++)
				{
					$id = $_POST['custom_field_id'][$i];
					$valval = $_POST['custom_field_value_'.$id];

					if(is_array($valval))
							update_post_meta($pid, 'custom_field_ID_'.$id, $valval);
					else
						update_post_meta($pid, 'custom_field_ID_'.$id, strip_tags($valval));
				}


			//----------------------

			if($not_OK_to_just_publish == 1 or $auctionTheme_admin_approves_each_auction == "yes")
			{

				$my_post = array();
				$my_post['ID'] = $pid;
				$my_post['post_status'] = 'draft';

				wp_update_post( $my_post );

			}
			else
			{

				$my_post = array();
				$my_post['ID'] = $pid;
				$my_post['post_status'] = 'publish';

				wp_update_post( $my_post );

			}
		}





		$cid = $uid;


//-------------------------------------

	get_header();

	$post_au = get_post($pid);

?>


	<div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">

            <div class="my_box3">

            	<div class="box_title"><?php printf(__("Edit Auction - %s", "AuctionTheme"), $post_au->post_title); ?></div>
                <div class="padd10">


                 <!-- ########################################### -->
                <?php

				do_action('AuctionTheme_action_when_posting_auction_payment_arr', $payment_arr, $new_total);

				if($not_OK_to_just_publish == 2) //ok published
				{
					if($auctionTheme_admin_approves_each_auction == "yes"):

						echo '<div class="stuff-paid-ok"><div class="padd10">';
						echo sprintf(__('Your auction has been updated and but is not live. The admin must approve it before it goes live.','AuctionTheme'));
						echo '</div></div>';


					else:

						echo '<div class="stuff-paid-ok"><div class="padd10">';
						echo sprintf(__('Your auction has been updated and is live now. <a href="%s"><strong>Click here</strong></a> to see your auction.','AuctionTheme'), get_permalink($pid));
						echo '</div></div>';

					endif;

				}

				elseif($not_OK_to_just_publish == 2) //ok published
				{
					echo '<div class="stuff-paid-ok"><div class="padd10">';
					echo sprintf(__('Your auction has been updated and is live now. <a href="%s"><strong>Click here</strong></a> to see your auction.','AuctionTheme'), get_permalink($pid));
					echo '</div></div>';
				}

				elseif($not_OK_to_just_publish == 1)
				{
						echo '<div class="stuff-not-paid"><div class="padd10">';
						echo __('You have added extra options to your auction. In order to publish your auction you need to pay for the options.','AuctionTheme');
						echo '<br/><br/><table width="100%">';

						$ttl = 0;

						$opt = get_option('AuctionTheme_enable_pay_credits');

						foreach($payment_arr as $payment_item):

							$feature_cost 			= $payment_item['amount'];
							$feature_description 	= $payment_item['description'];


							echo '<tr>';
							echo '<td width="320">'.$feature_description.'</td>';
							echo '<td>'.auctionTheme_get_show_price($feature_cost,2).'</td>';
							echo '</tr>';

						endforeach;

							echo '<tr>';
							echo '<td width="320">&nbsp;</td>';
							echo '<td>&nbsp;</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td width="320"><b>'.__('Total','AuctionTheme').'</b></td>';
							echo '<td>'.auctionTheme_get_show_price($my_total,2).'</td>';
							echo '</tr>';

							if($opt != "no")
							{
								echo '<tr>';
								echo '<td><strong>'.__('Your Total Credits','AuctionTheme').'</strong></td>';
								echo '<td><strong>'.AuctionTheme_get_show_price(AuctionTheme_get_credits($uid),2).'</strong></td>';
								echo '</tr>';
							}

						echo '</table><br/><br/>';



						if($opt != "no")
						echo '<a href="'.get_bloginfo('siteurl').'/?a_action=credits_listing&pid='.$pid.'" class="edit_auction_pay_cls">'.__('Pay by Credits','AuctionTheme').'</a>';

						global $auction_ID;
						$auction_ID = $pid;

						//-------------------

						$AuctionTheme_paypal_enable 		= get_option('AuctionTheme_paypal_enable');
						$AuctionTheme_alertpay_enable 		= get_option('AuctionTheme_alertpay_enable');
						$AuctionTheme_moneybookers_enable 	= get_option('AuctionTheme_moneybookers_enable');

						if($AuctionTheme_paypal_enable == "yes")
							echo '<a href="'.get_bloginfo('siteurl').'/?a_action=paypal_listing&pid='.$pid.'" class="edit_auction_pay_cls">'.__('Pay by PayPal','AuctionTheme').'</a>';

						if($AuctionTheme_moneybookers_enable == "yes")
							echo '<a href="'.get_bloginfo('siteurl').'/?a_action=mb_listing&pid='.$pid.'" class="edit_auction_pay_cls">'.__('Pay by MoneyBookers/Skrill','AuctionTheme').'</a>';

						if($AuctionTheme_alertpay_enable == "yes")
							echo '<a href="'.get_bloginfo('siteurl').'/?a_action=payza_listing&pid='.$pid.'" class="edit_auction_pay_cls">'.__('Pay by Payza','AuctionTheme').'</a>';

						do_action('AuctionTheme_add_payment_options_to_edit_auction', $pid);

						echo '</div></div>';
				}




				?>

                <!-- ########################################## -->
<!-- ##################################################################### -->

<script type="text/javascript">

		function check_quant()
		{
			$('#quantity_li').toggle('slow');
			$('#start_prc').toggle('slow');
			$('#res_prc').toggle('slow');
		}

	</script>



<!-- ####################################################################### -->


                <div class="clear10"></div>

                <?php

				if($uploaders == "html") $enc = 'enctype="multipart/form-data"';

				?>


                    <?php

	$post_au 		  = get_post($pid);
	$location 	  = wp_get_object_terms($pid, 'auction_location');
	$cat 		  = wp_get_object_terms($pid, 'auction_cat');

	$bids_number  = auctionTheme_number_of_bid($pid);

					?>

        <div class="clear10"></div>



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
            <td class="error" colspan="2"><span class="label label-important"><?php _e('Error','AuctionTheme') ?></span> {%=file.error%}</td>
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



    <form method="post" <?php echo $enc; ?>>
    <ul class="post-new3">

    <?php
	$AuctionTheme_currency_position = get_option('AuctionTheme_currency_position');
	$AuctionTheme_enable_images_in_auctions = get_option('AuctionTheme_enable_images_in_auctions');
				if($AuctionTheme_enable_images_in_auctions != "no"):

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
		 $.ajax({
						method: 'get',
						url : '<?php echo get_bloginfo('siteurl');?>/?_ad_delete_pid='+id,
						dataType : 'text',
						success: function (text) {   $('#image_ss'+id).remove(); window.location.reload();  }
					 });
		  //alert("a");

	}

</script>


    <?php




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



<?php endif; endif; ?>



           <li>
        	<h2><?php echo __('Your auction title', 'AuctionTheme'); ?>:</h2>
        	<p><input type="text" size="50" class="do_input form-control" name="auction_title"
            value="<?php echo (empty($_POST['auction_title']) ?
			($post_au->post_title == "Auto Draft" ? "" : $post_au->post_title) : $_POST['auction_title']); ?>" /> <?php do_action('AuctionTheme_step1_after_title_field');  ?></p>
        </li>

        <?php do_action('ActionTheme_after_title_li'); ?>


        <li>
        	<h2><?php echo __('Location', 'AuctionTheme'); ?>:</h2>
        <p><?php	echo AuctionTheme_get_categories("auction_location",
		empty($_POST['auction_location_cat']) ? (is_array($location) ? $location[0]->term_id : "") : $_POST['auction_location_cat'], __("Select Location","AuctionTheme"), "do_input"); ?></p>
        </li>

        <?php do_action('ActionTheme_after_location_li'); ?>

         <li><h2><?php echo __('Category', 'AuctionTheme'); ?>:</h2>
        	<p>
			<?php if(get_option('AuctionTheme_enable_multi_cats') == "yes"): ?>
			<div class="multi_cat_placeholder_thing2">

            	<?php

					$selected_arr = AuctionTheme_build_my_cat_arr($post_au->ID);
					echo AuctionTheme_get_categories_multiple('auction_cat', $selected_arr);

				?>

            </div>

            <?php else: ?>


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
			<?php

			$cat 		= wp_get_object_terms($post_au->ID, 'auction_cat', array('order' => 'ASC', 'orderby' => 'term_id' ));

			 	echo AuctionTheme_get_categories_clck("auction_cat",
                                !isset($_POST['auction_cat_cat']) ? (is_array($cat) ? $cat[0]->term_id : "") : htmlspecialchars($_POST['auction_cat_cat'])
                                , __('Select Category','AuctionTheme'), "do_input", 'onchange="display_subcat(this.value)"' );


								echo '<br/><span id="sub_cats">';


											if(!empty($cat[1]->term_id))
											{
												$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat[0]->term_id;
												$sub_terms2 = get_terms( 'auction_cat', $args2 );

												$ret = '<select class="do_input" name="subcat" onchange="display_subcat_cat2(this.value)" >';
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

												$ret = '<select class="do_input" name="subcat2"  onchange="display_subcat_cat3(this.value)">';
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

												$ret = '<select class="do_input" name="subcat3">';
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


        <?php do_action('ActionTheme_after_category_li'); ?>


        <?php do_action('ActionTheme_after_category_li'); ?>
		<?php

			$only_buy_now = get_post_meta($pid, 'only_buy_now', true);

			if($only_buy_now != "1"):

		?>
        <li id='start_prc' style="<?php echo ($only_buy_now == "1" ? 'display:none' : '');  ?>">
        	<h2><?php echo __('Start Price', 'AuctionTheme'); ?>:</h2>
        <p><?php if($AuctionTheme_currency_position == "front") echo auctiontheme_get_currency_USD(); ?><input type="text" size="10" name="start_price" class="do_input"
        	value="<?php echo  get_post_meta($pid, 'start_price', true) ; ?>" />
			<?php if($AuctionTheme_currency_position != "front") echo auctiontheme_get_currency_USD(); ?> <?php do_action('AuctionTheme_step1_after_start_rpice_field');  ?></p>
        </li>


        <?php do_action('ActionTheme_after_start_price_li'); ?>

         <li id="res_prc" style="<?php echo ($only_buy_now == "1" ? 'display:none' : '');  ?>">
        	<h2><?php echo __('Reserve Price', 'AuctionTheme'); ?>:</h2>
        <p><?php if($AuctionTheme_currency_position == "front") echo auctiontheme_get_currency_USD(); ?><input type="text" size="10" name="reserve" class="do_input"
        	value="<?php echo   get_post_meta($pid, 'reserve', true) ; ?>" />
			<?php if($AuctionTheme_currency_position != "front") echo auctiontheme_get_currency_USD(); ?>
            <?php do_action('AuctionTheme_step1_after_reserve_price_field');  ?></p>
        </li>

        <?php do_action('ActionTheme_after_reserve_price_li'); ?>

        <?php endif; ?>

         <li>
        	<h2><?php echo __('Buy Now Price', 'AuctionTheme'); ?>:</h2>
        <p><?php if($AuctionTheme_currency_position == "front") echo auctiontheme_get_currency_USD(); ?><input type="text" size="10" name="buy_now" class="do_input"
        	value="<?php echo  get_post_meta($pid, 'buy_now', true) ; ?>" /> <?php if($AuctionTheme_currency_position != "front") echo auctiontheme_get_currency_USD(); ?>


             <?php do_action('AuctionTheme_step1_after_buy_now_field');  ?>
             </p>
        </li>

        <?php do_action('ActionTheme_after_buy_now_li'); ?>

       <?php if($only_buy_now == "1"): ?>

        <li id="quantity_li" style="<?php echo ($only_buy_now != "1" ? 'display:none' : '');  ?>">
        	<h2><?php echo __('Quantity', 'AuctionTheme'); ?>:</h2>
        <p><input type="text" size="10" name="quant" class="do_input"
        	value="<?php echo get_post_meta($pid, 'quant', true); ?>" />
            <?php do_action('AuctionTheme_step1_after_quantity_field');  ?></p>
        </li>

        <?php endif; ?>

        <?php do_action('ActionTheme_after_quantity_li'); ?>

           <li>
        <h2><?php _e("Allow Offers?",'AuctionTheme');  ?>:</h2>
        <p><input type="checkbox" class="do_input" name="allow_offers" <?php echo (get_post_meta($pid,'allow_offers', true) == "1" ? 'checked="checked"' : ''); ?> value="1" />
        <?php do_action('AuctionTheme_step1_after_allow_offers_field');  ?>

       </p>
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

		<li class="my_sub_title">
        	<h3><?php _e('Item Shipping','AuctionTheme'); ?></h3>
            <p class="class_p"><input type="checkbox" value="1" onchange="turn_me_off_on()" <?php echo $chk1 ?> name="do_not_require_shipping" /> <?php _e('This item does not require shipping.','AuctionTheme'); ?></p>

        </li>



        <li class="shipping_component">
            <h2 class="h2_shipping"><input type="radio" name="shipping_type" onclick="change_shipping_mode()"
            value="flat" <?php if($shipping_type == "flat") echo 'checked="checked"'; ?> /> <?php echo __('Flat Shipping:', 'AuctionTheme'); ?></h2>

            <p class="p_shipping"><?php if($AuctionTheme_currency_position == "front") echo auctiontheme_get_currency_USD(); ?>
            <input type="text" size="10" name="shipping" class="do_input"
                value="<?php echo get_post_meta($pid, 'shipping', true); ?>" /> <?php if($AuctionTheme_currency_position != "front") echo auctiontheme_get_currency_USD(); ?>
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

						$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
						$terms = get_terms( 'auction_shipping', $args );

						foreach($terms as $term):

					?>
                    	<tr>
                        	<td><?php printf(  $term->name); ?></td>
                        	<td>

							<?php if($AuctionTheme_currency_position == "front") echo auctiontheme_get_currency_USD(); ?>
                            <input type="text" class="do_input" name="shipping_value_<?php echo $term->term_id ?>" size="10" value="<?php echo auctiontheme_get_shipping_charge($pid, $term->term_id) ?>" />
							<?php if($AuctionTheme_currency_position != "front") echo auctiontheme_get_currency_USD(); ?>

                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </table>

                </div>
            </div>
        </li>



        <?php do_action('ActionTheme_after_shipping_li'); ?>




        <li>
        	<h2><?php echo __('Private Bids', 'AuctionTheme'); ?>:</h2>
        <p><select name="private_bids" class="do_input">
        <option value="no" <?php if(get_post_meta($pid,'private_bids',true) == "no") echo 'selected="selected"'; ?>><?php _e("No",'AuctionTheme'); ?></option>
        <option value="yes" <?php if(get_post_meta($pid,'private_bids',true) == "yes") echo 'selected="selected"'; ?>><?php _e("Yes",'AuctionTheme'); ?></option>
        </select>
        <?php do_action('AuctionTheme_step1_after_private_bids_field');  ?>
        </p>
        </li>

        <?php do_action('ActionTheme_after_private_bids_li'); ?>


        <li>
        	<h2><?php echo __('Address','AuctionTheme'); ?>:</h2>
        <p><input type="text" size="50" class="do_input form-control"  name="auction_location_addr" value="<?php echo !isset($_POST['auction_location_addr']) ?
		get_post_meta($pid, 'Location', true) : $_POST['auction_location_addr']; ?>" />
        <?php do_action('AuctionTheme_step1_after_address_field');  ?>
        </p>
        </li>

        <?php do_action('ActionTheme_after_address_li'); ?>

        <?php

			$AuctionTheme_enable_html_description = get_option('AuctionTheme_enable_html_description');

		?>

        <li>
        	<h2><?php echo __('Description', 'AuctionTheme'); ?>:</h2>
        <p>
        <?php if($AuctionTheme_enable_html_description == 'yes'): ?>

        <div class="description_html_box_placeholder2">
        <?php wp_editor( $post_au->post_content, 'auction_description', array('media_buttons' => false) ); ?>
        </div>

        <?php else: ?>

        <textarea rows="6" cols="60" class="do_input form-control"  name="auction_description"><?php
		echo empty($_POST['auction_description']) ? trim($post_au->post_content) : $_POST['auction_description']; ?></textarea>
        <?php do_action('AuctionTheme_step1_after_description_field');  ?>
        <?php endif; ?>


        </p>
        </li>


        <?php

		do_action('AuctionTheme_step2_form_thing', $post_au->ID);

		?>

		<?php do_action('ActionTheme_after_description_li'); ?>

	 <li>
        <h2><?php _e("Feature auction?",'AuctionTheme');  ?>:</h2>
        <p><input type="checkbox" class="do_input" name="featured" <?php echo (get_post_meta($pid,'featured', true) == "1" ? 'checked="checked"' : ''); ?> value="1" />
        <?php do_action('AuctionTheme_step1_after_featured_field');  ?>

       </p>
        </li>

   <?php

		$cat 		  	= wp_get_object_terms($pid, 'auction_cat');
		$catidarr 		= array();

		foreach($cat as $catids)
		{
			$catidarr[] = $catids->term_id;
		}

		$arr 	= get_auction_category_fields($catidarr, $pid);

		for($i=0;$i<count($arr);$i++)
		{

			        echo '<li><h2>';
					echo $arr[$i]['field_name'].$arr[$i]['id'].':</h2>';
					echo '<p>'.$arr[$i]['value'];
					do_action('AuctionTheme_step3_after_custom_field_'.$arr[$i]['id'].'_field');
					echo '</p>';
					echo '</li>';


		}

        ?>

        <li>
        <h2>&nbsp;</h2>
        <p><input type="submit" class="submit_bottom" name="save-auction" value="<?php _e("Save Auction",'AuctionTheme'); ?>" /></p>
        </li>



		</ul>
          </form>


                </div>
                </div>
                </div>


	<?php AuctionTheme_get_users_links(); ?>

<?php get_footer(); ?>
