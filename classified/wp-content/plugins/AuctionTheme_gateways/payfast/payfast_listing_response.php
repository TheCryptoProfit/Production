<?php

function AT_payfast_listing_response_pfst()
{
		$c  	= $_POST['custom_str1']; $xx = $c;
		$c 		= explode('|',$c);
		
		$pid					= $c[0];
		$uid					= $c[1];
		$datemade 				= $c[2];
	 
		
		//--------------------------------------------
		
		update_post_meta($pid, "paid", 				"1");
		update_post_meta($pid, "closed", 			"0");
		
		//--------------------------------------------
		
		update_post_meta($pid, 'base_fee_paid', '1');
		
		$featured = get_post_meta($pid,'featured',true);	
		if($featured == "1") update_post_meta($pid, 'featured_paid', '1');
		
		$private_bids = get_post_meta($pid,'private_bids',true);	
		if($private_bids == "yes") update_post_meta($pid, 'private_bids_paid', '1');
		 
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

?>