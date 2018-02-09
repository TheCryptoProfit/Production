<?php
	$opt = get_option('AuctionTheme_new_emails_453_2_master');
	if(empty($opt)):
		
		update_option('AuctionTheme_new_emails_453_2_master', 'DonE');
		
		$opt = get_option('AuctionTheme_new_emails_453_2_slave');
		if(empty($opt)):
			
			update_option('AuctionTheme_new_emails_453_2_slave', 'DonE');
			//AuctionTheme_winthdrawal_rejected_subject
			
			
			update_option('AuctionTheme_winthdrawal_rejected_subject', 'Your withdrawal request was rejected ##withdrawal_amount##');
			update_option('AuctionTheme_winthdrawal_rejected_message', 'Hello ##seller_user##,'.PHP_EOL.PHP_EOL.
																'Your withdrawal request was rejected:'.PHP_EOL.
																'Withdrawal Amount: ##withdrawal_amount##'.PHP_EOL.PHP_EOL.

																'Login ro your account: ##my_account_url##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
																
																
			update_option('AuctionTheme_winthdrawal_accepted_subject', 'Your withdrawal request was processed ##withdrawal_amount##');
			update_option('AuctionTheme_winthdrawal_accepted_message', 'Hello ##seller_user##,'.PHP_EOL.PHP_EOL.
																'Your withdrawal request was processed:'.PHP_EOL.
																'Withdrawal Amount: ##withdrawal_amount##'.PHP_EOL.PHP_EOL.

																'Login ro your account: ##my_account_url##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');													
		
		
		endif;
		
	//-------------------------------------------
	
	$opt = get_option('AuctionTheme_new_emails_46akas_asd');
	if(empty($opt)):
		
		AuctionTheme_insert_pages('AuctionTheme_my_account_offers_seller_id', 		'Received Offers', 		 '[auction_theme_my_account_seller_offers]',		get_option('AuctionTheme_my_account_page_id') );
		AuctionTheme_insert_pages('AuctionTheme_my_account_offers_buyer_id', 		'Submitted Offers', 		 '[auction_theme_my_account_buyer_offers]',		get_option('AuctionTheme_my_account_page_id') );
		
		
		
		update_option('AuctionTheme_no_winner_owner_email_subject', 'Not sold item ##item_name##');
		update_option('AuctionTheme_no_winner_owner_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																'The auctioned item: ##item_name## was not sold on our website. See the details below:'.PHP_EOL.
																'Item Name: ##item_name##'.PHP_EOL.
																'Item Link: ##item_link##'.PHP_EOL.PHP_EOL.

																'Login ro your account: ##my_account_url##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
		
		//------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_new_emails_46akas_asd', 'DonE');
		
		update_option('AuctionTheme_offer_received_email_subject', 'Offer Received for ##item_name##');
		update_option('AuctionTheme_offer_received_email_message', 'Hello ##seller_username##,'.PHP_EOL.PHP_EOL.
																'The user ##buyer_username## has submitted an offer for your item: ##item_name##. See below the details:'.PHP_EOL.
																'Offered Price: ##offer_price##'.PHP_EOL.
																'Potential Buyer: ##buyer_username##'.PHP_EOL.
																'Go and accept or reject the offer from here: ##item_link##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
																
		//--------------------------------------------------------------------------------------	
		
		update_option('AuctionTheme_offer_accepted_email_subject', 'Offer Accepted for ##item_name##');
		update_option('AuctionTheme_offer_accepted_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																'The seller of the item: ##item_name## has accepted your offer. See the details below:'.PHP_EOL.
																'Offered Price: ##offer_price##'.PHP_EOL.
																'Item Link: ##item_link##'.PHP_EOL.PHP_EOL.

																'Login ro your account: ##my_account_url##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
																
		//--------------------------------------------------------------------------------------	
		
		update_option('AuctionTheme_offer_rejected_email_subject', 'Offer Rejected for ##item_name##');
		update_option('AuctionTheme_offer_rejected_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																'The seller of the item: ##item_name## has rejected your offer. See the details below:'.PHP_EOL.
																'Offered Price: ##offer_price##'.PHP_EOL.
																'Item Link: ##item_link##'.PHP_EOL.PHP_EOL.

																'Login ro your account: ##my_account_url##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
																
																
		//--------------------------------------------------------------------------------------	
		
		update_option('AuctionTheme_counter_offer_received_email_subject', 'Counter Offer Received for ##item_name##');
		update_option('AuctionTheme_counter_offer_received_email_message', 'Hello ##buyer_username##,'.PHP_EOL.PHP_EOL.
																'The seller: ##seller_username## has submitted a counter offer for the item: ##item_name##. See below the details:'.PHP_EOL.
																'Offered Price: ##offer_price##'.PHP_EOL.
																'Seller: ##seller_username##'.PHP_EOL.
																'Go and accept or reject the offer from here: ##item_link##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
																													
		
		//--------------------------------------------------------------------------------------	
		
		update_option('AuctionTheme_counter_offer_rejected_email_subject', 'Counter Offer Rejected for ##item_name##');
		update_option('AuctionTheme_counter_offer_rejected_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																'The porential buyer for your item: ##item_name## has rejected your counter offer. See the details below:'.PHP_EOL.
																'Offered Price: ##offer_price##'.PHP_EOL.
																'Item Link: ##item_link##'.PHP_EOL.PHP_EOL.

																'Login ro your account: ##my_account_url##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
				//--------------------------------------------------------------------------------------	
		
		update_option('AuctionTheme_counter_offer_accepted_email_subject', 'Counter Offer Accepted for ##item_name##');
		update_option('AuctionTheme_counter_offer_accepted_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																'The buyer for your item: ##item_name## has accepted your counter offer. See the details below:'.PHP_EOL.
																'Offered Price: ##offer_price##'.PHP_EOL.
																'Item Link: ##item_link##'.PHP_EOL.PHP_EOL.

																'Login ro your account: ##my_account_url##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
															
		
	endif;	


	$opt = get_option('AuctionTheme_new_emails_453_2');
	if(empty($opt)):
		
		update_option('AuctionTheme_new_emails_453_2', 'DonE');
		
		update_option('AuctionTheme_winthdrawal_request_user_subject', 'Withdraw requested: ##seller_user##');
		update_option('AuctionTheme_winthdrawal_request_user_message', 'Hello ##seller_user##,'.PHP_EOL.PHP_EOL.
																					'Your withdrawal request has been received.'.PHP_EOL.
																					'Requested Amount: ##withdrawal_amount##'.PHP_EOL.PHP_EOL.		
																					'Visit your account area: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																					'Thank you,'.PHP_EOL.
																					'##your_site_name## Team');
		
		//-------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_winthdrawal_request_admin_subject', 'Withdraw requested: ##seller_user##');
		update_option('AuctionTheme_winthdrawal_request_admin_message', 'Hello admin,'.PHP_EOL.PHP_EOL.
																'the user ##seller_user## has requested a withdrawal from his account.'.PHP_EOL.
																'The amount: ##withdrawal_amount##'.PHP_EOL.PHP_EOL.																
																'you can process this withdrawal request and after mark it as done from your admin account in your website ##your_site_url##'.PHP_EOL.PHP_EOL.																
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
		
		//------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_new_user_email_subject', 'Welcome to ##your_site_name##');
		update_option('AuctionTheme_new_user_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																'Welcome to our website.'.PHP_EOL.
																'Your username: ##username##'.PHP_EOL.
																'Your password: ##user_password##'.PHP_EOL.PHP_EOL.
																'Login here: ##site_login_url##'.PHP_EOL.PHP_EOL.
																'Thank you,'.PHP_EOL.
																'##your_site_name## Team');
	
		//-----------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_new_user_email_admin_subject', 'New user registration on your site');
		update_option('AuctionTheme_new_user_email_admin_message', 'Hello admin,'.PHP_EOL.PHP_EOL.
																	'A new user has been registered on your website.'.PHP_EOL.
																	'See the details below:'.PHP_EOL.PHP_EOL.
																	'Username: ##username##'.PHP_EOL.
																	'Email: ##user_email##');
																	
		//------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_new_item_email_not_approve_admin_subject', 'New item posted: ##item_name##');
		update_option('AuctionTheme_new_item_email_not_approve_admin_message', 'Hello admin,'.PHP_EOL.PHP_EOL.
																				'The user ##username## has posted a new item on your website.'.PHP_EOL.
																				'The item name: [##item_name##]'.PHP_EOL.
																				'The item was automatically approved on your website.'.PHP_EOL.PHP_EOL.																				
																				'View the item here: ##item_link##'.PHP_EOL.PHP_EOL.																				
																				'Thank you,'.PHP_EOL.
																				'##your_site_name## Team');
		
		//------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_new_item_email_approve_admin_subject', 'New item posted. Must approve ##item_name##');
		update_option('AuctionTheme_new_item_email_approve_admin_message', 'Hello admin,'.PHP_EOL.PHP_EOL.
																			'The user ##username## has posted a new item on your website.'.PHP_EOL.
																			'The item name: <b>##item_name##</b> '.PHP_EOL.
																			'The item is not automatically approved so you have to manually approve the item before it appears on your website.'.PHP_EOL.PHP_EOL.																			
																			'You can approve the item by going to your admin dashboard in your website'.PHP_EOL.
																			'Go here: ##your_site_url##/wp-admin');
		
		//------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_new_item_email_not_approved_subject', 'Your new item posted, but not yet approved: ##item_name##');
		update_option('AuctionTheme_new_item_email_not_approved_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.																			
																			'Your item <b>##item_name##</b> has been posted on the website. However it is not live yet.'.PHP_EOL.
																			'The item needs to be approved by the admin before it goes live. '.PHP_EOL.
																			'You will be notified by email when the item is active and published.'.PHP_EOL.PHP_EOL.																			
																			'After is approved the item will appear here: ##item_link##'.PHP_EOL.PHP_EOL.																			
																			'Thank you,'.PHP_EOL.
																			'##your_site_name## Team');
																			
		//--------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_new_item_email_approved_subject', 'Your new item posted, and approved: ##item_name##');
		update_option('AuctionTheme_new_item_email_approved_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																				'Your item <b>##item_name##</b> has been posted on the website.'.PHP_EOL.
																				'The item is live and you can see it here: ##item_link##'.PHP_EOL.
																				'Also you can check your item offers here: ##my_account_url##'.PHP_EOL.PHP_EOL.																				
																				'Thank you,'.PHP_EOL.
																				'##your_site_name## Team');
																				
		//--------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_bid_item_owner_email_subject', 'Your have received a new bid to your item: ##item_name##');
		update_option('AuctionTheme_bid_item_owner_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																				'You have received a new bid to your item <a href="##item_link##"><b>##item_name##</b></a>.'.PHP_EOL.
																				'See your bid details below:'.PHP_EOL.PHP_EOL.
																				'Bidder Username: ##bidder_username##'.PHP_EOL.
																				'Bid Value: ##bid_value##'.PHP_EOL.PHP_EOL.																				
																				'Thank you,'.PHP_EOL.
																				'##your_site_name## Team');
		
		//--------------------------------------------------------------------------------------------------------------
		
		
		update_option('AuctionTheme_bid_item_bidder_email_subject', 'Your has been posted to the item: ##item_name##');
		update_option('AuctionTheme_bid_item_bidder_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																				'You posted a new bid to the item <a href="##item_link##"><b>##item_name##</b></a>.'.PHP_EOL.
																				'See your bid details below:'.PHP_EOL.PHP_EOL.
																				'Item Link: ##item_link##'.PHP_EOL.
																				'Bid Value: ##bid_value##'.PHP_EOL.PHP_EOL.																				
																				'Thank you,'.PHP_EOL.
																				'##your_site_name## Team');
		
		//--------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_won_item_loser_email_subject', 'The item: ##item_name## has ended. You did not win.');
		update_option('AuctionTheme_won_item_loser_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																				'The item: <a href="##item_link##"><b>##item_name##</b></a> has ended.'.PHP_EOL.
																				'Sorry, you did not win. See won item details below:'.PHP_EOL.PHP_EOL.
																				'Item Link: ##item_link##'.PHP_EOL.
																				'Winner Bid Value: ##winner_bid_value##'.PHP_EOL.
																				'Winner Username: ##winner_bid_username##'.PHP_EOL.
																				'Your bid on this item: ##user_bid_value##'.PHP_EOL.PHP_EOL.																				
																				'Thank you,'.PHP_EOL.
																				'##your_site_name## Team');
		
		//--------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_won_item_winner_email_subject', 'The item: ##item_name## has ended. You just won it.');
		update_option('AuctionTheme_won_item_winner_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																				'The item: <a href="##item_link##"><b>##item_name##</b></a> has ended.'.PHP_EOL.
																				'You just wont it. See won item details below:'.PHP_EOL.PHP_EOL.
																				'Item Link: ##item_link##'.PHP_EOL.
																				'Winner Bid Value: ##winner_bid_value##'.PHP_EOL.PHP_EOL.
																																						
																				'Thank you,'.PHP_EOL.
																				'##your_site_name## Team');
		
		//--------------------------------------------------------------------------------------------------------------
		
		update_option('AuctionTheme_won_item_owner_email_subject', 'Your have selected a winner for your item: ##item_name##.');
		update_option('AuctionTheme_won_item_owner_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																				'Your item: <a href="##item_link##"><b>##item_name##</b></a> 
																				has ended.'.PHP_EOL.
																				'You just selected a winner for it. 
																				See won item details below:'.PHP_EOL.PHP_EOL.
																				'Item Link: ##item_link##'.PHP_EOL.
																				'Winner Bidder Username: ##winner_bid_username##'.PHP_EOL.
																				'Winner Bid Value: ##winner_bid_value##'.PHP_EOL.PHP_EOL.
																																						
																				'Thank you,'.PHP_EOL.
																				'##your_site_name## Team');

																				
		//--------------------------------------------------------------------------------------------------------------																	
		
		update_option('AuctionTheme_priv_mess_received_email_subject', 'Your have received a private message from user: ##sender_username##.');
		update_option('AuctionTheme_priv_mess_received_email_message', 'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.
																				'You have received a private message from <b>##sender_username##</b>'.PHP_EOL.
																				'To read it, just login to your account: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
																				'##your_site_name## Team');
		
		//--------------------------------------------------------------------------------------------------------------																	
																
		
		update_option('AuctionTheme_buy_now_auction_buyer_email_subject', 'You have just purchased: ##item_name##.');
		update_option('AuctionTheme_buy_now_auction_buyer_email_message', 'Hello ##buyer_user##,'.PHP_EOL.PHP_EOL.
																				'You have just purchased the item: <b>##item_name##</b> ( ##item_link## ).'.PHP_EOL.
																				'You need to login into your account area, under outstanding payments and pay the item price.'.PHP_EOL.
																				'Login here: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
		
																				'##your_site_name## Team');	
																				
																				
				//--------------------------------------------------------------------------------------------------------------																	
		
		update_option('AuctionTheme_buy_now_auction_seller_email_subject', 'Your item has been purchased: ##item_name##.');
		update_option('AuctionTheme_buy_now_auction_seller_email_message', 'Hello ##seller_user##,'.PHP_EOL.PHP_EOL.
																				'Your item: <b>##item_name##</b> ( ##item_link## ) has just been purchased by the user ##buyer_user##.'.PHP_EOL.
																				'You can track the payment status in your account area.'.PHP_EOL.
																				'Login here: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
		
																				'##your_site_name## Team');		
		
		//--------------------------------------------------------------------
																				
		update_option('AuctionTheme_paid_auction_buyer_email_subject', 'You have just paid for the item: ##item_name##.');
		update_option('AuctionTheme_paid_auction_buyer_email_message', 'Hello ##buyer_user##,'.PHP_EOL.PHP_EOL.
																				'You have just paid for the item : <b>##item_name##</b> ( ##item_link## ).'.PHP_EOL.
																				'You can track the shipping status(if available) of the item from your account area.'.PHP_EOL.
																				'Login here: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
		
																				'##your_site_name## Team');	
																				
		//--------------------------------------------------------------------
																				
		update_option('AuctionTheme_paid_auction_seller_email_subject', 'You have just received a payment for: ##item_name##.');
		update_option('AuctionTheme_paid_auction_seller_email_message', 'Hello ##seller_user##,'.PHP_EOL.PHP_EOL.
																				'You have just received a payment for the item: <b>##item_name##</b> ( ##item_link## ) from user ##buyer_user##.'.PHP_EOL.
																				'You mark the item as shipped(if needed) from your account area.'.PHP_EOL.
																				'Login here: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
		
																				'##your_site_name## Team');	
																				
		//-----------------------------------------------------------------------																																																																																																											
		
		update_option('AuctionTheme_ship_auction_buyer_email_subject', 'Your item has been dispatched: ##item_name##.');
		update_option('AuctionTheme_ship_auction_buyer_email_message', 'Hello ##buyer_user##,'.PHP_EOL.PHP_EOL.
																				'The user ##seller_user## has shipped your item: <b>##item_name##</b>.'.PHP_EOL.
																				'You can check the item status in your account or by communicating with the seller.'.PHP_EOL.
																				'Login here: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
		
																				'##your_site_name## Team');	
																				
		//-----------------------------------------------------------------------																																																																																																											
		
		update_option('AuctionTheme_ship_auction_seller_email_subject', 'You have marked an item as shipped: ##item_name##.');
		update_option('AuctionTheme_ship_auction_seller_email_message', 'Hello ##seller_user##,'.PHP_EOL.PHP_EOL.
																				'You have marked the item <b>##item_name##</b> as shipped for ##buyer_user##.'.PHP_EOL.
																				'You can check keep communicating with the seller from your account area.'.PHP_EOL.
																				'Login here: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
		
																				'##your_site_name## Team');	
		
		//-----------------------------------------------------------------------																																																																																																											
		
		update_option('AuctionTheme_review_to_award_email_subject', 'Please award a review for item: ##item_name##.');
		update_option('AuctionTheme_review_to_award_email_message', 'Hello ##awarding_user##,'.PHP_EOL.PHP_EOL.
																				'You have to award a review to the user ##rated_user## for the item <b>##item_name##</b>.'.PHP_EOL.
																				'You can do this from your account area.'.PHP_EOL.
																				'Login here: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
		
																				'##your_site_name## Team');	
		
		//-----------------------------------------------------------------------																																																																																																											
		
		update_option('AuctionTheme_review_received_email_subject', 'Please award a review for item: ##item_name##.');
		update_option('AuctionTheme_review_received_email_message', 'Hello ##rated_user##,'.PHP_EOL.PHP_EOL.
																				'You have just received a review from ##awarding_user## for the item <b>##item_name##</b>.'.PHP_EOL.
																				'You can do check the feedback in your account area.'.PHP_EOL.
																				'Login here: ##my_account_url##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
		
																				'##your_site_name## Team');	
																				
																																																																												
				//-----------------------------------------------------------------------																																																																																																											
		
		update_option('AuctionTheme_bid_item_outbid_email_subject', 'You have been outbid for item: ##item_name##.');
		update_option('AuctionTheme_bid_item_outbid_email_message', 'Hello ##username##,'.PHP_EOL.PHP_EOL.
																				'You have been outbid for the item <b>##item_name##</b> with the price of ##bid_value##.'.PHP_EOL.
																				'You can do check the the latest bids and post a new bid by following the next link.'.PHP_EOL.
																				'Check here: ##item_link##'.PHP_EOL.PHP_EOL.
																					
																				'Thank you,'.PHP_EOL.
		
																				'##your_site_name## Team');	
																				
	endif;
	endif;
	
?>