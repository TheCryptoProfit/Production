<?php

add_filter('AuctionTheme_payment_methods_content_divs_m',	'AuctionTheme_payment_methods_content_divs_ideal_mollie');
add_filter('AuctionTheme_payment_methods_action',			'AuctionTheme_payment_methods_action_ideal_mollie');
add_filter('AuctionTheme_payment_methods_tabs',				'AuctionTheme_payment_methods_tabs_ideal_mollie');
add_filter('template_redirect',				'AuctionTheme_payment_mollie_template_redirect');

add_filter('AuctionTheme_pay_for_item_page','AuctionTheme_pay_for_item_page_IDS2');

function AuctionTheme_pay_for_item_page_IDS2($bid_id)
{
	
	$AuctionTheme_ideal_mollie_enable = get_option('AuctionTheme_ideal_mollie_enable');
	if($AuctionTheme_ideal_mollie_enable == "yes")
	{
	
	?>
    
    <br/><br/>
	<a href="<?php bloginfo('siteurl') ?>/?id_mol=<?php echo $bid_id ?>" class="post_bid_btn"><?php _e('Pay by iDeal','AuctionTheme'); ?></a><br/><br/>
                
    
                
                
    <?php	
	}
}

function AuctionTheme_payment_mollie_template_redirect()
{
	
	if(isset($_GET['id_mol']))
	{
	
		global $wp, $wpdb;
		global $wp_query, $wp_rewrite, $post;
		$bid_id 	=  $_GET['id_mol'];
		
		$s = "select * from ".$wpdb->prefix."auction_bids where id='$bid_id'";
		$r = $wpdb->get_results($s);
		$row = $r[0]; $bid = $row;
		
		$pid = $row->pid;
		$post_au = get_post($pid);
	
	//-------------------------------
	
	$amount = $bid->bid;
					$itms_val = $amount;
				
				$quant_tk = $bid->quant;
				if($quant_tk > 0)
				$amount = $bid->bid * $quant_tk;
				else				
				$amount = $bid->bid; 
					
					
					$shipping = auctionTheme_calculate_shipping_charge_for_auction($pid, $bid_id); //get_post_meta($pid, 'shipping', true);
 
				
					$amount = $amount + $shipping;
	
	
		include 'API/Client.php';
		include 'API/CompatibilityChecker.php';
		include 'API/Exception.php';
		include 'API/Exception/IncompatiblePlatform.php';
		include 'API/Object/Issuer.php';
		include 'API/Object/List.php';
		include 'API/Object/Method.php';
		include 'API/Object/Payment.php';
		include 'API/Object/Payment/Refund.php';

		
		
		include 'API/Resource/Base.php';
		include 'API/Resource/Issuers.php';
		include 'API/Resource/Methods.php';
		include 'API/Resource/Payments.php';
		include 'API/Resource/Payments/Refunds.php';
	 
		
 
	 
		
		$mollie = new Mollie_API_Client;
		$mollie->setApiKey(get_option('AuctionTheme_ideal_apy_key_mollie'));
		global $current_user;
		get_currentuserinfo();		 

		 
		try
		{
			$payment = $mollie->payments->create(
				array(
					'amount'      => $amount,
					'description' => $post_au->post_title,
					'redirectUrl' => get_bloginfo('siteurl'),
					'webhookUrl' =>  get_bloginfo('siteurl').'?hk_mollie2=1',
					'metadata'    => array(
						'order_id' => $_GET['id_mol']
					)
				)
			);
			
		 
			/*
			 * Send the customer off to complete the payment.
			 */
			header("Location: " . $payment->getPaymentUrl());
			exit;
		}
		catch (Mollie_API_Exception $e)
		{
			echo "API call failed: " . htmlspecialchars($e->getMessage()) . " on field " + htmlspecialchars($e->getField());
		}
		
		die();
	 
	}
	
	
	
	if(isset($_GET['hk_mollie2']))
	{
				include 'API/Client.php';
		include 'API/CompatibilityChecker.php';
		include 'API/Exception.php';
		include 'API/Exception/IncompatiblePlatform.php';
		include 'API/Object/Issuer.php';
		include 'API/Object/List.php';
		include 'API/Object/Method.php';
		include 'API/Object/Payment.php';
		include 'API/Object/Payment/Refund.php';

		
		
		include 'API/Resource/Base.php';
		include 'API/Resource/Issuers.php';
		include 'API/Resource/Methods.php';
		include 'API/Resource/Payments.php';
		include 'API/Resource/Payments/Refunds.php';
	 
	 
	 	
		$id = $_POST['id'];
		
				$mollie = new Mollie_API_Client;
		$mollie->setApiKey(get_option('AuctionTheme_ideal_apy_key_mollie'));
		
		$payment    = $mollie->payments->get($id);
		
		
		$order_id = $payment->metadata->order_id;

		if ($payment->isPaid())
		{
			/*
			 * At this point you'd probably want to start the process of delivering the product
			 * to the customer.
			 */
			$bid_id = $payment->metadata->order_id;
			
			global $wp, $wpdb;
			global $wp_query, $wp_rewrite, $post;
			 
			$s = "select * from ".$wpdb->prefix."auction_bids where id='$bid_id'";
			$r = $wpdb->get_results($s);
			$row = $r[0]; 
			$bid = $row;
			
			AuctionTheme_send_email_when_item_is_paid_seller($row->pid, $bid_id);
			AuctionTheme_send_email_when_item_is_paid_buyer($row->pid, $bid_id);
			
		
			$wpdb->query("update ".$wpdb->prefix."auction_bids set paid='1' where id='$bid_id'");
			update_post_meta($pid, 'paid_on_'.$bid_id, current_time('timestamp',0));
								
			 
			 
		}
		elseif (! $payment->isOpen())
		{
			/*
			 * The payment isn't paid and isn't open anymore. We can assume it was aborted.
			 */
		}
		
		
		
			die();
		
	}
	
	if(isset($_GET['hk_mollie']))
	{
		//mail("andreisaioc@gmail.com","asd",print_r($_POST,true));	
		
				include 'API/Client.php';
		include 'API/CompatibilityChecker.php';
		include 'API/Exception.php';
		include 'API/Exception/IncompatiblePlatform.php';
		include 'API/Object/Issuer.php';
		include 'API/Object/List.php';
		include 'API/Object/Method.php';
		include 'API/Object/Payment.php';
		include 'API/Object/Payment/Refund.php';

		
		
		include 'API/Resource/Base.php';
		include 'API/Resource/Issuers.php';
		include 'API/Resource/Methods.php';
		include 'API/Resource/Payments.php';
		include 'API/Resource/Payments/Refunds.php';
	 
	 
	 	
		$id = $_POST['id'];
		
				$mollie = new Mollie_API_Client;
		$mollie->setApiKey(get_option('AuctionTheme_ideal_apy_key_mollie'));
		
		$payment    = $mollie->payments->get($id);
		
		
		$order_id = $payment->metadata->order_id;

		if ($payment->isPaid())
		{
			/*
			 * At this point you'd probably want to start the process of delivering the product
			 * to the customer.
			 */
			 $uid = $payment->metadata->order_id;
			 $am = $payment->amount;
			  //mail("andreisaioc@gmail.com","asd", print_r($payment, true));	
		$tm = time();
		
				$cr = auctionTheme_get_credits($uid);
			auctionTheme_update_credits($uid,($am + $cr));
			
			update_option('AuctionTheme_deposit_'.$uid.$tm, "1");
			$reason = __("Deposit through iDeal Mollie.","AuctionTheme"); 
			auctionTheme_add_history_log('1', $reason, $mc_gross, $uid);
			 
			 
		}
		elseif (! $payment->isOpen())
		{
			/*
			 * The payment isn't paid and isn't open anymore. We can assume it was aborted.
			 */
		}
		
		
		
			die();
	}
}

function AuctionTheme_payment_methods_action_ideal_mollie()
{
	if(isset($_POST['AuctionTheme_save_ideal_mollie']))
	{
		update_option('AuctionTheme_ideal_apy_key_mollie', 	trim($_POST['AuctionTheme_ideal_apy_key_mollie']));
		update_option('AuctionTheme_ideal_mollie_enable', 		trim($_POST['AuctionTheme_ideal_mollie_enable']));
		
		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';		
	}
}


function AuctionTheme_payment_methods_content_divs_ideal_mollie()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="tabs_mollie" >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs_mollie">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_ideal_mollie_enable'); ?></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('iDeal Mollie API KEY:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_ideal_apy_key_mollie" value="<?php echo get_option('AuctionTheme_ideal_apy_key_mollie'); ?>"/></td>
                    </tr>
                    
          

                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_ideal_mollie" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          	
          </div>

<?php
}


function AuctionTheme_payment_methods_tabs_ideal_mollie()
{
?>
		<li><a href="#tabs_mollie">iDeal Mollie</a></li>
<?php	
}

add_action('AuctionTheme_dposit_fields_page','AuctionTheme_dposit_fields_page_ideal_mollie_fnc');

function AuctionTheme_dposit_fields_page_ideal_mollie_fnc()
{
	$opt = get_option('AuctionTheme_ideal_mollie_enable');
	if($opt == "yes"):
	
	?>
	
    <br/><br/>
	<strong><?php _e('Deposit money by iDeal Mollie','AuctionTheme'); ?></strong><br/><br/>
                
                <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_ideal_mollie" value="<?php echo $_POST['amount_ideal_mollie']; ?>" /> <?php echo auctionTheme_currency(); ?><br/>
               
                
                &nbsp; &nbsp; <input type="submit" name="deposit_ideal_mollie_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    
    <?php
	
	endif;	
	
}

add_action('template_redirect','AuctionTheme_mollie_action_deposit_ideal');

 


function AuctionTheme_mollie_action_deposit_ideal()
{

	if(isset($_POST['deposit_ideal_mollie_me']))
	{
		include 'API/Client.php';
		include 'API/CompatibilityChecker.php';
		include 'API/Exception.php';
		include 'API/Exception/IncompatiblePlatform.php';
		include 'API/Object/Issuer.php';
		include 'API/Object/List.php';
		include 'API/Object/Method.php';
		include 'API/Object/Payment.php';
		include 'API/Object/Payment/Refund.php';

		
		
		include 'API/Resource/Base.php';
		include 'API/Resource/Issuers.php';
		include 'API/Resource/Methods.php';
		include 'API/Resource/Payments.php';
		include 'API/Resource/Payments/Refunds.php';
	 
		
	 
		
		$amount_ideal_mollie = $_POST['amount_ideal_mollie'];
	 
		
		$mollie = new Mollie_API_Client;
		$mollie->setApiKey(get_option('AuctionTheme_ideal_apy_key_mollie'));
		global $current_user;
		get_currentuserinfo();		 

		 
		try
		{
			$payment = $mollie->payments->create(
				array(
					'amount'      => $amount_ideal_mollie,
					'description' => 'My first payment',
					'redirectUrl' => get_bloginfo('siteurl'),
					'webhookUrl' =>  get_bloginfo('siteurl').'?hk_mollie=1',
					'metadata'    => array(
						'order_id' => $current_user->ID
					)
				)
			);
			
		 
			/*
			 * Send the customer off to complete the payment.
			 */
			header("Location: " . $payment->getPaymentUrl());
			exit;
		}
		catch (Mollie_API_Exception $e)
		{
			echo "API call failed: " . htmlspecialchars($e->getMessage()) . " on field " + htmlspecialchars($e->getField());
		}
		
		die();
	}

}


?>