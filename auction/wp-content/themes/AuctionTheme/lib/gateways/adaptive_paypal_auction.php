<?php
/***************************************************************************
*
*	AuctionTheme - copyright (c) - sitemile.com
*	The most popular auction theme for wordress on the internet. Launch your
*	auction site in minutes from purchasing. Turn-key solution.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/p/auctionTheme
*	since v4.4.7.1
*
***************************************************************************/


	global $wp_query, $wpdb;
	$bid_id = $wp_query->query_vars['bid_id'];

	$s = "select * from ".$wpdb->prefix."auction_bids where id='$bid_id'";
	$r = $wpdb->get_results($s);
	$row = $r[0]; $bid = $row;
	$pid = $bid->pid;
	$post = get_post($pid);

	global $current_user, $wpdb;
		$current_user = wp_get_current_user();
	$uid = $current_user->ID;


	$total = $bid->bid*$bid->quant;
	$itms_val = $bid->bid;

	$shipping = auctionTheme_calculate_shipping_charge_for_auction($pid, $bid_id); //get_post_meta($pid, 'shipping', true);
	if(is_numeric($shipping) && $shipping > 0 && !empty($shipping))
			$shipping = $shipping;
					else $shipping = 0;

	 $total += $shipping;



	$projectplugin_signature 	= get_option('auction_theme_signature');
	$projectplugin_apipass 		= get_option('auction_theme_apipass');
	$projectplugin_apiuser 		= get_option('auction_theme_apiuser');
	$projectplugin_appid 		= get_option('auction_theme_appid');


  	$signature 	= $projectplugin_signature;
  	$api_pass 	= $projectplugin_apipass;
  	$api_user 	= $projectplugin_apiuser;
  	$apiid 		= $projectplugin_appid;

 //------------------------------------------------------------------------

	$pmnt_id = time().$pid;

	$ret = get_bloginfo('siteurl')."/?return_chained=" . $pmnt_id;
	$ret2 = get_permalink(get_option('AuctionTheme_my_account_page_id'));
	$receiver_user_id = $post->post_author;

 	$fee = get_option('projectTheme_fee_after_paid');

	$auctionTheme_fee_after_paid = get_option('AuctionTheme_take_percent_fee');
	if(!empty($auctionTheme_fee_after_paid)):
		$deducted = $total*($auctionTheme_fee_after_paid * 0.01);
	else:
		$deducted = 0;
	endif;


	//------------------------------------

	$auctionTheme_fee_after_paid_flat_fee  = get_option('AuctionTheme_take_flat_fee');
	if(!empty($auctionTheme_fee_after_paid_flat_fee)):
		if(is_numeric($auctionTheme_fee_after_paid_flat_fee)):
			$deducted = $auctionTheme_fee_after_paid_flat_fee;
		endif;
	endif;

	//-----------------------------------

	$AuctionTheme_enable_cust_commissions  = get_option('AuctionTheme_enable_cust_commissions');
	if(($AuctionTheme_enable_cust_commissions) == "yes"):



		global $wpdb;
		$s = "select * from ".$wpdb->prefix."auction_commissions where (low_range+0)<'$itms_val' order by (low_range+0) desc limit 1";
		$r = $wpdb->get_results($s);



		if(count($r) > 0)
		{
			$row = $r[0];
			$deducted = $total*($row->commission * 0.01);

		}

	endif;



	$fee = $deducted;

	if(empty($fee))
	{
		wp_redirect(home_url()."?a_action=no_percent_speficied");
		die();
	}

 //-------------------------------------------------------------------------


	$adminfee = $fee; //$total*($fee*0.01);

	$receiver1 	= get_option('AuctionTheme_paypal_email'); if(empty($receiver1)) { die('ERROR. The admin has no paypal email defined.'); }
	$amount1 	= AuctionTheme_formats_special($adminfee, 2);


	$receiver2 	= get_user_meta($receiver_user_id, 'paypal_email', 'true'); if(empty($receiver2))
	{
		wp_redirect(home_url()."?a_action=no_paypal_email");
		die();

	}

	$amount2 	= AuctionTheme_formats_special($total-$adminfee, 2);


	$currency 	= get_option('AuctionTheme_currency');
 	$enasdbx 	= get_option('AuctionTheme_paypal_enable_sdbx');

	if($enasdbx == "yes")
	$link = "https://svcs.sandbox.paypal.com/AdaptivePayments/Pay";
	else $link = "https://svcs.paypal.com/AdaptivePayments/Pay";

	$notifyURL = get_bloginfo('siteurl') . "/?notify_chained=1";
	$trID = $bid_id."_".time();

	$auctionTheme_paypal_ad_model = get_option('auctionTheme_paypal_ad_model');
	if($auctionTheme_paypal_ad_model != "chained"):

	$params = "actionType=PAY&cancelUrl=".$ret2."&trackingId=".$trID."&ipnNotificationUrl=".$notifyURL."&currencyCode=".$currency.
  	"&receiverList.receiver(0).amount=".$amount1."&receiverList.receiver(0).email=".$receiver1.
  	"&receiverList.receiver(0).primary=false&receiverList.receiver(1).amount=".$amount2.
  	"&receiverList.receiver(1).email=".$receiver2."&requestEnvelope.errorLanguage=en_US&senderOptions.referrerCode=SiteMile_SP".
  	"&returnUrl=".$ret; //.".paymentType=DIGITALGOODS";

	else:


		$params = "actionType=PAY&cancelUrl=".$ret2."&trackingId=".$trID."&ipnNotificationUrl=".$notifyURL."&currencyCode=".$currency.
  	"&receiverList.receiver(0).amount=".($amount1+$amount2)."&receiverList.receiver(0).email=".$receiver1.
  	"&receiverList.receiver(0).primary=true&receiverList.receiver(1).amount=".$amount2.
  	"&receiverList.receiver(1).email=".$receiver2."&requestEnvelope.errorLanguage=en_US&senderOptions.referrerCode=SiteMile_SP".
  	"&returnUrl=".$ret; //.".paymentType=DIGITALGOODS";


	endif;

function array_push_assoc($array, $key, $value){
 $array[$key] = $value;
 return $array;
}

function my_xml2array($contents)
{
    $xml_values = array();
    $parser = xml_parser_create('');
    if(!$parser)
        return false;

    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);
    if (!$xml_values)
        return array();

    $xml_array = array();
    $last_tag_ar =& $xml_array;
    $parents = array();
    $last_counter_in_tag = array(1=>0);
    foreach ($xml_values as $data)
    {
        switch($data['type'])
        {
            case 'open':
                $last_counter_in_tag[$data['level']+1] = 0;
                $new_tag = array('name' => $data['tag']);
                if(isset($data['attributes']))
                    $new_tag['attributes'] = $data['attributes'];
                if(isset($data['value']) && trim($data['value']))
                    $new_tag['value'] = trim($data['value']);
                $last_tag_ar[$last_counter_in_tag[$data['level']]] = $new_tag;
                $parents[$data['level']] =& $last_tag_ar;
                $last_tag_ar =& $last_tag_ar[$last_counter_in_tag[$data['level']]++];
                break;
            case 'complete':
                $new_tag = array('name' => $data['tag']);
                if(isset($data['attributes']))
                    $new_tag['attributes'] = $data['attributes'];
                if(isset($data['value']) && trim($data['value']))
                    $new_tag['value'] = trim($data['value']);

                $last_count = count($last_tag_ar)-1;
                $last_tag_ar[$last_counter_in_tag[$data['level']]++] = $new_tag;
                break;
            case 'close':
                $last_tag_ar =& $parents[$data['level']];
                break;
            default:
                break;
        };
    }
    return $xml_array;
}

function get_web_page( $url, $posts,$api_user,$api_pass,$signature,$apiid )
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
		CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => false,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "sitemile", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_POSTFIELDS		 => $posts,
        CURLOPT_HTTPHEADER		 =>
        array(
        "X-PAYPAL-SECURITY-USERID: ".$api_user,
        "X-PAYPAL-SECURITY-PASSWORD: ".$api_pass,
        "X-PAYPAL-SECURITY-SIGNATURE: ".$signature,
        "X-PAYPAL-APPLICATION-ID: ".$apiid,

        "X-PAYPAL-DEVICE-IPADDRESS: ".$_SERVER['REMOTE_ADDR'],
        "X-PAYPAL-REQUEST-DATA-FORMAT: NV",
        "X-PAYPAL-RESPONSE-DATA-FORMAT: XML")
    );

	$c1 = 'cur';
	$c2 = 'l_init';
	$c3 = $c1.$c2;

	$c1 = 'cur';
	$c2 = 'l_exec';
	$c4 = $c1.$c2;

    $ch      = $c3( $url );
    curl_setopt_array( $ch, $options );
    $content = $c4( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}


$cont = get_web_page( $link, $params ,$api_user,$api_pass,$signature,$apiid );
$co = $cont['content'];

$arr =my_xml2array($co);
//echo '<pre>';
//print_r( $arr );
//echo '<pre>';

//exit;

$ap = $arr[0][1]['value'];

if(empty($ap))
{
	echo "ERROR<br/>";

	echo '<pre>';
	print_r( $arr );
	echo '<pre>';
	exit;
}

//-----------------------------

	if($enasdbx == "yes")
	$link = "https://svcs.sandbox.paypal.com/AdaptivePayments/SetPaymentOptions";
	else $link = "https://svcs.paypal.com/AdaptivePayments/SetPaymentOptions";

	if($auctionTheme_paypal_ad_model != "chained"):
	$params = "requestEnvelope.errorLanguage=en_US".
				"&receiverOptions[0].receiver.email=".$receiver1.
				"&receiverOptions[0].invoiceData.item[0].name=".__('Site fee taken for item.','AuctionTheme').
				"&receiverOptions[0].invoiceData.item[0].price=".($amount1).
				"&receiverOptions[0].invoiceData.item[0].itemCount=1".
				"&receiverOptions[0].invoiceData.item[0].itemPrice=".($amount1).
				"&receiverOptions[0].invoiceData.totalTax=0".
				"&receiverOptions[0].invoiceData.totalShipping=0".

				"&receiverOptions[1].receiver.email=".$receiver2.
				"&receiverOptions[1].invoiceData.item[0].name=".sprintf(__('Item Paid: %s','AuctionTheme'), $post->post_title).
				"&receiverOptions[1].invoiceData.item[0].price=".($amount2).
				"&receiverOptions[1].invoiceData.item[0].itemCount=1".
				"&receiverOptions[1].invoiceData.item[0].itemPrice=".($amount2).
				"&receiverOptions[1].invoiceData.totalTax=0".
				"&receiverOptions[1].invoiceData.totalShipping=0".
				"&payKey=".$ap;

				else:

		$params = "requestEnvelope.errorLanguage=en_US".
				"&receiverOptions[0].receiver.email=".$receiver1.
				"&receiverOptions[0].invoiceData.item[0].name=".sprintf(__('Item Paid: %s','AuctionTheme'), $post->post_title).
				"&receiverOptions[0].invoiceData.item[0].price=".($amount1+$amount2).
				"&receiverOptions[0].invoiceData.item[0].itemCount=1".
				"&receiverOptions[0].invoiceData.item[0].itemPrice=".($amount1+$amount2).
				"&receiverOptions[0].invoiceData.totalTax=0".
				"&receiverOptions[0].invoiceData.totalShipping=0".
				"&payKey=".$ap;

				endif;

	$cont = get_web_page( $link, $params ,$api_user,$api_pass,$signature,$apiid );
 	//print_r($cont);

	//exit;

//---------------------------

	$auctionTheme_checkout_way = get_option('auctionTheme_checkout_way');
	if(empty($auctionTheme_checkout_way)) $auctionTheme_checkout_way = "normal";

	if($auctionTheme_checkout_way == "normal")
	{
		if($enasdbx == "yes")
		header("Location: https://www.sandbox.paypal.com/webscr?cmd=_ap-payment&paykey=".$ap);
		else header("Location: https://www.paypal.com/webscr?cmd=_ap-payment&paykey=".$ap);

	}
	else
	{
		if($enasdbx == "yes")
		header("Location: https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay?paykey=".$ap);
		else header("Location: https://www.paypal.com/webapps/adaptivepayment/flow/pay?paykey=".$ap);

	}


	//header("Location: https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay?paykey=" . $ap);


?>
