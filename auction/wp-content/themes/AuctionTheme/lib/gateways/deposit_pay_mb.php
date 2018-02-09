<?php

	global $wp_query, $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;


	$pstn 			= get_option('AuctionTheme_my_account_payments_page_id');
	$cancel_url 	= get_bloginfo("siteurl").'/?page_id='.$pstn;
	$response_url 	= get_bloginfo('siteurl').'/?deposit_response_mb=1';
	$ccnt_url		= get_permalink(get_option('AuctionTheme_my_account_page_id'));

	//----------------------------------------------------------------------------------

 	$total = (trim($_GET['am']));

	$currency 		= get_option('AuctionTheme_currency');
	$tm 			= current_time('timestamp',0);

?>

<html>
<head> </head>
<body onLoad="document.form_mb.submit();">
<center><h3><?php _e('Please wait, your order is being processed...','AuctionTheme'); ?></h3></center>

    <form name="form_mb" action="https://www.moneybookers.com/app/payment.pl">
    <input type="hidden" name="pay_to_email" value="<?php echo get_option('AuctionTheme_moneybookers_email'); ?>">
    <input type="hidden" name="payment_methods" value="ACC,OBT,GIR,DID,SFT,ENT,EBT,SO2,IDL,PLI,NPY,EPY">

    <input type="hidden" name="recipient_description" value="<?php bloginfo('name'); ?>">

    <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>">
    <input type="hidden" name="status_url" value="<?php echo $response_url; ?>">

    <input type="hidden" name="language" value="EN">

    <input type="hidden" name="merchant_fields" value="field1">
    <input type="hidden" name="field1" value="<?php echo $uid."_".$tm; ?>">

    <input type="hidden" name="amount" value="<?php echo $total; ?>">
    <input type="hidden" name="currency" value="<?php echo $currency ?>">

    <input type="hidden" name="detail1_description" value="Product: ">
    <input type="hidden" name="detail1_text" value="<?php echo __('Deposit Money','AuctionTheme'); ?>">

    <input type="hidden" name="return_url" value="<?php echo $ccnt_url; ?>">


    </form>



</body>
</html>
