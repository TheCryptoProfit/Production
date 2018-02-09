<?php

include 'coinbase_listing.php';
include 'coinbase_deposit.php';

add_filter('AuctionTheme_payment_methods_content_divs',	'AuctionTheme_add_new_coinbase_cnt');
add_filter('AuctionTheme_payment_methods_tabs',			'AuctionTheme_add_new_coinbase_tab');
add_filter('AuctionTheme_payment_methods_action',		'AuctionTheme_add_new_coinbase_pst');
add_filter('template_redirect',		'auctiontheme_coinbase_temp_redir');

function auctiontheme_coinbase_temp_redir()
{
	if(!empty($_GET['coinbase_code']))
	{ 
		$code =  $_GET['coinbase_code'];
		
		$_CLIENT_ID 	= get_option('AuctionTheme_coinbase_id');
		$_CLIENT_SECRET = get_option('AuctionTheme_client_secret_key');
					 
		include( dirname(__FILE__).'/coinbase_php/lib/coinbase.php');
			
		//-------------------------------------------------------------------------------------
			
		$redirectUrl = str_replace("http://", "http://", plugins_url( 'AuctionTheme_gateways/coinbase/coinbase_redirect.php' )); //get_bloginfo('siteurl') . "/?bitcoins=1";
		$coinbaseOauth = new Coinbase_OAuth($_CLIENT_ID, $_CLIENT_SECRET, $redirectUrl);

		//----------------------	  
		
		$tokens = $coinbaseOauth->getTokens($code);
        update_option( 'coinbase_tokens', $tokens );
		wp_redirect(get_bloginfo('siteurl'). "/wp-admin");
		
		die();	
	}
	
	if(isset($_GET['my_custom_button_callback_coinbase_deposit']))
	{
		$uid = $_GET['my_custom_button_callback_coinbase_deposit'];
		$mc_gross =( $_POST['order']['total_native']['cents'] / 100);
		$tm = current_time('timestamp',0);
			$cr = auctionTheme_get_credits($uid);
			auctionTheme_update_credits($uid,$mc_gross + $cr);
			
			update_option('AuctionTheme_deposit_'.$uid.$tm, "1");
			$reason = __("Deposit through Coinbase.","AuctionTheme"); 
			auctionTheme_add_history_log('1', $reason, $mc_gross, $uid);
		
		die();	
	}
}
 
function AuctionTheme_add_new_coinbase_pst()
{
	
	if(isset($_POST['AuctionTheme_save_coinbase'])):
	
		$AuctionTheme_client_secret_key 		= trim($_POST['AuctionTheme_client_secret_key']);
		$AuctionTheme_coinbase_id 			= trim($_POST['AuctionTheme_coinbase_id']);
		$AuctionTheme_coinbase_enable 		= $_POST['AuctionTheme_coinbase_enable'];
		
		update_option('AuctionTheme_coinbase_enable',	$AuctionTheme_coinbase_enable);
		update_option('AuctionTheme_client_secret_key',		$AuctionTheme_client_secret_key);
		update_option('AuctionTheme_coinbase_id',		$AuctionTheme_coinbase_id);
		
		
		echo '<div class="saved_thing">Settings Saved</div>';
	
	endif;
} 
 
function AuctionTheme_add_new_coinbase_tab()
{
	?>
    
    	<li><a href="#tabs_coinbase">Coinbase - Bitcoins</a></li>
    
    <?php	
	
}

function AuctionTheme_add_new_coinbase_cnt()
{
		$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	$clientId = get_option('AuctionTheme_coinbase_id');
?>

<div id="tabs_coinbase"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs_coinbase">
          
          
            <?php
    
      if(!empty($clientId)) {
		  
		  	$_CLIENT_ID 	= get_option('AuctionTheme_coinbase_id');
			$_CLIENT_SECRET = get_option('AuctionTheme_client_secret_key');
			
 			 
			include( dirname(__FILE__).'/coinbase_php/lib/coinbase.php');
			
			//-------------------------------------------------------------------------------------
			
			$redirectUrl = str_replace("http://", "http://", plugins_url( 'AuctionTheme_gateways/coinbase/coinbase_redirect.php' )); //get_bloginfo('siteurl') . "/?bitcoins=1";
			echo 'Callback URL: '; echo $redirectUrl; echo '<br/> ';
			$coinbaseOauth = new Coinbase_OAuth($_CLIENT_ID, $_CLIENT_SECRET, $redirectUrl);
			  
		  
		  
      ?>
      
      <?php
        $authorizeUrl = $coinbaseOauth->createAuthorizeUrl("buttons");
      ?>
      <p>Please authorize this website with coinbase: <a href="<?php echo $authorizeUrl; ?>" class="button"><?php _e( 'Authorize Wordpress Plugin' ); ?></a></p>
                        <?php 
      }
 
                        ?>
          
          
          First, create an OAuth2 application for this plugin at <a href="https://coinbase.com/oauth/applications">https://coinbase.com/oauth/applications</a>
          <br/><br/>
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_coinbase_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Client ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_coinbase_id" value="<?php echo get_option('AuctionTheme_coinbase_id'); ?>"/> find this on: https://coinbase.com/oauth/applications</td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Client Secret:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_client_secret_key" value="<?php echo get_option('AuctionTheme_client_secret_key'); ?>"/> find this on: https://coinbase.com/oauth/applications</td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_coinbase" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
 	
	
}



?>