<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - AuctionTheme
*	More Info: http://sitemile.com/p/auctionTheme
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/	
		if(!function_exists('AuctionTheme_do_register_scr')) {
		function AuctionTheme_do_register_scr()
		{
		  global $wpdb, $wp_query ;
		
		  if (!is_array($wp_query->query_vars))
			$wp_query->query_vars = array();
		
		header('Content-Type: '.get_bloginfo('html_type').'; charset='.get_bloginfo('charset'));
		
	
		  switch( $_REQUEST["action"] ) 
		  {
			
			case 'register':
			  require_once( ABSPATH . WPINC . '/registration-functions.php');
			  
				$user_login = sanitize_user( str_replace(" ","",$_POST['user_login']) );
				$user_email = trim($_POST['user_email']);
			
				$sanitized_user_login = $user_login;
		
			
				
				$errors = AuctionTheme_register_new_user_sitemile($user_login, $user_email);
				
					if (!is_wp_error($errors)) 
					{	
						$ok_reg = 1;						
					}	
					
				
			  if ( 1 == $ok_reg ) 
			  {//continues after the break; 
				
				global $real_ttl;
				$real_ttl = __("Registration Complete", 'AuctionTheme');			
				add_filter( 'wp_title', 'AuctionTheme_sitemile_filter_ttl', 10, 3 );	
				
				get_header();
				 
				
		?>
				
				<div class="my_box3 breadcrumb-wrap">
            
            	<div class="box_title"><?php _e('Registration Complete','AuctionTheme') ?></div>
                <div class="padd10">
							<p><?php printf(__('Username: %s','AuctionTheme'), "<strong>" . esc_html($user_login) . "</strong>") ?><br />
							<?php printf(__('Password: %s','AuctionTheme'), '<strong>' . __('emailed to you','AuctionTheme') . '</strong>') ?> <br />
							<?php printf(__('E-mail: %s','AuctionTheme'), "<strong>" . esc_html($user_email) . "</strong>") ?><br /><br />
							<?php _e("Please check your <strong>Junk Mail</strong> if your account information does not appear within 5 minutes.",'AuctionTheme'); ?>
                            </p>

							<p class="submit"><a href="wp-login.php"><?php _e('Login', 'AuctionTheme'); ?> &raquo;</a></p>
						</div></div>
		<?php
								
				
				get_footer();
		
				die();
			break;
			  }//continued from the error check above
		
			default:
			
			global $real_ttl;
			$real_ttl = __("Register", 'AuctionTheme');			
			add_filter( 'wp_title', 'AuctionTheme_sitemile_filter_ttl', 10, 3 );	
			
			get_header();
			
		
				
				?>
				
				<div class="my_box3 breadcrumb-wrap">
            
            	<div class="box_title"><?php _e("Register",'AuctionTheme'); ?> - <?php echo  get_bloginfo('name'); ?></div>
                <div class="box_content">                                          
						  
						  <?php if ( isset($errors) && isset($_POST['action']) ) : ?>
						  <div class="bam_bam"> <div class="error">
							<ul>
							<?php 
							 
							$me = $errors->get_error_messages();
					 
						 	foreach($me as $mm)
							 echo "<li>".($mm)."</li>";
							
							
							 
							?>
							</ul>
						  </div> </div>
						  <?php endif; ?>
						  <div class="login-submit-form">
                          
                          
                          <form method="post" id="registerform" action="<?php echo esc_url( site_url('wp-login.php?action=register', 'login_post') ); ?>">
						  <input type="hidden" name="action" value="register" />	
							
							<p>
                            <label for="register-username"><?php _e('Username:','AuctionTheme') ?></label>
							<input type="text" class="do_input" name="user_login" id="user_login" size="30" maxlength="20" value="<?php echo esc_html($user_login); ?>" />
							</p>							
					
							<p>							 
							<label for="register-email"><?php _e('E-mail:','AuctionTheme') ?></label>
							<input type="text" class="do_input" name="user_email" id="user_email" size="30" maxlength="100" value="<?php echo esc_html($user_email); ?>" />
							</p>
							
                            <?php
							
								$AuctionTheme_force_paypal_address = get_option('AuctionTheme_force_paypal_address');
								if($AuctionTheme_force_paypal_address == "yes"):
								
								$paypal_email = $_POST['paypal_email'];
							 
								
							?>
                            
                
                            
                            <?php endif; ?>
                            
                            <?php
							
								$AuctionTheme_force_shipping_address = get_option('AuctionTheme_force_shipping_address');
								if($AuctionTheme_force_shipping_address == "yes"):
								
							 
								$shipping_info = $_POST['shipping_info'];
								
							?>
                            
                            <p>							 
							<label for="register-email"><?php _e('Shipping address:','AuctionTheme') ?></label>
							<input type="text" class="do_input" name="shipping_info" id="shipping_info" size="50" maxlength="100" value="<?php echo esc_html($shipping_info); ?>" />
							</p>	
                            
                            <?php endif; ?>
                        
							<?php do_action('register_form'); ?>

							<p><label for="submitbtn">&nbsp;</label>
							<?php _e('A password will be emailed to you.','AuctionTheme') ?></p>
							

                          
							
						<p class="submit">
                        <label for="submitbtn">&nbsp;</label>
							 <input type="submit" class="submit_bottom" value="<?php _e('Register','AuctionTheme') ?>" id="submits" name="submits" />
						</p>
                          
						  <ul id="logins">
							<li><a class="green_btn" href="<?php echo esc_url( home_url() ); ?>/" title="<?php _e('Are you lost?','AuctionTheme') ?>"><?php _e('Home','AuctionTheme') ?></a></li>
							<li><a class="green_btn" href="<?php echo esc_url( site_url() ); ?>/wp-login.php"><?php _e('Login','AuctionTheme') ?></a></li>
							<li><a class="green_btn" href="<?php echo esc_url( site_url() ); ?>/wp-login.php?action=lostpassword" title="<?php _e('Password Lost?','AuctionTheme') ?>"><?php _e('Lost your password?','AuctionTheme') ?></a></li>
						  </ul>
                          </form>
						</div>
                        
                        
                        
                        
                        </div>
                        </div>
                        
                        
		<?php
				
				
	 			  get_footer();
		
			  die();
			break;
			case 'disabled':
     
	 				global $real_ttl;
			$real_ttl = __("Registration Disabled", 'AuctionTheme');			
			add_filter( 'wp_title', 'AuctionTheme_sitemile_filter_ttl', 10, 3 );	
			
	 
	 			  get_header();
				
			
		?>
        <div class="clear10"></div>	
				<div class="my_box3 breadcrumb-wrap">
            	<div class="padd10">

        <div class="box_title"><?php _e('Registration Disabled','AuctionTheme') ?></div>
                <div class="box_content">
                                                  
							
							<p><?php _e('User registration is currently not allowed.','AuctionTheme') ?><br />
							<a href="<?php echo esc_url( site_url() ); ?>/" title="<?php _e('Go back to the blog','AuctionTheme') ?>"><?php _e('Home','AuctionTheme') ?></a>
							</p>
						</div></div></div>
		<?php
				
				 get_footer();
		
			  die();
			break;
		  }
		}

		}


//===================================================================
if(!function_exists('AuctionTheme_register_new_user_sitemile')) {
function AuctionTheme_register_new_user_sitemile( $user_login, $user_email ) {
	$errors = new WP_Error();
	 
	$sanitized_user_login = sanitize_user( $user_login );
	$user_email = apply_filters( 'user_registration_email', $user_email );

	// Check the username
	if ( $sanitized_user_login == '' ) {
		$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.', 'AuctionTheme' ) );
	} elseif ( ! validate_username( $user_login ) ) {
		$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.', 'AuctionTheme' ) );
		$sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
		$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered, please choose another one.', 'AuctionTheme' ) );
	}

	// Check the e-mail address
	if ( $user_email == '' ) {
		$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your e-mail address.', 'AuctionTheme' ) );
	} elseif ( ! is_email( $user_email ) ) {
		$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'AuctionTheme' ) );
		$user_email = '';
	} elseif ( email_exists( $user_email ) ) {
		$errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.', 'AuctionTheme' ) );
	}
	
	
	$AuctionTheme_force_shipping_address = get_option('AuctionTheme_force_shipping_address');
	if($AuctionTheme_force_shipping_address == "yes"):
	
		$shipping_info = $_POST['shipping_info'];
		
		if ( empty($shipping_info) ) {
			$errors->add( 'shipping_info', __( '<strong>ERROR</strong>: Please type your shipping information/address.', 'AuctionTheme' ) );
		}
	
	endif;
	
	//----------------------------------------
	
	$AuctionTheme_force_paypal_address = get_option('AuctionTheme_force_paypal_address');
	if($AuctionTheme_force_paypal_address == "yes"):
	
		$paypal_email = $_POST['paypal_email'];
		
		if ( empty($paypal_email) ) {
			//$errors->add( 'paypal_email', __( '<strong>ERROR</strong>: Please type your PayPal email address.', 'AuctionTheme' ) );
		}
	
	endif;
	
	do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

	$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

	if ( $errors->get_error_code() )
		return $errors;

	$user_pass = wp_generate_password( 12, false);
	$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
	if ( ! $user_id ) {
		$errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !', 'AuctionTheme' ), get_option( 'admin_email' ) ) );
		return $errors;
	}

	update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
	
	update_user_meta($user_id, 'shipping_info', $shipping_info);
	update_user_meta($user_id, 'paypal_email', $paypal_email);
	
	AuctionTheme_new_user_notification($user_id, $user_pass );
	AuctionTheme_new_user_notification_admin($user_id);
	
	return $user_id;
}
}

?>