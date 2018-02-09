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
	$uid = $current_user->ID;

	$post_auction = get_post($pid);


	if(isset($_POST['yes']))
	{
		$quant_taken = trim($_POST['quant']);
		if(!is_numeric($quant_taken)) $quant_taken = 1;
		if(empty($quant_taken)) $quant_taken = 1;
		if($quant_taken < 0) $quant_taken = 1;

		$quant_taken 			= apply_filters('AuctionTheme_filter_quant_taken', 		$quant_taken, $pid);

		//----------------------------------------------------


		$tm = current_time('timestamp',0);
		$quant = get_post_meta($pid, 'quant',true);
		$skk = $quant;


		if($quant < $quant_taken)
		{

			$quant_is_to_small = 1;

		}
		else
		{

		$quant 			= apply_filters('AuctionTheme_filter_quant_to_be_updated', 		$quant, $pid);

		update_post_meta($pid, 'quant',$quant - $quant_taken);

		//-----------------

		$quant 			= apply_filters('AuctionTheme_filter_quant_to_be_checked', 		$quant, $pid);
		if($quant == 1 or $quant == 0) {

			update_post_meta($pid, 'closed','1');
			update_post_meta($pid, 'closed_date',$tm);

		}

		//---------------------
		$bid = get_post_meta($pid, 'buy_now',true);

		$quant_tk = get_post_meta($pid, "quant_tk_".$uid ,true);
		if(empty($quant_tk)) update_post_meta($pid, "quant_tk_".$uid, $quant_taken);
		else update_post_meta($pid, "quant_tk_".$uid, $quant_tk + $quant_taken);

		//------------

		$query = "insert into ".$wpdb->prefix."auction_bids (from_buy_now, quant, bid, uid, pid, date_made, winner, date_choosen) values('1','$quant_taken','$bid','$uid','$pid','$tm','1', '$tm')";
		$wpdb->query($query);


		$query 		= "select * from ".$wpdb->prefix."auction_bids where uid='$uid' AND pid='$pid' AND date_made='$tm'";
		$res 		= $wpdb->get_results($query);
		$winner_bid = $res[0]->id;


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
						global $wpdb;
						$s = "select distinct uid from ".$wpdb->prefix."auction_bids where uid!='$uid' and pid='$pid'";
						$r = $wpdb->get_results($s);

						foreach($r as $row)
						{
							AuctionTheme_send_email_on_win_to_loser($pid, $row->uid, $winner_bid, $res[0]->bid, $row->bid);
						}

						AuctionTheme_send_email_on_win_to_owner($pid, $uid, $winner_bid);
						AuctionTheme_send_email_on_win_to_bidder($pid, $uid, $winner_bid);
					}
					else
					{
						AuctionTheme_send_email_on_buy_now_auction_seller($pid, $uid, $winner_bid);
						AuctionTheme_send_email_on_buy_now_auction_buyer($pid, $uid, $winner_bid);

					}
					//----------

		add_post_meta($pid, 	'winner',		$uid);
		update_post_meta($pid, 	'paid_user',	"0");
		update_post_meta($pid, 	'paid_user_'.$uid.'_'.$winner_bid,	"0");
		add_post_meta($pid, 	'bid',			$uid);

		do_action('AuctionTheme_when_bidding_is_executed_before_payment', $winner_bid);

		wp_redirect(auctionTheme_pay_for_item_link($winner_bid));
		exit;
		}
	}
	if(isset($_POST['no']))
	{
		wp_redirect(get_permalink($pid));
		exit;
	}

//==========================

get_header();

?>
<div class="clear10"></div>


			<div class="my_box3 breadcrumb-wrap">

            	<div class="box_title"><?php echo sprintf(__("Buy Now auction: %s",'AuctionTheme'), $post_auction->post_title); ?></div>
                <div class="box_content">
               <?php

			   if($quant_is_to_small == 1)
			   {
					echo '<div class="error">' . sprintf(__('The available quantity (%s) is lower than what you requested to buy.','AuctionTheme'), $skk) . '</div> <div class="clear10"></div>';
			   }

			   echo sprintf(__("You are about to buy now auction: %s",'AuctionTheme'), $post_auction->post_title);

			   ?>
               <div class="clear10"></div>

               <form method="post" enctype="application/x-www-form-urlencoded">
               <?php _e("Quantity","AuctionTheme"); ?>:

               <input type="text" size="5" name="quant" value="<?php echo $_GET['quant']; ?>" />

                <br/><br/>
               <input type="submit" name="yes" value="<?php _e("Yes, Buy Now!",'AuctionTheme'); ?>" />
               <input type="submit" name="no"  value="<?php _e("No",'AuctionTheme'); ?>" />

               </form>
    </div>
			</div>



        <div class="clear100"></div>


<?php

get_footer();

?>
