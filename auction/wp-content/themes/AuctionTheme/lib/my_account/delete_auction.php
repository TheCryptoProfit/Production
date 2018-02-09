<?php

	 $current_user = wp_get_current_user();
	global  $wp_query;
	$pid 	=  $wp_query->query_vars['pid'];

	global $post_au;
	$post_au = get_post($pid);

		if(auctionTheme_number_of_bid_see_and_buy_now($pid) != false) { $mms = 1; }
	else {

	if(	get_option('AuctionTheme_enable_editing_when_bid_placed') == "yes")
	{

	} else { wp_redirect(get_bloginfo('siteurl')); die(); } }



	function AuctionTheme_filter_ttl($title){ global $post_au; return __("Delete Auction",'AuctionTheme')." - ".$post_au->post_title;}
	add_filter( 'wp_title', 'AuctionTheme_filter_ttl', 10, 3 );

	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }


	$current_user = wp_get_current_user();

	$uid 	= $current_user->ID;
	$title 	= $post_au->post_title;
	$cid 	= $current_user->ID;

	if($uid != $post_au->post_author) { echo 'Not your post. Sorry!'; exit; }

	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

			global $wpdb,$wp_rewrite,$wp_query;

			if(isset($_POST['no_confirm']))
				{
						$acc = auctionTheme_my_account_link();
						wp_redirect($acc);
						exit;
				}

//-------------------------------------

	get_header();

	$post_au = get_post($pid);

?>


	<div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">

            <div class="my_box3">

            	<div class="box_title"><?php printf(__("Delete Auction - %s", "AuctionTheme"), $post_au->post_title); ?></div>
                <div class="box_content">

                <?php

				if(isset($_POST['yes_confirm']))
				{
					$acc = auctionTheme_my_account_link();
					echo '<div class="deleted_item_ok">';
					printf(__('Your item has been deleted. <a href="%s">Return to your account</a>.','AuctionTheme'), $acc);
					echo '</div>';
					wp_delete_post($pid);

					//wp_redirect($acc);
				}
				else
				{

		?>


                <form method="post">
                <div class="are_you_sure_delete">
        		<?php

				_e('Are you sure you want to delete this item?','AuctionTheme');


				?>

                </div>

                <input type="submit" value="<?php _e('Yes','AuctionTheme'); ?>" name="yes_confirm" />
                 <input type="submit" value="<?php _e('No','AuctionTheme'); ?>" name="no_confirm" />


                </form>

                <?php } ?>
                </div>
                </div>
                </div>

	<?php AuctionTheme_get_users_links(); ?>

<?php get_footer(); ?>
