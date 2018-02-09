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
		if($row->approved == 0 and $row->rejected == 0)
		{
		//-----------------------------
		$quant_taken = 1;
		$bid = $row->counteroffer_price;
		$uid = $row->uid;
		$tm = current_time('timestamp',0);

		$quant_tk = get_post_meta($pid, "quant_tk_".$uid ,true);
		if(empty($quant_tk)) update_post_meta($pid, "quant_tk_".$uid, $quant_taken);
		else update_post_meta($pid, "quant_tk_".$uid, $quant_tk + $quant_taken);


		$quant = get_post_meta($pid, 'quant',true);
		$skk = $quant;

		$quant 			= apply_filters('AuctionTheme_filter_quant_to_be_updated', 		$quant, $pid);
		update_post_meta($pid, 'quant',$quant-1);

		//-----------------

		$quant 			= apply_filters('AuctionTheme_filter_quant_to_be_checked', 		$quant, $pid);
		if($quant == 1) {

			update_post_meta($pid, 'closed','1');
			update_post_meta($pid, 'closed_date',$tm);

		}


		$query = "insert into ".$wpdb->prefix."auction_bids (quant, bid, uid, pid, date_made, winner, date_choosen) values('$quant_taken','$bid','$uid','$pid','$tm','1', '$tm')";
		$wpdb->query($query);


		$query 		= "select * from ".$wpdb->prefix."auction_bids where uid='$uid' AND pid='$pid' AND date_made='$tm'";
		$res 		= $wpdb->get_results($query);
		$winner_bid = $res[0]->id;

		do_action('AuctionTheme_when_bidding_is_executed_before_payment', $winner_bid);

		//-----------------------


		$prepare_ratings = 1;
		$prepare_ratings = apply_filters('AuctionTheme_filter_prepare_ratings_buy_now', 		$prepare_ratings, $pid);

		if($prepare_ratings == 1):
			auctionTheme_prepare_rating($pid, $uid, $post_auction->post_author, $winner_bid);
			auctionTheme_prepare_rating($pid, $post_auction->post_author, $uid, $winner_bid);
		endif;


					//-----email to the other lower bidders-----

					$quant = apply_filters('AuctionTheme_filter_recheck_count_again', 		$quant, $pid);
					$only_buy_now = get_post_meta($pid, 'only_buy_now', true);
					if($quant == 0 && $only_buy_now != "1")
					{

						$s = "select distinct uid from ".$wpdb->prefix."auction_bids where uid!='$uid' and pid='$pid'";
						$r = $wpdb->get_results($s);

						foreach($r as $rowk)
						{
							AuctionTheme_send_email_on_win_to_loser($pid, $rowk->uid);
						}

						AuctionTheme_send_email_on_win_to_owner($pid, $uid, $winner_bid);
						AuctionTheme_send_email_on_win_to_bidder($pid, $uid, $winner_bid);
					}
					else
					{
						AuctionTheme_send_email_on_buy_now_auction_seller($pid, $uid);
						AuctionTheme_send_email_on_buy_now_auction_buyer($pid, $uid);

					}
					//----------

		add_post_meta($pid, 	'winner',		$uid);
		update_post_meta($pid, 	'paid_user',	"0");
		update_post_meta($pid, 	'paid_user_'.$uid.'_'.$winner_bid,	"0");
		add_post_meta($pid, 	'bid',			$uid);

		//-----------------------------
 			AuctionTheme_send_email_counter_offer_accepted($pid, $row->counteroffer_price, $row->uid);
		//-------------------------------

		$wpdb->query("update ".$wpdb->prefix."auction_offers set approved='1',counteroffer_accepted='1', counteroffer_answered_datemade='$tm' where id='$ids'");
		}

		if(AuctionTheme_using_permalinks())
		wp_redirect(get_permalink($pid) . "/?accepted_offer=1");
		else
		wp_redirect(get_permalink($pid) . "&accepted_offer=1");

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
            	<div class="padd10">

            	<div class="box_title"><?php echo sprintf(__("Accept Offer: %s",'AuctionTheme'), $post_auction->post_title); ?></div>
                <div class="box_content">
               <?php

			   echo sprintf(__("You are about to accept the offer of %s for the item <b>%s</b>",'AuctionTheme'), $price, $post_auction->post_title);

			   ?>
               <div class="clear10"></div>

               <form method="post" enctype="application/x-www-form-urlencoded">
               <input type="submit" name="yes" value="<?php _e("Yes, Accept Offer",'AuctionTheme'); ?>" />
               <input type="submit" name="no"  value="<?php _e("No",'AuctionTheme'); ?>" />

               </form>
    </div>
			</div>
			</div>


        <div class="clear10"></div>


<?php

get_footer();

?>
