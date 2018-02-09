<?php

include 'coinpayments_deposit.php';

add_filter('AuctionTheme_payment_methods_tabs','AuctionTheme_payment_methods_tabs_coinpayments_net');
add_filter('AuctionTheme_payment_methods_content_divs_m','AuctionTheme_payment_methods_content_divs_cnpmnts_net');
add_filter('AuctionTheme_payment_methods_action', 'AuctionTheme_payment_methods_action_coinpmntsnet' );
add_filter('template_redirect','AT_templ_redir_gateways_coinpmntsnet');
add_filter('AuctionTheme_withdrawal_options_me','at_coinpayments_with');


function at_coinpayments_with()
{
	
	$opt = get_option('AuctionTheme_coinpaymentsnet_enable');					
					if($opt == "yes"): 
	
	?>
    <br /><br />
    <h3><?php echo __("Withdraw amount (coins):","AuctionTheme"); ?></h3>
                    <table>
                    <form method="post">
                    <input type="hidden" value="coinpayments" name="methods" />
                    <tr>
                    <td width="140" height="40"><?php echo __("Withdraw amount():","AuctionTheme"); ?></td>
                    <td> <input value="<?php echo $_POST['amount']; ?>" type="text" 
                    size="10" name="amount" /> <?php echo auctionTheme_currency(); ?></td>
                    </tr>
                    
                    <tr>
                    <td   height="40"><?php echo __("BitCoins Address:","AuctionTheme"); ?></td>
                    <td> <input type="text" size="50" name="bank_details" /> </td>
                    </tr>
                   
                   
                   	<input type="hidden" value="<?php echo time(); ?>" name="tmmm" />
                   
                    <tr>
                    <td height="40"></td>
                    <td>
                    <input type="submit" name="withdraw" value="<?php echo __("Withdraw","AuctionTheme"); ?>" /></td></tr></form></table>
    
    
    <?php	
		endif;
}

function AT_templ_redir_gateways_coinpmntsnet()
{
	if(isset($_POST['amount_coinpayments']))
	{
		if(!empty($_POST['amount_coinpayments']) and is_numeric($_POST['amount_coinpayments']))
		{
			AT_coinpayments_deposit_auction_theme_me(); 
			die();	
		}
		else
		{
			global $am_err;
			$am_err = 1;	
		}
	}
	
	if($_GET['a_action'] == "coinpayments_deposit_response")
	{
		AT_coinpayments_thing_response_dep();
		die();	
	}
		
	
}


function AuctionTheme_payment_methods_action_coinpmntsnet()
{
	
	if(isset($_POST['AuctionTheme_save_coinpaymentsnet_id']))
	{
		update_option('AuctionTheme_coinpaymentsnet_id', 	trim($_POST['AuctionTheme_coinpaymentsnet_id'])); 
		update_option('AuctionTheme_coinpaymentsnet_enable', 		trim($_POST['AuctionTheme_coinpaymentsnet_enable']));
		
		
		update_option('AuctionTheme_coinpaymentsnet_priv_key', 		trim($_POST['AuctionTheme_coinpaymentsnet_priv_key']));
		update_option('AuctionTheme_coinpaymentsnet_pub_key', 		trim($_POST['AuctionTheme_coinpaymentsnet_pub_key']));
		
		
		
		
		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';		
	}
		
}


function AuctionTheme_payment_methods_content_divs_cnpmnts_net()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="tabs_c_178" >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs_c_178">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_coinpaymentsnet_enable'); ?></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Coinpayments.net Merchant ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_coinpaymentsnet_id" value="<?php echo get_option('AuctionTheme_coinpaymentsnet_id'); ?>"/></td>
                    </tr>
                    
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Coinpayments.net Priv Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_coinpaymentsnet_priv_key" value="<?php echo get_option('AuctionTheme_coinpaymentsnet_priv_key'); ?>"/></td>
                    </tr>
                    
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Coinpayments.net Pub Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_coinpaymentsnet_pub_key" value="<?php echo get_option('AuctionTheme_coinpaymentsnet_pub_key'); ?>"/></td>
                    </tr>
                    
              
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_coinpaymentsnet_id" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          	
          </div>

<?php	
	
}

function AuctionTheme_payment_methods_tabs_coinpayments_net()
{
?>
		<li><a href="#tabs_c_178">Coinpayments.net</a></li>
<?php	
}



?>