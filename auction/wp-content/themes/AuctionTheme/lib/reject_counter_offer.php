<?php
if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }
//-----------

	add_filter('sitemile_before_footer', 'auctionTheme_my_account_before_footer');
	function auctionTheme_my_account_before_footer()
	{
		echo '<div class="clear10"></div>';
	}

	//----------

	global $wpdb,$wp_rewrite,$wp_query;
	$pid = $wp_query->query_vars['pid'];



	global $current_user;
		$current_user = wp_get_current_user();
	$uid 	= $current_user->ID;
	$tm 	= current_time('timestamp',0);
	$post_auction = get_post($pid);


	$ids 	= $_GET['ids'];
	$ss 	= "select * from ".$wpdb->prefix."auction_offers where id='$ids'";
	$rr 	= $wpdb->get_results($ss);
	$row 	= $rr[0];

	if($row->uid != $uid) { wp_redirect(get_bloginfo('siteurl')); exit; }

	//-------------------------------------------------------------------------

	if(isset($_POST['yes']))
	{
		if($row->counteroffer_rejected == 0 and $row->counteroffer_accepted == 0)
		{

			//AuctionTheme_send_email_offer_rejected($pid, $row->price, $row->uid);
			AuctionTheme_send_email_counter_offer_rejected($pid, $row->counteroffer_price, $row->uid);
			$wpdb->query("update ".$wpdb->prefix."auction_offers set counteroffer_rejected='1', counteroffer_answered_datemade='$tm' where id='$ids'");
		}

		if(AuctionTheme_using_permalinks())
		wp_redirect(get_permalink($pid) . "/?rejected_offer=1");
		else
		wp_redirect(get_permalink($pid) . "&rejected_offer=1");

	}
	if(isset($_POST['no']))
	{
		wp_redirect(get_permalink($pid));
		exit;
	}

//==========================



get_header();

	$AuctionTheme_currency_position = get_option('AuctionTheme_currency_position');
	$price = auctiontheme_get_show_price($row->counteroffer_price);

?>
<div class="clear10"></div>


			<div class="my_box3 breadcrumb-wrap">

            	<div class="box_title"><?php echo sprintf(__("Reject Offer: %s",'AuctionTheme'), $post_auction->post_title); ?></div>
                <div class="box_content">
               <?php

			   echo sprintf(__("You are about to reject the counter offer of %s for the item <b>%s</b>",'AuctionTheme'), $price, $post_auction->post_title);

			   ?>
               <div class="clear10"></div>

               <form method="post" enctype="application/x-www-form-urlencoded">
               <input type="submit" name="yes" value="<?php _e("Yes, Reject Offer",'AuctionTheme'); ?>" />
               <input type="submit" name="no"  value="<?php _e("No",'AuctionTheme'); ?>" />

               </form>
    </div>
			</div>



        <div class="clear10"></div>


<?php

get_footer();

?>
