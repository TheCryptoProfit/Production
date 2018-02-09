<?php

add_filter('AuctionTheme_payment_methods_tabs',				'AuctionTheme_payment_methods_tabs_gocoins');
add_filter('AuctionTheme_payment_methods_content_divs_m',	'AuctionTheme_payment_methods_content_divs_gocoins');
add_filter('AuctionTheme_payment_methods_action', 			'AuctionTheme_payment_methods_action_gocoins' );
add_action('AuctionTheme_dposit_fields_page',				'AuctionTheme_dposit_fields_page_bitcoins_fnc');
add_filter('template_redirect',								'AT_templ_redir_gateways_btcxs');

global $TOKENS ;

$TOKENS = array(
  'basic' => 'YOUR_BASIC_ACCESS_TOKEN',
  'dashboard' => 'A_TOKEN_PROVIDED_BY_DASHBOARD',
  'full_access' => 'FULL_ACCESS_TOKEN',
);

//**********************************************************************************

function VApostData() {
      //get webhook content
      $response = new stdClass();
      $post_data = file_get_contents("php://input");
      if (!$post_data) {
        $response->error = 'Request body is empty';
      }
      $post_as_json = json_decode($post_data);
      if (is_null($post_as_json)){
        $response->error = 'Request body was not valid json';
      } else {
        $response = $post_as_json;
      }
      return $response;
  }


function AT_templ_redir_gateways_btcxs()
{
	if(isset($_POST['deposit_bitcoins_me']) and is_numeric($_POST['amount_bitcoins']) and !empty($_POST['amount_bitcoins']))
	{
		include 'bitcoins_deposit.php';
		die();
	}

	if(isset($_POST['deposit_litecoins_me']) and is_numeric($_POST['amount_litecoins']) and !empty($_POST['amount_litecoins']))
	{
		include 'litecoins_deposit.php';
		die();
	}

  if(isset($_GET['call_back_go_coin']))
  {

    $VApostData = VApostData();
//mail('andreisaioc@gmail.com','test_goc', print_r($VApostData , true));

    $invoice            = $VApostData->payload;
    $status             = $invoice->status;
    $order_id           = $invoice->id;
$xx = $invoice->base_price;

    $uid = get_option('go_co_' . $order_id);
 

    if($status == 'paid')
    {

          $credits = get_user_meta($uid, 'credits', true);


$cc = $xx + $credits;
update_user_meta($uid, 'credits', $cc);



    }
	   die();
  }

}

//**********************************************************************************

function AuctionTheme_dposit_fields_page_bitcoins_fnc()
{
	$opt = get_option('AuctionTheme_gocoins_bitcoins_enable');
	if($opt == "yes"):

	?>

    <br/><br/>
	<strong><?php _e('Deposit money - Pay with Bitcoins','AuctionTheme'); ?></strong><br/><br/>

                <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_bitcoins" value="<?php echo $_POST['amount_bitcoins']; ?>" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_bitcoins_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>

    <?php

	endif;


	$opt = get_option('AuctionTheme_save_gocoins_litecoins');
	if($opt == "yes"):

	?>

    <br/><br/>
	<strong><?php _e('Deposit money - Pay with Litecoins','AuctionTheme'); ?></strong><br/><br/>

                <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_litecoins" value="<?php echo $_POST['amount_litecoins']; ?>" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_litecoins_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>

    <?php

	endif;

}

//**********************************************************************************

function AuctionTheme_payment_methods_action_gocoins()
{

	if(isset($_POST['AuctionTheme_save_gocoins_bitcoins']))
	{
		update_option('AuctionTheme_gocoins_bitcoins_enable', 	trim($_POST['AuctionTheme_gocoins_bitcoins_enable']));
		update_option('AuctionTheme_gocoins_id_bitcoins', 		trim($_POST['AuctionTheme_gocoins_id_bitcoins']));
		update_option('AuctionTheme_gocoins_api_bitcoins', 		trim($_POST['AuctionTheme_gocoins_api_bitcoins']));


		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';
	}


	if(isset($_POST['AuctionTheme_save_gocoins_litecoins']))
	{
		update_option('AuctionTheme_gocoins_litecoins_enable', 	trim($_POST['AuctionTheme_gocoins_litecoins_enable']));
		update_option('AuctionTheme_gocoins_id_litecoins', 		trim($_POST['AuctionTheme_gocoins_id_litecoins']));
		update_option('AuctionTheme_gocoins_api_litecoins', 		trim($_POST['AuctionTheme_gocoins_api_litecoins']));


		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';
	}

}

function AuctionTheme_payment_methods_tabs_gocoins()
{
?>
		<li><a href="#tabs81">Gocoins - Bitcoins</a></li>
        <li><a href="#tabs82">Gocoins - Litecoins</a></li>
<?php
}


function AuctionTheme_payment_methods_content_divs_gocoins()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));

?>

<div id="tabs81" >
          in order to use this, please register a merchant account with https://www.gocoin.com  <br/><br/>
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs81">
            <table width="100%" class="sitemile-table">

                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_gocoins_bitcoins_enable'); ?></td>
                    </tr>

                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Gocoin Merchant ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_gocoins_id_bitcoins" value="<?php echo get_option('AuctionTheme_gocoins_id_bitcoins'); ?>"/></td>
                    </tr>


                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Gocoin API KEY:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_gocoins_api_bitcoins" value="<?php echo get_option('AuctionTheme_gocoins_api_bitcoins'); ?>"/></td>
                    </tr>



                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_gocoins_bitcoins" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>

            </table>
          	</form>
</div>



<div id="tabs82" >
           in order to use this, please register a merchant account with https://www.gocoin.com  <br/><br/>
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs82">
            <table width="100%" class="sitemile-table">

                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_gocoins_litecoins_enable'); ?></td>
                    </tr>

                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Gocoin Merchant ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_gocoins_id_litecoins" value="<?php echo get_option('AuctionTheme_gocoins_id_litecoins'); ?>"/></td>
                    </tr>


                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Gocoin API KEY:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_gocoins_api_litecoins" value="<?php echo get_option('AuctionTheme_gocoins_api_litecoins'); ?>"/></td>
                    </tr>



                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_gocoins_litecoins" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>

            </table>
          	</form>
</div>

<?php

}

?>
