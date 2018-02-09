<?php
	
	global $TOKENS ;
	
	require_once('src/GoCoin.php');	
	$MERCHANT_ID = get_option('AuctionTheme_gocoins_id_litecoins');

	  $new_invoice = array(
		'price_currency' => 'LTC',
		'base_price' => '456.00',
		'base_price_currency' => 'USD',
		'notification_level' => 'all',
		'confirmations_required' => 5,
	  );
	  
	  $new_invoice = GoCoin::createInvoice('c84f717b660bd7ee5610b50971f145f0d419a6eb447c0a3fbc6079cb6a40bfb9',	$MERCHANT_ID, $new_invoice);
  	echo "asd";
	print_r($new_invoice);
  
?>