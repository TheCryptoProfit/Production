<?php


	global $current_user,  $wpdb, $wp_query;
	$id = $_GET['id'];


	function AuctionTheme_filter_ttl($title){ return __("Buy Membership",'AuctionTheme');}
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

            	<div class="box_title"><?php printf(__("Buy Membership - %s", "AuctionTheme"), $row->membership_name); ?></div>
                <div class="box_content">

                <p>
                <?php


					echo sprintf(__('You are about to upgrade to the membership plan: <b>%s</b>. The cost for this package is: <b>%s</b>.<br/>Use the options below to upgrade.','AuctionTheme'), $row->membership_name,
					auctiontheme_get_show_price($row->membership_cost));


				?>
                </p>

                <div class="clear10"></div>

                <p>
                <?php

                	$AuctionTheme_enable_pay_credits = get_option('AuctionTheme_enable_pay_credits');
					if($AuctionTheme_enable_pay_credits != 'no'):

				?>

                <a class="post_bid_btn" href="<?php echo esc_url( home_url() ); ?>?a_action=buy_memberships_credits&id=<?php echo $id ?>"><?php echo __('Pay by credits','AuctionTheme'); ?></a>


                <?php endif; ?>

                <?php

				$AuctionTheme_paypal_enable = get_option('AuctionTheme_paypal_enable');
				if($AuctionTheme_paypal_enable == "yes"):

				?>
                <a href="<?php bloginfo('siteurl'); ?>/?purchase_mem_paypal=1&id=<?php echo $id; ?>" class="post_bid_btn"><?php _e('Pay by PayPal','AuctionTheme'); ?></a>

                <?php
				endif;
				?>


                <?php

				$AuctionTheme_moneybookers_enable = get_option('AuctionTheme_moneybookers_enable');
				if($AuctionTheme_moneybookers_enable == "yes"):

				?>
                <a href="<?php bloginfo('siteurl'); ?>/?purchase_mem_skrill=1&id=<?php echo $id; ?>" class="post_bid_btn"><?php _e('Pay by Skrill','AuctionTheme'); ?></a>

                <?php
				endif;
				?>


                <?php

				$AuctionTheme_alertpay_enable = get_option('AuctionTheme_alertpay_enable');
				if($AuctionTheme_alertpay_enable == "yes"):

				?>
                <a href="<?php bloginfo('siteurl'); ?>/?purchase_mem_payza=1&id=<?php echo $id; ?>" class="post_bid_btn"><?php _e('Pay by Payza','AuctionTheme'); ?></a>

                <?php
				endif;
				?>

                <?php do_action('AuctionTheme_membership_payment_links_act'); ?>
                </p>

                </div>
                </div>
                </div>

	<?php AuctionTheme_get_users_links(); ?>

<?php get_footer(); ?>
