<?php
/**
* Plugin Name: Social Integration
* Plugin URI: https://www.thecryptoprofit.com
* Description: Social Integration
* Version: 99.999
* Author: Social_Integration
* Author URI: https://www.thecryptoprofit.com
* License: GPL2
*/

require('handler/oauth_handler.php');
include_once dirname( __FILE__ ) . '/class-mo-oauth-widget.php';
require('mo_oauth_settings_page.php');


class mo_oauth {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'Social_Integration_menu' ) );
		add_action( 'admin_init',  array( $this, 'Social_Integration_oauth_save_settings' ) );
		add_action( 'plugins_loaded',  array( $this, 'mo_login_widget_text_domain' ) );
		add_shortcode('mo_oauth_login', array( $this,'mo_oauth_shortcode_login'));
	}

	
	private $settings = array(
		'mo_oauth_facebook_client_secret'	=> '',
		'mo_oauth_facebook_client_id' 		=> '',
		'mo_oauth_facebook_enabled' 		=> 0
	);

	function Social_Integration_menu() {

		//Add Social_Integration plugin to the menu
		$page = add_menu_page( 'Settings ' . __( 'Configure OAuth', 'mo_oauth_settings' ), 'Social Integration', 'administrator', 'mo_oauth_settings', array( $this, 'mo_oauth_login_options' ) );

		

		global $submenu;
		if ( is_array( $submenu ) AND isset( $submenu['mo_oauth_settings'] ) )
		{
			$submenu['mo_oauth_settings'][0][0] = __( 'Configure OAuth', 'mo_oauth_login' );
		}
	}

	function  mo_oauth_login_options () {
		mo_register();
	}

	
	

	function mo_login_widget_text_domain(){
		load_plugin_textdomain( 'flw', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	private function mo_oauth_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_oauth_success_message') );
		add_action( 'admin_notices', array( $this, 'mo_oauth_error_message') );
	}

	private function mo_oauth_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'mo_oauth_error_message') );
		add_action( 'admin_notices', array( $this, 'mo_oauth_success_message') );
	}

	public function mo_oauth_check_empty_or_null( $value ) {
		if( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}

	function Social_Integration_oauth_save_settings(){
		if( isset( $_POST['option'] ) and $_POST['option'] == "mo_oauth_add_app" ) {
			$scope = '';
			$clientid = '';
			$clientsecret = '';
			if($this->mo_oauth_check_empty_or_null($_POST['mo_oauth_client_id']) || $this->mo_oauth_check_empty_or_null($_POST['mo_oauth_client_secret'])) {
				update_option( 'message', 'Please enter valid Client ID and Client Secret.');
				$this->mo_oauth_show_error_message();
				return;
			} else{
				$scope = stripslashes(sanitize_text_field( $_POST['mo_oauth_scope'] ));
				$clientid = stripslashes(sanitize_text_field( $_POST['mo_oauth_client_id'] ));
				$clientsecret = stripslashes(sanitize_text_field( $_POST['mo_oauth_client_secret'] ));
				$appname = stripslashes(sanitize_text_field( $_POST['mo_oauth_app_name'] ));


				if(get_option('mo_oauth_apps_list'))
					$appslist = get_option('mo_oauth_apps_list');
				else
					$appslist = array();

				$email_attr = "";
				$name_attr = "";
				$newapp = array();

				$isupdate = false;
				foreach($appslist as $key => $currentapp){
					if($appname == $key){
						$newapp = $currentapp;
						$isupdate = true;
						break;
					}
				}


				$newapp['clientid'] = $clientid;
				$newapp['clientsecret'] = $clientsecret;
				$newapp['scope'] = $scope;
				$newapp['redirecturi'] = site_url().'/socintecallback';
				
				$authorizeurl = stripslashes(sanitize_text_field($_POST['mo_oauth_authorizeurl']));
				$accesstokenurl = stripslashes(sanitize_text_field($_POST['mo_oauth_accesstokenurl']));
				$resourceownerdetailsurl = stripslashes(sanitize_text_field($_POST['mo_oauth_resourceownerdetailsurl']));
				$appname = stripslashes(sanitize_text_field( $_POST['mo_oauth_custom_app_name'] ));
				//$email_attr = sanitize_text_field( $_POST['mo_oauth_email_attr'] );
				//$name_attr = sanitize_text_field( $_POST['mo_oauth_name_attr'] );
			

				$newapp['authorizeurl'] = $authorizeurl;
				$newapp['accesstokenurl'] = $accesstokenurl;
				$newapp['resourceownerdetailsurl'] = $resourceownerdetailsurl;
				//$newapp['email_attr'] = $email_attr;
				//$newapp['name_attr'] = $name_attr;
				$appslist[$appname] = $newapp;
				update_option('mo_oauth_apps_list', $appslist);
				//update_option( 'message', 'Your settings are saved successfully.' );
				//$this->mo_oauth_show_success_message();
				wp_redirect('admin.php?page=mo_oauth_settings&action=update&app='.urlencode($appname));
			}
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_oauth_app_customization" ) {
			update_option( 'mo_oauth_icon_width', stripslashes(sanitize_text_field($_POST['mo_oauth_icon_width'])));
			update_option( 'mo_oauth_icon_height', stripslashes(sanitize_text_field($_POST['mo_oauth_icon_height'])));
			update_option( 'mo_oauth_icon_margin', stripslashes(sanitize_text_field($_POST['mo_oauth_icon_margin'])));
			update_option('mo_oauth_icon_configure_css', stripcslashes(sanitize_text_field($_POST['mo_oauth_icon_configure_css'])));
			update_option( 'message', 'Your settings were saved' );
			$this->mo_oauth_show_success_message();
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_oauth_attribute_mapping" ) {
			$appname = stripslashes(sanitize_text_field( $_POST['mo_oauth_app_name'] ));
			$email_attr = stripslashes(sanitize_text_field( $_POST['mo_oauth_email_attr'] ));
			$name_attr = stripslashes(sanitize_text_field( $_POST['mo_oauth_name_attr'] ));

			$appslist = get_option('mo_oauth_apps_list');
			foreach($appslist as $key => $currentapp){
				if($appname == $key){
					$currentapp['email_attr'] = $email_attr;
					$currentapp['name_attr'] = $name_attr;
					$appslist[$key] = $currentapp;
					break;
				}
			}
			update_option('mo_oauth_apps_list', $appslist);
			update_option( 'message', 'Your settings are saved successfully.' );
			$this->mo_oauth_show_success_message();
			wp_redirect('admin.php?page=mo_oauth_settings&action=update&app='.urlencode($appname));
		}
		
	}

	function mo_oauth_shortcode_login(){
		$mowidget = new Mo_Oauth_Widget;
		$mowidget->mo_oauth_login_form();
	}

}

	

	

new mo_oauth;