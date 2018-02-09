<?php
	
	$x_fp_sequence = $_POST['x_fp_sequence'];
	//$va = $uid.'|'.$id.'|'.crrent_time('timestamp',0);
	$va1 = get_option('more_seq_'.$x_fp_sequence);
	$va = explode('|', $va1);
	$uid = $va[0];
	$id = $va[1];
	$tm = $va[2];
	
		//$vaa = print_r($va1, true);
		
		//echo $va1; exit;
		//mail("andreisaioc@gmail.com","asd12" , $vaa);
		
		
		
	global $wpdb;
	$s = "select * from ".$wpdb->prefix."auction_membership_packs where id='$id'";
	$r = $wpdb->get_results($s);
	$row = $r[0];
	
	//-------------------------
	
	$mem_available 	 = get_user_meta($uid, 'mem_available', true);
							$ct				 = current_time('timestamp',0);;	
							
							if($ct > $mem_available or empty($mem_available))
							{
							 	$cts = ($ct + 3600*24*30.5);
								
								update_user_meta($uid, 'mem_available', $cts);
								update_user_meta($uid, 'auctions_available', $row->number_of_items);
								update_user_meta($uid, 'membership_id', $row->id);
								
								$mem_available 	 = get_user_meta($uid, 'mem_available', true);
								//echo $uid."asdf" . $mem_available;
								
							}
	
	echo "<a href='".get_permalink(get_option('AuctionTheme_my_account_page_id'))."'>Get Back to the website</a>";
	
?>