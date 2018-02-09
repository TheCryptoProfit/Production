<?php
/** 
 * ChimpMate - WordPress MailChimp Assistant
 *
 * @package   ChimpMate - WordPress MailChimp Assistant
 * @author    Voltroid<care@voltroid.com>
 * @link      http://voltroid.com/chimpmate
 * @copyright 2017 Voltroid
 */

/**
 *
 * @package   ChimpMate - WordPress MailChimp Assistant
 * @author    Voltroid<care@voltroid.com>
 * 
 */
class ChimpMate_WPMC_Assistant_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * @since     1.0.0
	 */
	private function __construct() {

		add_action( 'init', array( $this, 'load_plugin' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . 'chimpmate.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

 		add_action('wp_ajax_chimpmate_tab', array( $this, 'tab' ) );
 		add_action('wp_ajax_chimpmate_prev', array( $this, 'preview' ) );
 		add_action('wp_ajax_chimpmate_mailserv_connect', array( $this, 'mailserv_connect' ) );
 		add_action('wp_ajax_chimpmate_mailserv_getlists', array( $this, 'mailserv_getlists' ) );
 		add_action('wp_ajax_chimpmate_mailserv_getfields', array( $this, 'mailserv_getfields' ) );

		add_action('wp_ajax_chimpmate_update', array( $this, 'update_options' ) );
		add_action('wp_ajax_chimpmate_usync', array( $this, 'usync' ) );

		add_action('wp_ajax_chimpmate_secure', array( $this, 'restorebackup' ) );
		add_action( 'admin_head', array( $this,'admin_css' ));

		add_action('admin_notices', array( $this, 'update_notice') );
		add_action('admin_init', array( $this, 'update_notice_ignore') );
	}
	function update_notice($a=null) {
		// echo $this->settings['db_change'];
		if(!isset($this->settings['db_change']) || $this->settings['db_change']==0)return;
		global $pagenow;
		if ( $pagenow != 'admin.php' ) 
			$b=1;
		else if($a)
			$b=1;
		if(isset($b)){
			global $current_user;
			$user_id = $current_user->ID;
			// delete_user_meta($user_id, 'chimpmate_dbchange_notice');
			// print_r(get_user_meta($user_id, 'chimpmate_dbchange_notice'));
			if (!get_user_meta($user_id, 'chimpmate_dbchange_notice')) {
				echo '<div class="notice notice-warning"><p>'. __('ChimpMate has updated. We recommend you to verify your settings for forms and themes.') .' <a href="?chimpmate-dbchange-notice">Dismiss</a></p></div>';
			}
		}
	}
	function update_notice_ignore() {
		global $current_user;
		$user_id = $current_user->ID;
		if (isset($_GET['chimpmate-dbchange-notice'])) {
			add_user_meta($user_id, 'chimpmate_dbchange_notice', 'true', true);
			$this->settings['db_change']=0;
			update_option('wpmchimpa_options',$this->settings);
			if ( wp_get_referer() ){
				wp_safe_redirect( wp_get_referer() );
			}
			else{
				wp_safe_redirect( get_home_url() );
			}
		}
	}
	/*
	 * Call $plugin_slug from public plugin class.
	 *
	 * @TODO:
	 *
	 */
	public function load_plugin(){
		$this->plugin = ChimpMate_WPMC_Assistant::get_instance();
		$this->plugin_slug = $this->plugin->get_plugin_slug();
		$this->settings = $this->plugin->settings;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', WPMCA_PLUGIN_URL. 'admin/assets/css/admin.css', array(), ChimpMate_WPMC_Assistant::VERSION );
			wp_register_style('googleFonts', '//fonts.googleapis.com/css?family=Raleway|Roboto:300');
            wp_enqueue_style( 'googleFonts');
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {

			$chimpmate = $this->settings;

			$opt['mymail'] = ( function_exists( 'mailster' ) )?true:false;
			$opt['goog_fonts']=json_decode(file_get_contents(WPMCA_PLUGIN_PATH.'src/google_fonts.json'),true);
			$opt['web_fonts']=$this->plugin->webfont();
			$opt['svglist']=$this->plugin->svglist();
			$opt['iconlist']=$this->plugin->iconlist();
			$opt['plug_url']=WPMCA_PLUGIN_URL;
			wp_enqueue_script('jquery');
			wp_enqueue_script( $this->plugin_slug . '-admin-script', WPMCA_PLUGIN_URL. 'admin/assets/js/admin.js', array( 'jquery'), ChimpMate_WPMC_Assistant::VERSION );
			wp_localize_script( $this->plugin_slug . '-admin-script',  'wpmchimpa_script', array( 'ajaxurl' =>admin_url('admin-ajax.php')));
			wp_localize_script( $this->plugin_slug . '-admin-script', 'wpmchimpa', $chimpmate );
			wp_localize_script( $this->plugin_slug . '-admin-script', 'wpmcopt', $opt );
			wp_register_script( 'angular-core', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js', ChimpMate_WPMC_Assistant::VERSION );
			wp_register_script( 'angular-animate', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js', ChimpMate_WPMC_Assistant::VERSION );
			wp_register_script( 'angular-route', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-route.min.js', ChimpMate_WPMC_Assistant::VERSION );
			wp_enqueue_script('angular-core');
			wp_enqueue_script('angular-animate');
			wp_enqueue_script('angular-route');
			wp_enqueue_script( $this->plugin_slug . '-admin-script2', '//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js', ChimpMate_WPMC_Assistant::VERSION );
			wp_enqueue_media();
		}
	}
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_menu_page(
			'ChimpMate',
			'ChimpMate',
			'chimpmate_cap',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' ),
			'none' , 85
		);
		$role = get_role( 'administrator' );
		$role->add_cap( 'chimpmate_cap' );
		add_submenu_page($this->plugin_slug,$this->plugin_slug,$this->plugin_slug,'chimpmate_cap',$this->plugin_slug,array( $this, 'display_plugin_admin_page' ));
		remove_submenu_page( $this->plugin_slug, $this->plugin_slug );
		add_submenu_page( $this->plugin_slug,'General','General','chimpmate_cap',$this->plugin_slug.'#/general',array( $this, 'display_plugin_admin_page' ));
		add_submenu_page( $this->plugin_slug, 'Theme', 'Theme','chimpmate_cap', $this->plugin_slug.'#/theme',array( $this, 'display_plugin_admin_page' ));
		add_submenu_page( $this->plugin_slug, 'Lightbox', 'Lightbox','chimpmate_cap', $this->plugin_slug.'#/lightbox',array( $this, 'display_plugin_admin_page' ));
		add_submenu_page( $this->plugin_slug, 'Slider', 'Slider','chimpmate_cap', $this->plugin_slug.'#/slider',array( $this, 'display_plugin_admin_page' ));
		add_submenu_page( $this->plugin_slug, 'Widget', 'Widget','chimpmate_cap', $this->plugin_slug.'#/widget',array( $this, 'display_plugin_admin_page' ));
		add_submenu_page( $this->plugin_slug, 'Addon', 'Addon','chimpmate_cap', $this->plugin_slug.'#/addon',array( $this, 'display_plugin_admin_page' ));
		add_submenu_page( $this->plugin_slug, 'Advanced', 'Advanced','chimpmate_cap', $this->plugin_slug.'#/advanced',array( $this, 'display_plugin_admin_page' ));
	}
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug ) . '">Settings</a>'
			),
			$links
		);

	}
	/**
	 * Render Preview of the requested theme
	 *
	 * @since    1.0.0
	 */
	public function preview(){
		if(!isset($_GET['type']) || !isset($_GET['theme']))die();
		include_once( 'includes/'.$_GET['type'].$_GET['theme'].'.php' );
		die();
	}
	/**
	 * Return tab content
	 *
	 * @since    1.0.0
	 */
	public function tab(){
		if(!isset($_GET['tab']))die();
		$tab = $_GET['tab'];
		include_once( 'views/admin_'.$tab.'.php' );
		die();
	}
	public function mailserv_connect(){
		$config = json_decode(file_get_contents("php://input"),true);
		$MailChimp = new ChimpMate_WPMC_MailChimp($config['key']);
		$result=$MailChimp->get('');
		$res = array();
		if(!$result || isset($result['status'])){
			if($result['status'] == 500)
				$res['status'] = 2;
			else $res['status'] = 0;
		}
		else{
			$res['status'] = 1;
			$res['acc'] = $result['account_name'];
			$res['lists'] = $this->getlists($config);
		}
		echo json_encode($res);
		die();
	}
	public function getlists($config){
		$MailChimp = new ChimpMate_WPMC_MailChimp($config['key']);
		$result=$MailChimp->get('/lists/?count=50&offset=0');
		$list = array();
		if($result['total_items'] > 0){
	   		$list = array();
	   		foreach($result['lists'] as $mclist){
					array_push($list, array(
							"id" => $mclist['id'],
							"name" => $mclist['name']));
			}
	   		for($i=50;$result['total_items'] > 50;$i+=50,$result['total_items']-=50){
				$res=$MailChimp->get('/lists/?count=50&offset='.$i);
		   		foreach($res['lists'] as $mclist){
						array_push($list, array(
								"id" => $mclist['id'],
								"name" => $mclist['name']));
				}
	   		}
		}
		return $list;
	}
	public function mailserv_getlists(){
		$config = json_decode(file_get_contents("php://input"),true);
		echo json_encode($this->getlists($config));
		die();
	}	
	public function mailserv_getfields(){
		$config = json_decode(file_get_contents("php://input"),true);
		$api = $config[0]['key'];
		$list = $config[1];
		$MailChimp = new ChimpMate_WPMC_MailChimp($api);
		print(json_encode(array($this->retrieve_fields($list,$MailChimp), $this->retrieve_groups($list,$MailChimp))));
		die();
	}
	function retrieve_groups($list,$MailChimp){
		$groups_result =  $MailChimp->get('/lists/'.$list.'/interest-categories?count=50');
		$groups = array();
		if($groups_result['total_items'] > 0){
			foreach ($groups_result['categories'] as $grouping) {
				$g = array();
				$g['id'] = $grouping['id'];
				$g['name'] = $grouping['title'];
				$g['label'] = $grouping['title'];
				$g['type'] = $grouping['type'];
				$g['cat'] = 'group';
				$s = array();
				$i = 0;$gtot = false;
				do {
					$res=$MailChimp->get('/lists/'.$list.'/interest-categories/'.$grouping['id'].'/interests?count=50&offset='.$i);
					if(!$gtot)$gtot = $res['total_items'];
					foreach ($res['interests'] as $group) {
						$t = array();
						$t['id']=$group['id'];
						$t['gname']=$group['name'];
						array_push($s, $t);
					}
					$i+=50;$gtot-=50;
				} while ($gtot > 50);
				$g['groups'] = $s;
				array_push($groups,$g);
			}
		}
		return $groups;
	}

	function retrieve_fields($list,$MailChimp){
		$groups_result =  $MailChimp->get('/lists/'.$list.'/merge-fields?count=50');
		$groups = array(
			array(
				"id"=>"email",
				"name"=>"Email address",
				"icon"=>"idef",
				"label"=>"Email address",
				"tag"=>"email",
				"type"=>"email",
				"req"=>true,
				"cat"=>"field"
			)
		);
		if($groups_result['total_items'] > 0){
			foreach ($groups_result['merge_fields'] as $grouping) {
				if($grouping['type'] == 'address')continue;
				$g = array();
				$g['name'] = $grouping['name'];
				$g['icon'] = 'idef';
				$g['label'] = $grouping['name'];
				$g['id'] = $grouping['merge_id'];
				$g['tag'] = $grouping['tag'];
				$g['type'] = $grouping['type'];
				$g['opt'] = $grouping['options'];
				$g['req'] = $grouping['required'];
				$g['cat'] = 'field';
				array_push($groups,$g);
			}
		}
		return $groups;
	}
	public function update_options(){
		$data = json_decode(file_get_contents("php://input"),true);
		$json = array_filter($data,array($this , 'myFilter'));
		$chimpmate = $this->settings;
		if (function_exists('curl_init') && function_exists('curl_setopt')){
			$up=0;if(function_exists('curl_init')){if(isset($settings_array['get_email_update'])){if(!isset($chimpmate['get_email_update']) || (isset($chimpmate['get_email_update']) && $settings_array['email_update'] != $chimpmate['email_update'])){$up=1;}}else{if(isset($chimpmate['get_email_update'])){$up=2;}}if($up>0){$curl = curl_init();curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1,CURLOPT_URL => 'http://voltroid.com/chimpmate/api.php',CURLOPT_REFERER => home_url(),CURLOPT_POST => 1));if($up==1)curl_setopt($curl, CURLOPT_POSTFIELDS, array('action' => 'subs','email' => $settings_array['email_update']));else curl_setopt($curl, CURLOPT_POSTFIELDS, array('action' => 'unsubs'));$res=curl_exec($curl);curl_close($curl);}}
		}
		update_option('wpmchimpa_options',$json);
		die('1');
	}
	/**
	 * Ajax call for one Click Backup and Restore
	 * @since    1.0.0
	 * 
	 */
	public function restorebackup(){
		if ( !is_super_admin()) die();
		if($_REQUEST['q']=='backup'){
			$chimpmate = $this->settings;
			header('Content-disposition: attachment; filename=ChimpMate_Backup-'.date('Y-m-d H:i:s').'.json');
			header('Content-Type: application/json');
			echo json_encode($chimpmate);
		}
		else if ($_REQUEST['q'] == 'restore'){
				$json = $_REQUEST['data'];
				update_option('wpmchimpa_options',$json);
		}
		else if ($_REQUEST['q'] == 'reset'){
			$json=json_decode(file_get_contents(WPMCA_PLUGIN_PATH.'src/default.json'),true);
				update_option('wpmchimpa_options',$json);
		}
		die();
	}
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Voltroid Control Panel Icon
	 * @since    1.0.0
	 * 
	 */
public function admin_css() {
		?>
<style>

@font-face {
  font-family: "vapanel_fonts";
  src:url("<?php echo WPMCA_PLUGIN_URL;?>assets/fonts/vapanel_fonts.eot");
  src:url("<?php echo WPMCA_PLUGIN_URL;?>assets/fonts/vapanel_fonts.eot?#iefix") format("embedded-opentype"),
    url("<?php echo WPMCA_PLUGIN_URL;?>assets/fonts/vapanel_fonts.woff") format("woff"),
    url("<?php echo WPMCA_PLUGIN_URL;?>assets/fonts/vapanel_fonts.ttf") format("truetype"),
    url("<?php echo WPMCA_PLUGIN_URL;?>assets/fonts/vapanel_fonts.svg#vapanel_fonts") format("svg");
  font-weight: normal;
  font-style: normal;
}
#toplevel_page_chimpmate .wp-menu-image::before{
	content :'\c032';
	font-family: "vapanel_fonts"!important;
	font-size:17px;
}

</style>

		<?php }
	/**
	 * Function to remove Null Value
	 * @since    1.0.0
	 * 
	 */
	function myFilter($var){
	  return ($var !== NULL && $var !== FALSE && $var !== '');
	}
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		
		include_once( 'views/admin.php' );
	}
	/**
	 * Sync users database to mail list
	 *
	 * @since    1.0.0
	 */
	public function usync(){
		$data = json_decode(file_get_contents("php://input"),true);
		$emails = array();
		if($data['t'] == 1){
			foreach (get_comments() as $comment){
				array_push($emails, $comment->comment_author_email);
			}
		}
		else if ($data['t'] == 2){
			foreach ( get_users() as $user ) {
				if(isset($data['r']) && in_array($user->roles[0], $data['r']))
					array_push($emails, $user->user_email);
			}
		}
		$this->batchsubs(array_values(array_filter(array_unique($emails),array($this , 'myFilter'))),$data['f']);
		die('');
	}
	public function batchsubs($emails,$list){
		$data = json_decode(file_get_contents("php://input"),true);
		$settings=$this->settings;
		$api = $settings['mailserv']['key'];
		if(empty($api) || empty($list)){ die("0");}
		$MailChimp = new ChimpMate_WPMC_MailChimp($api);
		$Batch     = $MailChimp->new_batch();
		foreach ($emails as $key => $email) {
			$Batch->post("op".$key, "lists/$list/members", array(
                'email_address' => $email,
                'status'        => ((isset( $settings['optin']))?'pending':'subscribed'),
            ));
		}
		$result = $Batch->execute();
		if( $result['status'] === 'error' ) {
			echo json_encode($result);
		}
		else{
			echo 1;
		}
		die();
	}

}
