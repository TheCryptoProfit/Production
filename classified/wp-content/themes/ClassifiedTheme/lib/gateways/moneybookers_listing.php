<?php

	global $wp_query, $wpdb, $current_user;
	$pid = $wp_query->query_vars['pid'];
		$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	$post = get_post($pid);

	$business = get_option('AuctionTheme_moneybookers_email');
	if(empty($business)) die('ERROR. Please input your Moneybookers email.');
	//-------------------------------------------------------------------------

			$features_not_paid 	= array();
			$payment_arr 		= array();
			$base_fee_paid 		= get_post_meta($pid, 'base_fee_paid', true);
			$base_fee 			= get_option('AuctionTheme_new_auction_listing_fee');

			///----custom fee check--------------

			$catid = AuctionTheme_get_auction_primary_cat($pid);
			$AuctionTheme_get_images_cost_extra = AuctionTheme_get_images_cost_extra($pid);

			$custom_set = get_option('auctionTheme_enable_custom_posting');
			if($custom_set == 'yes')
			{
				$base_fee = get_option('auctionTheme_theme_custom_cat_'.$catid);
				if(empty($base_fee)) $base_fee = 0;
			}

			//----------------------------------

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
				$my_small_arr['fee_code'] 		= 'sealed_auction';
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


	//----------------------------------------------
	$additional_paypal = 0;
	$additional_paypal = apply_filters('AuctionTheme_filter_paypal_listing_additional', $additional_paypal, $pid);

	$total = $my_total + $additional_paypal;

	$title_post = $post->post_title;
	$title_post = apply_filters('AuctionTheme_filter_paypal_listing_title', $title_post, $pid);

	//---------------------------------

	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?a_action=mb_listing_response&pid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?a_action=mb_listing_response';
	$ccnt_url		= AuctionTheme_my_account_link();
	$currency 		= get_option('AuctionTheme_currency');

?>


<html>
<head> </head>
<body onLoad="document.form_mb.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'AuctionTheme'); ?></h3></center>


    <form name="form_mb" action="https://www.moneybookers.com/app/payment.pl">
    <input type="hidden" name="pay_to_email" value="<?php echo get_option('AuctionTheme_moneybookers_email'); ?>">
    <input type="hidden" name="payment_methods" value="ACC,OBT,GIR,DID,SFT,ENT,EBT,SO2,IDL,PLI,NPY,EPY">

    <input type="hidden" name="recipient_description" value="<?php bloginfo('name'); ?>">

    <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>">
    <input type="hidden" name="status_url" value="<?php echo $response_url; ?>">

    <input type="hidden" name="language" value="EN">

    <input type="hidden" name="merchant_fields" value="field1">
    <input type="hidden" name="field1" value="<?php echo $pid.'|'.$uid.'|'.$tm; ?>">

    <input type="hidden" name="amount" value="<?php echo $total; ?>">
    <input type="hidden" name="currency" value="<?php echo $currency ?>">

    <input type="hidden" name="detail1_description" value="Product: ">
    <input type="hidden" name="detail1_text" value="<?php echo $title_post; ?>">

    <input type="hidden" name="return_url" value="<?php echo $ccnt_url; ?>">


    </form>


</body>
</html>
