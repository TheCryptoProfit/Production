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


function AuctionTheme_my_account_pay4item_area_function()
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
            	<div class="box_title"><?php printf(__("Pay for Item - %s","AuctionTheme"), $post_au->post_title);; ?></div>
            	<div class="box_content">

             		      <?php

			if(isset($_POST['svs']))
					{
						$shipping = $_POST['shipping_mode'];

						if(empty($shipping))
						{
							echo '<div class="error">'.__('Please select your shipping option.','AuctionTheme') .'</div>';
						}
						else
						{

							update_post_meta($pid,'shipping' . $bid_id, $_POST['shipping_mode']);
							update_post_meta($pid,'shipping_was_set',1);
							echo '<div class="saved_thing">'.__('Your option has been saved. Proceed with the payment options below.','AuctionTheme') .'</div>';
						}
					}



			$do_not_require_shipping = get_post_meta($pid,'do_not_require_shipping',true);
			if($do_not_require_shipping == 1)
			{
				$is_OK = 1;
			}
			else
			{
			 	$shipping_type = get_post_meta($pid,'shipping_type',true);

				if($shipping_type == "flat")
				{

				}
				else
				{
			?>
             <h4>Choose shipping method for your item</h4>

             <form method="post">
          	   <?php


						$args = "orderby=id&order=ASC&hide_empty=0&parent=0";
						$terms = get_terms( 'auction_shipping', $args );

						foreach($terms as $term):

							$auctiontheme_get_shipping_charge = auctiontheme_get_shipping_charge($pid, $term->term_id);
							if(!empty($auctiontheme_get_shipping_charge) or $auctiontheme_get_shipping_charge == "0")
							{
								$shipping_mode = get_post_meta($pid, 'shipping'.$bid_id, true);
								echo '<input type="radio" value="'.$term->term_id.'" name="shipping_mode" '.($shipping_mode == $term->term_id ? 'checked="checked"' : '').' /> <strong>'.$term->name.':</strong> '.
								(  $auctiontheme_get_shipping_charge == "0" ? __('FREE','AuctionTheme') : auctiontheme_get_show_price($auctiontheme_get_shipping_charge)).'<br/>';

							}

						endforeach;





			   ?>

               <input type="submit" value="Save Shipping Option" name="svs" /> </form>

             <div class="clear10"></div>
              <div class="clear10"></div>
            <?php }} ?>



                <div class="post" id="post-<?php the_ID(); ?>">
                <div class="padd10">
                <div class="image_holder">
                <a href="<?php echo get_permalink($pid); ?>"><?php echo AuctionTheme_get_first_post_image($pid,75,65); ?></a>
                </div>
                <div  class="title_holder3" >
                     <h2><a href="<?php echo get_permalink($pid) ?>" rel="bookmark" title="Permanent Link to <?php echo $post->post_title; ?>">
                        <?php

                        echo $post_au->post_title;


                        ?></a></h2>
      			</div>

                <div class="details_holder3">
             <?php echo __('You are about to pay for this auction. Use the accepted methods below to pay for it.','AuctionTheme'); ?>

              <?php

					$shipping = auctionTheme_calculate_shipping_charge_for_auction($pid, $bid_id); //get_post_meta($pid, 'shipping', true);
					if(is_numeric($shipping) && $shipping > 0 && !empty($shipping))
						echo '<b>'.__('Shipping','AuctionTheme')."</b>: BTC".get_rate_USD_to_btc($shipping).'<br/>';
					else $shipping = 0;

              ?>

              <br/><br/>



              <a href="<?php

			$using_perm = AuctionTheme_using_permalinks();

			if($using_perm)	$privurl_m = get_permalink(get_option('AuctionTheme_my_account_priv_mess_page_id')). "?";
			else $privurl_m = get_bloginfo('siteurl'). "/?page_id=". get_option('AuctionTheme_my_account_priv_mess_page_id'). "&";

			echo $privurl_m."priv_act=send&";
            $post1 = get_post($pid);
			echo 'pid='.$pid.'&uid='.$post1->post_author;

			?>" class="post_bid_btn"><?php _e('Contact Seller','AuctionTheme'); ?></a><br/><br/>


                <b><?php echo __('The price for the auction is','AuctionTheme'); ?>:
				<?php



				$quant_tk = $bid->quant;
				if($quant_tk > 0)
				{


					echo 'BTC '.get_rate_USD_to_btc($bid->bid)." x ". $quant_tk." ".($shipping > 0 ? "+ ".get_rate_USD_to_btc($shipping).' +' : '')." = BTC ".get_rate_USD_to_btc($bid->bid * $quant_tk + $shipping);
				}
				else
				echo auctionTheme_get_show_price($bid->bid + $shipping); ?></b>
                <br/><br/>
                <?php

				$AuctionTheme_paypal_enable = get_option('AuctionTheme_paypal_enable');
				if($AuctionTheme_paypal_enable == "yes"):

				?>
                <a href="<?php bloginfo('siteurl'); ?>/?pay_for_item=paypal&bid_id=<?php echo $bid_id; ?>" class="post_bid_btn"><img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" border="0" /></a><br/><br/>

                <?php

				endif;


				$AuctionTheme_alertpay_enable = get_option('AuctionTheme_alertpay_enable');
				if($AuctionTheme_alertpay_enable == "yes"):

				?>
                <a class="post_bid_btn" href="<?php bloginfo('siteurl'); ?>/?pay_for_item=payza&bid_id=<?php echo $bid_id; ?>"><?php _e('Pay by Payza','AuctionTheme'); ?></a><br/><br/>

                <?php

				endif;


					//lets see if we enabled pay by credits
					$AuctionTheme_enable_pay_credits = get_option('AuctionTheme_enable_pay_credits');
					if($AuctionTheme_enable_pay_credits != 'no'):

				?>

                <a class="post_bid_btn" href="<?php echo auctionTheme_pay_for_item_by_credits_link($bid_id); ?>"><?php echo __('Pay by credits','AuctionTheme'); ?></a> <br/><br/>
                <a class="post_bid_btn" style="border:2px solid red" href="<?php echo AuctionTheme_get_payments_page_url('escrow', $bid_id ); ?>"><?php echo __('Pay by escrow','AuctionTheme'); ?></a><br/>
					<span style="color:red">( Highly recommended to buy via ESCROW, User is protected with escrow for 14 days)  </span>




                <?php endif; ?>


                <?php

				$AuctionTheme_offline_payments = get_option('AuctionTheme_offline_payments');
				if($AuctionTheme_offline_payments == "yes"):


				?>

                <br/><br/>

                <?php echo sprintf(__('Bank Details: %s','AuctionTheme'), get_option('AuctionTheme_offline_payment_dets')); ?>

                <?php
					endif;

					do_action('AuctionTheme_pay_for_item_page', $bid_id);

				?>
                </div>

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
