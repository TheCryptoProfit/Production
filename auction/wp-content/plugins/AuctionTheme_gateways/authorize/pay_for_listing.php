<?php

function auctiontheme_payment_for_authorize_form_listing($pid)
{
	$loginID = get_option('AuctionTheme_authorize_id'); 
	$loginID = trim($loginID);
	$transactionKey = get_option('AuctionTheme_authorize_key'); 
	$transactionKey = trim($transactionKey);
	
	//-----------------------------------
	
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
	$amount = $total;
	
	//**********************************************************************
	
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	//----------------
	
	$url			= "https://secure.authorize.net/gateway/transact.dll";
	$invoice		= date('YmdHis');
	$sequence		= rand(1, 1000);
	$timestamp		= time();
	
	if( phpversion() >= '5.1.2' )
	{ $fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transactionKey); }
	else 
	{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transactionKey)); }
	
	$testMode		= "false";	
	$relay_url 		= get_bloginfo("siteurl") . "/?autho_resp_listing=1";
	
	$nr 	= $pid.'|'.$uid.'|'.$timestamp;
	$sq_ur 	= $invoice.$sequence;
	update_option('sequence_custom_' .$sq_ur , $nr); 
	
	//-----------------------------------
	
	$title_post = $post->post_title;
	$title_post = apply_filters('AuctionTheme_filter_paypal_listing_title', $title_post, $pid);
	
?>

	<html>
<head><title>Processing Authorize Payment...</title></head>
<body onLoad="document.frmPay.submit();" >
<center><h3><?php _e('Please wait, your order is being processed...', 'AuctionTheme'); ?></h3></center>

	
    <form method='post' action='<?php echo $url; ?>' name="frmPay" id="frmPay" >
<!--  Additional fields can be added here as outlined in the SIM integration
 guide at: http://developer.authorize.net -->
	<input type='hidden' name='x_login' value='<?php echo $loginID; ?>' />
	<input type='hidden' name='x_amount' value='<?php echo $amount; ?>' />
	<input type='hidden' name='x_description' value='<?php echo $title_post; ?>' />
	<input type='hidden' name='x_invoice_num' value='<?php echo $invoice; ?>' />
	<input type='hidden' name='x_fp_sequence' value='<?php echo $sequence; ?>' />
	<input type='hidden' name='x_fp_timestamp' value='<?php echo $timestamp; ?>' />
	<input type='hidden' name='x_fp_hash' value='<?php echo $fingerprint; ?>' />
	<input type='hidden' name='x_test_request' value='<?php echo $testMode; ?>' />
	<input type='hidden' name='x_show_form' value='PAYMENT_FORM' />
    <input type='hidden' name='x_cust_id' value='<?php echo $sq_ur; ?>' />
    
    <INPUT TYPE='hidden' NAME="x_relay_response" VALUE="TRUE">
	<INPUT TYPE='hidden' NAME="x_relay_url" VALUE="<?php echo $relay_url; ?>">
    
 
</form>
    


        <!-- Transaction Details -->
       <!-- <input type="hidden" name="m_payment_id" value="<?php $pid.'|'.$uid.'|'.$tm.$xtra_stuff; ?>">
        <input type="hidden" name="custom_str1" value="<?php echo $pid.'|'.$uid.'|'.$tm.$xtra_stuff; ?>">
        -->
        
        

    
 

</body>
</html>

<?php	
	
}

?>