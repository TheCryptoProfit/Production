<?php
add_action('AuctionTheme_dposit_fields_page','AuctionTheme_dposit_fields_page_coinpmnts_fnc');

function AuctionTheme_dposit_fields_page_coinpmnts_fnc()
{
	$opt = get_option('AuctionTheme_coinpaymentsnet_enable');
	if(1):
	
	?>
	
    <br/><br/>
	<strong><?php _e('Deposit money by Coinpayments','AuctionTheme'); ?></strong><br/><br/>
                
                <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_coinpayments" value="<?php echo $_POST['amount_coinpayments']; ?>" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_coinpayments_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    
    <?php
	
	endif;	
	
}

function AT_coinpayments_thing_response_dep()
{
	
			$c  	= $_POST['on1'];
	$ipn_id = $_POST['txn_id'];
	
		$c 		= explode('_',$c);
		
		$uid				= $c[0];
		$tm					= $c[1];
	
	mail('andreisaioc@gmail.com','tessst',print_r($_POST,true));
	$ipn_id_gg = get_option('ddd_' . $ipn_id);
	
	if(empty($ipn_id_gg) and $_POST['received_confirms'] >= 1)
	{
		//-------------------
		
			$mc_gross = $_POST['amount1'] - $_POST['tax'];
			
			$cr = auctionTheme_get_credits($uid);
			auctionTheme_update_credits($uid,$mc_gross + $cr);
			
			update_option('AuctionTheme_deposit_'.$uid.$tm, "1");
			$reason = __("Deposit through CoinPayments.Net.","AuctionTheme"); 
			auctionTheme_add_history_log('1', $reason, $mc_gross, $uid);
			
 			$mc_gross = $_POST['tax'];
			update_option('ddd_' . $ipn_id,'asd');
		
		
			$seller = get_userdata($uid);
			
			$message = "Hello, <br/>Your bitcoin deposit of ".$mc_gross." has been added to your wallet. <br/><br/>Thanks,<br/>http://bitcoinmarvels.com";
			AuctionTheme_send_email($seller->user_email, 'Your bitcoin deposit has been confirmed.', $message);
		
		
		if($mc_gross > 0)
		{
			
			$reason = __("Tax/Fee for deposit through CoinPayments.Net.","AuctionTheme"); 
			auctionTheme_add_history_log('0', $reason, $mc_gross, $uid);
		}
	}
	
	if($_POST['received_confirms'] == 0)
	{
		$ipn_id_gga = get_option('ddd1_' . $ipn_id);
		
		if(empty($ipn_id_gga))
		{
			update_option('ddd1_' . $ipn_id,'asd');
			$seller = get_userdata($uid);
			
			$message = "Hello, <br/>Your bitcoin deposit of ".$mc_gross." has been registered. It will take from a few minutes to a few hours for the coins to appear in your account. <br/><br/>Thanks,<br/>http://bitcoinmarvels.com";
			AuctionTheme_send_email($seller->user_email, 'Your bitcoin deposit has been registered.', $message);
			
		}
	}
	
}

function AT_coinpayments_deposit_auction_theme_me()
{
	
	
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	$amount_coinpayments = $_POST['amount_coinpayments'];
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?a_action=coinpayments_deposit_response&uid='.$uid;
	$response_url 	= get_bloginfo('siteurl').'/?a_action=coinpayments_deposit_response';
	$ccnt_url		= get_permalink(get_option('AuctionTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('AuctionTheme_currency');
	
	
	
	
?>


<html>
<head><title>Processing Coinpayments Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'ProjectTheme'); ?></h3></center>

	
    <form action="https://www.coinpayments.net/index.php" method="post" name="frmPay" id="frmPay">
 
	<input type="hidden" name="cmd" value="_pay">
	<input type="hidden" name="reset" value="1">
	<input type="hidden" name="merchant" value="<?php echo get_option('AuctionTheme_coinpaymentsnet_id') ?>">
	<input type="hidden" name="currency" value="<?php echo $currency ?>">
	<input type="hidden" name="amountf" value="<?php echo $amount_coinpayments ?>">
	<input type="hidden" name="item_name" value="Money Deposit">
  
  
  <input type="hidden" name="ipn_url" value="<?php echo $response_url ?>">
  <input type="hidden" name="success_url" value="<?php echo $ccnt_url ?>">
  <input type="hidden" name="cancel_url" value="<?php echo $ccnt_url ?>">
  
  <input type="hidden" name="on1" value="<?php echo $uid.'_'.$tm; ?>">
  
        </form>
    
 

</body>
</html>


<?php	
	
}

?>