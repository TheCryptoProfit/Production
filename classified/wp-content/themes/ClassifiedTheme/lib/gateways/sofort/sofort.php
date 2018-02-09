<?php

add_filter('AuctionTheme_payment_methods_tabs',				'AuctionTheme_payment_methods_tabs_sofort');
add_filter('AuctionTheme_payment_methods_content_divs_m',	'AuctionTheme_payment_methods_content_divs_sofort');
add_filter('AuctionTheme_payment_methods_action', 			'AuctionTheme_payment_methods_action_sofort' );

function AuctionTheme_payment_methods_action_sofort()
{
	
	if(isset($_POST['AuctionTheme_save_sofort']))
	{
		update_option('AuctionTheme_sofort_enable', 	trim($_POST['AuctionTheme_sofort_enable']));
		update_option('AuctionTheme_sofort_id', 		trim($_POST['AuctionTheme_sofort_id']));
		update_option('AuctionTheme_sofort_key', 		trim($_POST['AuctionTheme_sofort_key']));
		
		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';		
	}
		
}

function AuctionTheme_payment_methods_content_divs_sofort()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="sofort_tb" >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=sofort_tb">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_sofort_enable'); ?></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Merchant ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_sofort_id" value="<?php echo get_option('AuctionTheme_sofort_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Secret Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_sofort_key" value="<?php echo get_option('AuctionTheme_sofort_key'); ?>"/></td>
                    </tr>
                    
  
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_sofort" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          	
          </div>

<?php	
	
}


function AuctionTheme_payment_methods_tabs_sofort()
{
?>
		<li><a href="#sofort_tb">Sofort</a></li>
<?php	
}


?>