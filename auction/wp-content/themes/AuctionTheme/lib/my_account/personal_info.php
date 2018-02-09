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
//

if(!function_exists('AuctionTheme_my_account_pers_info_area_function'))
{
function AuctionTheme_my_account_pers_info_area_function()
{
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');

?>
		<div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">

          <?php do_action('AuctionTheme_before_pers_info_i_page'); ?>


        <div class="my_box3">


            	<div class="box_title"><?php _e("Personal Information",'AuctionTheme'); ?></div>
                <div class="box_content">
				<?php

				if(isset($_POST['save-info']))
				{
						$nonce = $_REQUEST['_wpnonce'];
						if ( ! wp_verify_nonce( $nonce, 'pers_info' ) ) {
						  exit; // Get out of here, the nonce is rotten!
						}

					//-----------------------------------------------------------------


					$personal_info = strip_tags(nl2br($_POST['personal_info']), '<br />');
					update_user_meta($uid, 'personal_info', $personal_info);

					$shipping_info = trim($_POST['shipping_info']);
					update_user_meta($uid, 'shipping_info', $shipping_info);

					$user_location = trim($_POST['auction_location_cat']);
					update_user_meta($uid, 'user_location', $user_location);



					if(isset($_POST['password']) && !empty($_POST['password']))
					{
						$p1 = trim($_POST['password']);
						$p2 = trim($_POST['reppassword']);

						if($p1 == $p2)
						{
							global $wpdb;
							$newp = md5($p1);
							$sq = "update $wpdb->users set user_pass='$newp' where ID='$uid'" ;
							$wpdb->query($sq);

							do_action('AuctionTheme_update_password_success',$uid);
						}
						else
						echo __("Passwords do not match!","AuctionTheme");
					}


					$paypal_email = trim($_POST['paypal_email']);
					update_user_meta($uid, 'paypal_email', $paypal_email);



					require_once(ABSPATH . "wp-admin" . '/includes/file.php');
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');

					if(!empty($_FILES['avatar']["name"]))
					{

						$upload_overrides 	= array( 'test_form' => false );
               			$uploaded_file 		= wp_handle_upload($_FILES['avatar'], $upload_overrides);

						$file_name_and_location = $uploaded_file['file'];
                		$file_title_for_media_library = $_FILES['avatar'  ]['name'];

						$file_name_and_location = $uploaded_file['file'];
						$file_title_for_media_library = $_FILES['avatar']['name'];

						$arr_file_type 		= wp_check_filetype(basename($_FILES['avatar']['name']));
						$uploaded_file_type = $arr_file_type['type'];
						$urls  = $uploaded_file['url'];



						if($uploaded_file_type == "image/png" or $uploaded_file_type == "image/jpg" or $uploaded_file_type == "image/jpeg" or $uploaded_file_type == "image/gif" )
						{

							$attachment = array(
											'post_mime_type' => $uploaded_file_type,
											'post_title' => 'User Avatar',
											'post_content' => '',
											'post_status' => 'inherit',
											'post_parent' =>  0,
											'post_author' => $uid,
										);



							$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, 0 );
							$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
							wp_update_attachment_metadata($attach_id,  $attach_data);


							$_wp_attached_file = get_post_meta($attach_id,'_wp_attached_file',true);

							if(!empty($_wp_attached_file))
							update_user_meta($uid, 'avatar_new_' . auctionTheme_theme_get_current_site(),  ($attach_id) );



						}

					}

					echo '<div class="saved_thing">'.__('Your profile information was updated.','AuctionTheme').'</div>';
					echo '<div class="clear10"></div>';

				}

				?>
                <form method="post"  enctype="multipart/form-data">
                  <table class="table">
 

        <tr>
        	<td><?php echo __('Shipping Info:','AuctionTheme'); ?></td>
        	<td><textarea type="shipping_info" cols="40" class="do_input" rows="3" name="shipping_info"><?php echo stripslashes(get_user_meta($uid, 'shipping_info', true)); ?></textarea></td>
        </tr>


         <tr>
        	<td><?php echo __('Your Location:','AuctionTheme'); ?></td>
        	<td><?php

				 echo AuctionTheme_get_categories_clck("auction_location", get_user_meta($uid,'user_location',true), __('Select Location','AuctionTheme'), "do_input" );

			?></td>
        </tr>




        <tr>
        	<td><?php echo __('Profile Description','AuctionTheme'); ?>:</td>
        	<td><textarea type="textarea" cols="40" class="do_input" rows="5" name="personal_info"><?php echo stripslashes(get_user_meta($uid, 'personal_info', true)); ?></textarea></td>
        </tr>


         <tr>
        	<td><?php echo __('New Password', "AuctionTheme"); ?>:</td>
        	<td><input type="password" value="" class="do_input" name="password" size="35" /></td>
        </tr>


        <tr>
        	<td><?php echo __('Repeat Password', "AuctionTheme"); ?>:</td>
        	<td><input type="password" value="" class="do_input" name="reppassword" size="35"  /></td>
        </tr>


        <tr>
        	<td><?php echo __('Profile Avatar','AuctionTheme'); ?>:</td>
        	<td> <input type="file" name="avatar" /> <br/>
           <?php _e('max file size: 1mb. Formats: jpeg, jpg, png, gif', 'AuctionTheme'); ?>
            <br/>
            <img width="50" height="50" border="0" src="<?php echo auctionTheme_get_avatar($uid,50,50); ?>" />
            </td>
        </tr>


        <tr>
        <td>&nbsp;</td>
        <td><input type="submit" class="submit_bottom" name="save-info" value="<?php _e("Save" ,'AuctionTheme'); ?>" /></td>
        </tr>

        </table>


        <?php wp_nonce_field( 'pers_info' ); ?>
                </form>
                </div>
           </div>
           </div>



<?php	auctionTheme_get_users_links();
}}

?>
