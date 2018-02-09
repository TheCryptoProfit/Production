<?php

	global $wp_query, $wpdb, $current_user;
		$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$bid_id 	=  $wp_query->query_vars['bid_id'];

	$pstn 			= get_option('AuctionTheme_my_account_payments_page_id');
	$cancel_url 	= get_bloginfo("siteurl").'/?page_id='.$pstn;
	$response_url 	= get_bloginfo('siteurl').'/?item_response_payza=1';
	$ccnt_url		= get_permalink(get_option('AuctionTheme_my_account_page_id'));

	//----------------------------------------------------------------------------------



	$s = "select * from ".$wpdb->prefix."auction_bids where id='$bid_id'";
	$r = $wpdb->get_results($s);
	$row = $r[0]; $bid = $row;
	$pid = $bid->pid;
	$uid = $bid->uid;
	$post_as = get_post($pid);

	$total = $bid->bid;

	$shipping = get_post_meta($pid, 'shipping', true);
	if(is_numeric($shipping) && $shipping > 0 && !empty($shipping))
			$shipping = $shipping;
					else $shipping = 0;

	$total += $shipping;


	$currency 		= get_option('AuctionTheme_currency');
	$tm 			= current_time('timestamp',0);

?>

<html>
<head> </head>
<body onLoad="document.form_yaza.submit();">
<center><h3><?php _e('Please wait, your order is being processed...','AuctionTheme'); ?></h3></center>

<form name="form_yaza" action="https://secure.payza.com/checkout" method="post" >
        <input name="ap_purchasetype" type="hidden" value="item-goods" >
        <input name="ap_merchant" type="hidden" value="<?php echo get_option('AuctionTheme_alertpay_email'); ?>" >
        <input name="ap_itemname" type="hidden" value="<?php echo sprintf(__('Payment for item %s','AuctionTheme'), $post_as->post_title); ?>" >
        <input name="ap_description" type="hidden" value="<?php echo sprintf(__('Payment for item %s','AuctionTheme'), $post_as->post_title); ?>" >
        <input name="ap_cancelurl" type="hidden" value="<?php echo $cancel_url; ?>" >
        <input name="ap_returnurl" type="hidden" value="<?php echo $ccnt_url; ?>" >
        <input name="ap_alerturl" type="hidden" value="<?php echo $response_url; ?>" >
        <input name="ap_quantity" type="hidden" value="1" >
        <input name="ap_currency" type="hidden" value="<?php echo $currency; ?>" >
        <input name="ap_itemcode" type="hidden" value="<?php echo $uid."_".$tm."_".$bid_id; ?>" >
        <input name="ap_shippingcharges" type="hidden" value="0" >

        <input name="apc_1" type="hidden" value="<?php echo $pid.'|'. $uid. '|'.$tm."|".$bid_id; ?>" >



        <input name="ap_amount" type="hidden" value="<?php echo $total; ?>" >

</form>

</body>
</html>
