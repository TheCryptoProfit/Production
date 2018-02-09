<?php

if(!function_exists('curl_init')) {
    throw new Exception('The Coinbase client library requires the CURL PHP extension.');
}

require( 'coinbase/Exception.php');
require( 'coinbase/ApiException.php');
require( 'coinbase/ConnectionException.php');
require( 'coinbase/Coinbase.php');
require( 'coinbase/Requestor.php');
require( 'coinbase/Rpc.php');
require( 'coinbase/OAuth.php');
require( 'coinbase/TokensExpiredException.php');
