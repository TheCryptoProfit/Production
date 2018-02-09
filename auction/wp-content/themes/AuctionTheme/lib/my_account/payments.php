<?php
/***************************************************************************
*
*	AuctionTheme - copyright (c) - sitemile.com
*	The most popular auction theme for wordress on the internet. Launch your
*	auction site in minutes from purchasing. Turn-key solution.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/p/auctionTheme
*	since v4.4.7.1
*
***************************************************************************/

function AuctionTheme_my_account_payments_area_function()
{

		global $wpdb, $wp_query;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;


	?>
		<div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">

           <?php

			$pg = $_GET['pg'];
			if(!isset($pg)) $pg = 'home';

 

			global $wpdb;

			if($_GET['pg'] == 'closewithdrawal')
					{
						$id = $_GET['id'];

						$s = "select * from ".$wpdb->prefix."auction_withdraw where id='$id' AND uid='$uid'";
						$r = $wpdb->get_results($s);

						if(count($r) == 1)
						{
							$row = $r[0];
							$amount = $row->amount;

							$cr = auctionTheme_get_credits($uid);
							auctionTheme_update_credits($uid, $cr + $amount);

							$s = "delete from ".$wpdb->prefix."auction_withdraw where id='$id' AND uid='$uid'";
							$wpdb->query($s);

							echo __('Request canceled!','AuctionTheme').'<br/><br/>';
						}
					}


					if($_GET['pg'] == 'releasepayment')
					{
						$id = $_GET['id'];

						$s = "select * from ".$wpdb->prefix."auction_escrow where id='$id' AND fromid='$uid' and released!='1'";
						$r = $wpdb->get_results($s);

						if(count($r) > 0)
						{
							$row = $r[0];
							$amount = $row->amount;
							$toid = $row->toid;
							$psta = get_post($row->pid);

							//----------------
							$deducted2  = get_option('AuctionTheme_take_flat_fee');
							$auctionTheme_fee_after_paid = get_option('AuctionTheme_take_percent_fee');
								if(!empty($auctionTheme_fee_after_paid)):
									$deducted = ($amount-$deducted2)*($auctionTheme_fee_after_paid * 0.01);
								else:
									$deducted = 0;
								endif;


								//------------------------------------

					 
							//----------------------------------


							$cr = auctionTheme_get_credits($toid);
							auctionTheme_update_credits($toid, ($cr + $amount - $deducted - $deducted2));

							$reason = sprintf(__('Payment received from %s (escrow)','AuctionTheme'), $current_user->user_login);
							auctionTheme_add_history_log('1', $reason, ($amount), $toid, $uid);

							if($deducted > 0)
							{
								$reason = sprintf(__('Payment fee for product (percent based) %s  (escrow)','AuctionTheme'), $psta->post_title);
								auctionTheme_add_history_log('0', $reason, $deducted, $toid);
							}
							
							if($deducted2 > 0)
							{
								$reason = sprintf(__('Escrow fee for product %s  (escrow)','AuctionTheme'), $psta->post_title);
								auctionTheme_add_history_log('0', $reason, $deducted2, $toid);
							}
							//--------------------------------
							$bid_id = $row->bid_id;

							$wpdb->query("update ".$wpdb->prefix."auction_bids set paid='1' where id='$bid_id'");
							update_post_meta($pid, 'paid_on_'.$bid_id, current_time('timestamp',0));

							//-----------------------------
							$email 		= get_bloginfo('admin_email');
							$site_name 	= get_bloginfo('name');

							$usr = get_userdata($uid);

							$subject = __("Money Escrow Completed",'AuctionTheme');
							$message = sprintf(__("You have released the escrow of: %s","AuctionTheme"), auctionTheme_get_show_price($amount,2));

							//sitemile_send_email($usr->user_email, $subject , $message);

							//-----------------------------

							$usr = get_userdata($toid);

							$reason = sprintf(__('Payment sent to %s for product %s  (escrow)','AuctionTheme'), $usr->user_login, $psta->post_title);
							auctionTheme_add_history_log('0', $reason, $amount, $uid, $toid);

							$subject = __("Money Escrow Completed","AuctionTheme");
							$message = sprintf(__("You have received the amount of: %s","AuctionTheme"), auctionTheme_get_show_price($amount,2));

							//sitemile_send_email($usr->user_email, $subject , $message);

							//-----------------------------
							$tm = current_time('timestamp',0);
							$s = "update ".$wpdb->prefix."auction_escrow set released='1', releasedate='$tm' where id='$id'";
							$r = $wpdb->query($s);

						

						}
						
							echo __('Escrow completed! Redirecting...','AuctionTheme'); echo '<br/><br/>';

							$url_redir = get_permalink(get_option('AuctionTheme_my_account_payments_page_id'));
							echo '<meta http-equiv="refresh" content="2;url='.$url_redir.'" />';
					}


			if($pg == 'home'):
			?>

            <?php

				$AuctionTheme_enable_membership = get_option('AuctionTheme_enable_membership');
				if($AuctionTheme_enable_membership == "yes"):
				?>
            <?php

				if(isset($_GET['prc_mem']))
				{

			?>
            <div class="errrs_mm">
                	<?php _e('In order to post items you need to purchase some membership.','AuctionTheme') ?>
            </div>
            <?php } ?>

            <div class="my_box3">

            	<div class="box_title"><?php _e("Membership","AuctionTheme"); ?></div>
            	<div class="box_content">
                <div class="padd10">
                <?php

					$mem_available = get_user_meta($uid,'mem_available',true);
					$ct = current_time('timestamp', 0);

					if($ct > $mem_available or empty($mem_available)):

				?>
                	<div class="errrs_mm">
                		<?php _e('Your membership is expired. Please renew it.','AuctionTheme') ?>
            		</div>


                <?php endif; ?>


                <?php
				global $wpdb;
				$s = "select * from ".$wpdb->prefix."auction_membership_packs order by (membership_cost+0) asc";
				$r = $wpdb->get_results($s);

				if(count($r) == 0)
				{
					echo __('There are no membership packs defined yet.','AuctionTheme');
				}
				else
				{

			?>

              <table width="100%">
              	<?php

					if($ct < $mem_available):
					?>

                    <div class="saved_thing">
                    	<?php echo sprintf(__('Your membership will expire on: %s. You have left %s free items to post.','AuctionTheme'), date_i18n('d-M-Y', get_user_meta($uid,'mem_available',true)  ),
						get_user_meta($uid,'auctions_available',true) ); ?>
                    </div>
                    <div class="clear10"></div>

                    <?php
					endif;

					foreach($r as $row)
					{

				?>
              			<tr>
                        	<td><?php echo $row->membership_name ?></td>
                            <td><?php echo auctiontheme_get_show_price($row->membership_cost) ?></td>
                            <td><?php echo sprintf(__('%s monthly items','AuctionTheme'), $row->number_of_items); ?></td>
                        	<td><a href="<?php echo esc_url( home_url() ) ?>?a_action=buy_memberships&id=<?php echo $row->id ?>" class="green_btn"><?php _e('Purchase Now','AuctionTheme'); ?></a></td>
                        </tr>



              <?php }   ?>
              </table>





            <?php } ?>

              </div>
                </div>
                </div>

            <div class="clear10"></div>
            <?php endif; ?>

            <div class="my_box3">

            	<div class="box_title"><?php _e("Payments","AuctionTheme"); ?></div>
            	<div class="box_content">
                <div class="padd10">


                <?php
				$bal = auctionTheme_get_credits($uid);
				echo '<span class="balance">'.__("Your Current Balance is", "AuctionTheme").": ".auctionTheme_get_show_price($bal)."</span>";


				?>



                </div></div>
            </div>


            <div class="clear10"></div>

            <div class="my_box3">


            	<div class="box_title"><?php _e('What do you want to do','AuctionTheme'); ?></div>
            	<div class="box_content">
                <div class="padd10">


                <a href="<?php echo AuctionTheme_get_payments_page_url('deposit'); ?>" 		class="green_btn"><?php _e('Deposit Money','AuctionTheme'); ?></a>
                <a href="<?php echo AuctionTheme_get_payments_page_url('makepayment'); ?>" 	class="green_btn"><?php _e('Make Payment','AuctionTheme'); ?></a>
                <!-- <a href="<?php echo AuctionTheme_get_payments_page_url('escrow'); ?>" 		class="green_btn"><?php _e('Deposit Escrow','AuctionTheme'); ?></a> -->
                <a href="<?php echo AuctionTheme_get_payments_page_url('withdraw'); ?>" 	class="green_btn"><?php _e('Withdraw Money','AuctionTheme'); ?></a>
                <a href="<?php echo AuctionTheme_get_payments_page_url('transactions'); ?>" class="green_btn"><?php _e('Transactions','AuctionTheme'); ?></a>

                <?php

					$opt = get_option('AuctionTheme_offline_payments');
					if(!empty($opt)):

				?>
                <a href="<?php echo AuctionTheme_get_payments_page_url('bktransfer'); ?>" 	class="green_btn"><?php _e('Bank Transfer Details','AuctionTheme'); ?></a>
    			<?php endif; ?>

                </div></div>
            </div>


            <!-- ###################### -->
                        <div class="clear10"></div>

            <div class="my_box3">


            	<div class="box_title"><?php _e('Pending Withdrawals','AuctionTheme'); ?></div>
            	<div class="box_content">
                <div class="padd10">

         				<?php

					global $wpdb;

					//----------------

					$s = "select * from ".$wpdb->prefix."auction_withdraw where done='0' AND uid='$uid' order by id desc";
					$r = $wpdb->get_results($s);

					if(count($r) == 0) echo __('No withdrawals pending yet.','AuctionTheme');
					else
					{
						echo '<table width="100%">';
						foreach($r as $row) // = mysql_fetch_object($r))
						{


							echo '<tr>';
							echo '<td>'.date('d-M-Y H:i:s', $row->datemade).'</td>';
							echo '<td>'.auctionTheme_get_show_price($row->amount).'</td>';
							echo '<td>'.$row->methods .'</td>';
							echo '<td>'.$row->payeremail .'</td>';
							echo '<td><a href="'.AuctionTheme_get_payments_page_url('closewithdrawal', $row->id) .'"
							class="green_btn">'.__('Close Request','AuctionTheme'). '</a></td>';
							echo '</tr>';


						}
						echo '</table>';

					}

				?>

                </div></div>
            </div>



           <!-- ###################### -->
                        <div class="clear10"></div>

            <div class="my_box3">


            	<div class="box_title"><?php _e("Pending Incoming Payments","AuctionTheme"); ?></div>
            	<div class="box_content">
                <div class="padd10">

   				<?php

					$s = "select * from ".$wpdb->prefix."auction_escrow where released='0' AND toid='$uid' order by id desc";
					$r = $wpdb->get_results($s);

					if(count($r) == 0) echo __('No payments pending yet.','AuctionTheme');
					else
					{
						echo '<table width="100%">';
						foreach($r as $row) // = mysql_fetch_object($r))
						{
							$post = get_post($row->pid);
							$from = get_userdata($row->fromid);

							echo '<tr>';
							echo '<td><a href="'.AuctionTheme_get_user_profile_link($from->ID).'">'.$from->user_login.'</a></td>';
							echo '<td><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></td>';
							echo '<td>'.date('d-M-Y H:i:s', $row->datemade).'</td>';
							echo '<td>'.auctionTheme_get_show_price($row->amount).'</td>';

							echo '</tr>';


						}
						echo '</table>';

					}

				?>

                </div></div>
            </div>



                    <!-- ###################### -->
                        <div class="clear10"></div>

            <div class="my_box3">


            	<div class="box_title"><?php _e('Pending Outgoing Payments','AuctionTheme'); ?></div>
            	<div class="box_content">
                <div class="padd10">

      				<?php

					$s = "select * from ".$wpdb->prefix."auction_escrow where released='0' AND fromid='$uid' order by id desc";
					$r = $wpdb->get_results($s);

					if(count($r) == 0) echo __('No payments pending yet.','AuctionTheme');
					else
					{
						echo '<table width="100%">';

						echo '<tr>';
							echo '<td><b>'.__('User','AuctionTheme').'</b></td>';
							echo '<td><b>'.__('Auction','AuctionTheme').'</b></td>';
							echo '<td><b>'.__('Date','AuctionTheme').'</b></td>';
							echo '<td><b>'.__('Amount','AuctionTheme').'</b></td>';
							echo '<td><b>'.__('Options','AuctionTheme').'</b></td>';

							echo '</tr>';


						foreach($r as $row) // = mysql_fetch_object($r))
						{
							$post = get_post($row->pid);
							$from = get_userdata($row->toid);

							echo '<tr>';
							echo '<td><a href="'.AuctionTheme_get_user_profile_link($from->ID).'">'.$from->user_login.'</a></td>';
							echo '<td><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></td>';
							echo '<td>'.date_i18n('d-M-Y H:i:s', $row->datemade).'</td>';
							echo '<td>'.auctionTheme_get_show_price($row->amount).'</td>';
							echo '<td><a href="'.AuctionTheme_get_payments_page_url('releasepayment', $row->id).'"
							class="green_btn">'.__('Release Payment','AuctionTheme').'</a></td>';

							echo '</tr>';


						}
						echo '</table>';

					}

				?>

                </div></div>
            </div>

        <?php
			elseif($pg == 'escrow'):
		?>


        <div class="my_box3">


            	<div class="box_title"><?php _e('Make Escrow Payment','AuctionTheme'); ?></div>
            	<div class="box_content">
                <div class="padd10">


                <?php

				$bal = auctionTheme_get_credits($uid);


				if(isset($_POST['escrowme']))
				{
					$amount 	= $_POST['amount'];
					$fee 		= get_option('AuctionTheme_take_flat_fee');
					$auctions 	= $_POST['auctionss'];
					$bid_id     = $_POST['bid_id'];
					$tm     = $_POST['tmz'];

					if(!is_numeric($amount) || $amount < 0)
					{
						echo '<span class="newauction_error">'.__('Provide a well formated amount.','AuctionTheme').'</span><br/><br/>';

					}
					else if(empty($auctions))
					{
						echo '<span class="newauction_error">'.__('Please choose an auction.','AuctionTheme').'</span><br/><br/>';
					}
					else
					{
						if($bal < ($amount + $fee))
						{
							echo '<div class="newauction_error marg_btm">'.sprintf(__('Your balance is smaller than the amount requested. <a href="%s">Click here</a> to deposit.','AuctionTheme'),
							AuctionTheme_get_payments_page_url('deposit')).'</div>';
						}
						else
						{
							$post 	= get_post($auctions);
							$uid2   = get_post_meta($auctions, "winner", true);

							//$tm = current_time('timestamp',0);


							if($post->post_author != $uid)
								$uid2 = $post->post_author;



							//-----------------------
							$email 		= get_bloginfo('admin_email');
							$site_name 	= get_bloginfo('name');

							$usr = get_userdata($uid);

							$subject = __("Money Escrow Sent","AuctionTheme");
							$message = sprintf(__("You have placed in escrow the amount of: %s %s to user:
							<b>%s</b>","AuctionTheme"),$amount,'BTC',$username);

							//sitemile_send_email($usr->user_email, $subject , $message);

							$s = "select * from ".$wpdb->prefix."auction_escrow where datemade='$tm'";
							$r = $wpdb->get_results($s);
							
							if(count($r) == 0)
							{
							
								$amount+=$fee;
								
							$s = "insert into ".$wpdb->prefix."auction_escrow (datemade, amount, fromid, toid, pid, bid_id)
							values('$tm','$amount','$uid','$uid2','$auctions', '$bid_id')";
							$wpdb->query($s);
								
															 $wpdb->query("update ".$wpdb->prefix."auction_bids set paid='1' where id='$bid_id'");

								// for logged in user, the user who sends
								//======================================================
								$cr = auctionTheme_get_credits($uid);
								auctionTheme_update_credits($uid, $cr - $amount);
								
							}
							//======================================================

							// for other user, the user who receives
							//======================================================

							$usr2 = get_userdata($uid2);

							$subject = __("Money Escrow Received","AuctionTheme");
							$message = sprintf(__("You have received in escrow the amount of: %s %s
							from user: <b>%s</b>","AuctionTheme"),$amount,'BTC',$usr->user_login);

							//sitemile_send_email($usr2->user_email, $subject , $message);


							//======================================================

							echo '<span class="balance">'.__('Your payment has been sent. Redirecting...','AuctionTheme').'</span>';
							$url_redir = auctionTheme_get_payments_link(); //get_bloginfo('siteurl').'/my-account/payments/';
							echo '<meta http-equiv="refresh" content="2;url='.$url_redir.'" />';
						}

					}

				}


				$bal = auctionTheme_get_credits($uid);
				echo '<span class="balance">'.sprintf(__('Your Current Balance is: %s','AuctionTheme'), 'BTC '.($bal))."</span><br/><br/>";

				?>
    				<br /><br />
                    <table>
                    <form method="post" >
                    <tr>
                    <td><?php _e('Escrow amount','AuctionTheme'); ?>:</td><td>
                    <?php

					global $wpdb;
						$id = $_GET['id'];
						$s = "select * from ".$wpdb->prefix."auction_bids where id='$id'";
						$r = $wpdb->get_results($s);
						$row = $r[0]; $bid = $row; $pid = $bid->pid;


					$shipping = auctionTheme_calculate_shipping_charge_for_auction($pid, $row->uid); //get_post_meta($pid, 'shipping', true);
					if(is_numeric($shipping) && $shipping > 0 && !empty($shipping))
						$shipping = $shipping;
					else
						$shipping = 0;


					$quant_tk = $bid->quant;
					if($quant_tk > 0)
					{


						$prc = $bid->bid * $quant_tk + $shipping ;
					}
					else	  $prc = $bid->bid + $shipping ;


					?>

                    <?php echo 'BTC' . get_rate_USD_to_btc($prc ); ?></td>
                    </tr>
                    
                    <tr>
                    <td>Escrow fee:</td>
                    <td>BTC <?php echo get_option('AuctionTheme_take_flat_fee') ?></td>
						</tr>
                    
                    
                    
                     <tr>
                    <td width="135">Total Cost:</td>
                    <td>BTC <?php echo get_option('AuctionTheme_take_flat_fee') + get_rate_USD_to_btc($prc ) ?></td>
						</tr>
                    
                    
                    <tr>
                    <td><?php _e('Escrow for Auction','AuctionTheme'); ?>:</td><td> <?php /* $st = auction_get_my_awarded_auctions($uid);
					if($st == false) echo '<strong>'.__('You dont have any awarded auctions.','AuctionTheme').'</strong>'; else echo $st; */




						echo '<input type="hidden" value="'.$bid->pid.'" name="auctionss" />';
						echo '<input type="hidden" value="'.get_rate_USD_to_btc($prc).'" name="amount" />';
						echo '<input type="hidden" value="'.$id.'" name="bid_id" />';


						$pst = get_post($bid->pid);
						echo $pst->post_title;

						//--------------------------------------


                    ?></td>
                    </tr>

                    <tr>
                    <td></td>
                    <td>
                    <input type="submit" name="escrowme" value="<?php _e('Make Escrow','AuctionTheme'); ?>" />
                    <input type="hidden" name="tmz" value="<?php echo time() ?>" />
                    
                    </td></tr></form></table>


                </div></div>
            </div>




        <?php
			elseif($pg == 'bktransfer'):
		?>


        <div class="my_box3">


            	<div class="box_title"><?php _e('Set your Bank Transfer Details','AuctionTheme'); ?></div>
            	<div class="box_content">
                <div class="padd10">


                <?php

				$bal = auctionTheme_get_credits($uid);


				if(isset($_POST['bank_details']))
				{
					$bank_details 	= $_POST['bank_details'];
					update_user_meta($uid, 'bank_details', $bank_details);
					echo '<div class="saved_thing">'.__("Details Saved","AuctionTheme") . "</div>";

				}


				?>
    				<br /><br />
                    <table>
                    <form method="post">
                    <tr>
                    <td valign="top"><?php _e("Bank details","AuctionTheme"); ?>:</td>
                    <td> <textarea cols="60" name="bank_details" rows="6"><?php echo get_user_meta($uid,'bank_details',true); ?></textarea></td>
                    </tr>


                    <tr>
                    <td></td>
                    <td>
                    <input type="submit" name="submit" value="<?php _e("Save Details","AuctionTheme"); ?>" /></td></tr></form></table>


                </div></div>
            </div>



        <?php
			elseif($pg == 'makepayment'):
		?>

          <div class="my_box3">


            	<div class="box_title"><?php echo __("Make Payment","AuctionTheme"); ?></div>
            	<div class="box_content">
                <div class="padd10">


                <?php

				$bal = auctionTheme_get_credits($uid);


				if(isset($_POST['payme']))
				{
					$amount 	= $_POST['amount'];
					$username 	= $_POST['username'];

					if(!is_numeric($amount) || $amount < 0)
					{
						echo '<div class="newauction_error">'.__('Provide a well formated amount.','AuctionTheme').'</div><br/>';

					}
					else if(auctionTheme_username_is_valid2($username) == false)
					{
						echo '<div class="newauction_error">'.__('Invalid email provided.','AuctionTheme').'</div><br/>';
					}

					else if($username == $current_user->user_login)
					{
						echo '<div class="newauction_error">'.__('You cannot transfer money to your own account.','AuctionTheme').'</div><br/>';
					}
					else
					{
						$min = get_option('auction_theme_transfer_limit');
						if(empty($min)) $min = 20;
						$min = 0.0015;
						

						if($bal < $amount)
						{
							echo '<div class="newauction_error">'.__('Your balance is smaller than the amount requested.','AuctionTheme').'</div><br/>';
						}
						 
						else
						{
							$tm = current_time('timestamp',0);
							$uid2 = auctionTheme_get_userid_from_username2($username);

							// for logged in user, the user who sends
							//======================================================
							$cr = auctionTheme_get_credits($uid);
							auctionTheme_update_credits($uid, $cr - $amount);

							//-----------------------
							$email 		= get_bloginfo('admin_email');
							$site_name 	= get_bloginfo('name');

							$usr = get_userdata($uid);

							$subject = __("Money Sent","AuctionTheme");
							$message = sprintf(__("You have sent amount of: %s %s to user: <b>%s</b>","AuctionTheme")
							,$amount,auctionTheme_currency(),$username);

							//sitemile_send_email($usr->user_email, $subject , $message);

							$reason = sprintf(__("Amount transfered to user %s","AuctionTheme"),$username);
							auctionTheme_add_history_log('0', $reason, $amount, $uid, $uid2);

							//======================================================

							// for other user, the user who receives
							//======================================================

							$cr = auctionTheme_get_credits($uid2);
							auctionTheme_update_credits($uid2, $cr + $amount);


							$usr2 = get_userdata($uid2);

							$subject = __("Money Received","AuctionTheme");
							$message = sprintf(__("You have received amount of: %s %s from user: <b>%s</b>","AuctionTheme"),
							$amount,auctionTheme_currency(),$usr->user_login);

							//sitemile_send_email($usr2->user_email, $subject , $message);

							$reason = sprintf(__("Amount transfered from user %s","AuctionTheme"), $usr->user_login);
							auctionTheme_add_history_log('1', $reason, $amount, $uid2, $uid);

							//======================================================

							echo '<span class="balance">'.__('Your payment has been sent. Redirecting...','AuctionTheme').'</span>';
							$url_redir = auctionTheme_get_payments_link();
							echo '<meta http-equiv="refresh" content="2;url='.$url_redir.'" />';
						}

					}

				}


				$bal = auctionTheme_get_credits($uid);
				echo '<span class="balance">'. sprintf(__("Your Current Balance is %s","AuctionTheme"), auctionTheme_get_show_price($bal)).":</span><br/><br/>";

				?>
    				<br /><br />
                    <div class="table-responsive">
                    <table class="table">
                    <form method="post" enctype="application/x-www-form-urlencoded">
                    <tr>
                    <td><?php echo __("Payment amount","AuctionTheme"); ?>:</td>
                    <td> <input value="<?php echo $_POST['amount']; ?>" type="text"
                    size="10" name="amount" /> <?php echo 'BTC'; ?></td>
                    </tr>
                    <tr>
                    <td><?php echo __("Pay to user(email)","AuctionTheme"); ?>:</td>
                    <td><input value="<?php echo $_POST['username']; ?>" type="text" size="30" name="username" /></td>
                    </tr>

                    <tr>
                    <td></td>
                    <td>
                    <input type="submit" name="payme" value="<?php echo __("Make Payment","AuctionTheme"); ?>" /></td></tr></form></table></div>


                </div></div>
            </div>



        <?php
            elseif($pg == 'withdraw'):

		?>


               <div class="my_box3">


            	<div class="box_title"><?php _e("Request Withdrawal","AuctionTheme"); ?></div>
            	<div class="box_content">
                <div class="padd10">


                <?php

				$bal = auctionTheme_get_credits($uid);
				echo '<span class="balance">';
				printf(__('Your Current Balance is: %s','AuctionTheme'), auctionTheme_get_show_price($bal));
				echo "</span><br/><br/>";

				if(isset($_POST['withdraw']))
				{
					$amount 	= $_POST['amount'];
					$paypal 	= $_POST['bank_details'];
					$methods 	= $_POST['methods'];

					if(!is_numeric($amount) || $amount < 0)
					{
						echo '<span class="newauction_error">'.__('Provide a well formated amount.','AuctionTheme').'</span>';

					}
					
					else
					{
						$min = get_option('auction_theme_min_withdraw');
						  $min = 0.0015;

						if($bal < $amount)
						{
							echo '<span class="newauction_error">'.__('Your balance is smaller than the amount requested.','AuctionTheme').'</span>';
						}
						
						else if(strlen($paypal) < 24)
						{
							echo '<span class="newauction_error">'.__('Make sure you provide a valid bitcoin wallet address.','AuctionTheme').'</span>';
						}
						else if($amount < $min)
						{
							echo '<span class="newauction_error">'.sprintf(__('The amount should not be less than %s','AuctionTheme'),
							$min.' BTC').'.</span>';
						}
						else
						{

							if($methods == "Bank") $paypal = $_POST['bank_details'];
							if($methods == "coinpayments") $paypal = $_POST['bank_details'];
							
							$tm = $_POST['tmmm'];
							
							if(empty($tm))
							$tm = current_time('timestamp',0); 
							
							
							global $wpdb;
							
							$s = "select * from ".$wpdb->prefix."auction_withdraw where datemade='$tm' ";
							$r = $wpdb->get_results($s);
							
							if(count($r) == 0)
							{
							
								$s = "insert into ".$wpdb->prefix."auction_withdraw (payeremail, methods, amount, datemade, uid)
								values('$paypal', '$methods','$amount','$tm','$uid')";
								$wpdb->query($s);
								
								$cr = auctionTheme_get_credits($uid);
								auctionTheme_update_credits($uid, $cr - $amount);
							
							}
							
							//----------------------
							
							
					

							//-----------------------
							$email 		= get_bloginfo('admin_email');
							$site_name 	= get_bloginfo('name');

							$usr = get_userdata($uid);

							AuctionTheme_send_email_when_seller_withdrawal($uid, $amount);
							AuctionTheme_send_email_when_seller_withdrawal_admin($uid, $amount);

							//-----------------------

							echo '<span class="balance">'.__('Your request has been queued. Redirecting...','AuctionTheme').'</span>';
							$url_redir = auctionTheme_get_payments_link();
							echo '<meta http-equiv="refresh" content="2;url='.$url_redir.'" />';
						}

					}

				}

				$current_user = wp_get_current_user();
				$uid = $current_user->ID;


					$opt = get_option('AuctionTheme_paypal_enable');
					if($opt == "yes"):

				?>
    				<br /><br />
                    <table>
                    <form method="post" enctype="application/x-www-form-urlencoded">
                    <input type="hidden" value="PayPal" name="methods" />
                    <tr>
                    <td><?php echo __("Withdraw amount","AuctionTheme"); ?>:</td>
                    <td> <input value="<?php echo $_POST['amount']; ?>" type="text"
                    size="10" name="amount" /> <?php echo auctionTheme_currency(); ?></td>
                    </tr>
                    <tr>
                    <td><?php echo __("PayPal Email","AuctionTheme"); ?>:</td>
                    <td><input value="<?php echo get_user_meta($uid, 'paypal_email',true); ?>" type="text" size="30" name="paypal" /></td>
                    </tr>

                    <tr>
                    <td></td>
                    <td>
                    <input type="submit" name="withdraw" value="<?php echo __("Withdraw","AuctionTheme"); ?>" /></td></tr></form></table>
    			<?php endif;


					$opt = get_option('AuctionTheme_offline_payments');
					if($opt == "yes"):

				?>
    				<br /><br />
                    <table>
                    <form method="post" enctype="application/x-www-form-urlencoded">
                    <input type="hidden" value="Bank" name="methods" />
                    <tr>
                    <td><?php echo __("Withdraw amount(bank):","AuctionTheme"); ?></td>
                    <td> <input value="<?php echo $_POST['amount']; ?>" type="text"
                    size="10" name="amount" /> <?php echo auctionTheme_currency(); ?></td>
                    </tr>

                    <tr>
                    <td valign="top"><?php echo __("Bank Details:","AuctionTheme"); ?></td>
                    <td> <textarea cols="60" name="bank_details" rows="6"><?php echo get_user_meta($uid,'bank_details',true); ?></textarea></td>
                    </tr>




                    <tr>
                    <td></td>
                    <td>
                    <input type="submit" name="withdraw" value="<?php echo __("Withdraw","AuctionTheme"); ?>" /></td></tr></form></table>
    			<?php endif; ?>

                <?php do_action('AuctionTheme_withdrawal_options_me') ?>


                </div></div>
            </div>




        <?php
            elseif($pg == 'deposit'):


			global $am_err;

			if($am_err == 1)
			{
				echo '<div class="errrs3">'.__('Please input a proper amount.','AuctionTheme').'</div>';

			}


		?>



        <div class="my_box3">


            	<div class="box_title"><?php _e('Deposit Money','AuctionTheme'); ?></div>
            	<div class="box_content">
                <div class="padd10">
                <?php
				$opt = get_option('AuctionTheme_offline_payments');
				if($opt =="yes"):
				?>

               <strong><?php _e('Deposit money by Bank','AuctionTheme'); ?></strong><br/><br/>

               <?php echo sprintf(__('Bank Details: %s','AuctionTheme'), get_option('AuctionTheme_offline_payment_dets')); ?>

               <br/><br/>


                <?php endif; ?>

                  <?php
				$opt = get_option('AuctionTheme_paypal_enable');
				if($opt =="yes"):
				?>

                <strong><?php _e('Deposit money by PayPal','AuctionTheme'); ?></strong><br/><br/>

                <form method="post">
               <?php _e('Amount to deposit:','AuctionTheme'); ?> <input type="text" size="10" name="amount" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_pay_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    				<hr color="#dedede" />
    <?php endif;




				$opt = get_option('AuctionTheme_alertpay_enable');
				if($opt =="yes"):
				?>

                <br/><br/>
                <strong><?php _e('Deposit money by Payza','AuctionTheme'); ?></strong><br/><br/>

                <form method="post">
               <?php _e('Amount to deposit:','AuctionTheme'); ?> <input type="text" size="10" name="amount" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_pay_me_payza" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    			<hr color="#dedede" />
    <?php endif;


	$opt = get_option('AuctionTheme_moneybookers_enable');
				if($opt =="yes"):
				?>

                <br/><br/>
                <strong><?php _e('Deposit money by Skrill','AuctionTheme'); ?></strong><br/><br/>

                <form method="post">
               <?php _e('Amount to deposit:','AuctionTheme'); ?> <input type="text" size="10" name="amount" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_pay_me_mb" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    			<hr color="#dedede" />
    <?php endif;




	do_action('AuctionTheme_dposit_fields_page')


	?>

                </div></div>
            </div>


        <?php
            elseif($pg == 'transactions'):

		?>


            <div class="my_box3">

            	<div class="box_title"><?php _e('Payment Transactions','AuctionTheme'); ?> </div>
            	<div class="box_content no_padding">


                <?php

					$s = "select * from ".$wpdb->prefix."auction_payment_transactions where uid='$uid' order by id desc";
					$r = $wpdb->get_results($s);

					if(count($r) == 0) echo '<p class="padd10">'.__('No activity yet.','AuctionTheme') . '</p>';
					else
					{
						$i = 0;
						echo '<table width="100%" cellpadding="5">';
						foreach($r as $row) // = mysql_fetch_object($r))
						{
							if($row->tp == 0){ $class="redred"; $sign = "-"; }
							else { $class="greengreen"; $sign = "+"; }

							echo '<tr class="'.($i%2 ? "s_bg_1" : "s_bg_2").'" >';
							echo '<td>'.$row->reason.'</td>';
							echo '<td width="25%">'.date('d-M-Y H:i:s',$row->datemade).'</td>';
							echo '<td width="20%" class="'.$class.'"><b>'.$sign.auctionTheme_get_show_price($row->amount).'</b></td>';

							echo '</tr>';
							$i++;
						}

						echo '</table>';


					}

				?>


                </div>
            </div>




		<?php endif; ?>


        </div>


<?php	auctionTheme_get_users_links();
}

?>
