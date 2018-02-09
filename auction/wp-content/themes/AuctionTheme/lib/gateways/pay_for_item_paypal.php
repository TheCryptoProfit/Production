<?php



	$opt = get_option('auctionTheme_enable_paypal_ad');

	if($opt == "yes") 
		include 'adaptive_paypal_auction.php';
	else 
		include 'normal_paypal_auction.php';
	
	
?>