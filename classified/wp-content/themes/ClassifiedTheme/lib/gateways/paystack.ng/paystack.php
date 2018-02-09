<?php

add_filter('AuctionTheme_payment_methods_tabs','AuctionTheme_payment_methods_tabs_paystack');
add_filter('AuctionTheme_payment_methods_content_divs_m','AuctionTheme_payment_methods_content_divs_paystc');
add_filter('AuctionTheme_payment_methods_action', 'AuctionTheme_payment_methods_action_paystck' );

add_action('AuctionTheme_dposit_fields_page',	'AuctionTheme_dposit_fields_page_paystach_fnc');
add_filter('template_redirect',								'AT_templ_redir_gateways_pstk');

function AT_templ_redir_gateways_pstk()
{



	if(isset($_GET['callback_ps']))
	{
		///	mail('andreisaioc@gmail.com','asd1',print_r($_POST,true));


	}

	if(isset($_GET['webhook_paystack']))
	{

						// Retrieve the request's body
				$body = @file_get_contents("php://input");
				$signature = (isset($_SERVER['HTTP_X_PAYSTACK_SIGNATURE']) ? $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] : '');


				/* It is a good idea to log all events received. Add code *
				 * here to log the signature and body to db or file       */

				if (!$signature) {
				    // only a post with paystack signature header gets our attention
				    exit();
				}

				define('PAYSTACK_SECRET_KEY',get_option('AuctionTheme_paystack_test_private'));
				// confirm the event's signature
				if( $signature !== hash_hmac('sha512', $body, PAYSTACK_SECRET_KEY) ){
				  // silently forget this ever happened
				  exit();
				}

				http_response_code(200);
				// parse event (which is json string) as object
				// Give value to your customer but don't give any output
				// Remember that this is a call from Paystack's servers and
				// Your customer is not seeing the response here at all
				$event = json_decode($body);
				switch($event->event){
				    // charge.success
				    case 'charge.success':
				        // TIP: you may still verify the transaction
				    		// before giving value.
								//mail('andreisaioc@gmail.com','asd1', print_r($event, true));
							$rf = $event->data->reference;
							$uid = get_option('trx_id_' . $rf);


							$am = $event->data->amount/100;
							$cr = auctionTheme_get_credits($uid);
							auctionTheme_update_credits($uid,($am + $cr));


							$reason = __("Deposit through Paystack.","AuctionTheme");
							auctionTheme_add_history_log('1', $reason, $am, $uid);


				        break;
				}
				exit();


	}

	if(isset($_GET['return_paystack']))
	{
		//	mail('andreisaioc@gmail.com','asd1',print_r($_POST,true));


	}


		if(isset($_POST['amount_paystack']))
			{
				if(!empty($_POST['amount_paystack']) and is_numeric($_POST['amount_paystack']))
				{
					//Payfast_deposit_auction_theme_me();

					$curl = curl_init();
					$cu = wp_get_current_user();
					$email = $cu->user_email;

						curl_setopt_array($curl, array(
						  CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
						  CURLOPT_RETURNTRANSFER => true,
						  CURLOPT_CUSTOMREQUEST => "POST",
						  CURLOPT_POSTFIELDS => json_encode([
						    'amount'=>$_POST['amount_paystack'] *100,
						    'email'=>$email,
								'callback_url' => home_url().'/?return_paystack=1'
						  ]),
						  CURLOPT_HTTPHEADER => [
						    "authorization: Bearer " . get_option('AuctionTheme_paystack_test_private'),
						    "content-type: application/json",
						    "cache-control: no-cache"
						  ],
						));

						$response = curl_exec($curl);
						$err = curl_error($curl);

						if($err){
						  // there was an error contacting the Paystack API
						  die('Curl returned error: ' . $err);
						}

						$tranx = json_decode($response);

						if(!$tranx->status){
						  // there was an error from the API
						  die('API returned error: ' . $tranx->message);
						}

						// store transaction reference so we can query in case user never comes back
						// perhaps due to network issue
					//	save_last_transaction_reference($tranx->data->reference);
					update_option('trx_id_' . $tranx->data->reference , $cu->ID);
					update_option('money_am_' . $tranx->data->reference, $_POST['amount_paystack']);


						// redirect to page so User can pay
						header('Location: ' . $tranx->data->authorization_url);
					//===================================================================

					die();
				}
				else
				{
					global $am_err;
					$am_err = 1;
				}
			}
}

function AuctionTheme_dposit_fields_page_paystach_fnc()
{
	$opt = get_option('AuctionTheme_paystck_enable');
	if($opt == "yes"):

	?>

    <br/><br/>
	<strong><?php _e('Deposit money by Paystack','AuctionTheme'); ?></strong><br/><br/>

                <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_paystack" required value="<?php echo $_POST['amount_paystack']; ?>" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_paystack_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>

    <?php

	endif;

}

function AuctionTheme_payment_methods_content_divs_paystc()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
  	$arr2 = array("test" => __("Test",'AuctionTheme'), "live" => __("Live",'AuctionTheme'));

?>

<div id="tabs_pstk" >

          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs_pstk">
            <table width="100%" class="sitemile-table">

              <tr>
              <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
              <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
              <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_paystck_enable'); ?></td>
              </tr>

              <tr>
              <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
              <td width="200"><?php _e('Test/Live:','AuctionTheme'); ?></td>
              <td><?php echo AuctionTheme_get_option_drop_down($arr2, 'AuctionTheme_paystck_tst'); ?></td>
              </tr>



                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Paystack Test Public Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_paystack_test_public" value="<?php echo get_option('AuctionTheme_paystack_test_public'); ?>"/></td>
                    </tr>

                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Paystack Test Private Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_paystack_test_private" value="<?php echo get_option('AuctionTheme_paystack_test_private'); ?>"/></td>
                    </tr>

                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Paystack Live Public Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_paystack_live_public" value="<?php echo get_option('AuctionTheme_paystack_live_public'); ?>"/></td>
                    </tr>

										<tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Paystack Live Private Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_paystack_live_private" value="<?php echo get_option('AuctionTheme_paystack_live_private'); ?>"/></td>
                    </tr>



										<tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Webhook url:','AuctionTheme'); ?></td>
                    <td><?php echo home_url() ?>/?webhook_paystack=1</td>
                    </tr>



                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_paystc" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>

            </table>
          	</form>

          </div>

<?php

}


function AuctionTheme_payment_methods_action_paystck()
{

	if(isset($_POST['AuctionTheme_save_paystc']))
	{
		update_option('AuctionTheme_paystack_live_private', 	trim($_POST['AuctionTheme_paystack_live_private']));
		update_option('AuctionTheme_paystack_live_public', 		trim($_POST['AuctionTheme_paystack_live_public']));
		update_option('AuctionTheme_paystack_test_private', 		trim($_POST['AuctionTheme_paystack_test_private']));
		update_option('AuctionTheme_paystack_test_public', 		trim($_POST['AuctionTheme_paystack_test_public']));
		update_option('AuctionTheme_paystck_tst', 		trim($_POST['AuctionTheme_paystck_tst']));
		update_option('AuctionTheme_paystck_enable', 		trim($_POST['AuctionTheme_paystck_enable']));

		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';
	}

}

function AuctionTheme_payment_methods_tabs_paystack()
{
?>
		<li><a href="#tabs_pstk">Paystack</a></li>
<?php
}


 ?>
