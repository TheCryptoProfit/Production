<?php

include 'paypal.class.php';

global $wp_query, $wpdb;
$bid_id = $wp_query->query_vars['bid_id'];

$action = $_GET['action'];


	$s = "select * from ".$wpdb->prefix."auction_bids where id='$bid_id'";
	$r = $wpdb->get_results($s);
	$row = $r[0]; $bid = $row;
	$pid = $bid->pid;
	$uid = $bid->uid;

$p = new paypal_class;             // initiate an instance of the class
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';   // testing paypal url

//---------------------

$AuctionTheme_paypal_enable_sdbx = get_option('AuctionTheme_paypal_enable_sdbx');
if($AuctionTheme_paypal_enable_sdbx == "yes")
$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';     // paypal url

//--------------------
 

$this_script = get_bloginfo('siteurl').'/?pay_for_item=paypal&bid_id='.$bid_id;

$post = get_post($pid);
$paypal_email = get_user_meta($post->post_author, 'paypal_email', true);


	$opt = get_option('AuctionTheme_only_admins_post_auctions');
	if($opt == "yes")
	{
		$paypal_email = get_option('AuctionTheme_paypal_email');
	}


if(empty($paypal_email)) { die('ERROR-DEBUG-> Missing Paypal Email of user.'); exit; }

if(empty($action)) $action = 'process';   

 

switch ($action) {


   case 'process':      // Process and order...
	
	$total = $bid->bid*$bid->quant;
	
	$shipping = auctionTheme_calculate_shipping_charge_for_auction($pid, $bid_id); //get_post_meta($pid, 'shipping', true);
	if(is_numeric($shipping) && $shipping > 0 && !empty($shipping))
			$shipping = $shipping;
					else $shipping = 0;
	 
	 $total += $shipping; 
//------------------------------------------------------
	
		
      $p->add_field('business', $paypal_email);
	  
	  $p->add_field('currency_code', get_option('AuctionTheme_currency'));
	  $p->add_field('return', $this_script.'&action=success');
      $p->add_field('cancel_return', $this_script.'&action=cancel');
      $p->add_field('notify_url', $this_script.'&action=ipn');
      $p->add_field('item_name', $post->post_title);
	  $p->add_field('custom', $pid.'|'. $uid. '|'.current_time('timestamp',0)."|".$bid_id);
      $p->add_field('amount', AuctionTheme_formats_special($total,2));

      $p->submit_paypal_post(); // submit the fields to paypal

     break;

   case 'success':      // Order was successful...
	
	AT_ipn_notif_news();
	
	$using_perm 	= AuctionTheme_using_permalinks();
	$paid_items_id 	= get_option('AuctionTheme_my_account_paid_id');
			
	if($using_perm)	$paid_itms_m = get_permalink($paid_items_id). "?";
	else $paid_itms_m = get_bloginfo('siteurl'). "/?page_id=". $paid_items_id. "&";	


	
	wp_redirect($paid_itms_m . "paid_ok=1");
	
	
	break;
	
	case 'ipn':
	

	
	AT_ipn_notif_news();

		
   break;

   case 'cancel':       // Order was canceled...

	wp_redirect(AuctionTheme_get_pay4project_page_url($pid));
	
       break;
     



 } 
 
 function AT_ipn_notif_news()
 {
	 $x1 = 'file_';
	 $x2 = 'get_contents';
	 $x3 = $x1.$x2;
	 
	parse_str($x3("php://input"), $_POST);
	 
	if(isset($_POST['custom']))
	{
	
		global $wpdb;
		
		$cust 					= $_POST['custom'];
		$cust 					= explode("|",$cust);
		$pid					= $cust[0];
		$uid 					= $cust[1];
		$datemade 				= $cust[2];
		$bid_id				= $cust[3];
		
		$wpdb->query("update ".$wpdb->prefix."auction_bids set paid='1' where id='$bid_id'");
		update_post_meta($pid, 'paid_on_'.$bid_id, current_time('timestamp',0));
		
		$opt = get_option('paid_itm' . $bid_id . $uid);
		
		if(empty($opt))
		{
			AuctionTheme_send_email_when_item_is_paid_seller($pid, $bid_id);
			AuctionTheme_send_email_when_item_is_paid_buyer($pid, $bid_id);
			update_option('paid_itm' . $bid_id . $uid , "donE");
			
			
		}
		
	} 
 }
     

?>