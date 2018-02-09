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
	$ids = $wp_query->query_vars['ids'];

	$ss = "select * from ".$wpdb->prefix."auction_offers where id='$ids'";
	$rr = $wpdb->get_results($ss);
	$rows = $rr[0];

	global $current_user;
		$current_user = wp_get_current_user();
	$uid 	= $current_user->ID;
	$tm 	= current_time('timestamp',0);
	$post_auction = get_post($pid);


	if(isset($_POST['yes']))
	{
		$offered_price = $_POST['offered_price'];

		if(empty($offered_price) or !is_numeric($offered_price))
		{
				$quant_is_to_small = 1;
		}
		else
		{

			if($rows->counteroffer_sent == 0)
			{
				$s = "update ".$wpdb->prefix."auction_offers set counteroffer_sent='1', counteroffer_price='$offered_price', counteroffer_sent_datemade='$tm' where id='$ids'";
				$wpdb->query($s);

				//AuctionTheme_send_email_offer_received($pid, $offered_price, $uid);
				AuctionTheme_send_email_counter_offer_received($pid, $offered_price, $rows->uid);
			}


			if(AuctionTheme_using_permalinks())
			wp_redirect(get_permalink($pid) . "/?offer_posted=1");
			else
			wp_redirect(get_permalink($pid) . "&offer_posted=1");

		}
	}
	if(isset($_POST['no']))
	{
		wp_redirect(get_permalink($pid));
		exit;
	}

//==========================

get_header();

	$AuctionTheme_currency_position = get_option('AuctionTheme_currency_position');

?>
<div class="clear10"></div>


			<div class="my_box3 breadcrumb-wrap">

            	<div class="box_title"><?php echo sprintf(__("Make a Counter Offer: %s",'AuctionTheme'), $post_auction->post_title); ?></div>
                <div class="box_content">
               <?php

			   if($quant_is_to_small == 1)
			   {
					echo '<div class="error">' . __('Please input a proper price for your offer.','AuctionTheme') . '</div> <div class="clear10"></div>';
			   }

			   $prc = auctiontheme_get_show_price($rows->price);
			   echo sprintf(__("The initial offer was <b>%s</b>. You are about to make a counter offer for this item: <b>%s</b>",'AuctionTheme'), $prc, $post_auction->post_title);

			   ?>
               <div class="clear10"></div>

               <form method="post" enctype="application/x-www-form-urlencoded">
               <?php _e("Counter Offer Price:","AuctionTheme"); ?>

               <?php if($AuctionTheme_currency_position == "front") echo auctiontheme_get_currency(); ?>
               <input type="text" size="5" name="offered_price" value="<?php if(isset($_POST['offered_price'])) echo $POST['offered_price']; else echo $_GET['offered_price']; ?>" />
               <?php if($AuctionTheme_currency_position != "front") echo auctiontheme_get_currency(); ?>

                <br/><br/>
               <input type="submit" name="yes" value="<?php _e("Yes, Confirm Offer",'AuctionTheme'); ?>" />
               <input type="submit" name="no"  value="<?php _e("No",'AuctionTheme'); ?>" />

               </form>
    </div>
			</div>



        <div class="clear10"></div>


<?php

get_footer();

?>
