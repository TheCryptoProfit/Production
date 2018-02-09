<?php

	global $TOKENS ;

	require_once('src/GoCoin.php');
	$MERCHANT_ID = get_option('AuctionTheme_gocoins_id_bitcoins');

	  $new_invoice = array(
		'price_currency' => 'BTC',
		'base_price' => $_POST['amount_bitcoins'],
		'base_price_currency' => 'USD',
		'notification_level' => 'all',
		'confirmations_required' => 5,

		"callback_url" => home_url() . '?call_back_go_coin=1',
	  "redirect_url" => home_url()

	  );

	  $new_invoice = GoCoin::createInvoice(get_option('AuctionTheme_gocoins_api_bitcoins'),	$MERCHANT_ID, $new_invoice);

	$gateway_url = $new_invoice->gateway_url;
	$cu = wp_get_current_user();
	update_option('go_co_' . $new_invoice->id, $cu->ID);
	if(!empty($gateway_url))
	{
		header("Location: " . $gateway_url);
	}
	else echo 'there is an error';
?>
