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

		wp_redirect(get_bloginfo('siteurl')."/?a_action=buy_now&pid=".$pid);
		exit;
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
                <div class="padd10">
               <?php


			   echo sprintf(__("You are about to buy this item and this represents a binding contract. Please agree and commit to pay for this item.",'AuctionTheme'));

			   ?>
               <div class="clear10"></div>

               <form method="post" enctype="application/x-www-form-urlencoded">

               <input type="submit" name="yes" value="<?php _e("Yes, Confirm Purchase and Agree!",'AuctionTheme'); ?>" />
               <input type="submit" name="no"  value="<?php _e("No",'AuctionTheme'); ?>" />

               </form>
    </div> </div>
			</div>



        <div class="clear100"></div>


<?php

get_footer();

?>
