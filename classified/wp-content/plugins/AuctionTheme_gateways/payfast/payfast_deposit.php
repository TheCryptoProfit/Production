<?php

add_action('AuctionTheme_dposit_fields_page','AuctionTheme_dposit_fields_page_payfast_fnc');

function AuctionTheme_dposit_fields_page_payfast_fnc()
{
	$opt = get_option('AuctionTheme_payfast_enable');
	if($opt == "yes"):
	
	?>
	
    <br/><br/>
	<strong><?php _e('Deposit money by Payfast','AuctionTheme'); ?></strong><br/><br/>
                
                <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_payfast" value="<?php echo $_POST['amount_payfast']; ?>" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_payfast_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    
    <?php
	
	endif;	
	
}


function AuctionTheme_payfast_deposit_response()
{
	
		$c  	= $_POST['custom_str1'];
		$c 		= explode('|',$c);
		
		$uid				= $c[0];
		$tm					= $c[1];
		
		//-------------------
		
			$mc_gross = $_POST['amount_gross'];
			
			$cr = auctionTheme_get_credits($uid);
			auctionTheme_update_credits($uid,($mc_gross + $cr));
			
			update_option('AuctionTheme_deposit_'.$uid.$tm, "1");
			$reason = __("Deposit through PayFast.","AuctionTheme"); 
			auctionTheme_add_history_log('1', $reason, $mc_gross, $uid);
	
	
}

function Payfast_deposit_auction_theme_me()
{
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	$amount_payfast = $_POST['amount_payfast'];
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?a_action=payfast_deposit_response&uid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?a_action=payfast_deposit_response';
	$ccnt_url		= get_permalink(get_option('AuctionTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('AuctionTheme_currency');
	
	
?>


<html>
<head><title>Processing PayFast Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'ProjectTheme'); ?></h3></center>

	
    <form action="https://www.payfast.co.za/eng/process" method="post" name="frmPay" id="frmPay">

        <!-- Receiver Details -->
        <input type="hidden" name="merchant_id" value="<?php echo get_option('AuctionTheme_payfast_id'); ?>">
        <input type="hidden" name="merchant_key" value="<?php echo get_option('AuctionTheme_payfast_key'); ?>">
        <input type="hidden" name="return_url" value="<?php echo $ccnt_url; ?>">
        <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>">
        <input type="hidden" name="notify_url" value="<?php echo $response_url; ?>">
        

        <!-- Transaction Details -->
        <input type="hidden" name="m_payment_id" value="<?php echo $uid.'_'.$tm; ?>">
        <input type="hidden" name="custom_str1" value="<?php echo $uid.'|'.$tm; ?>">
        
        
        
        <input type="hidden" name="amount" value="<?php echo $amount_payfast; ?>">
        <input type="hidden" name="item_name" value="Deposit:">
        <input type="hidden" name="item_description" value="<?php echo auctiontheme_get_show_price($amount_payfast); ?>">
 
        
        </form>
    
 

</body>
</html>


<?php	
	
}

?>