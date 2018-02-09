<?php

include 'quickpay_deposit.php';
include 'quickpay_listing.php';

add_filter('AuctionTheme_payment_methods_tabs',				'AuctionTheme_payment_methods_tabs_quickpay');
add_filter('AuctionTheme_payment_methods_content_divs_m',	'AuctionTheme_payment_methods_content_divs_quickpay');
add_filter('AuctionTheme_payment_methods_action', 			'AuctionTheme_payment_methods_action_quickpay' );
add_filter('template_redirect',								'AT_templ_redir_gateways_qckpay');

function AT_templ_redir_gateways_qckpay()
{
	if(isset($_POST['amount_quickpay']))
	{
		if(!empty($_POST['amount_quickpay']) and is_numeric($_POST['amount_quickpay']))
		{
			quickpay_deposit_auction_theme_me(); 
			die();	
		}
		else
		{
			global $am_err;
			$am_err = 1;	
		}
	}
	
	if($_GET['a_action'] == "quickpay_listing")
	{
		AT_quickpay_s_listing_fncs();
		die();	
	}
	
	if($_GET['a_action'] == "quickpay_listing_response")
	{
		AT_quickpay_listing_response_lst();
		die();	
	}
	
	
	
	if($_GET['a_action'] == "quickpay_deposit_response")
	{
		quickpay_auction_theme_callback_fncs_deposit();
		die();	
	}
		
}

function AuctionTheme_payment_methods_action_quickpay()
{
	
	if(isset($_POST['AuctionTheme_save_quickpay']))
	{
		update_option('AuctionTheme_quickpay_enable', 	trim($_POST['AuctionTheme_quickpay_enable']));
		update_option('AuctionTheme_quickpay_id', 		trim($_POST['AuctionTheme_quickpay_id']));
		update_option('AuctionTheme_quickpay_key', 		trim($_POST['AuctionTheme_quickpay_key']));
		
		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';		
	}
		
}

function AuctionTheme_payment_methods_content_divs_quickpay()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="tabs_quick" >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs_quick">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_quickpay_enable'); ?></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Merchant ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_quickpay_id" value="<?php echo get_option('AuctionTheme_quickpay_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('MD5 Secret Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_quickpay_key" value="<?php echo get_option('AuctionTheme_quickpay_key'); ?>"/></td>
                    </tr>
                    
  
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_quickpay" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          	
          </div>

<?php	
	
}


function AuctionTheme_payment_methods_tabs_quickpay()
{
?>
		<li><a href="#tabs_quick">Quickpay</a></li>
<?php	
}

?>