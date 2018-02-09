<?php

	if(isset($_POST['SID_STATUS']))
	{
		if($_POST['SID_STATUS'] == "COMPLETED")
		{
			$SID_REFERENCE 	= $_POST['SID_REFERENCE'];
			$bid_id 		= get_option('payment_staff_' .$SID_REFERENCE);
			
			global $wpdb;
			
			$s = "select * from ".$wpdb->prefix."auction_bids where id='$bid_id'";
			$r = $wpdb->get_results($s);
			$row = $r[0]; $bid = $row;
			
			
			$wpdb->query("update ".$wpdb->prefix."auction_bids set paid='1' where id='$bid_id'");
			update_post_meta($bid->pid, 'paid_on_'.$bid_id, current_time('timestamp',0));
			
		}	
		
	}
	
	mail("andreisaioc@gmail.com","as",print_r($_POST, true));
	
	wp_redirect(get_bloginfo('siteurl'));

?>