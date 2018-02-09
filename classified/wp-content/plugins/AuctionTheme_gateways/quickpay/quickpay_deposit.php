<?php
add_action('AuctionTheme_dposit_fields_page','AuctionTheme_dposit_fields_page_quickpay_fnc');

function AuctionTheme_dposit_fields_page_quickpay_fnc()
{
	$opt = get_option('AuctionTheme_quickpay_enable');
	if($opt == "yes"):
	
	?>
	
    <br/><br/>
	<strong><?php _e('Deposit money by Quickpay','AuctionTheme'); ?></strong><br/><br/>
                
                <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_quickpay" value="<?php echo $_POST['amount_quickpay']; ?>" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_quickpay_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    
    <?php
	
	endif;	
	
}

function quickpay_auction_theme_callback_fncs_deposit()
{
	
	//$mess = print_r($_POST, true);
	//mail("andreisaioc@gmail.com", "asd", $mess);
	
	if($_POST['qpstat'] == '000')
	{	
		$md5 = $_POST['md5check'];
		if(!empty($md5))
		{
			$tranid = $_GET['tranid'];
			$gr = get_option('ACT_QUICKPAY_' . $tranid, true);
			$gr = explode("_", $gr);
			
			$uid = $gr[0];
			$tm	 = $gr[1];
			
			$amount = round($_POST['amount']/100, 2);
			
			$cr = auctionTheme_get_credits($uid);
			auctionTheme_update_credits($uid, $amount + $cr);
			
			update_option('AuctionTheme_deposit_'.$uid.$tm, "1");
			$reason = __("Deposit through Quickpay.","AuctionTheme"); 
			auctionTheme_add_history_log('1', $reason, $amount, $uid);
			
		}
	}	
}

function quickpay_deposit_auction_theme_me()
{
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	$amount_quickpay = $_POST['amount_quickpay'];
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?a_action=quickpay_deposit_response&uid='.$pid;
	
	$ccnt_url		= get_permalink(get_option('AuctionTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('AuctionTheme_currency');
	
	
?>


<html>
<head><title>Processing Quickpay Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'AuctionTheme'); ?></h3></center>

 
    <?php
	
	$md5secret 		= get_option('AuctionTheme_quickpay_key');
	$merchant 		= get_option('AuctionTheme_quickpay_id');
 	$payurl 		= 'https://secure.quickpay.dk/form/'; 
	$amount 		= $amount_quickpay *100;
	$callbackurl 	= $response_url;
	$continueurl 	= $ccnt_url;
	$cancelurl 		= $ccnt_url;
	$ordernumber	= rand(0,999).time().$uid;
	$language		= 'en';
	$protocol		= '7';
	$testmode		= '0';
	$autocapture	= '0';
	$cardtypelock 	= 'creditcard';
	$msgtype 		= 'authorize';
	$callbackurl 	= get_bloginfo('siteurl').'/?a_action=quickpay_deposit_response&tranid=' . $ordernumber;
	
	//---------------------
	
	update_option('ACT_QUICKPAY_' . $ordernumber, $uid.'_'.$tm);
	
	$md5check = md5($protocol . $msgtype . $merchant . $language . $ordernumber . $amount . $currency . $continueurl . $cancelurl . $callbackurl . $autocapture . $cardtypelock . $testmode . $md5secret);
	
	echo "<form id=\"frmPay\" name=\"frmPay\" action=\"$payurl\" method=\"post\">
		<input type=\"hidden\" name=\"protocol\" value=\"$protocol\"/>
		<input type=\"hidden\" name=\"msgtype\" value=\"$msgtype\"/>
		<input type=\"hidden\" name=\"merchant\" value=\"$merchant\"/>
		<input type=\"hidden\" name=\"language\" value=\"$language\"/>
		<input type=\"hidden\" name=\"ordernumber\" value=\"$ordernumber\"/>
		<input type=\"hidden\" name=\"amount\" value=\"$amount\"/>
		<input type=\"hidden\" name=\"currency\" value=\"$currency\"/>
		<input type=\"hidden\" name=\"continueurl\" value=\"$continueurl\"/>
		<input type=\"hidden\" name=\"cancelurl\" value=\"$cancelurl\"/>
		<input type=\"hidden\" name=\"callbackurl\" value=\"$callbackurl\"/>
		<input type=\"hidden\" name=\"autocapture\" value=\"$autocapture\"/>
		<input type=\"hidden\" name=\"cardtypelock\" value=\"$cardtypelock\"/>
		<input type=\"hidden\" name=\"testmode\" value=\"$testmode\"/>
		<input type=\"hidden\" name=\"md5check\" value=\"$md5check\"/>
		
		</form>";
	
	?>
 

</body>
</html>


<?php	
	
}


?>