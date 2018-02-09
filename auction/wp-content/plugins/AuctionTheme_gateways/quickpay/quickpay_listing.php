<?php


add_filter('AuctionTheme_add_payment_options_to_post_new_project','AuctionTheme_add_payment_options_to_post_new_project_quickpay');
add_filter('AuctionTheme_add_payment_options_to_edit_auction','AuctionTheme_add_payment_options_to_post_new_project_quickpay');

function AuctionTheme_add_payment_options_to_post_new_project_quickpay($pid)
{
	$AuctionTheme_quickpay_enable = get_option('AuctionTheme_quickpay_enable');
	
	if($AuctionTheme_quickpay_enable == "yes")
	echo '<a href="'.get_bloginfo('siteurl').'/?a_action=quickpay_listing&pid='.$pid.'" class="post_bid_btn">'.__('Pay by Quickpay','AuctionTheme').'</a>';
		
}

function AT_quickpay_listing_response_lst()
{
	if($_POST['qpstat'] == '000')
	{	
		$md5 = $_POST['md5check'];
		if(!empty($md5))
		{
			$tranid = $_GET['tranid'];
			$gr = get_option('LST_QUICKPAY_' . $tranid, true);
			 
			
			//******************************
			 
				$c 		= explode('|',$gr);
				
				$pid					= $c[0];
				$uid					= $c[1];
				$datemade 				= $c[2];
			 
				
				//--------------------------------------------
				
				update_post_meta($pid, "paid", 				"1");
				update_post_meta($pid, "closed", 			"0");
				
				//--------------------------------------------
				
				update_post_meta($pid, 'base_fee_paid', '1');
				
				$featured = get_post_meta($pid,'featured',true);	
				if($featured == "1") update_post_meta($pid, 'featured_paid', '1');
				
				$private_bids = get_post_meta($pid,'private_bids',true);	
				if($private_bids == "yes") update_post_meta($pid, 'private_bids_paid', '1');
				 
				//--------------------------------------------
				
				do_action('AuctionTheme_paypal_listing_response', $pid);
				
				$auctionTheme_admin_approves_each_project = get_option('auctionTheme_admin_approves_each_project');
				$paid_listing_date = get_post_meta($pid,'paid_listing_date',true);
				
				if(empty($paid_listing_date))
				{
					
					if($auctionTheme_admin_approves_each_project != "yes")
					{
						wp_publish_post( $pid );	
						$post_new_date = date('Y-m-d h:s',current_time('timestamp',0));  
						
						$post_info = array(  "ID" 	=> $pid,
						  "post_date" 				=> $post_new_date,
						  "post_date_gmt" 			=> $post_new_date,
						  "post_status" 			=> "publish"	);
						
						wp_update_post($post_info);
						
						AuctionTheme_send_email_posted_item_approved($pid);
						AuctionTheme_send_email_posted_item_not_approved_admin($pid);
						
					}
					else
					{ 
				
						AuctionTheme_send_email_posted_item_not_approved($pid);
						AuctionTheme_send_email_posted_item_approved_admin($pid);			
						//AuctionTheme_send_email_subscription($pid);	
						
					}
					
					update_post_meta($pid, "paid_listing_date", current_time('timestamp',0));
				}
			
			
			//******************************
			
		}
	}	
	
}

function AT_quickpay_s_listing_fncs()
{
	$pid = $_GET['pid'];
	
	global $wp_query, $wpdb, $current_user;
	$pid = $wp_query->query_vars['pid'];
	get_currentuserinfo();
	$uid = $current_user->ID;
	$post = get_post($pid);
	
	//----------------------------
	
			$features_not_paid = array();		
			$catid = AuctionTheme_get_auction_primary_cat($pid);
			$AuctionTheme_get_images_cost_extra = AuctionTheme_get_images_cost_extra($pid);
			$payment_arr = array();
			
			//-----------------------------------
			
			$base_fee_paid 	= get_post_meta($pid, 'base_fee_paid', true);
			$base_fee 		= get_option('AuctionTheme_new_auction_listing_fee');

			
			$custom_set = get_option('auctionTheme_enable_custom_posting');
			if($custom_set == 'yes')
			{
				$base_fee = get_option('auctionTheme_theme_custom_cat_'.$catid);
				if(empty($base_fee)) $base_fee = 0;		
			}
			
			//----------------------------------------------------------
			
			if($base_fee_paid != "1" && $base_fee > 0)
			{

				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'base_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $base_fee;
				$my_small_arr['description'] 	= __('Base Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
				
			}
			
			//----------------------------------------------------------
			
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'extra_img';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $AuctionTheme_get_images_cost_extra;
				$my_small_arr['description'] 	= __('Extra Images Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
			
			
			//-------- Featured Project Check --------------------------
			
			
			$featured 		= get_post_meta($pid, 'featured', true);
			$featured_paid 	= get_post_meta($pid, 'featured_paid', true);
			$feat_charge 	= get_option('AuctionTheme_new_auction_feat_listing_fee');
			
			if($featured == "1" && $featured_paid != "1" && $feat_charge > 0)
			{
				
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
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'sealed_project';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $auctionTheme_sealed_bidding_fee;
				$my_small_arr['description'] 	= __('Sealed Bidding Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
			}

			//---------------------
			
			$payment_arr = apply_filters('AuctionTheme_filter_payment_array', $payment_arr, $pid);
		
						
			$my_total = 0;
			foreach($payment_arr as $payment_item):
				if($payment_item['amount'] > 0):
					$my_total += $payment_item['amount'];
				endif;
			endforeach;			
			
			$my_total = apply_filters('AuctionTheme_filter_payment_total', $my_total, $pid);

//----------------------------------------------
	$additional_paypal = 0;
	$additional_paypal = apply_filters('AuctionTheme_filter_paypal_listing_additional', $additional_paypal, $pid);
	
	//$AuctionTheme_get_show_price = AuctionTheme_get_show_price($pid);
	$total = $my_total + $additional_paypal;
	
	$title_post = $post->post_title;
	$title_post = apply_filters('AuctionTheme_filter_paypal_listing_title', $title_post, $pid);
	  
//---------------------------------------------		

	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?a_action=quickpay_listing_response&pid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?a_action=quickpay_listing_response';
	$ccnt_url		= get_permalink(get_option('AuctionTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('AuctionTheme_currency');
	

?>


<html>
<head><title>Processing PayFast Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'AuctionTheme'); ?></h3></center>

	
 
    
    
     <?php
	
	$md5secret 		= get_option('AuctionTheme_quickpay_key');
	$merchant 		= get_option('AuctionTheme_quickpay_id');
 	$payurl 		= 'https://secure.quickpay.dk/form/'; 
	$amount 		= $total *100;
	$callbackurl 	= $response_url;
	$continueurl 	= $ccnt_url;
	$cancelurl 		= $ccnt_url;
	$ordernumber	= rand(0,999).time().$uid.$pid;
	$language		= 'en';
	$protocol		= '7';
	$testmode		= '0';
	$autocapture	= '0';
	$cardtypelock 	= 'creditcard';
	$msgtype 		= 'authorize';
	$callbackurl 	= get_bloginfo('siteurl').'/?a_action=quickpay_listing_response&tranid=' . $ordernumber;
	
	//---------------------
	
	update_option('LST_QUICKPAY_' . $ordernumber, $pid.'_'.$uid.'_'.$tm);
	
	$md5check = md5($protocol . $msgtype . $merchant . $language . $ordernumber . $amount . $currency . $continueurl . $cancelurl . $callbackurl . $autocapture . $cardtypelock . $testmode . $md5secret);
	
	echo "<form id=\"frmPay\" name=\"frmPay\" action=\"$payurl\" method=\"post\">
		<input type=\"hidden\" name=\"protocol\" value=\"$protocol\"/>
		<input type=\"hidden\" name=\"msgtype\" value=\"$msgtype\"/>
		<input type=\"hidden\" name=\"merchant\" value=\"$merchant\"/>
		<input type=\"hidden\" name=\"language\" value=\"$language\"/>
		<input type=\"hidden\" name=\"ordernumber\" value=\"$ordernumber\"/>
		<input type=\"hidden\" name=\"amount\" value=\"$amount\"/>
		<input type=\"hidden\" name=\"currency\" value=\"$currency\"/>
		<input type=\"hidden\" name=\"continueurl\" value=\"$continueurl\"/>
		<input type=\"hidden\" name=\"cancelurl\" value=\"$cancelurl\"/>
		<input type=\"hidden\" name=\"callbackurl\" value=\"$callbackurl\"/>
		<input type=\"hidden\" name=\"autocapture\" value=\"$autocapture\"/>
		<input type=\"hidden\" name=\"cardtypelock\" value=\"$cardtypelock\"/>
		<input type=\"hidden\" name=\"testmode\" value=\"$testmode\"/>
		<input type=\"hidden\" name=\"md5check\" value=\"$md5check\"/>
		
		</form>";
	
	?>
 

</body>
</html>


<?php	
}


?>