<?php
function AT_authorize_resp_deposit()
{
	if(isset($_POST['x_cust_id'])):
		
		$seq 					= $_POST['x_cust_id'];
		$opt 					= get_option('AuctionTheme_deposit_'.$seq);
		
		 
		
		if(empty($opt) and ($_POST['x_response_code'] == 1 or $_POST['x_response_code'] == '1') )
		{
		
				$cust 					= get_option('sequence_custom_dep_' . $seq); 		
				$cust 					= explode("|",$cust);
				
				$uid					= $cust[0];
				$amount					= $_POST['x_amount'];
		
			//-----------------------------------------------
		
				$mc_gross = $amount;
				
				$cr = auctionTheme_get_credits($uid);
				auctionTheme_update_credits($uid,$mc_gross + $cr);
				
				update_option('AuctionTheme_deposit_'.$seq, "1");
				$reason = __("Deposit through Authorize.NET.","AuctionTheme"); 
				auctionTheme_add_history_log('1', $reason, $mc_gross, $uid);
			
		}
		
	endif;	
	
	
}

?>