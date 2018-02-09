<?php
add_filter('AuctionTheme_payment_methods_tabs',			'AuctionTheme_add_new_firstdata_tab');
add_filter('AuctionTheme_payment_methods_content_divs',	'AuctionTheme_add_new_firstdata_cnt');
add_filter('AuctionTheme_payment_methods_action',		'AuctionTheme_add_new_frstdata_pst');
add_filter('template_redirect',							'AuctionTheme_firstdata_temp_redir');
add_filter('AuctionTheme_membership_payment_links_act', 'AuctionTheme_membership_payment_links_act_firstdata');

function AuctionTheme_firstdata_temp_redir()
{
	if(isset($_GET['pay_membership_payson']))
	{
		include 'firstdata_pay_membership.php';	
		die();
	}
	
	if(isset($_GET['relay_response']))
	{
	
		include 'firstdata_response_pay_mem.php';
		die();	
	}
	
}

function AuctionTheme_membership_payment_links_act_firstdata()
{
	?>
    	
        <a href="<?php bloginfo('siteurl') ?>/?id=<?php echo $_GET['id'] ?>&pay_membership_payson=1" class="post_bid_btn"><?php _e('Pay by FirstData','AuctionTheme') ?></a>
    
    <?php	
	
}

function AuctionTheme_add_new_frstdata_pst()
{
	
	if(isset($_POST['AuctionTheme_save_frst'])):
	
	$AuctionTheme_firstdata_key 		= trim($_POST['AuctionTheme_firstdata_key']);
	$AuctionTheme_firstdata_id 			= trim($_POST['AuctionTheme_firstdata_id']);
	$AuctionTheme_firstdata_enable 		= $_POST['AuctionTheme_firstdata_enable'];
	
	update_option('AuctionTheme_firstdata_enable',	$AuctionTheme_firstdata_enable);
	update_option('AuctionTheme_firstdata_key',		$AuctionTheme_firstdata_key);
	update_option('AuctionTheme_firstdata_id',		$AuctionTheme_firstdata_id);
	
	endif;
}

function AuctionTheme_add_new_firstdata_tab()
{
	?>
    
    	<li><a href="#tabs_firstdata">Firstdata</a></li>
    
    <?php	
	
}


function AuctionTheme_add_new_firstdata_cnt()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="tabs_firstdata"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs_firstdata">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_firstdata_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Firstdata Login ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_firstdata_id" value="<?php echo get_option('AuctionTheme_firstdata_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Firstdata Transaction Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_firstdata_key" value="<?php echo get_option('AuctionTheme_firstdata_key'); ?>"/></td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_frst" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}

?>