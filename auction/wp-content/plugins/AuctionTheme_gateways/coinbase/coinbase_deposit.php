<?php
add_action('AuctionTheme_dposit_fields_page','AuctionTheme_dposit_fields_page_coinbase_fnc');

function AuctionTheme_dposit_fields_page_coinbase_fnc()
{
	
	$opt = get_option('AuctionTheme_coinbase_enable');
	if($opt == "yes"):
	
	?>
	
    <br/><br/>
	<strong><?php _e('Deposit money pay by Bitcoins','AuctionTheme'); ?></strong><br/><br/>
    <?php
	
		
	if(isset($_POST['deposit_bitcoins_me']))
	{
		
		$amount_bitcoins = $_POST['amount_bitcoins'];
		
		if(empty($amount_bitcoins))
		{
			echo '<div class="error">'.__('Please input a well formated amount to deposit.','AuctionTheme').'</div>';
			echo '<div class="clear10"></div>';	
		}
		else
		{
			global $wp_query, $wpdb, $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
			
			$_CLIENT_ID 	= get_option('AuctionTheme_coinbase_id');
			$_CLIENT_SECRET = get_option('AuctionTheme_client_secret_key');
			
 			 
			include( dirname(__FILE__).'/coinbase_php/lib/coinbase.php');
			
			//-------------------------------------------------------------------------------------
			
			$redirectUrl = str_replace("http://", "http://", plugins_url( 'AuctionTheme_gateways/coinbase/coinbase_redirect.php' )); //get_bloginfo('siteurl') . "/?bitcoins=1";
			$coinbaseOauth = new Coinbase_OAuth($_CLIENT_ID, $_CLIENT_SECRET, $redirectUrl);
			  
			 $args = array(
              'name' => __('Deposit Money','AuctionTheme'),
              'price_string' => $amount_bitcoins,
              'price_currency_iso' => get_option('AuctionTheme_currency'),
			  "callback_url" => get_bloginfo('siteurl') . "?my_custom_button_callback_coinbase_deposit=" . $uid,
              'custom' => $custom_id,
              'description' => __('Deposit Money','AuctionTheme'),
              'type' => 'buy_now',
              'style' => 'buy_now_large');
			  
			    
			$tokens = get_option( 'coinbase_tokens');
			  
			if($tokens) 
			  {
					try 
					{
				  		$coinbase = new Coinbase($coinbaseOauth, $tokens);
				  		$button = $coinbase->createButtonWithOptions($args)->embedHtml;
					} 
					catch (Coinbase_TokensExpiredException $e) 
					{
						  $tokens = $coinbaseOauth->refreshTokens($tokens);
						  update_option( 'coinbase_tokens', $tokens );
				
						  $coinbase = new Coinbase($coinbaseOauth, $tokens);
						  $button = $coinbase->createButtonWithOptions($args)->embedHtml;
					}
				 	echo '<br/>';
				 	echo $button;
					
				 	echo '<br/>';
					//return $button;
			  	} 
				else {
					
					//return "The Coinbase plugin has not been properly set up - please visit the Coinbase settings page in your administrator console.";
			  		echo 'Please set coinbase up right. From backend.';
					}
			
		}
	}
	
	
	?>            
                <form method="post">
                Amount to deposit: <input type="text" size="10" name="amount_bitcoins" value="<?php echo $_POST['amount_bitcoins']; ?>" /> <?php echo auctionTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit_bitcoins_me" value="<?php _e('Deposit','AuctionTheme'); ?>" /></form>
    
 
    
    <hr color="#dedede" />
    
    <?php
	
	endif;	
}

?>