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


function AuctionTheme_my_account_pay_item_by_credits_area_function()
{
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

 	global $wp, $wpdb;
	global $wp_query, $wp_rewrite, $post;
	$bid_id 	=  $wp_query->query_vars['bid_id'];

	$s = "select * from ".$wpdb->prefix."auction_bids where id='$bid_id'";
	$r = $wpdb->get_results($s);
	$row = $r[0]; $bid = $row;

	$pid = $row->pid;
	$post_au = get_post($pid);

	?>


  <div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">


      <div class="my_box3">


            	<div class="box_title"><?php printf(__("Pay Item by Credits - %s","AuctionTheme"), $post_au->post_title);; ?></div>
            	<div class="box_content">




           <div class="post" id="post-<?php the_ID(); ?>">
                <div class="padd10">
                <div class="image_holder">
                <a href="<?php echo get_permalink($pid); ?>"><?php echo AuctionTheme_get_first_post_image($pid,75,65); ?></a>
                </div>


                <div  class="title_holder" >
                     <h2><a href="<?php echo get_permalink($pid); ?>" rel="bookmark" title="Permanent Link to <?php echo $post_au->post_title; ?>">
                        <?php  echo $post_au->post_title;  ?></a></h2>
      			</div>
                <?php

					if(isset($_GET['pay'])):
					echo '<div class="details_holder">';

					$cr = auctionTheme_get_credits($uid);
 

					$amount = $bid->bid;
					$itms_val = $amount;

				$quant_tk = $bid->quant;
				if($quant_tk > 0)
				$amount = $bid->bid * $quant_tk;
				else
				$amount = $bid->bid;


					$shipping = auctionTheme_calculate_shipping_charge_for_auction($pid, $bid_id); //get_post_meta($pid, 'shipping', true);
					if(is_numeric($shipping) && $shipping > 0 && !empty($shipping))
						echo '<b>'.__('Shipping','AuctionTheme')."</b>: BTC ".get_rate_USD_to_btc($shipping).'<br/>';
					else $shipping = 0;

					$amount = get_rate_USD_to_btc($amount + $shipping);

					if($cr < $amount) { echo '<div class="error2">'; echo __('You do not have enough credits to pay for this auction.','AuctionTheme');
					echo '</div>';
					?>
					<div class="tripp">
                    <a class="post_bid_btn" href="<?php echo AuctionTheme_get_payments_page_url('deposit'); ?>"><?php
					echo __('Add More Credits','AuctionTheme'); ?></a>
                    </div>

					<?php
					}
					else
					{
						//auctionTheme_send_email_to_auction_payer($pid, $uid, $post->post_author, $amount, '1');
						/**************************************************************************************/

						if($bid->paid != 1):

								//---- send emails ----------------

								 AuctionTheme_send_email_when_item_is_paid_seller($pid, $bid_id);
								 AuctionTheme_send_email_when_item_is_paid_buyer($pid, $bid_id);

								//-------------

								$perm 			= get_permalink($pid);
								$post_au = get_post($pid);
								$post = get_post($pid);

								$payer_user_id = $uid;
								$receiver_user_id = $post_au->post_author;

								$receiver_user 	= get_userdata($receiver_user_id);
								$payer_user 	= get_userdata($payer_user_id);
								$post_au = $post;
								//---------------------------

								$cr = auctionTheme_get_credits($payer_user_id);
								auctionTheme_update_credits($payer_user_id, $cr - $amount);

								$uprof 	= AuctionTheme_get_user_profile_link($receiver_user->ID);
								$reason = sprintf(__('Payment sent to <a href="%s">%s</a> for product <a href="%s">%s</a> (credits)','AuctionTheme'),$uprof, $receiver_user->user_login , $perm,
								$post->post_title);
								auctionTheme_add_history_log('0', $reason, $amount, $payer_user_id, $receiver_user_id);

								//=========================

								$auctionTheme_fee_after_paid = get_option('AuctionTheme_take_percent_fee');
								if(!empty($auctionTheme_fee_after_paid)):
									$deducted = $amount*($auctionTheme_fee_after_paid * 0.01);
								else:
									$deducted = 0;
								endif;


								//------------------------------------

								$auctionTheme_fee_after_paid_flat_fee  = get_option('AuctionTheme_take_flat_fee');
								if(!empty($auctionTheme_fee_after_paid_flat_fee)):
									if(is_numeric($auctionTheme_fee_after_paid_flat_fee)):
										$deducted = $auctionTheme_fee_after_paid_flat_fee;
									endif;
								endif;

								//-----------------------------------
								// $itms_val

								$AuctionTheme_enable_cust_commissions  = get_option('AuctionTheme_enable_cust_commissions');
								if( $AuctionTheme_enable_cust_commissions  == "yes"):

									global $wpdb;
									$s = "select * from ".$wpdb->prefix."auction_commissions where (low_range+0)<'$itms_val' order by (low_range+0) desc limit 1";
									$r = $wpdb->get_results($s);

									if(count($r) > 0)
									{
										$row = $r[0];
										$deducted = $amount*($row->commission * 0.01);
									}

								endif;

								//------------------------------------
								

								$cr = auctionTheme_get_credits($receiver_user_id);
								auctionTheme_update_credits($receiver_user_id, $cr + $amount - $deducted);

								$uprof 	= AuctionTheme_get_user_profile_link($payer_user_id->ID);
								$reason = sprintf(__('Payment received from <a href="%s">%s</a> for product <a href="%s">%s</a> (credits)','AuctionTheme'),$uprof, $payer_user->user_login , $perm,
								$post_au->post_title);
								auctionTheme_add_history_log('1', $reason, $amount , $receiver_user_id, $payer_user_id);

								//--------

								if($deducted > 0)
								{

									$reason = sprintf(__('Payment fee for auction <a href="%s">%s</a> (credits payment)','AuctionTheme'), $perm, $post_au->post_title);
									auctionTheme_add_history_log('0', $reason, $deducted, $receiver_user_id);
								}
						endif;

						/***************************************************************************************/

						$wpdb->query("update ".$wpdb->prefix."auction_bids set paid='1' where id='$bid_id'");
						update_post_meta($pid, 'paid_on_'.$bid_id, current_time('timestamp',0));

						$ok_paid_me = 1;
						echo '<div class="paid_ok_thing">'.__('Your payment has been sent.','AuctionTheme'). '</div>';

					}
					echo '</div>';

					if($ok_paid_me == 1)
					{


					}

				?>


                <?php else: ?>
                <div class="details_holder">

           		<?php


					$shipping = auctionTheme_calculate_shipping_charge_for_auction($pid, $bid_id); //get_post_meta($pid, 'shipping', true);
					if(is_numeric($shipping) && $shipping > 0 && !empty($shipping))
						echo '<b>'.__('Shipping','AuctionTheme')."</b>: ".auctionTheme_get_show_price($shipping).'<br/>';
					else $shipping = 0;

				?>


                <b>
                 <?php echo __('The price for the auction is','AuctionTheme'); ?>: <br/>




                 <?php


				$quant_tk = $bid->quantity;
				if($quant_tk > 0)
				echo 'BTC '.get_rate_USD_to_btc($bid->bid)." x ". $quant_tk." = BTC ".get_rate_USD_to_btc($bid->bid * $quant_tk + $shipping);
				else
				echo 'BTC '.get_rate_USD_to_btc($bid->bid + $shipping); ?>

                 </b>
                <br/><br/>

               <?php _e("Your cash amount",'AuctionTheme'); ?>: <?php echo auctionTheme_get_show_price(auctionTheme_get_credits($uid),2); ?> <br/><br/>
               <a class="post_bid_btn" href="<?php echo auctionTheme_pay_for_item_by_credits_link($bid_id); ?>&pay=yes"><?php echo __('Pay Now','AuctionTheme'); ?></a>
               <a class="post_bid_btn" href="<?php echo AuctionTheme_get_payments_page_url('deposit'); ?>"><?php echo __('Add More Credits','AuctionTheme'); ?></a>
                </div><?php endif; ?>

                </div>
                </div>






                </div>
                </div>





    <!-- ############################################# -->
    </div>

    <?php

	auctionTheme_get_users_links();

}

?>
