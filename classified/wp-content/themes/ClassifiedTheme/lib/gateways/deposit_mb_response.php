<?php

if($_POST['status'] > -1)
{
		
		$c  	= $_POST['field1'];
		$c 		= explode('_',$c);
		
		$cust = $c;
		
		$uid					= $cust[0];
		$datemade				= $cust[1];

		$op = get_option('AuctionTheme_deposit_'.$uid.$datemade);
		
		//----------------------------------------------------------
		
		if($op != "1")
		{
			$mc_gross = $_POST['amount']; // - $feeAmount;
			
			$cr = auctionTheme_get_credits($uid);
			auctionTheme_update_credits($uid,$mc_gross + $cr);
			
			update_option('AuctionTheme_deposit_'.$uid.$datemade, "1");
			$reason = __("Deposit through PayPal.","AuctionTheme"); 
			auctionTheme_add_history_log('1', $reason, $netAmount, $uid);
			
			
			$reason = __("PayPal deposit fee.","AuctionTheme"); 
			auctionTheme_add_history_log('0', $reason, $feeAmount, $uid);
		
		}
	 	
		wp_redirect(get_bloginfo('siteurl'));
}
	
?>