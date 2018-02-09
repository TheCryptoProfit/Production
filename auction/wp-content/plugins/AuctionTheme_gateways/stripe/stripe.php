<?php

include 'stripe_listing.php';

add_filter('AuctionTheme_payment_methods_tabs',				'AuctionTheme_payment_methods_tabs_stripe');
add_filter('AuctionTheme_payment_methods_content_divs_m',	'AuctionTheme_payment_methods_content_divs_stripe');
add_filter('AuctionTheme_payment_methods_action', 			'AuctionTheme_payment_methods_action_stripe', 0 );
add_filter('AuctionTheme_add_payment_options_to_post_new_project','AuctionTheme_add_payment_options_to_post_new_project_stripe');
add_filter('AuctionTheme_add_payment_options_to_edit_auction','AuctionTheme_add_payment_options_to_post_new_project_stripe');
 
 
add_filter('template_redirect','AuctionTheme_temp_redir_stripe');


add_action('AuctionTheme_dposit_fields_page','AuctionTheme_dposit_fields_page_stripe_fnc');

function AuctionTheme_dposit_fields_page_stripe_fnc()
{
	$opt = get_option('AuctionTheme_stripe_enable');
	if($opt == "yes"):
	
	?>
	
    <br/><br/>
	<strong><?php _e('Deposit money by Stripe','AuctionTheme'); ?></strong><br/><br/>
                
        <?php       	//$total = stripe_pay_for_listing_AT($pid);
		
			require_once('stripe-php/init.php');

			$tms = $_GET['tms'];
			$stripe = array(
			  "secret_key"      => get_option('AuctionTheme_stripe_api_key'),
			  "publishable_key" =>  get_option('AuctionTheme_stripe_p_api_key')
			);
			
			\Stripe\Stripe::setApiKey($stripe['secret_key']);
			
			if(!isset($_POST['deposit_stripe_me']))
			{
				$ok = 0;	
			}
			else
			{
				if(!is_numeric($_POST['amount_stripe'])) // < 0)
				{
					$err = 1;
					$ok = 0;		
				}
				elseif($_POST['amount_stripe'] > 0)
				{
					$ok = 1;	
				} else { $err = 1;
					$ok = 0; }
			}
			
			if($ok == 0)
			{ 
					if($err == 1) echo '<div class="error">Please input a proper amount.</div> <div class="clear10"> </div>';	 
		?>
        
      <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_stripe" value="<?php echo $_POST['amount_stripe']; ?>" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_stripe_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
      <?php }  
	  
	  
	  
	  if($ok == 1) {
		  
		  
		  global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
		  
		   ?>
        
        Depositing <b><?php echo auctiontheme_get_show_price($_POST['amount_stripe']); ?></b> via stripe. Click the button below to pay: <br/>
        
    <form action="<?php echo get_site_url() ?>/?charge_stripe_deposit=1&tts=<?php echo $_POST['amount_stripe'] *100 ?>&tms=<?php echo $uid ?>" method="post">
	  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
              data-key="<?php echo $stripe['publishable_key']; ?>"
              data-description="Pay by Credit Card" 
			  data-customer="<?php echo $tms ?>" 
			  data-source="<?php echo $tms ?>" 
			  data-currency="<?php echo get_option('AuctionTheme_currency') ?>"
              data-amount="<?php echo $_POST['amount_stripe'] *100 ?>"
              data-locale="auto"></script>
    </form>
   
    <?php
	  }
	  
	  
	endif;	
	
}


function AuctionTheme_temp_redir_stripe($pid)
{
	if($_GET['a_action'] == "stripe_listing_payment_a")
	{
		stripe_pay_for_listing_AT();
		die();	
	}
	
	 if(isset($_GET['stripe_hook_act']))
	  {
				//mail('andreisaioc@gmail.com','asd','asd');
				require_once('stripe-php/init.php');
				$stripe = array(
				  "secret_key"      => get_option('AuctionTheme_stripe_api_key'),
				  "publishable_key" =>  get_option('AuctionTheme_stripe_p_api_key')
				);
				
				\Stripe\Stripe::setApiKey($stripe['secret_key']);
				
				$body = @file_get_contents('php://input');
				$event_json = json_decode($body); 
				$event_id = $event_json->id;
				
				
				if($event_json->type == 'charge.succeeded')
				{
					$pid = get_option("stripe_m_". $event_json->data->object->customer);
					
					//mail('andreisaioc@gmail.com','asd - ' . $pid, print_r($event_json,true));
					
					if($pid == "listing")
					{
							$pid = get_option("stripe_". $event_json->data->object->customer);
							$po = get_post($pid);
							
							 
							$uid					= $po->post_author;
							$datemade 				= time();
						 
							
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
					else
					{
						$uid = get_option("stripe_". $event_json->data->object->customer);
						$mc_gross = $event_json->data->object->amount/100;
			
						$cr = auctionTheme_get_credits($uid);
						auctionTheme_update_credits($uid,($mc_gross + $cr));
						
						update_option('AuctionTheme_deposit_'.$uid.$tm, "1");
						$reason = __("Deposit through stripe.","AuctionTheme"); 
						auctionTheme_add_history_log('1', $reason, $mc_gross, $uid);
					}
				}
				
			
			echo 'ok';
			die();
	  }
	
	if(isset($_GET['charge_stripe_listing']))
	{
			require_once('stripe-php/init.php');

			$stripe = array(
			  "secret_key"      => get_option('AuctionTheme_stripe_api_key'),
			  "publishable_key" =>  get_option('AuctionTheme_stripe_p_api_key')
			);
			
			\Stripe\Stripe::setApiKey($stripe['secret_key']);
		 
			//------------------------------	
		
			  $token  = $_POST['stripeToken'];
			  $pid  = $_GET['tms'];
			
			  $customer = \Stripe\Customer::create(array(
				  'email' =>	$_POST['stripeEmail'],
				  'card'  => 	$token
			  ));
			
			  $charge = \Stripe\Charge::create(array(
				  'customer' => $customer->id,
				  'amount'   => urlencode($_GET['tts']),
				  'currency' => get_option('AuctionTheme_currency')
			  ));
			
		 
			update_option("stripe_m_". $customer->id,  'listing');
			update_option("stripe_". $customer->id,  $pid);
			wp_redirect(get_permalink(get_option('AuctionTheme_my_account_page_id'))); exit;
		
	}
	
	if(isset($_GET['charge_stripe_deposit']))
	{
			require_once('stripe-php/init.php');
			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
			
			$stripe = array(
			  "secret_key"      => get_option('AuctionTheme_stripe_api_key'),
			  "publishable_key" =>  get_option('AuctionTheme_stripe_p_api_key')
			);
			
			\Stripe\Stripe::setApiKey($stripe['secret_key']);
		 
			//------------------------------	
		
			  $token  = $_POST['stripeToken'];
			 // $uid  = $_GET['tms'];
			
			  $customer = \Stripe\Customer::create(array(
				  'email' =>	$_POST['stripeEmail'],
				  'card'  => 	$token
			  ));
			
			  $charge = \Stripe\Charge::create(array(
				  'customer' => $customer->id,
				  'amount'   => urlencode($_GET['tts']),
				  'currency' => get_option('AuctionTheme_currency')
			  ));
			
		 
			update_option("stripe_m_". $customer->id,  'deposit');
			update_option("stripe_". $customer->id,  $uid);
			wp_redirect(get_permalink(get_option('AuctionTheme_my_account_page_id'))); exit;
		
	}
}

function AuctionTheme_add_payment_options_to_post_new_project_stripe($pid)
{
	$AuctionTheme_stripe_enable = get_option('AuctionTheme_stripe_enable');
	if($AuctionTheme_stripe_enable == "yes")
	{
		//echo '<a href="'.get_bloginfo('siteurl').'/?a_action=stripe_listing_payment_a&pid='.$pid.'" class="post_bid_btn">'.__('Stripe Credit Card Payment','AuctionTheme'). '</a>';
		$total = stripe_pay_for_listing_AT($pid);
		
			require_once('stripe-php/init.php');

			$tms = $_GET['tms'];
			$stripe = array(
			  "secret_key"      => get_option('AuctionTheme_stripe_api_key'),
			  "publishable_key" =>  get_option('AuctionTheme_stripe_p_api_key')
			);
			
			\Stripe\Stripe::setApiKey($stripe['secret_key']);
			 
		?>
        
        
    <form action="<?php echo get_site_url() ?>/?charge_stripe_listing=1&tts=<?php echo $total *100 ?>&tms=<?php echo $pid ?>" method="post">
	  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
              data-key="<?php echo $stripe['publishable_key']; ?>"
              data-description="Pay by Credit Card" 
			  data-customer="<?php echo $tms ?>" 
			  data-source="<?php echo $tms ?>" 
			  data-currency="<?php echo get_option('AuctionTheme_currency') ?>"
              data-amount="<?php echo $total *100 ?>"
              data-locale="auto"></script>
    </form>
   
        
        <?php	
	}
}

function AuctionTheme_payment_methods_action_stripe()
{ 
	 
	if(isset($_POST['AuctionTheme_save_stripe2']))
	{
		update_option('AuctionTheme_stripe_enable', 		trim($_POST['AuctionTheme_stripe_enable']));
		update_option('AuctionTheme_stripe_api_key', 		trim($_POST['AuctionTheme_stripe_api_key']));
		update_option('AuctionTheme_stripe_p_api_key', 		trim($_POST['AuctionTheme_stripe_p_api_key']));
		
		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';		
	}
		
}

function AuctionTheme_payment_methods_content_divs_stripe()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>
 
<div id="tabs9a" >	
          
          <form method="post"  action="<?php echo get_admin_url(); ?>admin.php?page=AT_pay_gate_&active_tab=tabs9a">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td colspan="3">Set as webhook in your stripe account: <?php echo get_site_url(); ?>/?stripe_hook_act=1</td>
                    </tr>
                    
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_stripe_enable'); ?></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Stripe API Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_stripe_api_key" value="<?php echo get_option('AuctionTheme_stripe_api_key'); ?>"/></td>
                    </tr>
                    
                     <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Stripe Publishable API Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_stripe_p_api_key" value="<?php echo get_option('AuctionTheme_stripe_p_api_key'); ?>"/></td>
                    </tr>
                    
  
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_stripe2" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          	
          </div>

<?php	
	
}

function AuctionTheme_payment_methods_tabs_stripe()
{
?>
		<li><a href="#tabs9a">Stripe</a></li>
<?php	
}

?>