<?php


	global $current_user,  $wpdb, $wp_query;
	$id = $_GET['id'];


	function AuctionTheme_filter_ttl($title){ return __("Buy Membership by Credits",'AuctionTheme');}
	add_filter( 'wp_title', 'AuctionTheme_filter_ttl', 10, 3 );

	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }


	$current_user = wp_get_current_user();
	$uid 	= $current_user->ID;

	$s = "select * from ".$wpdb->prefix."auction_membership_packs where id='$id'";
	$r = $wpdb->get_results($s);
	$row = $r[0];

//-------------------------------------

	get_header();

?>


	<div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">

            <div class="my_box3">

            	<div class="box_title"><?php printf(__("Buy Membership by Credits - %s", "AuctionTheme"), $row->membership_name); ?></div>
                <div class="box_content">
            	 <?php
					$cr = auctionTheme_get_credits($uid);
					if(isset($_GET['yes']))
					{
						if($cr >= 	$row->membership_cost):

							$mem_available 	 = get_user_meta($uid, 'mem_available', true);
							$ct				 = current_time('timestamp', 0);

							if($ct > $mem_available or empty($mem_available))
							{
								update_user_meta($uid, 'mem_available', ($ct + 3600*24*30.5));
								update_user_meta($uid, 'auctions_available', $row->number_of_items);
								update_user_meta($uid, 'membership_id', $row->id);

								$reason = sprintf(__('Payment for purchasing membership: %s','AuctionTheme'), $row->membership_name);
								auctionTheme_add_history_log('0', $reason, $row->membership_cost, $uid);

								$credits = auctiontheme_get_credits($uid);
								auctiontheme_update_credits($uid, ($credits - $row->membership_cost));


							}

							echo sprintf(__('Your membership was upgraded. <a href="%s"><strong>Get back</strong></a> to your account.','AuctionTheme'), get_permalink(get_option('AuctionTheme_my_account_page_id')));
				?>


                <?php else: ?>

               		<div class="errrs"><?php _e('You do not have enough credits.','AuctionTheme'); ?></div>

                <?php endif;
					} else { ?> <p>

                    <?php

					echo sprintf(__('You are about to upgrade to the membership plan: <b>%s</b>. The cost for this package is: <b>%s</b>.<br/>You can pay by credits.','AuctionTheme'), $row->membership_name,
					auctiontheme_get_show_price($row->membership_cost));


				?>
                </p>

                <div class="clear10"></div>

                <p>

                <?php
					$cr = auctionTheme_get_credits($uid);
					echo sprintf(__('Your total credits: %s','AuctionTheme'), auctiontheme_get_show_price($cr));
				?>
                <br/><br/>

                <?php


					if($cr >= $row->membership_cost)
					{

				?>

                <a href="<?php bloginfo('siteurl'); ?>/?a_action=buy_memberships_credits&id=<?php echo $id; ?>&yes=1" class="post_bid_btn"><?php _e('Yes, Finalise the upgrade','AuctionTheme'); ?></a>
                <?php } else { ?>

                <a href="<?php echo AuctionTheme_get_payments_page_url('deposit'); ?>" class="post_bid_btn"><?php _e('Add More Credits','AuctionTheme'); ?></a>

                <?php } ?>

                <a href="<?php bloginfo('siteurl'); ?>/?a_action=buy_memberships&id=<?php echo $id; ?>" class="post_bid_btn"><?php _e('No, Get Back','AuctionTheme'); ?></a>


                </p>
                <?php } ?>
                </div>
                </div>
                </div>

	<?php AuctionTheme_get_users_links(); ?>

<?php get_footer(); ?>
