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
*	since v4.4.7.1
*
***************************************************************************/

global $pagenow;
if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) 
{

	global $wpdb;
	
	update_option('AuctionTheme_right_side_footer', '<a title="WordPress Auction Theme" href="http://sitemile.com/products/wordpress-auction-reverse-auction-theme">Wordpress Auction Theme</a>');
	update_option('AuctionTheme_left_side_footer', 'Copyright (c) '.get_bloginfo('name'));		
	
	update_option('AuctionTheme_currency',						'USD');
	update_option('AuctionTheme_currency_symbol',				'$');
	update_option('AuctionTheme_currency_position',				'front');
	update_option('AuctionTheme_decimal_sum_separator',			'.');
	update_option('AuctionTheme_thousands_sum_separator',		',');
	update_option('AuctionTheme_take_percent_fee',				'5');
	update_option('AuctionTheme_new_auction_listing_fee',		'0');
	update_option('AuctionTheme_new_auction_feat_listing_fee',	'0');
	
	update_option('AuctionTheme_auction_time_listing',			'30');
	update_option('AuctionTheme_auction_featured_time_listing',	'30');
	update_option('AuctionTheme_listings_per_page_adv_search',	'12');
	update_option('AuctionTheme_enable_locations',				'yes');
	update_option('AuctionTheme_enable_pay_credits',			'yes');
	update_option('AuctionTheme_new_auction_feat_listing_fee',	'0');
	update_option('AuctionTheme_new_auction_feat_listing_fee',	'0');
	update_option('AuctionTheme_email_name_from', 				get_bloginfo('name'));
	update_option('AuctionTheme_email_addr_from', 				get_bloginfo('admin_email'));
	update_option('AuctionTheme_allow_html_emails', 			"yes");
	update_option('AuctionTheme_show_main_menu', 				"yes");
	update_option('AuctionTheme_show_stretch', 					"yes");
	update_option('AuctionTheme_only_admins_post_auctions', 	"no");
	update_option('AuctionTheme_enable_reverse', 				"no");
	update_option('AuctionTheme_no_time_on_buy_now', 				"no");
	update_option('AuctionTheme_enable_html_description', 				"no");
	update_option('AuctionTheme_enable_multi_cats', 				"no");
	update_option('AuctionTheme_enable_editing_when_bid_placed', 				"no");
	update_option('AuctionTheme_no_time_on_buy_now', 				"no");
	update_option('AuctionTheme_admin_approve_auction', 				"no");
	
	update_option('AuctionTheme_automatic_bid_enable', 				"no");
	update_option('AuctionTheme_enable_increase_bid_limit', 				"no");
	update_option('AuctionTheme_last_min_bid_ext', 				"no");
	
	update_option('AuctionTheme_enable_membership', 				"no");
	
	
	
	//---------------------------------------------------------------------------------------
	
	AuctionTheme_insert_pages('AuctionTheme_all_locs_id', 						'Show All Locations', 			'[auction_theme_show_all_locations]' );
	AuctionTheme_insert_pages('AuctionTheme_all_cats_id', 						'Show All Categories', 			'[auction_theme_show_all_categories]' );
	
	AuctionTheme_insert_pages('AuctionTheme_blog_home_id', 						'Blog Posts', 				'[auction_theme_blog_posts]' );
	AuctionTheme_insert_pages('AuctionTheme_watch_list_id', 					'Watch List', 				'[auction_theme_watch_list]' );
	AuctionTheme_insert_pages('AuctionTheme_adv_search_id', 					'Advanced Search', 			'[auction_theme_adv_search]' );
	AuctionTheme_insert_pages('AuctionTheme_post_new_page_id', 					'Post New Auction', 		'[auction_theme_post_new]' );
	
	AuctionTheme_insert_pages('AuctionTheme_my_account_page_id', 				'My Account', 				'[auction_theme_my_account]' );
	AuctionTheme_insert_pages('AuctionTheme_my_account_personal_info_page_id', 	'Personal Information', 	'[auction_theme_my_account_personal_info]', get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_payments_page_id', 		'Payments', 				'[auction_theme_my_account_payments]', 		get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_priv_mess_page_id', 		'Private Messages', 		'[auction_theme_my_account_priv_mess]',		get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_reviews_page_id', 		'Reviews/Feedback', 		'[auction_theme_my_account_reviews]',		get_option('AuctionTheme_my_account_page_id') );
	
	//-----seller menu---------------------
	
	
	AuctionTheme_insert_pages('AuctionTheme_my_account_active_auctions_id', 		'Active Auctions', 			'[auction_theme_my_account_active_act]',	get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_sold_auctions_id', 			'Sold Items', 				'[auction_theme_my_account_sold_act]',		get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_unpub_auctions_id', 			'Unpublished Auctions', 	'[auction_theme_my_account_unpub_act]',		get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_closed_auctions_id', 		'Closed Auctions', 			'[auction_theme_my_account_closed_act]',	get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_aw_pay_auctions_id', 		'Awaiting Payment Items', 	'[auction_theme_my_account_aw_pay]',		get_option('AuctionTheme_my_account_page_id') );
	
	AuctionTheme_insert_pages('AuctionTheme_my_account_not_shipped_auctions_id', 	'Not Shipped Items', '[auction_theme_my_account_no_shipped]',		get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_shipped_auctions_id', 		'Shipped Items', 		 '[auction_theme_my_account_shipped]',		get_option('AuctionTheme_my_account_page_id') );

	//------- buyer menu --------------------
	
	AuctionTheme_insert_pages('AuctionTheme_my_account_won_items_id', 		'Won Items', 			'[auction_theme_my_account_won_items]',					get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_not_won_items_id', 	'Did not Win', 			'[auction_theme_my_account_not_won_items]',				get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_bid_items_id', 		'Items I Bid On', 		'[auction_theme_my_account_bid_items]',					get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_otstanding_pay_id', 	'Outstanding Payments', '[auction_theme_my_account_outstanding_payments]',		get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_paid_ship_id', 		'Paid & Shipped', 		'[auction_theme_my_account_paid_ship]',					get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_paid_id', 			'Paid Items', 			'[auction_theme_my_account_paid_]',						get_option('AuctionTheme_my_account_page_id') );
	
	AuctionTheme_insert_pages('AuctionTheme_my_account_pay_for_item_id', 	'Pay for Item', 		'[auction_theme_my_account_pay4item]',				get_option('AuctionTheme_my_account_page_id') );
	AuctionTheme_insert_pages('AuctionTheme_my_account_pay_item_cr_id', 	'Pay Item by Credits', 	'[auction_theme_my_account_pay_item_by_credits]',	get_option('AuctionTheme_my_account_page_id') );
	
	

	//----------------------------------------------------------------------------------
	// the ratings/feedback table
	
			$ss = " CREATE TABLE `".$wpdb->prefix."auction_ratings` (
					`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`pid` BIGINT NOT NULL ,
					`fromuser` BIGINT NOT NULL ,
					`touser` BIGINT NOT NULL ,
					`comment` TEXT NOT NULL ,
					`grade` TINYINT NOT NULL ,
					`datemade` BIGINT NOT NULL ,
					`awarded` TINYINT NOT NULL DEFAULT '0'
					) ENGINE = MYISAM ";			
			
			$wpdb->query($ss);
			
			$ss = "ALTER TABLE `".$wpdb->prefix."auction_ratings` ADD `bid_id` BIGINT NOT NULL DEFAULT '0';";
			$wpdb->query($ss);	
			
	//------------------------------------------------------------------------------------
			
			 $ss = " CREATE TABLE `".$wpdb->prefix."auction_coupons` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`coupon_name` VARCHAR( 255 ) NOT NULL ,
					`coupon_solid_reduction` VARCHAR( 255 ) NOT NULL,
					`coupon_percent_reduction` VARCHAR( 255 ) NOT NULL,
					
					`ending` VARCHAR( 255 ) NOT NULL,
					`coupon_code` VARCHAR( 255 ) NOT NULL ,
					`datemade` VARCHAR( 255 ) NOT NULL ,
					`featured_free` INT NOT NULL DEFAULT '0',
					`pause` INT NOT NULL DEFAULT '0'
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);
			 
			//--------------------- 
			 
			 $ss = " CREATE TABLE `".$wpdb->prefix."auction_watchlist` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`uid` BIGINT NOT NULL DEFAULT '0',
					`pid` BIGINT NOT NULL DEFAULT '0'
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);
			 
			
			//--------------------
			
			$ss = "CREATE TABLE `".$wpdb->prefix."auction_pm` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`owner` INT NOT NULL DEFAULT '0',
					`user` INT NOT NULL DEFAULT '0',
					`content` TEXT NOT NULL ,
					`subject` TEXT NOT NULL ,
					`rd` TINYINT NOT NULL DEFAULT '0',
					`parent` BIGINT NOT NULL DEFAULT '0',
					`pid` INT NOT NULL DEFAULT '0',
					`datemade` INT NOT NULL DEFAULT '0',
					`readdate` INT NOT NULL DEFAULT '0',
					`initiator` INT NOT NULL DEFAULT '0',
					`attached` INT NOT NULL DEFAULT '0'
					) ENGINE = MYISAM ;
					";
			$wpdb->query($ss);
			
			//---------------
			
			$ss = "CREATE TABLE `".$wpdb->prefix."auction_bids` (
			`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`date_made` BIGINT NOT NULL DEFAULT '0',
			`bid` DOUBLE NOT NULL DEFAULT '0',
			`pid` BIGINT NOT NULL DEFAULT '0',
			`uid` BIGINT NOT NULL DEFAULT '0',
			`winner` TINYINT NOT NULL DEFAULT '0',
			`paid` TINYINT NOT NULL DEFAULT '0',
			`reserved1` VARCHAR( 255 ) NOT NULL DEFAULT '0' ,
			`date_choosen` BIGINT NOT NULL DEFAULT '0'
			) ENGINE = MYISAM ";
			
			$wpdb->query($ss);
			
			
			$ss = "ALTER TABLE `".$wpdb->prefix."auction_bids` ADD  `shipped` TINYINT NOT NULL DEFAULT '0';";
			$wpdb->query($ss);
			
			$ss = "ALTER TABLE `".$wpdb->prefix."auction_bids` ADD  `shipped_on` BIGINT NOT NULL DEFAULT '0';";
			$wpdb->query($ss);
			
			$ss = "ALTER TABLE `".$wpdb->prefix."auction_bids` ADD  `quant` BIGINT NOT NULL DEFAULT '1';";
			$wpdb->query($ss);			
	 
			
			//----------------
			
			$ss = " CREATE TABLE `".$wpdb->prefix."auction_packs` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`pack_name` VARCHAR( 255 ) NOT NULL ,
					`auctions_number` INT NOT NULL ,
					`pack_cost` VARCHAR( 255 ) NOT NULL ,
					`datemauctione` VARCHAR( 255 ) NOT NULL ,
					`featured_free` INT NOT NULL DEFAULT '0',
					`pause` INT NOT NULL DEFAULT '0' 
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);
			 
			 
			 $ss = " CREATE TABLE `".$wpdb->prefix."auction_coupons` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`coupon_name` VARCHAR( 255 ) NOT NULL ,
					`coupon_solid_reduction` VARCHAR( 255 ) NOT NULL,
					`coupon_percent_reduction` VARCHAR( 255 ) NOT NULL,
					
					`ending` VARCHAR( 255 ) NOT NULL,
					`coupon_code` VARCHAR( 255 ) NOT NULL ,
					`datemauctione` VARCHAR( 255 ) NOT NULL ,
					`featured_free` INT NOT NULL DEFAULT '0',
					`pause` INT NOT NULL DEFAULT '0'
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);
			 
			//----------------------- 
			 
			 		$ss = " CREATE TABLE `".$wpdb->prefix."auction_custom_fields` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`name` VARCHAR( 255 ) NOT NULL ,
					`tp` VARCHAR( 48 ) NOT NULL ,
					`ordr` INT NOT NULL ,
					`cate` VARCHAR( 255 ) NOT NULL ,
					`pause` INT NOT NULL DEFAULT '1'
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);
			 
		//-------------------	 
			 $ss = " CREATE TABLE `".$wpdb->prefix."auction_custom_options` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`valval` VARCHAR( 255 ) NOT NULL ,
					`ordr` INT( 11 ) NOT NULL ,
					`custid` INT NOT NULL
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);
			 

			
		//----------------------------
		
			$ss = " CREATE TABLE `".$wpdb->prefix."auction_custom_relations` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`custid` BIGINT NOT NULL ,
					`catid` BIGINT NOT NULL
					) ENGINE = MYISAM ";
			$wpdb->query($ss);
		
		//-----------------------------
		
			$ss = " CREATE TABLE `".$wpdb->prefix."auction_transactions` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`pid` BIGINT NOT NULL ,
					`datemauctione` INT NOT NULL ,
					`uid` INT NOT NULL ,
					`payment_date` VARCHAR( 255 ) NOT NULL ,
					`txn_id` VARCHAR( 255 ) NOT NULL ,
					`item_name` VARCHAR( 255 ) NOT NULL ,
					`mc_currency` VARCHAR( 255 ) NOT NULL ,
					`last_name` VARCHAR( 255 ) NOT NULL ,
					`first_name` VARCHAR( 255 ) NOT NULL ,
					`payer_email` VARCHAR( 255 ) NOT NULL ,
					`auctiondress_country` VARCHAR( 255 ) NOT NULL ,
					`auctiondress_state` VARCHAR( 255 ) NOT NULL ,
					`auctiondress_country_code` VARCHAR( 255 ) NOT NULL ,
					`auctiondress_zip` VARCHAR( 255 ) NOT NULL ,
					`auctiondress_street` VARCHAR( 255 ) NOT NULL ,
					`mc_fee` VARCHAR( 255 ) NOT NULL ,
					`mc_gross` VARCHAR( 255 ) NOT NULL
					) ENGINE = MYISAM ";
			
			$wpdb->query($ss);
		//-------$wpdb->query---------------------
		
		 $ss = " CREATE TABLE `".$wpdb->prefix."auction_withdraw` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`datemade` INT NOT NULL ,
					`done` INT NOT NULL ,
					`datedone` INT NOT NULL ,
					`payeremail` VARCHAR( 255 ) NOT NULL ,
					`uid` INT NOT NULL ,
					`amount` DOUBLE NOT NULL
					) ENGINE = MYISAM ";
		$wpdb->query($ss);
		
		
		$ss = " CREATE TABLE `".$wpdb->prefix."auction_escrow` (
				`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`fromid` INT NOT NULL ,
				`toid` INT NOT NULL ,
				`pid` INT NOT NULL ,
				`amount` DOUBLE NOT NULL ,
				`datemade` INT NOT NULL ,
				`releasedate` INT NOT NULL ,
				`released` TINYINT( 0 ) NOT NULL
				) ENGINE = MYISAM ";	
		 $wpdb->query($ss);
		 
		 
		 
		 $ss = " CREATE TABLE `".$wpdb->prefix."auction_payment_transactions` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`uid` INT NOT NULL ,
					`reason` TEXT NOT NULL ,
					`datemade` INT NOT NULL ,
					`amount` DOUBLE NOT NULL ,
					`tp` TINYINT NOT NULL DEFAULT '1',
					`uid2` INT NOT NULL
					) ENGINE = MYISAM ";
		$wpdb->query($ss); 
		
		
		
		//---------------------
		
			$ss = " CREATE TABLE `".$wpdb->prefix."auction_custom_fields` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`name` VARCHAR( 255 ) NOT NULL ,
					`tp` VARCHAR( 48 ) NOT NULL ,
					`ordr` INT NOT NULL ,
					`cate` VARCHAR( 255 ) NOT NULL ,
					`pause` INT NOT NULL DEFAULT '1'
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);
		//-------------------
		
		//-------------------	 
			 $ss = " CREATE TABLE `".$wpdb->prefix."auction_custom_options` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`valval` VARCHAR( 255 ) NOT NULL ,
					`ordr` INT( 11 ) NOT NULL ,
					`custid` INT NOT NULL
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);
			 
		//----------------------------
		
			$ss = " CREATE TABLE `".$wpdb->prefix."auction_custom_relations` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`custid` BIGINT NOT NULL ,
					`catid` BIGINT NOT NULL
					) ENGINE = MYISAM ";
			$wpdb->query($ss);
			
			$ss = "CREATE TABLE `".$wpdb->prefix."auction_max_bids` (
			`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`date_made` BIGINT NOT NULL ,
			`bid` DOUBLE NOT NULL ,
			`pid` BIGINT NOT NULL ,
			`uid` BIGINT NOT NULL ,
			`reached` TINYINT NOT NULL DEFAULT '0',
			`date_reached` BIGINT NOT NULL
			) ENGINE = MYISAM ";		

			$wpdb->query($ss);	
			
			$ss = "ALTER TABLE `".$wpdb->prefix."auction_custom_fields` ADD  `description` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ;";
			$wpdb->query($ss);
			
		//---------------------------	
			
			$ss = "ALTER TABLE `".$wpdb->prefix."auction_pm` ADD  `show_to_source` TINYINT NOT NULL DEFAULT '1';";
			$wpdb->query($ss);
		
		//---------------------------	
			
			$ss = "ALTER TABLE `".$wpdb->prefix."auction_pm` ADD  `show_to_destination` TINYINT NOT NULL DEFAULT '1';";
			$wpdb->query($ss);	
			
		//---------------------------	
			
}
	
	$opt = get_option('AucationThemae_aupdate_me5000a1aaka');
	
	if(empty($opt)  or isset($_GET['sitemile_reset_theme']))
	{
		global $wpdb; 	$wpdb->show_errors = true;
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_escrow` ADD  `bid_id` BIGINT NOT NULL DEFAULT '0';";
		$wpdb->query($ss);	
		update_option('AucationThemae_aupdate_me5000a1aaka', 'done');
		
		AuctionTheme_insert_pages('AuctionTheme_ending_soonest_page_id', 					'Ending Soonest Auctions', 		'[auction_theme_ending_soonest_act]' );
		AuctionTheme_insert_pages('AuctionTheme_already_closed_page_id', 					'Closed Auctions', 				'[auction_theme_already_closed_act]' );
		
		
		
		AuctionTheme_insert_pages('AuctionTheme_my_account_offers_seller_id', 					'Received Offers', 				'[auction_theme_my_account_seller_offers]' );
		AuctionTheme_insert_pages('AuctionTheme_my_account_offers_buyer_id', 					'Submitted Offers', 				'[auction_theme_my_account_buyer_offers]' );
		
		
					$ss = "CREATE TABLE `".$wpdb->prefix."auction_membership_packs` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`membership_name` VARCHAR( 255 ) NOT NULL ,
					`membership_cost` VARCHAR( 255 ) NOT NULL ,
					`number_of_items` VARCHAR( 255 ) NOT NULL 
					) ENGINE = MYISAM ;
					";
			$wpdb->query($ss);
		
		
		
		$ss = " CREATE TABLE `".$wpdb->prefix."auction_commissions` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`low_range` VARCHAR( 255 ) NOT NULL ,
					`high_range` VARCHAR( 255 ) NOT NULL ,
					`commission` VARCHAR( 255 ) NOT NULL  					 
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);
		
		
		$wpdb->query("ALTER TABLE `".$wpdb->prefix."auction_withdraw` ADD `methods` VARCHAR( 255 ) NOT NULL ;");
		$wpdb->query("ALTER TABLE `".$wpdb->prefix."auction_max_bids` ADD `autobids` TINYINT NOT NULL DEFAULT '0' ;");
		
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_withdraw` CHANGE  `methods`  `methods` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL";
		$wpdb->query($ss);
		
		
		$ss = " CREATE TABLE `".$wpdb->prefix."auction_offers` (
					`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`pid` BIGINT NOT NULL ,
					`uid` BIGINT NOT NULL ,
					`price` VARCHAR( 255 ) NOT NULL ,
					`datemade` BIGINT NOT NULL ,
					`dateanswered` BIGINT NOT NULL ,
					`approved` TINYINT NOT NULL DEFAULT '0',
					`rejected` TINYINT NOT NULL DEFAULT '0'
					) ENGINE = MYISAM ";			
			
			$wpdb->query($ss);
			
		//---------------------------	
			
		$ss = " CREATE TABLE `".$wpdb->prefix."auction_next_bid_levels` (
					`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`low_value` VARCHAR( 255 ) NOT NULL ,
					`high_value` VARCHAR( 255 ) NOT NULL ,
					`increase_value` VARCHAR( 255 ) NOT NULL  
					) ENGINE = MYISAM ";			
			
			$wpdb->query($ss);	
		
		//-------------------------------
			
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_offers` ADD  `counteroffer_sent` TINYINT NOT NULL DEFAULT '0';";
		$wpdb->query($ss);
		
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_offers` ADD  `counteroffer_sent_datemade` BIGINT NOT NULL DEFAULT '0';";
		$wpdb->query($ss);	
		
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_offers` ADD  `counteroffer_accepted` TINYINT NOT NULL DEFAULT '0';";
		$wpdb->query($ss);	
		
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_offers` ADD  `counteroffer_rejected` TINYINT NOT NULL DEFAULT '0';";
		$wpdb->query($ss);	
		
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_offers` ADD  `counteroffer_answered_datemade` BIGINT NOT NULL DEFAULT '0';";
		$wpdb->query($ss);	
		
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_offers` ADD  `counteroffer_price` VARCHAR( 255 ) NOT NULL;";
		$wpdb->query($ss);	
		
		//------------------------------
		
		$ss = " CREATE TABLE `".$wpdb->prefix."auction_shipping_values` (
					`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`pid` VARCHAR( 255 ) NOT NULL ,
					`location_id` VARCHAR( 255 ) NOT NULL ,
					`shipping_charge` VARCHAR( 255 ) NOT NULL  
					) ENGINE = MYISAM ";			
			
			$wpdb->query($ss);	
		
		
		//------------------
		
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_bids` ADD  `paid_on` BIGINT NOT NULL DEFAULT '0';";
		$wpdb->query($ss);
		
		$ss = "ALTER TABLE `".$wpdb->prefix."auction_bids` ADD  `from_buy_now` TINYINT NOT NULL DEFAULT '0';";
		$wpdb->query($ss);
				
		
	}

?>