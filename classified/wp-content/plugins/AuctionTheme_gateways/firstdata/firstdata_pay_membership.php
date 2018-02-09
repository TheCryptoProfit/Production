<?php
	global $wpdb, $current_user;
	$id = $_GET['id'];
	get_currentuserinfo();   
	$uid 	= $current_user->ID;
 	
	$s = "select * from ".$wpdb->prefix."auction_membership_packs where id='$id'";
	$r = $wpdb->get_results($s);
	$row = $r[0];
 	$x_fp_sequence = $id.time().$uid;
	$x_fp_timestamp = time();
	$x_amount = $row->membership_cost;
	
	$x_login = get_option('AuctionTheme_firstdata_id');
	
	$hh = $x_login. '^' .$x_fp_sequence. '^' . $x_fp_timestamp .'^'. $x_amount ;
	$key = get_option('AuctionTheme_firstdata_key');
	$x_fp_hash = hash_hmac('md5', $hh, $key);
	
	$va = $uid.'|'.$id.'|'.current_time('timestamp',0);
	update_option('more_seq_'.$x_fp_sequence, $va);
	
?>


<html>
<head><title>Processing Firstdata Payment...</title></head>
<body onLoad="document.frmPay.submit();" >
<center><h3><?php _e('Please wait, your order is being processed...', 'AuctionTheme'); ?></h3></center>

	
 
<form action="https://demo.globalgatewaye4.firstdata.com/payment" method="post" name="frmPay" id="frmPay"> 
  <input name="x_login" value="<?php echo $x_login ?>" type="hidden"> 
  <input name="x_amount" value="<?php echo  $x_amount; ?>" type="hidden"> 
  <input name="x_fp_sequence" value="<?php echo $x_fp_sequence ?>" type="hidden"> 
  <input name="x_fp_timestamp" value="<?php echo $x_fp_timestamp ?>" type="hidden"> 
  <input name="x_fp_hash" value="<?php echo $x_fp_hash ?>" type="hidden"> 
  <input name="x_show_form" value="PAYMENT_FORM" type="hidden"> 
  <input name="x_relay_response" value="TRUE" type="hidden"> 
  <input name="x_relay_url" value="<?php bloginfo('siteurl') ?>/?relay_response=1" type="hidden"> 
 
</form>
    
 

</body>
</html>
