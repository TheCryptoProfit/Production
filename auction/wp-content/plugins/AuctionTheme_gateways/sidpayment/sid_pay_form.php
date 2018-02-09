<?php
	
	global $wpdb;

	$bid_id 	= $_GET['bid_id'];
	$mer 		= get_option('AuctionTheme_sidpayment_id');
	$refe_id 	= rand(0,9999);
	
	$s = "select * from ".$wpdb->prefix."auction_bids where id='$bid_id'";
	$r = $wpdb->get_results($s);
	$row = $r[0]; $bid = $row;
	$pid = $bid->pid;
	$uid = $bid->uid;
	
	//----------------------
	
	$am = $bid->bid*$bid->quant;	

	$shipping = auctionTheme_calculate_shipping_charge_for_auction($pid); //get_post_meta($pid, 'shipping', true);
	if(is_numeric($shipping) && $shipping > 0 && !empty($shipping))
			$shipping = $shipping;
					else $shipping = 0;

	 $am += $shipping; 
	 
	 //--------------
	 
	$sid_merchant = $mer;
	$sid_country = 'ZA';
	$sid_currency = get_option('AuctionTheme_currency');
	$sid_reference = $refe_id;
	update_option('payment_staff_' .$refe_id ,$bid_id);
	
	$sid_amount = $am;
	$sid_secret_key = get_option('AuctionTheme_sidpyament_key');

	$sid_consistent = strtoupper(hash('sha512', $sid_merchant.$sid_currency.$sid_country.$sid_reference.$sid_amount.$sid_secret_key));

?>

<html>
<head><title>Processing SID Payment...</title></head>
<body onLoad="document.form_mb.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'AuctionTheme'); ?></h3></center>


<FORM METHOD="POST" name="form_mb" id="form_mb" ACTION="https://www.sidpayment.com/paySID/">
<INPUT TYPE="HIDDEN" NAME="SID_MERCHANT" VALUE="<?php echo $mer; ?>" />
<INPUT TYPE="HIDDEN" NAME="SID_CURRENCY" VALUE="<?php echo get_option('AuctionTheme_currency') ?>" />
<INPUT TYPE="HIDDEN" NAME="SID_COUNTRY" VALUE="ZA" />
<INPUT TYPE="HIDDEN" NAME="SID_REFERENCE" VALUE="<?php echo $refe_id ?>" />
<INPUT TYPE="HIDDEN" NAME="SID_AMOUNT" VALUE="<?php echo $am ?>" />
<INPUT TYPE="HIDDEN" NAME="SID_CONSISTENT" VALUE="<?php echo $sid_consistent ?>" />
<INPUT TYPE="hidden" NAME="PaySID" VALUE="1" />
</FORM>

</body>
</html>