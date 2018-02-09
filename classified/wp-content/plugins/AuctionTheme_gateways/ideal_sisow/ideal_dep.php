<?php
add_action('AuctionTheme_dposit_fields_page','AuctionTheme_dposit_fields_page_ideal_sisow_fnc');
add_action('template_redirect','AuctionTheme_sisow_action_deposit_ideal');

function AuctionTheme_sisow_action_deposit_ideal()
{
	if(isset($_GET['notify_ideal_deposit1']))
	{
		$dets_a = $_GET['dets_a'];
		$dets_a = explode('_', $dets_a);
		
		$pid 	= $dets_a[0];
		$uid	= $dets_a[1];
		$tm		= $dets_a[2];
		$bid_id	= $dets_a[3];
		
	//--------------------------------------------
		global $wpdb;
		
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
	
	
	if(isset($_GET['notify_ideal_deposit2']))
	{
		$dets_a = $_GET['dets_a'];
 
		
	//--------------------------------------------
	 	
		$c  	= $_GET['dets_a']; $xx = $c;
		$c 		= explode('|',$c);
		
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
		
	
	}
		
	if(isset($_GET['notify_ideal_deposit']))
	{
		
		$dets_a = $_GET['dets_a'];
		$dets_a = explode('_', $dets_a);
		
		$uid 	= $dets_a[0];
		$tm 	= $dets_a[1];
		$am 	= $dets_a[2] - 0.45;
		
		$opts = get_option('dep_ideal_'.$uid.$tm);
		
		if(empty($opts))
		{
			update_option('dep_ideal_'.$uid.$tm,"asd");
			$current_cash = AuctionTheme_get_credits($uid);
			AuctionTheme_update_credits($uid, $current_cash + $am);
		
			$reason = __('Payment deposit by iDeal','AuctionTheme');
			AuctionTheme_add_history_log('1', $reason, $am, $uid);
		}
		
		include 'ideal.php';
 		$sis = new Sisow_Ideal();
		
		$sTransactionId = $_GET['trxid'];
		
		$sMerchantId 	= get_option('AuctionTheme_ideal_email');
		$sMerchantKey 	= get_option('AuctionTheme_ideal_secret');
		$sis->setMerchant($sMerchantId, $sMerchantKey);
		$sis->doStatusRequest($sTransactionId);
		
		$acc = $sis->sConsumerAccount;
		$consumername = $sis->consumername;
		
		update_user_meta($uid,'acc_bank',$acc);
		update_user_meta($uid,'consumername',$consumername);
		
		
		die();	
	}
	
	if(isset($_POST['deposit_ideal_sisow_me']))
	{
		include 'ideal.php';
		global $current_user; get_currentuserinfo();
		$uid = $current_user->ID;
			
		$amount = $_POST['amount_ideal_sisow'] + 0.45;
		$issuer = $_POST['issuer'];
		$sis = new Sisow_Ideal();
		
		$sMerchantId 	= get_option('AuctionTheme_ideal_email');
		$sMerchantKey 	= get_option('AuctionTheme_ideal_secret');
		//$sShopId 		= get_option('AuctionTheme_ideal_shopid');
		
		$tm = current_time('timestamp',0);
		$dets = $uid . "_" . $tm . "_".$amount;
		
		$sCallbackUrl 			= get_bloginfo("siteurl") . "/?callback_ideal=1";
		$notifyurl				 = get_bloginfo("siteurl") . "/?notify_ideal_deposit=1&dets_a=" . $dets; 
		
		$sReturnUrl 			= get_bloginfo('siteurl');
		$sPurchaseDescription 	= "Deposit Money";
		$fPurchaseAmount 		= $amount;
		$sPurchaseId 			= $uid;
		$sIssuerId 				= $issuer;
		$sEntranceCode 			= "238334";
		
		$sis->setMerchant($sMerchantId, $sMerchantKey);
		$sis->doTransactionRequest($sIssuerId, $sPurchaseId, $fPurchaseAmount, $sPurchaseDescription, $sEntranceCode, $sReturnUrl, $sCallbackUrl, $notifyurl);
		
		 
		
		 $sis->doTransaction();
		
		die();	
	}
	
	if(isset($_POST['pay_ideal_sisow_me']))
	{
		$bid_id = $_POST['bid_id'];
		include 'ideal.php';
		global $current_user, $wpdb; get_currentuserinfo();
		$uid = $current_user->ID;
		
		//-----------------------------------------------------
		
		$s = "select * from ".$wpdb->prefix."auction_bids where id='$bid_id'";
		$r = $wpdb->get_results($s);
		$row = $r[0]; $bid = $row;
		$pid = $bid->pid;
		$uid = $bid->uid;
		
		$total = $bid->bid*$bid->quant;
	
		$shipping = auctionTheme_calculate_shipping_charge_for_auction($pid, $bid_id); //get_post_meta($pid, 'shipping', true);
		if(is_numeric($shipping) && $shipping > 0 && !empty($shipping))
				$shipping = $shipping;
						else $shipping = 0;
		 
		 $total += $shipping;
		
		//----------------------------------------------------
		
		$amount = $total;
		$issuer = $_POST['issuer'];
		$sis = new Sisow_Ideal();
		
		$sMerchantId 	= get_option('AuctionTheme_ideal_email');
		$sMerchantKey 	= get_option('AuctionTheme_ideal_secret');
		//$sShopId 		= get_option('AuctionTheme_ideal_shopid');
		
		$tm = current_time('timestamp',0);
		$dets =  $pid.'_'. $uid. '_'.current_time('timestamp',0)."_".$bid_id;
		
		$sCallbackUrl 			= get_bloginfo("siteurl") . "/?callback_ideal1=1";
		$notifyurl				 = get_bloginfo("siteurl") . "/?notify_ideal_deposit1=1&dets_a=" . $dets; 
		
		$sReturnUrl 			= get_bloginfo('siteurl');
		$sPurchaseDescription 	= "Item payment";
		$fPurchaseAmount 		= $amount;
		$sPurchaseId 			= $uid;
		$sIssuerId 				= $issuer;
		$sEntranceCode 			= "238334";
		
		$sis->setMerchant($sMerchantId, $sMerchantKey);
		$sis->doTransactionRequest($sIssuerId, $sPurchaseId, $fPurchaseAmount, $sPurchaseDescription, $sEntranceCode, $sReturnUrl, $sCallbackUrl, $notifyurl);
		
		 
		
		 $sis->doTransaction();
		
		
		die();
	}
	
	if(isset($_POST['deposit_ideal_sisow_me2']))
	{
		$pid = $_POST['bid_id'];
		AUCTION_THEME_PAY_LISTING_IDEAL($pid );
		die(); 
	}
	
}

add_filter('AuctionTheme_pay_for_item_page','AuctionTheme_pay_for_item_page_IDS');

function AuctionTheme_pay_for_item_page_IDS($bid_id)
{
	
	$AuctionTheme_ideal_sisow_enable = get_option('AuctionTheme_ideal_sisow_enable');
	
	if($AuctionTheme_ideal_sisow_enable == "yes")
	{
	
	?>
    
    <br/><br/>
	<strong><?php _e('Pay by iDeal','AuctionTheme'); ?></strong><br/><br/>
                
    <form method="post">
    <input type="hidden" value="<?php echo $bid_id ?>" name="bid_id" />
               
                Bank: 
                <?php
               
			   include 'ideal.php';
			   
			   $sis = new Sisow_Ideal();
			   $sis->doIssuerRequest();
			   echo '<select name="issuer">';
			   echo $sis->getIssuers(true);
			   echo '</select>';
			   
			   ?>
                
                &nbsp; &nbsp; <input type="submit" name="pay_ideal_sisow_me" value="<?php _e('Pay Now','AuctionTheme'); ?>" /></form>
                
                
    <?php }	
}


function AuctionTheme_dposit_fields_page_ideal_sisow_fnc()
{
	$opt = get_option('AuctionTheme_ideal_sisow_enable');
	if($opt == "yes"):
	
	?>
	
    <br/><br/>
	<strong><?php _e('Deposit money by iDeal','AuctionTheme'); ?></strong><br/><br/>
                
                <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_ideal_sisow" value="<?php echo $_POST['amount_ideal_sisow']; ?>" /> <?php echo auctionTheme_currency(); ?><br/>
                Bank: 
                <?php
               
			   include 'ideal.php';
			   
			   $sis = new Sisow_Ideal();
			   $sis->doIssuerRequest();
			   echo '<select name="issuer">';
			   echo $sis->getIssuers(true);
			   echo '</select>';
			   
			   ?>
                
                &nbsp; &nbsp; <input type="submit" name="deposit_ideal_sisow_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    
    <?php
	
	endif;	
	
}


?>