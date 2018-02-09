<?php

include 'payson_listing.php';

add_filter('AuctionTheme_payment_methods_tabs',				'AuctionTheme_payment_methods_tabs_payson');
add_filter('AuctionTheme_payment_methods_content_divs_m',	'AuctionTheme_payment_methods_content_divs_payson');
add_filter('AuctionTheme_payment_methods_action', 			'AuctionTheme_payment_methods_action_payson' );
add_filter('template_redirect','AT_templ_redir_gateways_payson');


function AT_templ_redir_gateways_payson()
{
	if($_GET['a_action'] == "payson_listing")
	{
		AT_payson_listing_fncs();
		die();	
	}
	
	if($_GET['a_action'] == "payson_listing_response")
	{
		AT_payson_listing_response_pfst();
		die();	
	
	}
	
	if(isset($_POST['amount_payson']))
	{
		if(!empty($_POST['amount_payson']) and is_numeric($_POST['amount_payson']))
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
	
	if(isset($_GET['payson_deposit_response']))
	{
		AuctionTheme_payson_deposit_response();
		die();	
	}
	
}

function AuctionTheme_payment_methods_action_payson()
{
	if(isset($_POST['AuctionTheme_save_payson']))
	{
		update_option('AuctionTheme_ideal_payson_ID', 		trim($_POST['AuctionTheme_ideal_payson_ID']));
		update_option('AuctionTheme_payson_enable', 		trim($_POST['AuctionTheme_payson_enable']));
		update_option('AuctionTheme_ideal_payson_key', 		trim($_POST['AuctionTheme_ideal_payson_key']));
		update_option('AuctionTheme_ideal_payson_em', 		trim($_POST['AuctionTheme_ideal_payson_em']));
		
		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';		
	}
}

function AuctionTheme_payment_methods_content_divs_payson()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="tabs99" >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs99">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_payson_enable'); ?></td>
                    </tr>
                    
                                  <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Payson ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_ideal_payson_ID" value="<?php echo get_option('AuctionTheme_ideal_payson_ID'); ?>"/></td>
                    </tr>
                    
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Payson Email:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_ideal_payson_em" value="<?php echo get_option('AuctionTheme_ideal_payson_em'); ?>"/></td>
                    </tr>
                    
                    
                     <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Payson Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_ideal_payson_key" value="<?php echo get_option('AuctionTheme_ideal_payson_key'); ?>"/></td>
                    </tr>
                    
  
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_payson" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          	
          </div>

<?php	
	
}

function AuctionTheme_payment_methods_tabs_payson()
{
?>
		<li><a href="#tabs99">Payson</a></li>
<?php	
}
