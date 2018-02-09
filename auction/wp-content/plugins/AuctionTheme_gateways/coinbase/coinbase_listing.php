<?php

add_filter('template_redirect','AT_template_redirect_coinbase');
add_filter('AuctionTheme_add_payment_options_to_post_new_project','AuctionTheme_add_payment_options_to_post_new_project_coinbase');

function AT_template_redirect_coinbase()
{
	if(isset($_GET['my_custom_button_callback_coinbase_listing']))
	{
		$order = $_POST['order']['custom'];
		$cst = get_option('coinbase_thing_' . $order);
		
		if(!empty($cst))
		{
	
			$cust 					= $cst;
			$cust 					= explode("|",$cust);
			
			$pid					= $cust[0];
			$uid					= $cust[1];
			$datemade 				= $cust[2];
			
			//--------------------------------------------
			
			update_post_meta($pid, "paid", 				"1");
			update_post_meta($pid, "closed", 			"0");
			
			//--------------------------------------------
			
			update_post_meta($pid, 'base_fee_paid', '1');
			
			$featured = get_post_meta($pid,'featured',true);	
			if($featured == "1") update_post_meta($pid, 'featured_paid', '1');
			
			$private_bids = get_post_meta($pid,'private_bids',true);	
			if($private_bids == "yes" or $private_bids == "1") update_post_meta($pid, 'private_bids_paid', '1');
			 
			//--------------------------------------------
			
			do_action('AuctionTheme_paypal_listing_response', $pid);
			
			$auctionTheme_admin_approves_each_project = get_option('auctionTheme_admin_approves_each_project');
			$paid_listing_date = get_post_meta($pid,'paid_listing_date',true);
			
			if(empty($paid_listing_date))
			{
				
				if($auctionTheme_admin_approves_each_project != "yes")
				{
					wp_publish_post( $pid );	
					$post_new_date = date('Y-m-d h:s',current_time('timestamp',0));  
					
					$post_info = array(  "ID" 	=> $pid,
					  "post_date" 				=> $post_new_date,
					  "post_date_gmt" 			=> $post_new_date,
					  "post_status" 			=> "publish"	);
					
					wp_update_post($post_info);
					
					AuctionTheme_send_email_posted_item_approved($pid);
					AuctionTheme_send_email_posted_item_not_approved_admin($pid);
					
				}
				else
				{ 
			
					AuctionTheme_send_email_posted_item_not_approved($pid);
					AuctionTheme_send_email_posted_item_approved_admin($pid);			
					//AuctionTheme_send_email_subscription($pid);	
					
				}
				
				update_post_meta($pid, "paid_listing_date", current_time('timestamp',0));
			}
		}
		
	}
}

function AuctionTheme_add_payment_options_to_post_new_project_coinbase($pid)
{
	$AuctionTheme_coinbase_enable = get_option('AuctionTheme_coinbase_enable');
	
	if($AuctionTheme_coinbase_enable == "yes")
	{
				 
				
		global $wp_query, $wpdb, $current_user;
	$pid = $wp_query->query_vars['pid'];
	get_currentuserinfo();
	$uid = $current_user->ID;
	$post = get_post($pid);
	
	//----------------------------
	
			$features_not_paid = array();		
			$catid = AuctionTheme_get_auction_primary_cat($pid);
			$AuctionTheme_get_images_cost_extra = AuctionTheme_get_images_cost_extra($pid);
			$payment_arr = array();
			
			//-----------------------------------
			
			$base_fee_paid 	= get_post_meta($pid, 'base_fee_paid', true);
			$base_fee 		= get_option('AuctionTheme_new_auction_listing_fee');

			
			$custom_set = get_option('auctionTheme_enable_custom_posting');
			if($custom_set == 'yes')
			{
				$base_fee = get_option('auctionTheme_theme_custom_cat_'.$catid);
				if(empty($base_fee)) $base_fee = 0;		
			}
			
			//----------------------------------------------------------
			
			if($base_fee_paid != "1" && $base_fee > 0)
			{

				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'base_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $base_fee;
				$my_small_arr['description'] 	= __('Base Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
				
			}
			
			//----------------------------------------------------------
			
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'extra_img';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $AuctionTheme_get_images_cost_extra;
				$my_small_arr['description'] 	= __('Extra Images Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
			
			
			//-------- Featured Project Check --------------------------
			
			
			$featured 		= get_post_meta($pid, 'featured', true);
			$featured_paid 	= get_post_meta($pid, 'featured_paid', true);
			$feat_charge 	= get_option('AuctionTheme_new_auction_feat_listing_fee');
			
			if($featured == "1" && $featured_paid != "1" && $feat_charge > 0)
			{
				
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'feat_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $feat_charge;
				$my_small_arr['description'] 	= __('Featured Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
				
			}
			
			//---------- Private Bids Check -----------------------------
			
			$private_bids 		= get_post_meta($pid, 'private_bids', true);
			$private_bids_paid 	= get_post_meta($pid, 'private_bids_paid', true);
			
			$auctionTheme_sealed_bidding_fee = get_option('AuctionTheme_new_auction_sealed_bidding_fee');
			if(!empty($auctionTheme_sealed_bidding_fee))
			{
				$opt = get_post_meta($pid,'private_bids',true);
				if($opt == "no") $auctionTheme_sealed_bidding_fee = 0;
			}
			
			
			if($private_bids == "yes" && $private_bids_paid != "1" && $auctionTheme_sealed_bidding_fee > 0)
			{				
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'sealed_project';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $auctionTheme_sealed_bidding_fee;
				$my_small_arr['description'] 	= __('Sealed Bidding Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
			}

			//---------------------
			
			$payment_arr = apply_filters('AuctionTheme_filter_payment_array', $payment_arr, $pid);
		
						
			$my_total = 0;
			foreach($payment_arr as $payment_item):
				if($payment_item['amount'] > 0):
					$my_total += $payment_item['amount'];
				endif;
			endforeach;			
			
			$my_total = apply_filters('AuctionTheme_filter_payment_total', $my_total, $pid);

	 
			//----------------------------------------
			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
			$tm = time();
			$cst = $pid.'|'.$uid.'|'.current_time('timestamp',0);	
			$custom_id = time().$pid. $uid;
			
			update_option('coinbase_thing_' . $custom_id, $cst);
	
 
			
			$_CLIENT_ID 	= get_option('AuctionTheme_coinbase_id');
			$_CLIENT_SECRET = get_option('AuctionTheme_client_secret_key');
			
 			 
			include( dirname(__FILE__).'/coinbase_php/lib/coinbase.php');
			
			//-------------------------------------------------------------------------------------
			
			$redirectUrl = str_replace("http://", "http://", plugins_url( 'AuctionTheme_gateways/coinbase/coinbase_redirect.php' )); //get_bloginfo('siteurl') . "/?bitcoins=1";
			$coinbaseOauth = new Coinbase_OAuth($_CLIENT_ID, $_CLIENT_SECRET, $redirectUrl);
			  
			 $args = array(
              'name' => $post->post_title,
              'price_string' => $my_total,
              'price_currency_iso' => get_option('AuctionTheme_currency'),
			  "callback_url" => get_bloginfo('siteurl') . "?my_custom_button_callback_coinbase_listing=1",
              'custom' => $custom_id,
              'description' => $job_title,
              'type' => 'buy_now',
              'style' => 'buy_now_large');
			  
			    
			$tokens = get_option( 'coinbase_tokens');
			  
			if($tokens) 
			  {
					try 
					{
				  		$coinbase = new Coinbase($coinbaseOauth, $tokens);
				  		$button = $coinbase->createButtonWithOptions($args)->embedHtml;
					} 
					catch (Coinbase_TokensExpiredException $e) 
					{
						  $tokens = $coinbaseOauth->refreshTokens($tokens);
						  update_option( 'coinbase_tokens', $tokens );
				
						  $coinbase = new Coinbase($coinbaseOauth, $tokens);
						  $button = $coinbase->createButtonWithOptions($args)->embedHtml;
					}
				 	echo '<br/>';
				 	echo $button;
					
				 	echo '<br/>';
					//return $button;
			  	} 
				else {
					
					//return "The Coinbase plugin has not been properly set up - please visit the Coinbase settings page in your administrator console.";
			  		echo 'Please set coinbase up right. From backend.';
					}
			
			
  
	
	}
}

?>