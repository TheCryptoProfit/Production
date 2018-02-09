<?php

include 'paypal.class.php';


	global $wp_query, $wpdb, $current_user;
	$pid = $wp_query->query_vars['pid'];
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	$post = get_post($pid);

$action = $_GET['action'];
$business = trim(get_option('AuctionTheme_paypal_email'));
if(empty($business)) die('Error. Admin, please add your paypal email in backend!');

$p = new paypal_class;             // initiate an instance of the class
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';   // testing paypal url

//--------------

	$AuctionTheme_paypal_enable_sdbx = get_option('AuctionTheme_paypal_enable_sdbx');
	if($AuctionTheme_paypal_enable_sdbx == "yes")
	$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';     // paypal url

//--------------

$this_script = get_bloginfo('siteurl').'/?a_action=paypal_listing&pid='.$pid;

if(empty($action)) $action = 'process';



switch ($action) {



   case 'process':      // Process and order...

			$features_not_paid = array();
			$catid = AuctionTheme_get_auction_primary_cat($pid);
			$AuctionTheme_get_images_cost_extra = AuctionTheme_get_images_cost_extra($pid);
			$payment_arr = array();

			//-----------------------------------

			$base_fee_paid 	= get_post_meta($pid, 'base_fee_paid', true);
			$AuctionTheme_get_images_cost_extra = AuctionTheme_get_images_cost_extra($pid);
			$catid 								= AuctionTheme_get_item_primary_cat($pid);

			$AuctionTheme_new_auction_listing_fee = get_option('AuctionTheme_new_auction_listing_fee');
			if(empty($AuctionTheme_new_auction_listing_fee)) $AuctionTheme_new_auction_listing_fee = 0;

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


			//----------------------------------------------------------

			if($base_fee_paid != "1" && $posting_fee > 0)
			{

				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'base_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $posting_fee;
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

      //$p->add_field('business', 'sitemile@sitemile.com');
      $p->add_field('business', $business);

	  $p->add_field('currency_code', get_option('AuctionTheme_currency'));
	  $p->add_field('return', $this_script.'&action=success');
      $p->add_field('cancel_return', $this_script.'&action=cancel');
      $p->add_field('notify_url', $this_script.'&action=ipn');
      $p->add_field('item_name', $title_post);
	  $p->add_field('bn', 'SiteMile_SP');
	  $p->add_field('custom', $pid.'|'.$uid.'|'.current_time('timestamp',0));
      $p->add_field('amount', AuctionTheme_formats_special($total,2));

      $p->submit_paypal_post(); // submit the fields to paypal

      break;

   case 'success':      // Order was successful...
	case 'ipn':



	if(isset($_POST['custom']))
	{

		$cust 					= $_POST['custom'];
		$cust 					= explode("|",$cust);

		$pid					= $cust[0];
		$uid					= $cust[1];
		$datemade 				= $cust[2];

		//--------------------------------------------

		update_post_meta($pid, "paid", 				"1");
		update_post_meta($pid, "closed", 			"0");

		//--------------------------------------------

		update_post_meta($pid, 'base_fee_paid', '1');

		$featured = get_post_meta($pid,'featured',true);
		if($featured == "1") update_post_meta($pid, 'featured_paid', '1');

		$private_bids = get_post_meta($pid,'private_bids',true);
		if($private_bids == "yes" or $private_bids == "1") update_post_meta($pid, 'private_bids_paid', '1');

		//--------------------------------------------

		do_action('AuctionTheme_paypal_listing_response', $pid);

		$auctionTheme_admin_approves_each_project = get_option('auctionTheme_admin_approves_each_project');
		$paid_listing_date = get_post_meta($pid,'paid_listing_date',true);

		if(empty($paid_listing_date))
		{

			if($auctionTheme_admin_approves_each_project != "yes")
			{
				wp_publish_post( $pid );
				$ct = time(); //current_time('timestamp',0);
				$post_new_date = date('Y-m-d H:i:s', $ct);
				$post_date_gmt = gmdate($post_new_date);

				$post_info = array(  "ID" 	=> $pid,
							  "post_date" 				=> $post_new_date,
							  "post_date_gmt" 			=> $post_date_gmt,
							  "post_status" 			=> "publish"	);

				wp_update_post($post_info);
				wp_publish_post( $pid );

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
	}


	wp_redirect(get_permalink($pid));
   break;

   case 'cancel':       // Order was canceled...

	wp_redirect(get_bloginfo('siteurl'));

       break;




 }

?>
