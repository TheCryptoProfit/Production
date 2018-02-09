<?php

//add_action('template_redirect', 'auctionTheme_delete_drafts'); //wp_init - here
add_action('init', 'auctionTheme_close_auctions'); //wp_init - here
add_action('init', 'auctionTheme_verify_things1'); //wp_init - here

function auctionTheme_verify_things1()
{
	$AuctionTheme_enable_automatically_repubs = get_option('AuctionTheme_enable_automatically_repubs');
	$AuctionTheme_republish_time_hrs = get_option('AuctionTheme_republish_time_hrs');
	
	if(!empty($AuctionTheme_republish_time_hrs) and 	$AuctionTheme_enable_automatically_repubs == "yes")
	{
		$xx 	= 3600*$AuctionTheme_republish_time_hrs;
		$tods 	= current_time('timestamp',0);
		
		//--------------------------------
		
		global $wpdb;
		$s = "select * from ".$wpdb->prefix."auction_bids where paid='0' and from_buy_now='1' and (date_made+$xx) < $tods";
		$r = $wpdb->get_results($s);
		
 
		
		foreach($r as $row)
		{
			$pid = $row->pid;
			$quant = get_post_meta($pid, 'quant', true);
			$quant = $quant + $row->quant;
			
			update_post_meta($pid, 'quant',$quant);
			update_post_meta($pid, 'closed','0');
			
			$s = "delete from ".$wpdb->prefix."auction_bids where id='".$row->id."'";	
			$wpdb->query($s);
			
		}
	}
	
}

function auctionTheme_delete_drafts()
{

		$drft = array(
			'key' => 'is_draft',
			'value' => "1",
			'compare' => 'LIKE'
		);
		
		$tm = current_time('timestamp',0);
		if(empty($tm)) $tm = time();
		
		$drft_until = array(
			'key' => 'draft_until',
			'value' => $tm,
			'type' => 'numeric',
			'compare' => '<'
		);
		
		
	$args2 = array( 'posts_per_page' =>'-1', 'post_type' => 'auction', 'post_status' => array('draft','publish'), 'meta_query' => array($drft,$drft_until));
	$the_query = new WP_Query( $args2 );
	
	
		if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			
			wp_delete_post(get_the_ID());
		
		endwhile;
		endif;
	
}


function auctionTheme_close_auctions()
{
	global $wpdb;
		$closed = array(
			'key' => 'closed',
			'value' => "0",
			'compare' => '='
		);
		
		
		$ending = array(
			'key' => 'ending',
			'value' => current_time('timestamp',0),
			'type' => 'numeric',
			'compare' => '<'
		);
		
		$AuctionTheme_no_time_on_buy_now = get_option('AuctionTheme_no_time_on_buy_now');
		
		if($AuctionTheme_no_time_on_buy_now == "yes"):
		
		$includss = array(
			'key' => 'only_buy_now',
			'value' => '0',
			'type' => 'numeric',
			'compare' => '='
		);
		 
		endif;
		
		
	$args2 = array( 'posts_per_page' =>'-1', 'post_type' => 'auction', 'post_status' => 'publish', 'meta_query' => array($closed, $includss, $ending));
	$the_query = new WP_Query( $args2 );
	
	
		if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			
			update_post_meta(get_the_ID(), 'closed',"1");
			update_post_meta(get_the_ID(), 'closed_date',current_time('timestamp',0));
			$pid = get_the_ID();
			
			$post = get_post($pid);
		
			$reverse = get_post_meta(get_the_ID(), 'reverse',true);
		
			if(0) //$reverse == "yes" || $reverse == "1")
			{
				// just close the auction and email to users maybe...				
				$s = "select distinct uid from ".$wpdb->prefix."auction_bids where pid='$pid'";
				$r = $wpdb->get_results($s);
				
				foreach($r as $row)
				{
					$uid = $row->uid;	
					
					$subject 	= sprintf(__("Auction closed: %s",'AuctionTheme'), $post->post_title);
					$message 	= sprintf(__("The auction <a href='%s'>%s</a> was just closed. A winner hasnt been chosen yet.", 'AuctionTheme'), 
					get_permalink($pid),$post->post_title );
					$user 		= get_userdata($uid);
					$email  	= $user->user_email;
					
					//AuctionTheme_send_email($email, $subject, $message);
					
					
				}
				
				//--- email to the owner as well ---
					
					$subject 	= sprintf(__("Your auction was closed: %s",'AuctionTheme'), $post->post_title);
					$message 	= sprintf(__("The auction <a href='%s'>%s</a> was just closed. Please choose a winner.",''),get_permalink($pid),$post->post_title );
					$user 		= get_userdata($post->post_author);
					$email  	= $user->user_email;
					
					//AuctionTheme_send_email($email, $subject, $message);
				
				//-------
				
			}
			else
			{
				// a little computation here, choose winner, inform people :P
				$action_reverse = get_post_meta(get_the_ID(),'reverse',true);
				if($action_reverse == "yes") $asc_cresc = "asc"; else  $asc_cresc = "desc";
				
				$s = "select * from ".$wpdb->prefix."auction_bids where pid='$pid' order by bid ".$asc_cresc." limit 1";
				$r = $wpdb->get_results($s);
				
				$bid_val_wn = $r[0]->bid;
				
				if(count($r) == 0)
				{
					do_action('AuctionTheme_cronjob_endauction_no_winner', $pid);
					//if($AuctionTheme_no_time_on_buy_now == "yes") AuctionTheme_send_email_when_no_winner_owner($pid);
					
					
							$auto_renew_item 	= get_post_meta($pid, 'auto_renew_item',true);
							$amount_times 		= get_post_meta($pid, 'amount_times',true);
							$amount_days 		= get_post_meta($pid, 'amount_days',true);
							$quant 				= get_post_meta($pid, 'quant',true);
							
						
							
							if($auto_renew_item == "1"): // and $quant > 0):
							 	
								//	echo "asd1";
									//	exit;
								
								
								if($amount_times > 0):
								 
									$N_amount_times = $amount_times--;
									update_post_meta($pid, 'amount_times',$N_amount_times);	
									
									$newtm = time() + 3600*24*$amount_days;
									
									update_post_meta($pid, 'closed','0');	
									update_post_meta($pid, 'ending',$newtm);	
									
								endif;
								
							endif;
				
				}
				else
				{
					$row = $r[0];	
					$id = $row->id;
					
				
					//update_post_meta($pid, 'closed',"1");
					//update_post_meta($pid, 'closed_date', current_time('timestamp',0));
					
					$only_buy_now = get_post_meta($pid, 'only_buy_now', true);
					
					if($only_buy_now == "1"):
						
						 
							
							$auto_renew_item 	= get_post_meta($pid, 'auto_renew_item',true);
							$amount_times 		= get_post_meta($pid, 'amount_times',true);
							$amount_days 		= get_post_meta($pid, 'amount_days',true);
							$quant 			= get_post_meta($pid, 'quant',true);
							
							if($auto_renew_item == "1" and $quant > 0):
							 
								if($amount_times > 0):
								 
									$N_amount_times = $amount_times--;
									update_post_meta($pid, 'amount_times',$N_amount_times);	
									
									$newtm = time() + 3600*24*$amount_days;
									
									update_post_meta($pid, 'closed','0');	
									update_post_meta($pid, 'ending',$newtm);	
									
								endif;
								
							endif;
							 
					
					else:
					
					$reserve = get_post_meta($pid, 'reserve', true);
					$reserve_ok = 1;
					
					if(!empty($reserve)){
						if($reserve <= $row->bid)  $reserve_ok = 1;
						else $reserve_ok = 2; // not met
					}
					
					if($reserve_ok == 1):
					
							$bidid = $id;
							add_post_meta($pid, 'winner', $row->uid);
							update_post_meta($pid, 'paid_user', "0");
							
							$tm = current_time('timestamp',0);
								
							$s = "update ".$wpdb->prefix."auction_bids set winner='1', date_choosen='$tm' where id='$id'";
							$wpdb->query($s);
							
							auctionTheme_prepare_rating($pid, $post->post_author, $row->uid, $id);
							auctionTheme_prepare_rating($pid, $row->uid, $post->post_author, $id);
							
							// send email to the winner -----
							AuctionTheme_send_email_on_win_to_bidder($pid, $row->uid, $bidid);
								
							// send email to the owner -----							
							AuctionTheme_send_email_on_win_to_owner($pid, $row->uid, $bidid);

							$winner_uid 		=  $row->uid;
							$winner_bid_value 	= $bid_val_wn;
							
							//-----email to the other lower bidders-----
							
								global $wpdb;
								$s1 = "select distinct uid from ".$wpdb->prefix."auction_bids where id!='".$bidid."' and uid!='".$row->uid."' AND pid='$pid'";
								$r1 = $wpdb->get_results($s1);
							
								foreach($r1 as $row1)
								{									
									$s1a = "select bid from ".$wpdb->prefix."auction_bids where uid='".$row1->uid."' AND pid='$pid' order by (bid+0) desc limit 1";
									$r1a = $wpdb->get_results($s1a);
								
									AuctionTheme_send_email_on_win_to_loser($pid, $row1->uid, $winner_uid, $winner_bid_value, $r1a[0]->bid);
								}
							
							//----------
						else:
						//------ reserve price is not met
							
				
							//AuctionTheme_send_email_on_no_win_reserve_not_met_owner($pid);
							do_action('AuctionTheme_cronjob_endauction_no_winner', $pid);
							if($AuctionTheme_no_time_on_buy_now != "yes") AuctionTheme_send_email_when_no_winner_owner($pid);
							
							global $wpdb;
							$s1 = "select distinct uid from ".$wpdb->prefix."auction_bids where pid='$pid'";
							$r1 = $wpdb->get_results($s1);
							
							foreach($r1 as $row1)
							{									
								//AuctionTheme_send_email_on_no_win_reserve_not_met_loser($pid, $row1->uid);
							}
							
							//---------------------
							
							$auto_renew_item = get_post_meta($pid, 'auto_renew_item',true);
							$amount_times = get_post_meta($pid, 'amount_times',true);
							$amount_days = get_post_meta($pid, 'amount_days',true);
							
							
							
							if($auto_renew_item == "1")
							{
								if($amount_times > 0)
								{
									$N_amount_times = $amount_times--;
									update_post_meta($pid, 'amount_times',$N_amount_times);	
									
									$newtm = time() + 3600*24*$amount_days;
									
									update_post_meta($pid, 'closed','0');	
									update_post_meta($pid, 'ending',$newtm);	
									
								}
								
							}
							
							//-------------------
							
						endif;
						endif;
					
				}
				
			}
		
		endwhile;
		endif;
	
}


?>