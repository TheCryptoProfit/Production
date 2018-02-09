<?php

include 'paypal.class.php';


	global $wp_query, $wpdb, $current_user;

		$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	$id = $_GET['id'];

//----------------------------------------------

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

$this_script = get_bloginfo('siteurl').'/?purchase_mem_paypal=1&id='.$id;

	$s = "select * from ".$wpdb->prefix."auction_membership_packs where id='$id'";
	$r = $wpdb->get_results($s);
	$row = $r[0];

if(empty($action)) $action = 'process';



switch ($action) {



   case 'process':      // Process and order...


	$total = $row->membership_cost;
	$title_post = sprintf(__('Membership Purchase: %s','AuctionTheme'), $row->membership_name);

//---------------------------------------------

      //$p->add_field('business', 'sitemile@sitemile.com');
      $p->add_field('business', $business);

	  $p->add_field('currency_code', get_option('AuctionTheme_currency'));
	  $p->add_field('return', $this_script.'&action=success');
      $p->add_field('cancel_return', $this_script.'&action=cancel');
      $p->add_field('notify_url', $this_script.'&action=ipn');
      $p->add_field('item_name', $title_post);
	  $p->add_field('bn', 'SiteMile_SP');
	  $p->add_field('custom', $uid.'|'.$id.'|'.current_time('timestamp',0));
      $p->add_field('amount', AuctionTheme_formats_special($total,2));

      $p->submit_paypal_post(); // submit the fields to paypal

      break;

   case 'success':      // Order was successful...
	case 'ipn':



	if(isset($_POST['custom']))
	{

		$cust 					= $_POST['custom'];
		$cust 					= explode("|",$cust);

		$uid					= $cust[0];
		$id						= $cust[1];
		$datemade 				= $cust[2];

	//-------------------------------------------------------

		$s = "select * from ".$wpdb->prefix."auction_membership_packs where id='$id'";
		$r = $wpdb->get_results($s);
		$row = $r[0];

	//--------------------------------------------

		$mem_pur_listing_date_ = get_user_meta($pid, 'mem_pur_listing_date_' . $datemade ,true);

		if(empty($mem_pur_listing_date_))
		{

			$mem_available 	 = get_user_meta($uid, 'mem_available', true);
			$ct				 = $datemade;

			if($ct > $mem_available or empty($mem_available))
							{
								update_user_meta($uid, 'mem_available', ($ct + 3600*24*30.5));
								update_user_meta($uid, 'auctions_available', $row->number_of_items);
								update_user_meta($uid, 'membership_id', $row->id);

							}

			update_user_meta($pid, "mem_pur_listing_date_" . $datemade, "111");
		}
	}


	wp_redirect(get_permalink(get_option('AuctionTheme_my_account_payments_page_id')));
    break;

   case 'cancel':       // Order was canceled...

	wp_redirect(get_bloginfo('siteurl'));

       break;




 }

?>
