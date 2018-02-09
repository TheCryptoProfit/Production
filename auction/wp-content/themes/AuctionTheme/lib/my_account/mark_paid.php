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
*	since v4.5.5
*
***************************************************************************/

if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }
//-----------

	add_filter('sitemile_before_footer', 'AuctionTheme_my_account_before_footer');
	function AuctionTheme_my_account_before_footer()
	{
		echo '<div class="clear10"></div>';
	}

	//----------

	global $wpdb,$wp_rewrite,$wp_query;
	$bid_id = $wp_query->query_vars['bid_id'];
	$bid 	= auctionTheme_get_winner_bid($bid_id);

	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$post_pr = get_post($bid->pid);

	//--------print_r($-------------------

	if(isset($_POST['yes']))
	{
		$tm = current_time('timestamp',0);

		$s = "update ".$wpdb->prefix."auction_bids set paid='1', shipped_on='$tm' where id='$bid_id'";
		$wpdb->query($s);

		//notify the buyer

		AuctionTheme_send_email_when_item_is_paid_seller($bid->pid, $bid_id);
		AuctionTheme_send_email_when_item_is_paid_buyer($bid->pid, $bid_id);

		wp_redirect(get_permalink(get_option('AuctionTheme_my_account_aw_pay_auctions_id')));
		exit;
	}

	if(isset($_POST['no']))
	{
		wp_redirect(get_permalink(get_option('AuctionTheme_my_account_aw_pay_auctions_id')));
		exit;
	}



	//---------------------------------

	get_header();

?>
<div class="clear10"></div>


			<div class="my_box3 breadcrumb-wrap">

            	<div class="box_title"><?php  printf(__("Mark the item paid: %s",'AuctionTheme'), $post_pr->post_title); ?></div>
                <div class="padd10">
               <?php

			   printf(__("You are about to mark this item as paid: %s",'AuctionTheme'), $post_pr->post_title); echo '<br/>';
			  _e("The buyer will be notified by email about this item.",'AuctionTheme') ;

			   ?>

                <div class="clear10"></div>

               <form method="post"  >

               <input type="submit" name="yes" value="<?php _e("Yes, Mark Paid!",'AuctionTheme'); ?>" />
               <input type="submit" name="no"  value="<?php _e("No",'AuctionTheme'); ?>" />

               </form>
    </div>
			</div>


        <div class="clear100"></div>


<?php

get_footer();

?>
