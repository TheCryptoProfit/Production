<?php

	include 'payfast_listing.php';
	include 'payfast_listing_response.php';
	
	include 'payfast_pay_bid.php';
	include 'payfast_deposit.php';
	
	
//---------------------------------------

add_filter('AuctionTheme_payment_methods_tabs','AuctionTheme_payment_methods_tabs_payfast');
add_filter('AuctionTheme_payment_methods_content_divs_m','AuctionTheme_payment_methods_content_divs_payfast');
add_filter('AuctionTheme_payment_methods_action', 'AuctionTheme_payment_methods_action_payfast' );
add_filter('template_redirect','AT_templ_redir_gateways');

function AT_templ_redir_gateways()
{
	if($_GET['a_action'] == "payfast_listing")
	{
		AT_payfast_listing_fncs();
		die();	
	}
	
	if($_GET['a_action'] == "payfast_listing_response")
	{
		AT_payfast_listing_response_pfst();
		die();	
	
	}
	
	if(isset($_POST['amount_payfast']))
	{
		if(!empty($_POST['amount_payfast']) and is_numeric($_POST['amount_payfast']))
		{
			Payfast_deposit_auction_theme_me(); 
			die();	
		}
		else
		{
			global $am_err;
			$am_err = 1;	
		}
	}
	
	if($_GET['a_action'] == 'payfast_deposit_response')
	{
		AuctionTheme_payfast_deposit_response();
		die();	
	}
	
}

function AuctionTheme_payment_methods_action_payfast()
{
	
	if(isset($_POST['AuctionTheme_save_payfast']))
	{
		update_option('AuctionTheme_payfast_enable', 	trim($_POST['AuctionTheme_payfast_enable']));
		update_option('AuctionTheme_payfast_id', 		trim($_POST['AuctionTheme_payfast_id']));
		update_option('AuctionTheme_payfast_key', 		trim($_POST['AuctionTheme_payfast_key']));
		
		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';		
	}
		
}

function AuctionTheme_payment_methods_content_divs_payfast()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="tabs78" >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs78">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_payfast_enable'); ?></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Payfast Merchant ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_payfast_id" value="<?php echo get_option('AuctionTheme_payfast_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Payfast Merchant Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_payfast_key" value="<?php echo get_option('AuctionTheme_payfast_key'); ?>"/></td>
                    </tr>
                    
  
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_payfast" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          	
          </div>

<?php	
	
}

function AuctionTheme_payment_methods_tabs_payfast()
{
?>
		<li><a href="#tabs78">Payfast</a></li>
<?php	
}

?>