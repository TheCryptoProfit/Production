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


	if(isset($_POST['yes']))
	{
		$offered_price = abs($_POST['offered_price']);
		if(empty($offered_price) or !is_numeric($offered_price))
		{
			$quant_is_to_small = 1;
		}
		elseif($offered_price < 0)
		{

			$quant_is_to_small = 1;
		}
		else
		{
			$s = "insert into ".$wpdb->prefix."auction_offers (uid,pid,datemade,price) values('$uid','$pid','$tm','$offered_price')";
			$wpdb->query($s);

			AuctionTheme_send_email_offer_received($pid, $offered_price, $uid);

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

            	<div class="box_title"><?php echo sprintf(__("Make an Offer: %s",'AuctionTheme'), $post_auction->post_title); ?></div>
                <div class="box_content">
               <?php

			   if($quant_is_to_small == 1)
			   {
					echo '<div class="error">' . __('Please input a proper price for your offer.','AuctionTheme') . '</div> <div class="clear10"></div>';
			   }

			   echo sprintf(__("You are about to make an offer for this item: %s",'AuctionTheme'), $post_auction->post_title);

			   ?>
               <div class="clear10"></div>

               <form method="post" enctype="application/x-www-form-urlencoded">
               <?php _e("Offered Price","AuctionTheme"); ?>:

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
