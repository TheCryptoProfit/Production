<?php
/*
Plugin Name: AuctionTheme Gateways
Plugin URI: http://sitemile.com/
Description: Adds extra payment gateways for the Auction Theme from sitemile. Extension.
Author: SiteMile.com
Author URI: http://sitemile.com/
Version: 1.2
Text Domain: at_gateways
*/

//--------------------------------------------------

include 'payfast/payfast.php';
include 'coinbase/coinbase.php';
include 'payson/payson.php';
include 'authorize/authorize.php';
include 'ideal_sisow/ideal_admin.php';
include 'ideal_mollie/ideal_mollie.php';
include 'sidpayment/sidpayment.php';
include 'quickpay/quickpay.php';
include 'coinpayments.net/coinpayments.net.php';
include 'gocoins/gocoins.php';
include 'stripe/stripe.php';
include 'blockchain/block.php'; 

?>
