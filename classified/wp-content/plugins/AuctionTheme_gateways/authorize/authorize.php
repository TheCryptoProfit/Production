<?php

include 'pay_for_listing.php';
include 'pay_for_listing_response.php';
include 'pay_for_deposit.php';
include 'pay_for_deposit_response.php';

add_filter('AuctionTheme_add_payment_options_to_post_new_project','AuctionTheme_add_payment_options_to_post_new_project_authorize');
add_filter('AuctionTheme_payment_methods_content_divs','AuctionTheme_add_new_authorize_cnt');
add_filter('AuctionTheme_payment_methods_action','AuctionTheme_add_new_authorize_pst');
add_filter('AuctionTheme_payment_methods_tabs','AuctionTheme_add_new_authorize_tab');
add_filter('template_redirect','auction_theme_payment_template_redir');
add_filter('AuctionTheme_dposit_fields_page','AuctionTheme_dposit_fields_page_authorize_net');


function AuctionTheme_dposit_fields_page_authorize_net()
{
	$opt = get_option('AuctionTheme_authorize_enable');
				if($opt =="yes"):				
				?>
                
                <br/><br/>
                <strong><?php _e('Deposit money by Authorize.NET','AuctionTheme'); ?></strong><br/><br/>
                
                <script>
				function return_me_function_auth()
				{
  	
						var deposit_pay_me_authorize_amount = jQuery("#deposit_pay_me_authorize_amount").val();
						if(deposit_pay_me_authorize_amount.length == 0)
						{
							alert("Please input a value.");
							return false;	
						}
						
						if(!jQuery.isNumeric(deposit_pay_me_authorize_amount))
						{
							alert("Please input a numeric value.");
							return false;	
						}
						
						if(deposit_pay_me_authorize_amount < 0)
						{
							alert("Please input a positive value.");
							return false;	
						}
 						return true;
				}
				
				</script>
                
                <form method="post" onsubmit="return return_me_function_auth();">
               <?php _e('Amount to deposit:','AuctionTheme'); ?> <input type="text" size="10" name="amount" id="deposit_pay_me_authorize_amount" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" id="deposit_pay_me_authorize" name="deposit_pay_me_authorize" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    			<hr color="#dedede" />
    <?php endif; 
		
	
}

function auction_theme_payment_template_redir()
{
	if(	$_GET['a_action'] == "authorize_listing_payment")
	{
		auctiontheme_payment_for_authorize_form_listing($_GET['pid']);
		die();	
	}
	
	if(isset($_GET['autho_resp_listing']))
	{
		AT_authorize_resp_listing();
		die();	
	}
	
	if(isset($_GET['autho_resp_listing_deposit']))
	{
		AT_authorize_resp_deposit();
		die();	
	}
	
	
	if(isset($_POST['deposit_pay_me_authorize']))
	{
		$amount = $_POST['amount'];
		global $my_amount;
		$my_amount = $amount;
		
		auctiontheme_payment_for_authorize_form_deposit($my_amount);
		
		die();	
	}
	
}

function AuctionTheme_add_payment_options_to_post_new_project_authorize($pid)
{
	echo '<a href="'.get_bloginfo('siteurl').'/?a_action=authorize_listing_payment&pid='.$pid.'" class="post_bid_btn">'.__('Pay by Authorize.NET','AuctionTheme'). '</a>';	
}

function AuctionTheme_add_new_authorize_tab()
{
	?>
    
    	<li><a href="#tabs_authorize">Authorize</a></li>
    
    <?php	
	
}


function AuctionTheme_add_new_authorize_pst()
{
	
	if(isset($_POST['AuctionTheme_save_auth'])):
	
	$AuctionTheme_authorize_key 		= trim($_POST['AuctionTheme_authorize_key']);
	$AuctionTheme_authorize_id 			= trim($_POST['AuctionTheme_authorize_id']);
	$AuctionTheme_authorize_enable 		= $_POST['AuctionTheme_authorize_enable'];
	
	update_option('AuctionTheme_authorize_enable',	$AuctionTheme_authorize_enable);
	update_option('AuctionTheme_authorize_key',		$AuctionTheme_authorize_key);
	update_option('AuctionTheme_authorize_id',		$AuctionTheme_authorize_id);
	
	endif;
}


function AuctionTheme_add_new_authorize_cnt()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="tabs_authorize"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs_authorize">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_authorize_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Authorize Login ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_authorize_id" value="<?php echo get_option('AuctionTheme_authorize_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Authorize Transaction Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_authorize_key" value="<?php echo get_option('AuctionTheme_authorize_key'); ?>"/></td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_auth" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}


?>