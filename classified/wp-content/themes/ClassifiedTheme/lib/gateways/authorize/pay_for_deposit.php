<?php

function auctiontheme_payment_for_authorize_form_deposit($total)
{
	$loginID = get_option('AuctionTheme_authorize_id'); 
	$loginID = trim($loginID);
	$transactionKey = get_option('AuctionTheme_authorize_key'); 
	$transactionKey = trim($transactionKey);
	
	//-----------------------------------
	
	$amount = $total;
	
	//**********************************************************************
	
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	//----------------
	
	$url			= "https://secure2.authorize.net/gateway/transact.dll";
	$invoice		= date('YmdHis');
	$sequence		= rand(1, 1000);
	$timestamp		= time();
	
	if( phpversion() >= '5.1.2' )
	{ $fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transactionKey); }
	else 
	{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transactionKey)); }
	
	$testMode		= "FALSE";	
	$relay_url 		= get_bloginfo("siteurl") . "/?autho_resp_listing_deposit=1";
	
	$nr 	= $uid.'|'.$amount;
	$sq_ur 	= $invoice.$sequence;
	update_option('sequence_custom_dep_' .$sq_ur , $nr); 
	
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
    <input type='hidden' name='x_receipt_link_url' value='<?php echo get_permalink(get_option('AuctionTheme_my_account_page_id')); ?>' />
 
      
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