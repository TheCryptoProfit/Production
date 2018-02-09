<?php 
/**
 * Automated testing class
 * Test the settings, and try fix detected misconfigutations and errors
 * @author null
 *
 */
class SwiftSecurityTroubleshooting{
	
	/**
	 * Create the Troubleshooting object
	 */
	public function __construct($settings){		
		$this->settings = $settings;
		$this->step = (isset($_POST['step']) ? $_POST['step'] : '');
		$this->url	= (isset($_POST['url']) ? $_POST['url'] : '');
		
		add_action('wp_ajax_SwiftSecurityTroubleshooting', array($this, 'Troubleshooting'));
	}
	
	
	/**
	 * Rung the test
	 */
	public function Troubleshooting(){
		//Check wp-nonce
		check_ajax_referer( 'swiftsecurity', 'wp-nonce' );
		
		SwiftSecurity::RemovePermanentMessage('AutomatedTesting');
		
		if (!preg_match('~^'.home_url().'~',$this->url)){
			$Response = array(
					'nextstep'	=> false,
					'msg'		=> __('You can check only this site', 'SwiftSecurity') . ' ('.home_url().')',
					'type'		=> 'error'
			);
			
			wp_die(json_encode($Response));
		}
		
		//Set all ok by default
		$Response = array(
				'nextstep'	=> false,
				'msg'		=> __('No error found', 'SwiftSecurity'),
				'type'		=> 'success'
		);
		
		//If Hide Wordpress is on
		if ($this->settings['Modules']['HideWP'] == 'enabled'){
			//Check hardcoded upload directory
			if ($this->step == 'hardcoded_upload_dir'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));

				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{			
					if (preg_match('~'.home_url(WP_CONTENT_DIRNAME . '/uploads').'~', $result['body']) || preg_match('~'.str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/uploads')).'~', $result['body'])){
						$Response = array(
								'nextstep'	=> 'fix_hardcoded_upload_dir',
								'msg'		=> __('Hardcoded upload directory detected', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'hardcoded_plugin_dir',
								'msg'		=> __('Upload directory - OK', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			else if ($this->step == 'fix_hardcoded_upload_dir'){
				//Check upload dir
				if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource'][home_url(WP_CONTENT_DIRNAME . '/uploads')])){
					$this->settings['HideWP']['regexInSource'][home_url(WP_CONTENT_DIRNAME . '/uploads')] = home_url($this->settings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads']);
				}
				if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource'][str_replace('/', '\\\\/',home_url(WP_CONTENT_DIRNAME . '\/uploads'))])){
					$this->settings['HideWP']['regexInSource'][str_replace('/', '\\\\/',home_url(WP_CONTENT_DIRNAME . '/uploads'))] = str_replace('/', '\/',home_url($this->settings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads']));
				}
				
				//Save settings
				update_option('swiftsecurity_plugin_options', $this->settings);
				
				$Response = array(
						'nextstep'	=> 'recheck_hardcoded_upload_dir',
						'msg'		=> __('Adding Regular expression in source rule...', 'SwiftSecurity'),
						'type'		=> 'info'
				);
				
			}
			else if ($this->step == 'recheck_hardcoded_upload_dir'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));

				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{			
					if (preg_match('~'.home_url(WP_CONTENT_DIRNAME . '/uploads').'~', $result['body']) || preg_match('~'.str_replace('/','\/',home_url(WP_CONTENT_DIRNAME . '/uploads')).'~', $result['body'])){
						$Response = array(
								'nextstep'	=> 'hardcoded_plugin_dir',
								'msg'		=> __('Couldn`t fix error, please send bug report to support.', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'hardcoded_plugin_dir',
								'msg'		=> __('Hardcoded upload directory fixed', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			
			//Check hardcoded plugin directory
			else if ($this->step == 'hardcoded_plugin_dir'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));
			
				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{
					if (preg_match('~'.home_url(WP_CONTENT_DIRNAME . '/plugins').'~', $result['body']) || preg_match('~'.str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/plugins')).'~', $result['body'])){
						$Response = array(
								'nextstep'	=> 'fix_hardcoded_plugins_dir',
								'msg'		=> __('Hardcoded plugin directory detected', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'hardcoded_template_dir',
								'msg'		=> __('Plugins directory - OK', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			else if ($this->step == 'fix_hardcoded_plugins_dir'){
				
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));
					
				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{
						preg_match_all('~'.home_url(WP_CONTENT_DIRNAME . '/plugins/').'([^/]*)/~', $result['body'], $plugin_dirs);
						preg_match_all('~'.str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/plugins/')).'([^\\\\/]*)\\\\/~', $result['body'], $escaped_plugin_dirs);
										
						//Check pligin dirs
						foreach ($plugin_dirs[1] as $plugin_dir){
							if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource'][home_url(WP_CONTENT_DIRNAME . '/plugins/' . $plugin_dir)])){
								$this->settings['HideWP']['regexInSource'][home_url(WP_CONTENT_DIRNAME . '/plugins/' . $plugin_dir)] = home_url($this->settings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] . '/' . $this->settings['HideWP']['plugins'][$plugin_dir]);
							}
						}
						foreach ($escaped_plugin_dirs[1] as $plugin_dir){
							if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource'][str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/plugins/' . $plugin_dir))])){
								$this->settings['HideWP']['regexInSource'][str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/plugins/' . $plugin_dir))] = str_replace('/','\/',home_url($this->settings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/plugins'] . '/'. $this->settings['HideWP']['plugins'][str_replace('\/','/',$plugin_dir)]));
							}
						}
				}
						
				//Save settings
				update_option('swiftsecurity_plugin_options', $this->settings);
			
				$Response = array(
						'nextstep'	=> 'recheck_hardcoded_plugin_dir',
						'msg'		=> __('Adding Regular expression in source rule...', 'SwiftSecurity'),
						'type'		=> 'info'
				);
			
			}
			else if ($this->step == 'recheck_hardcoded_plugin_dir'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));
			
				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{
					if (preg_match('~'.home_url(WP_CONTENT_DIRNAME . '/plugins').'~', $result['body']) || preg_match('~'.str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/plugins')).'~', $result['body'])){
						$Response = array(
								'nextstep'	=> 'hardcoded_template_dir',
								'msg'		=> __('Couldn`t fix error, please send bug report to support.', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'hardcoded_template_dir',
								'msg'		=> __('Hardcoded plugin directory fixed', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			
			//Check hardcoded template directory
			elseif ($this->step == 'hardcoded_template_dir'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));
			
				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{
					if ((preg_match('~'.home_url(WP_CONTENT_DIRNAME . '/themes'.get_template()).'~', $result['body']) || preg_match('~'.str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/themes/'.get_template())).'~', $result['body'])) || (preg_match('~'.home_url(WP_CONTENT_DIRNAME . '/themes'.get_stylesheet()).'~', $result['body']) || preg_match('~'.str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet())).'~', $result['body']))){
						$Response = array(
								'nextstep'	=> 'fix_hardcoded_template_dir',
								'msg'		=> __('Hardcoded template directory detected', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'hardcoded_ajax',
								'msg'		=> __('Template directory - OK', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			else if ($this->step == 'fix_hardcoded_template_dir'){
				//Check template dir
				if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource'][home_url(WP_CONTENT_DIRNAME . '/themes/'.get_template())])){
					$this->settings['HideWP']['regexInSource'][home_url(WP_CONTENT_DIRNAME . '/themes/'.get_template())] = home_url($this->settings['HideWP']['redirectTheme']);
				}
				if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource'][str_replace('/', '\\\\/',home_url(WP_CONTENT_DIRNAME . '\/themes\/'.get_template()))])){
					$this->settings['HideWP']['regexInSource'][str_replace('/', '\\\\/',home_url(WP_CONTENT_DIRNAME . '/themes/'.get_template()))] = str_replace('/', '\/',home_url($this->settings['HideWP']['redirectTheme']));
				}
				if (is_child_theme()){
					if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource'][home_url(WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet())])){
						$this->settings['HideWP']['regexInSource'][home_url(WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet())] = home_url($this->settings['HideWP']['redirectChildTheme']);
					}
					if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource'][str_replace('/', '\\\\/',home_url(WP_CONTENT_DIRNAME . '\/themes\/'.get_stylesheet()))])){
						$this->settings['HideWP']['regexInSource'][str_replace('/', '\\\\/',home_url(WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet()))] = str_replace('/', '\/',home_url($this->settings['HideWP']['redirectChildTheme']));
					}
				}
				//Save settings
				update_option('swiftsecurity_plugin_options', $this->settings);
			
				$Response = array(
						'nextstep'	=> 'recheck_hardcoded_template_dir',
						'msg'		=> __('Adding Regular expression in source rule...', 'SwiftSecurity'),
						'type'		=> 'info'
				);
			
			}
			else if ($this->step == 'recheck_hardcoded_template_dir'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));
			
				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{
					if ((preg_match('~'.home_url(WP_CONTENT_DIRNAME . '/themes'.get_template()).'~', $result['body']) || preg_match('~'.str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/themes/'.get_template())).'~', $result['body'])) || (preg_match('~'.home_url(WP_CONTENT_DIRNAME . '/themes'.get_stylesheet()).'~', $result['body']) || preg_match('~'.str_replace('/','\\\\/',home_url(WP_CONTENT_DIRNAME . '/themes/'.get_stylesheet())).'~', $result['body']))){
						$Response = array(
								'nextstep'	=> 'hardcoded_ajax',
								'msg'		=> __('Couldn`t fix error, please send bug report to support.', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'hardcoded_ajax',
								'msg'		=> __('Hardcoded upload directory fixed', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
	
			
			//Check hardcoded ajax url
			elseif ($this->step == 'hardcoded_ajax'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));
					
				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{
					if (preg_match('~wp-admin\\\\/admin-ajax\.php~', $result['body']) || preg_match('~wp-admin/admin-ajax\.php~', $result['body'])){
						$Response = array(
								'nextstep'	=> 'fix_hardcoded_ajax',
								'msg'		=> __('Hardcoded ajax URL detected', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'timthumb_src',
								'msg'		=> __('Ajax URL - OK', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			else if ($this->step == 'fix_hardcoded_ajax'){
				//Fix ajax url
				if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource']['wp-admin\\/admin-ajax\.php'])){
					$this->settings['HideWP']['regexInSource']['wp-admin\\\\/admin-ajax\.php'] = $this->settings['HideWP']['redirectFiles']['wp-admin/admin-ajax.php'];
					$this->settings['HideWP']['regexInSource']['wp-admin/admin-ajax\.php'] = $this->settings['HideWP']['redirectFiles']['wp-admin/admin-ajax.php'];
				}

				//Save settings
				update_option('swiftsecurity_plugin_options', $this->settings);
					
				$Response = array(
						'nextstep'	=> 'recheck_hardcoded_ajax',
						'msg'		=> __('Adding Regular expression in source rule...', 'SwiftSecurity'),
						'type'		=> 'info'
				);
					
			}
			else if ($this->step == 'recheck_hardcoded_ajax'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));
					
				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{
					if (preg_match('~wp-admin\\\\/admin-ajax\.php~', $result['body'])){
						$Response = array(
								'nextstep'	=> 'timthumb_src',
								'msg'		=> __('Couldn`t fix error, please send bug report to support.', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'timthumb_src',
								'msg'		=> __('Hardcoded ajax URL fixed', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			
			
			//Check timthumb src
			else if ($this->step == 'timthumb_src'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));

				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{			
					if (preg_match('~timthumb\.php\?src='.home_url($this->settings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads']).'~', $result['body'])){
						$Response = array(
								'nextstep'	=> 'fix_timthumb_src',
								'msg'		=> __('Wrong Timthumb src detected', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'wpcron',
								'msg'		=> __('Timthumb src - OK', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			else if ($this->step == 'fix_timthumb_src'){
				if (!isset($this->settings['HideWP']['regexInSource']) || !is_array($this->settings['HideWP']['regexInSource']) || !isset($this->settings['HideWP']['regexInSource']['timthumb\.php\?src='.home_url()])){
					$this->settings['HideWP']['regexInSource']['timthumb\.php\?src='.home_url($this->settings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads'])] = 'timthumb.php?src=uploads';
				}
				
				//Save settings
				update_option('swiftsecurity_plugin_options', $this->settings);
				
				$Response = array(
						'nextstep'	=> 'recheck_timthumb_src',
						'msg'		=> __('Adding Regular expression in source rule...', 'SwiftSecurity'),
						'type'		=> 'info'
				);
				
			}
			else if ($this->step == 'recheck_timthumb_src'){
				$result = wp_remote_get($this->url, array('timeout' => 60, 'sslverify' => false));

				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{			
					if (preg_match('~timthumb\.php\?src='.home_url($this->settings['HideWP']['redirectDirs'][WP_CONTENT_DIRNAME . '/uploads']).'~', $result['body'])){
						$Response = array(
								'nextstep'	=> 'wpcron',
								'msg'		=> __('Couldn`t fix error, please send bug report to support.', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> 'wpcron',
								'msg'		=> __('Timthumb fixed', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			
			//Check wp-cron.php
			else if ($this->step == 'wpcron'){
				$result = wp_remote_get(site_url('wp-cron.php'), array('timeout' => 60, 'sslverify' => false));
			
				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{
					if ($result['response']['code'] == '404'){
						$Response = array(
								'nextstep'	=> 'fix_wpcron',
								'msg'		=> __('wp-cron.php disabled', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> false,
								'msg'		=> __('wp-cron.php - OK', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
			else if ($this->step == 'fix_wpcron'){
				if (!isset($this->settings['HideWP']['directPHP']) || !is_array($this->settings['HideWP']['directPHP']) || !in_array('wp-cron.php',$this->settings['HideWP']['directPHP'])){
					$this->settings['HideWP']['directPHP'][] = 'wp-cron.php';
				}
			
				//Save settings
				update_option('swiftsecurity_plugin_options', $this->settings);
			
				$Response = array(
						'nextstep'	=> 'recheck_wpcron',
						'msg'		=> __('Adding Direct PHP exception...', 'SwiftSecurity'),
						'type'		=> 'info'
				);
			
			}
			else if ($this->step == 'recheck_wpcron'){
				$result = wp_remote_get(site_url('wp-cron.php'), array('timeout' => 60, 'sslverify' => false));
			
				if (is_wp_error($result)){
					$Response = array(
							'nextstep'	=> false,
							'msg'		=> $result->get_error_message(),
							'type'		=> 'error'
					);
				}
				else{
					if ($result['response']['code'] == '404'){
						$Response = array(
								'nextstep'	=> false,
								'msg'		=> __('Couldn`t fix error, please send bug report to support.', 'SwiftSecurity'),
								'type'		=> 'error'
						);
					}
					else{
						$Response = array(
								'nextstep'	=> false,
								'msg'		=> __('wp-cron.php fixed', 'SwiftSecurity'),
								'type'		=> 'success'
						);
					}
				}
			}
		}
		
		wp_die(json_encode($Response));
	}
	
}

?>