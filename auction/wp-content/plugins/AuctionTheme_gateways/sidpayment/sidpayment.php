<?php

add_filter('AuctionTheme_payment_methods_tabs',				'AuctionTheme_payment_methods_tabs_sds');
add_filter('AuctionTheme_payment_methods_content_divs_m',	'AuctionTheme_payment_methods_content_divs_sidpayment');
add_filter('AuctionTheme_payment_methods_action', 			'AuctionTheme_payment_methods_action_sds' );
add_filter('AuctionTheme_pay_for_item_page','AuctionTheme_pay_for_item_page_sid_payment');
add_filter('template_redirect','aT_sids_pay_template_redirect');

function aT_sids_pay_template_redirect()
{
	if(isset($_GET['sidpayment']))
	{
		include 'sid_pay_form.php';
		die();	
	}
	
	if(isset($_GET['sid_answer']))
	{
		include 'my_answer.php';
		die();		
	}
	
}

function AuctionTheme_pay_for_item_page_sid_payment($bid_id)
{
	$AuctionTheme_sidpayment_enable = get_option('AuctionTheme_sidpayment_enable');
	if($AuctionTheme_sidpayment_enable == "yes"):
	
	?>
    
      <a class="post_bid_btn" href="<?php bloginfo('siteurl'); ?>/?sidpayment=1&bid_id=<?php echo $bid_id; ?>"><?php _e('Pay by SID Payment','AuctionTheme'); ?></a><br/><br/>
    
    <?php	
	endif;
	
}

function AuctionTheme_payment_methods_action_sds()
{
	
	if(isset($_POST['AuctionTheme_save_sidpayment']))
	{
		update_option('AuctionTheme_sidpayment_enable', 	trim($_POST['AuctionTheme_sidpayment_enable']));
		update_option('AuctionTheme_sidpayment_id', 		trim($_POST['AuctionTheme_sidpayment_id']));
		update_option('AuctionTheme_sidpyament_key', 		trim($_POST['AuctionTheme_sidpyament_key']));
		
		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';		
	}
		
}

function AuctionTheme_payment_methods_tabs_sds()
{
?>
		<li><a href="#tabssidpayment">SidPayment</a></li>
<?php	
}

function AuctionTheme_payment_methods_content_divs_sidpayment()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="tabssidpayment" >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabssidpayment">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_sidpayment_enable'); ?></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Merchant ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_sidpayment_id" value="<?php echo get_option('AuctionTheme_sidpayment_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Merchant Secret Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_sidpyament_key" value="<?php echo get_option('AuctionTheme_sidpyament_key'); ?>"/></td>
                    </tr>
                    
  
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_sidpayment" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          	
          </div>

<?php	
	
}

?>