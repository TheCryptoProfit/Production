<?php
/*
* Define class pspSERP
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/

!defined('ABSPATH') and exit;
if (class_exists('pspSERP') != true) {
	class pspSERP
	{
		/*
		 * Some required plugin information
		 */
		const VERSION = '1.0';

		/*
		 * Store some helpers config
		 */
		public $the_plugin = null;

		private $module_folder = '';
		private $module_folder_path = '';
		private $module = '';

		static protected $_instance;

		public $localizationName = '';
		
		private $settings = array();
		private $settings_report = array();

		private $vars = array();

		private $db = null;
		private $serp_tables = array();

		private $serp_api = null;

		public $serp_settings = array(
			'engines_max' 				=> 5,
			'keywords_max_per_engine' 	=> 200,
			'keywords_max_global' 		=> 500,
			'websites_max_per_engine' 	=> 10,
			'websites_max_global' 		=> 50,
		);


		/*
		 * Required __construct() function that initalizes the AA-Team Framework
		 */
		public function __construct( $is_cron=false )
		{
			global $psp;

			$this->the_plugin = $psp;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/serp/';
			$this->module_folder_path = $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/serp/';
			$this->module = isset($this->the_plugin->cfg['modules']['serp']) ? $this->the_plugin->cfg['modules']['serp'] : array();

			$this->localizationName = $this->the_plugin->localizationName;

			$this->db = $this->the_plugin->db;
			$this->serp_tables = array(
				'website'			=> $this->db->prefix . 'psp_serprank_website',
				'keyword'			=> $this->db->prefix . 'psp_serprank_keyword',
				'mainrank'			=> $this->db->prefix . 'psp_serprank_mainrank',
				'pagerank'			=> $this->db->prefix . 'psp_serprank_pagerank',
			);

			// check if it's first time we load this module?
			$is_checked = get_option('psp_serp_checked', false);
			if ( ! $is_checked ) {
				$psp->plugin_integrity_check( 'check_database', true );
				update_option('psp_serp_checked', true);
			}

			$this->settings = $this->the_plugin->get_theoption( $this->the_plugin->alias . '_serp' );
			$this->settings_report = $this->the_plugin->get_theoption( $this->the_plugin->alias . '_report' );

			$this->load_inc();			

			// ajax  helper
			if ( $this->the_plugin->is_admin === true && !$is_cron ) {
				add_action('admin_menu', array( $this, 'adminMenu' ));

				// ajax handler
				add_action('wp_ajax_pspSERP', array( $this, 'ajax_request' ));
			}

			//if ( $this->the_plugin->capabilities_user_has_module('serp') )
			if ( !$this->the_plugin->verify_module_status( 'serp' ) ) ; //module is inactive
			else {
				if ( $this->the_plugin->is_admin !== true ) {
				}
			}

			//:: SERP API INIT
			require_once( $this->the_plugin->cfg['paths']['scripts_dir_path'] . '/serp/serp.api.class.php' );
			$this->serp_api = pspSERPCheck::getInstance();
			$this->serp_api->saveLog(true);

			//:: TESTING FIND KEYWORD
			//$this->do_testing(0);

			//:: TESTING CRONJOB UPDATE RANKS
			//$this->cronjob_keyword_update_ranks(array());

			//:: TESTING SUGGEST COMPETITORS
			//$this->suggest_competitor_fromdb(array('engine' => 'google.com'));
		}

		public function do_testing( $istesting=1 ) {
			if ( ! $istesting ) {
				return false;
			}

			//:: config
			$engine = 'google.com';
			$keyword = 'test'; // test | woozone | premium seo pack
			$domains = array('http://www.speedtest.net', 'html5test.com', 'https://123test.es');

			//:: execution
			$is_valid_serp_config = $this->serp_api->api_set_config(array(
				'gl' => $engine,
			));
			if ( 'invalid' == $is_valid_serp_config['status'] ) {
				var_dump('<pre>', $is_valid_serp_config , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			}

			$thepms = array(
				'engine'		=> $engine,
				'keyword'		=> $keyword,
				'domains' 		=> $domains,
			);

			/*
			$retFind = $serp->api_find_keyword( $thepms );
			//var_dump('<pre>FINAL KW', $retFind , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$retParse = $serp->api_parse_response( array(
				//'startPos' 		=> 0, // where to start (index position in content)
				'content' 		=> $retFind['content'], // a json which will be converted to an array
				'domains' 		=> $domains,
			));
			//var_dump('<pre>FINAL PARSE', $retParse , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			*/

			$retDomains = $this->serp_api->api_find_domains( $thepms );
			var_dump('<pre>', $retDomains , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		}

		public function verify_api_status() {
			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'verify_api_status: unknown!',
			);

			//:: config
			$engine = 'google.com';
			$keyword = 'test'; // test | woozone | premium seo pack

			// find keyword rank by API
			$is_valid_serp_config = $this->serp_api->api_set_config(array(
				'gl' => $engine,
			));
			if ( 'invalid' == $is_valid_serp_config['status'] ) {
				$ret = array_replace_recursive($ret, $is_valid_serp_config);
				return $ret;
			}

			$serp_api_pms = array(
				'top_type' 		=> 10,
				'engine'		=> $engine,
				'keyword'		=> $keyword,
				'domains' 		=> array(), //array_values($websites),
			);

			$websites_ranks = $this->serp_api->api_find_domains( $serp_api_pms );
			//var_dump('<pre>', $keyword, $websites_ranks , '</pre>');

			if ( 'invalid' == $websites_ranks['status'] ) {
				$ret = array_replace_recursive($ret, $websites_ranks);
				return $ret;
			}

			$ret = array_replace_recursive($ret, array(
				'status' => 'valid',
				'msg'	=> 'ok.',
			));
			return $ret;
		}
		
		/**
		 * Singleton pattern
		 *
		 * @return pspSERP Singleton instance
		 */
		static public function getInstance()
		{
			if (!self::$_instance) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Hooks
		 */
		static public function adminMenu()
		{
		   self::getInstance()
				->_registerAdminPages();
		}

		/**
		 * Register plug-in module admin pages and menus
		 */
		protected function _registerAdminPages()
		{
			if ( $this->the_plugin->capabilities_user_has_module('serp') ) {
				//Search Engine Results Page Tracking
				add_submenu_page(
					$this->the_plugin->alias,
					__('SERP Tracking', 'psp'),
					__('SERP Tracking', 'psp'),
					'read',
					$this->the_plugin->alias . "_SERP",
					array($this, 'print_interface_main')
				);

				add_submenu_page(
					$this->the_plugin->alias,
					__('SERP Stats', 'psp'),
					__('SERP Stats', 'psp'),
					'read',
					$this->the_plugin->alias . "_SERP_stats",
					array($this, 'print_interface_stats')
				);
			}

			return $this;
		}

		public function display_meta_box()
		{
			if ( $this->the_plugin->capabilities_user_has_module('serp') ) {
				$this->printBoxInterface();
			}
		}

		/**
		 * AJAX
		 *
		 */
		public function ajax_request()
		{
			global $wpdb;

			$request = array(
				'ajax_id'		=> isset($_REQUEST['ajax_id']) ? trim($_REQUEST['ajax_id']) : '',
				'action' 		=> isset($_REQUEST['sub_action']) ? trim($_REQUEST['sub_action']) : '',
				'itemid' 		=> isset($_REQUEST['itemid']) ? (int) $_REQUEST['itemid'] : 0,
			);
			extract( $request );

			$ret = array(
				'status'		=> 'invalid',
			);

			if ( 'engine_select' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				if ( '' != $request['engine'] ) {
					$_SESSION['psp_serp']['search_engine'] = $request['engine'];
				}

				$ret = array_replace_recursive($ret, array(
					'status' => 'valid',
				));
			}

			if ( 'engine_only_used' == $action ) {
				$request = array_replace_recursive($request, array(
					'only_used' 		=> isset($_REQUEST['only_used']) ? (int) $_REQUEST['only_used'] : 0,
				));
				extract( $request );

				update_option( 'psp-serp-engine-only-used', $only_used );

				$ret = array_replace_recursive($ret, array(
					'status' => 'valid',
					'html_engine' => $this->engine_html_reponse( $only_used ),
				));
			}

			if ( 'competitor_save' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
					'competitor' 	=> isset($_REQUEST['competitor']) ? trim($_REQUEST['competitor']) : '',
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				$request['competitor'] = $this->clean_website_url( $request['competitor'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->competitor_add_to_db( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			if ( 'keywords_save' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
					'kwlist'		=> isset($_REQUEST['kw']) ? trim($_REQUEST['kw']) : '',
					'delimiter'		=> isset($_REQUEST['delimiter']) ? trim($_REQUEST['delimiter']) : '',
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				$request['kwlist'] = $this->kw_by_delimiter__( $request['kwlist'], $request['delimiter'] );
				$request['kwlist'] = stripslashes_deep( $request['kwlist'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->keywords_add_to_db( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			if ( 'competitor_delete' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
					'id' 			=> isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0,
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->competitor_delete_from_db( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			if ( 'keywords_delete' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
					'ids' 			=> isset($_REQUEST['ids']) ? trim($_REQUEST['ids']) : '',
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				$request['ids'] = $this->the_plugin->get_ids_from_string( $request['ids'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->keywords_delete_from_db( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			if ( 'focus_keywords_load' == $action ) {
				$request = array_replace_recursive($request, array(
				));
				extract( $request );

				$ret = array_replace_recursive($ret, array(
					'status' => 'valid',
					'html_fkw' => $this->build_focus_keywords_table(),
				));
			}

			if ( 'keyword_update_rank' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
					'kw_id'			=> isset($_REQUEST['kw_id']) ? (int) $_REQUEST['kw_id'] : 0,
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->keyword_update_rank( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			if ( 'keyword_get_details' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
					'kw_id'			=> isset($_REQUEST['kw_id']) ? (int) $_REQUEST['kw_id'] : 0,
					'interval' 		=> isset($_REQUEST['interval']) ? trim($_REQUEST['interval']) : '',
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->keyword_get_details( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			if ( 'keyword_get_evolution_chart' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
					'kw_id'			=> isset($_REQUEST['kw_id']) ? (int) $_REQUEST['kw_id'] : 0,
					'interval' 		=> isset($_REQUEST['interval']) ? trim($_REQUEST['interval']) : '',
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->keyword_get_evolution_chart( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			if ( 'stats_kw_rank' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->get_stats_kw_rank( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			if ( 'get_suggested_competitors' == $action ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->suggest_competitor_box( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			if ( 'load_ws_stats' == $action ) {
				$request = array_replace_recursive($request, array(
					//'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : 'all',
					//'website_id'	=> isset($_REQUEST['website_id']) ? (int) $_REQUEST['website_id'] : 'all',
					'include_competitors' => isset($_REQUEST['include_competitors']) ? trim($_REQUEST['include_competitors']) : 'no',
					'date_from' 	=> isset($_REQUEST['date_from']) ? trim($_REQUEST['date_from']) : '',
					'date_to' 		=> isset($_REQUEST['date_to']) ? trim($_REQUEST['date_to']) : '',
				));
				$request['date_from'] = strtotime( $request['date_from'] );
				$request['date_to'] = strtotime( $request['date_to'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->serp_website_stats_box_load( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					//'status' => 'valid',
				), $opStat);
			}

			//:: refresh main table
			if ( in_array($action, array(
				'engine_select',
				'engine_only_used',
				'competitor_save',
				'keywords_save',
				'competitor_delete',
				'keywords_delete',
				//'focus_keywords_load',
				'keyword_update_rank',
				//'keyword_get_details',
				//'keyword_get_evolution_chart',
				//'stats_kw_rank',
				//'get_suggested_competitors',
				//'load_ws_stats',
			)) ) {
				// reset the paged as well
				$_SESSION['pspListTable'][$request['ajax_id']]['params']['paged'] = 1;

				//keep page number & items number per page
				$_SESSION['pspListTable']['keepvar'] = array('posts_per_page'=>true);

				// return for ajax
				$list_table = $this->ajax_list_table_rows();

				$ret = array_replace_recursive($ret, array(
					'html'		=> $list_table['html'],
				));
			}

			//:: refresh stats
			if ( in_array($action, array(
				'engine_select',
				//'engine_only_used',
				//'competitor_save',
				//'keywords_save',
				//'competitor_delete',
				'keywords_delete',
				//'focus_keywords_load',
				'keyword_update_rank',
				//'keyword_get_details',
				//'keyword_get_evolution_chart',
				//'stats_kw_rank',
				//'get_suggested_competitors',
				//'load_ws_stats',
			)) ) {
				$request = array_replace_recursive($request, array(
					'engine' 		=> isset($_REQUEST['engine']) ? trim($_REQUEST['engine']) : '',
				));
				$request['engine'] = $this->search_engine__( $request['engine'] );
				extract( $request );
				//var_dump('<pre>', $request , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$opStat = $this->get_stats_kw_rank( array_replace_recursive(array(), $request) );

				$ret = array_replace_recursive($ret, array(
					'msg_stats'		=> $opStat['msg'],
				));
			}

			die(json_encode($ret));
		}


		/**
		 *
		 * Search Engine
		 */
		private function engine_locations_dropdown( $has_all=false, $only_used=false ) {
			//$list = $this->getSearchEngineUsed();
			$list = $this->vars['google_locations'];

			$loc_used = $this->engine_locations_used_get();

			if ( $only_used && empty($loc_used) ) {
				return __('None yet', 'psp');
			}

			$html = array();
			$html[] = '<select id="select-engine">';
			if ( $has_all ) {
				$html[] = '<option value="--all--">' . __('all locations', 'psp') . '</option>';
			}

			$first = '';
			$engine_is_used = false;
			foreach ($list as $kk => $vv) {
				if ( $only_used && ! in_array( $this->search_engine__( $kk ), $loc_used) ) {
					continue 1;
				}

				if ( '' == $first ) {
					$first = $this->search_engine__( $kk );
				}
				if ( isset($_SESSION['psp_serp']['search_engine'])
					&& $_SESSION['psp_serp']['search_engine'] == $this->search_engine__( $kk )
				) {
					$engine_is_used = true;
				}

				$is_selected = isset($_SESSION['psp_serp']['search_engine'])
					&& $this->search_engine__( $kk ) == $_SESSION['psp_serp']['search_engine'] ? ' selected="selected"' : '';
				//$is_selected = '';

				$title = $vv;
				if ( !$only_used && in_array( $this->search_engine__( $kk ), $loc_used) ) {
					$title = "** $title";
				}

				$html[] = '<option value="' . $kk . '"' . $is_selected . '>' . $title . '</option>';
			} // end foreach

			$html[] = '</select>';

			if ( ! isset($_SESSION['psp_serp']['search_engine'])
				|| ('' == $_SESSION['psp_serp']['search_engine'])
				|| ! $engine_is_used
			) {
				$_SESSION['psp_serp']['search_engine'] = $first;
			}

			$html = implode(PHP_EOL, $html);
			return $html;
		}

		private function engine_locations_used_fromdb() {
			$table = $this->serp_tables['keyword'];
			$sql = "select distinct( a.search_engine ) from $table as a where 1=1 order by a.search_engine asc;";
			$res = $this->db->get_col( $sql );
			return $res;
		}

		private function engine_locations_used_get() {
			$ret = get_transient( 'psp-serp-engine-locations-used' );
	
			if ( $ret && is_array($ret) ) {
				return $ret;
			}

			$fromdb = $this->engine_locations_used_fromdb();
			set_transient( 'psp-serp-engine-locations-used', $fromdb, (3600 * 24) ); // expire in 1 day
			return $fromdb;
		}

		private function engine_locations_used_delete() {
			delete_transient( 'psp-serp-engine-locations-used' );

			//$this->find_nb_engines_del(); //don't uncomment it - infinite recursivity!
			$this->find_nb_keywords_del();
			$this->find_nb_websites_del();
		}

		private function engine_html_reponse( $only_used=false ) {
			ob_start();
		?>
			<?php /*<button id="search-engine-current-loc"><?php echo $_SESSION['psp_serp']['search_engine']; ?><img src="<?php echo $this->module_folder; ?>assets/flag.png"></button>*/ ?>
			<?php
				$is_checked = $only_used ? ' checked="checked"' : '';
				echo $this->engine_locations_dropdown( false, $only_used );
			?>

			<input type="checkbox" id="psp-serp-engine-used-only" name="psp-serp-engine-used-only" <?php echo $is_checked; ?> />
			<label for="psp-serp-engine-used-only" id="psp-serp-engine-used-only-label"><?php _e('only show used locations', 'psp'); ?></label>
		<?php
			$html = ob_get_clean();
			return $html;
		}


		/**
		 *
		 * Competitor
		 */
		private function competitor_get_competitors_fromdb( $pms=array() ){
			$pms = array_replace_recursive(array(
				'engine' 	=> '',
			), $pms);
			extract($pms);

			$table = $this->serp_tables['website'];

			$sql = "select a.id, a.website from $table as a where 1=1 and a.search_engine = %s order by a.id asc;";
			$sql = $this->db->prepare( $sql, $engine);
			$res = $this->db->get_results( $sql, OBJECT_K );
			//var_dump('<pre>', $sql, $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $res;
		}

		private function competitor_add_to_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'competitor' 	=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'competitor_add_to_db: unknown!',
			);

			$msg_engine = sprintf( __('| engine is %s', 'psp'), $engine );
			$msglist = array();

			//:: verify allowed max limits
			$verify_status = $this->verify_allowed_max_limits(array(
				'what' 		=> 'website',
				'engine' 	=> $engine,
			));
			if ( 'invalid' == $verify_status['status'] ) {
				$ret = array_replace_recursive($ret, $verify_status);
				return $ret;
			}

			//:: is competitor already added?
			$is_found = $this->competitor_exists_in_db( array(
				'engine'		=> $engine,
				'competitor'	=> $competitor,
			));

			//:: competitor IS found / already exists
			if ( $is_found ) {
				$msg = sprintf( __('Competitor %s already exists %s.', 'psp'), $competitor, $msg_engine );
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}

			//:: get all keywords associated to current engine
			$keywords = $this->keywords_get_keywords_fromdb( array(
				'engine'		=> $engine,
			));
			//var_dump('<pre>', $keywords , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			// NO keywords found => don't insert competitor
			if ( empty($keywords) || ! is_array($keywords) ) {
				$msg = sprintf( __('No Keywords found. You must add some keywords, before adding competitor %s %s.', 'psp'), $competitor, $msg_engine );
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}

			//:: competitor NOT found => try to insert it to db
			$competitor_id = $this->competitor_insert_in_db( array(
				'engine'		=> $engine,
				'competitor'	=> $competitor,
			));
			if ( ! $competitor_id ) {
				$msg = sprintf( __('Competitor %s could not be inserted in db %s.', 'psp'), $competitor, $msg_engine );
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}
			else {
				$msg = sprintf( __('Competitor %s was successfully inserted in db %s.', 'psp'), $competitor, $msg_engine );
				$msg = $this->build_operation_message( $msg, 'success' );

				$msglist[] = $msg;
			}

			//:: add competitor relations to keywords
			$opRelations = $this->competitor_add_keywords_relations_db( array(
				'engine'		=> $engine,
				'competitor_id'	=> $competitor_id,
				'keywords'		=> $keywords,
			));

			$msglist[] = $opRelations['msg'];

			//:: update competitor rank for all keywords, based on keywords top100 db field for the other websites/competitors
			$opRanks = $this->competitor_update_keywords_ranks( array(
				'engine'		=> $engine,
				'competitor' 	=> $competitor,
				'competitor_id'	=> $competitor_id,
				'keywords'		=> $keywords,
				'mr2kw' 		=> isset($opRelations['mr2kw']) ? (array) $opRelations['mr2kw'] : array(),
			));

			$msglist[] = $opRanks['msg'];


			$msglist = implode(PHP_EOL, $msglist);

			$ret = array_replace_recursive($ret, $opRelations, $opRanks, array(
				'msg'	=> $msglist,
			));
			$ret['status'] = 'valid';

			// engine used locations needs refresh
			$this->engine_locations_used_delete();

			return $ret;
		}

		private function competitor_exists_in_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'competitor' 	=> '',
			), $pms);
			extract($pms);

			$table = $this->serp_tables['website'];

			$sql = "select a.id from $table as a where 1=1 and a.website = %s and a.search_engine = %s limit 1;";
			$sql = $this->db->prepare( $sql, $competitor, $engine);
			$res = $this->db->get_var( $sql );
			//var_dump('<pre>', $sql, $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $res;
		}

		private function competitor_insert_in_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'competitor' 	=> '',
			), $pms);
			extract($pms);

			$table = $this->serp_tables['website'];

			if ( isset($is_competitor) ) {
				if ( ! in_array($is_competitor, array('Y', 'N')) ) {
					$is_competitor = $is_competitor ? 'Y' : 'N';
				}
				$sql = "INSERT IGNORE INTO $table (website, search_engine, is_competitor) VALUES (%s, %s, %s);";
				$sql = $this->db->prepare( $sql, $competitor, $engine, $is_competitor);
			}
			else {
				$sql = "INSERT IGNORE INTO $table (website, search_engine) VALUES (%s, %s);";
				$sql = $this->db->prepare( $sql, $competitor, $engine);
			}
			$res = $this->db->query( $sql );
			if ( $res ) {
				$res = $this->db->insert_id;
			}
			return $res;
		}

		private function competitor_add_keywords_relations_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'competitor_id' => 0,
				'keywords' 		=> array(),
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'competitor_add_keywords_relations_db: unknown!',
			);

			$ret = $this->add_mainrank_relations( array(
				'engine'		=> $engine,
				'type'			=> 'keywords',
				'items'			=> $keywords,
				'rel_id'		=> $competitor_id,
			));
			return $ret;
		}

		private function competitor_delete_from_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'id' 			=> 0,
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'competitor_delete_from_db: unknown!',
			);

			$msg_engine = sprintf( __('| engine is %s', 'psp'), $engine );
			$msglist = array();

			$table = $this->serp_tables['website'];
			$table_mr = $this->serp_tables['mainrank'];
			$table_pr = $this->serp_tables['pagerank'];

			//:: delete competitor from website table
			$sql = "DELETE ws FROM $table as ws WHERE 1=1 AND ws.id = %s and ws.search_engine = %s;";
			$sql = $this->db->prepare( $sql, $id, $engine);
			$res = $this->db->query( $sql );
			if ( false === $res ) {
				$msg = sprintf( __('Competitor ID #%s could not be deleted from db %s.', 'psp'), $id, $msg_engine );
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}
			else {
				$msg = sprintf( __('Competitor ID #%s was successfully deleted from db %s.', 'psp'), $id, $msg_engine );
				$msg = $this->build_operation_message( $msg, 'success' );

				$msglist[] = $msg;
			}

			//:: delete competitor rows from mainrank table
			$stat = true;
			$sql = "DELETE mr, pr FROM $table_mr as mr JOIN $table_pr as pr ON mr.id = pr.id_mainrank WHERE 1=1 AND mr.id_website = %s and mr.search_engine = %s;";
			$sql = $this->db->prepare( $sql, $id, $engine);
			$res = $this->db->query( $sql );
			if ( false === $res ) {
				$msg = sprintf( __('(id_website: %s, search_engine: %s) mainrank relation: could not delete any row from db %s.', 'psp'), $id, $engine, $msg_engine );
				$msg = $this->build_operation_message( $msg );
				$stat = false;
			}
			else {
				$msg = sprintf( __('(id_website: %s, search_engine: %s) mainrank relation: %s rows were successfully deleted from db %s.', 'psp'), $id, $engine, $res, $msg_engine );
				$msg = $this->build_operation_message( $msg, 'success' );
			}
			$msglist[] = $msg;

			$msglist = implode(PHP_EOL, $msglist);

			$ret = array_replace_recursive($ret, array(
				'status' => $stat ? 'valid' : 'invalid',
				'msg'	=> $msglist,
			));
			$ret['status'] = 'valid';

			// engine used locations needs refresh
			$this->engine_locations_used_delete();

			return $ret;
		}

		private function competitor_update_keywords_ranks( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'competitor' 	=> '',
				'competitor_id' => 0,
				'keywords' 		=> array(),
				'mr2kw' 		=> array(),
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'competitor_update_keywords_ranks: unknown!',
			);

			$msglist = array();

			//:: the number of keywords updated today (for current engine and per all engines)
			$table_mr = $this->serp_tables['mainrank'];

			$kwlist_ = array_values( $mr2kw );
			$kwlist_ = is_array($kwlist_) && ! empty($kwlist_) ? $kwlist_ : array(0);
			$kwlist_ = $this->db->_escape($kwlist_); //esc_sql
			$kwlist_ = array_map( array($this->the_plugin, 'prepareForInList'), $kwlist_);
			$kwlist_ = implode(',', $kwlist_);

			//mr.top100, mr.last_check_status, mr.last_check_msg, mr.last_check_data
			$sql = "select mr.id_keyword, mr.* from $table_mr as mr where 1=1 and mr.id_keyword in (" . $kwlist_ . ") and mr.id_website != $competitor_id and ( mr.last_check_status = 'valid' or mr.top100 regexp '^a:' ) group by mr.id_keyword order by mr.id asc;";
			//var_dump('<pre>', $sql , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$res = $this->db->get_results( $sql, OBJECT_K );
			//var_dump('<pre>', $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( false === $res ) {
				$msg = __('competitor_update_keywords_ranks: db error!', 'psp');
				$msg = $this->build_operation_message( $msg );
				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}
			if ( empty($res) ) {
				$msg = __('competitor_update_keywords_ranks: no rows found.', 'psp');
				$msg = $this->build_operation_message( $msg );
				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}

			// update competitor rank for each keyword to which it's associated
			$stat = true;
			$today = date('Y-m-d H:i:s');

			foreach ($mr2kw as $id_mr => $id_kw) {
				if ( ! isset($res["$id_kw"]) ) {
					continue 1;
				}

				//:: set the info
				$mr_row_found = $res["$id_kw"];

				$keyword = isset($keywords["$id_kw"]) ? $keywords["$id_kw"]->keyword : '';
				if ( empty($keyword) ) {
					//continue 1;
				}

				$last_check_status = $mr_row_found->last_check_status;
				$last_check_msg = $mr_row_found->last_check_msg;
				$last_check_data = $mr_row_found->last_check_data;

				$top100 = $mr_row_found->top100;
				$top100 = maybe_unserialize( $top100 );
				$top100_ = array(
					'request' 	=> array(
						'top_type' 		=> 100,
						'engine' 		=> $engine,
						'keyword' 		=> $keyword,
					),
					'items' 	=> array_values( $top100 ),
				);

				$wsinfo = array(
					'id' 				=> $id_mr,
					'position' 			=> -1,
					'position_worst' 	=> -1,
					'position_best' 	=> -1,

				);
				//var_dump('<pre>', $mr_row_found, $wsinfo , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				//:: find keyword rank by API
				$is_valid_serp_config = $this->serp_api->api_set_config(array(
					'gl' => $engine,
				));
				if ( 'invalid' == $is_valid_serp_config['status'] ) {
					continue 1;
				}

				$serp_api_pms = array(
					//'startPos' 		=> 0, // where to start (index position in content)
					'content' 		=> json_encode( $top100_ ), // a json which will be converted to an array
					'domains' 		=> array( $competitor ),
				);

				$websites_ranks = $this->serp_api->api_parse_response( $serp_api_pms );
				//var_dump('<pre>', $keyword, $websites_ranks , '</pre>');

				if ( 'invalid' == $websites_ranks['status'] ) {
					continue 1;
				}

				//:: update rank in table
				$msg_title = sprintf( __('(id_mr: %s) : ', 'psp'), $id_mr );

				$pms_mr_rank = array(
					'today' 		=> $last_check_data, //$today,
					'msg_title' 	=> $msg_title,
					'website' 		=> $competitor,
					'wsinfo' 		=> $wsinfo,
					'wsranks' 		=> $websites_ranks,
				);

				$stat_main_rank = $this->keyword_update_main_rank( $pms_mr_rank );

				if ( 'invalid' == $stat_main_rank['status'] ) {
					$msglist[] = $stat_main_rank['msg'];
					$stat = false;
					continue 1;
				}
				$msglist[] = $stat_main_rank['msg'];

				$stat_page_rank = $this->keyword_update_page_rank( array_replace_recursive($pms_mr_rank, array(
					'position' 		=> $stat_main_rank['position'],
				)));

				if ( 'invalid' == $stat_page_rank['status'] ) {
					$msglist[] = $stat_page_rank['msg'];
					$stat = false;
					continue 1;
				}
				$msglist[] = $stat_page_rank['msg'];
			}

			$msglist = implode(PHP_EOL, $msglist);

			$msg = $msglist;
			$msg = $stat ? $this->build_operation_message( $msg, 'success' ) : $this->build_operation_message( $msg );

			$ret = array_replace_recursive($ret, array(
				'status' => $stat ? 'valid' : 'invalid',
				'msg'	=> $msg,
			));
			return $ret;
		}


		/**
		 *
		 * Keywords
		 */
		private function keywords_get_keywords_fromdb( $pms=array() ){
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
			), $pms);
			extract($pms);

			$table = $this->serp_tables['keyword'];

			$sql = "select a.id, a.keyword from $table as a where 1=1 and a.search_engine = %s order by a.id asc;";
			$sql = $this->db->prepare( $sql, $engine);
			$res = $this->db->get_results( $sql, OBJECT_K );
			//var_dump('<pre>', $sql, $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $res;
		}

		private function keywords_add_to_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'kwlist' 		=> array(),
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keywords_add_to_db: unknown!',
			);

			$msg_engine = sprintf( __('| engine is %s', 'psp'), $engine );
			$msglist = array();

			//:: verify allowed max limits
			$verify_status = $this->verify_allowed_max_limits(array(
				'what' 		=> 'keyword',
				'engine' 	=> $engine,
			));
			if ( 'invalid' == $verify_status['status'] ) {
				$ret = array_replace_recursive($ret, $verify_status);
				return $ret;
			}

			//:: find keywords which already exists?
			$kw_indb = $this->keywords_exists_in_db_multi( array(
				'engine'		=> $engine,
				'kwlist'		=> $kwlist,
			));
			//var_dump('<pre>', $kw_indb , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$kw_notindb = array_diff($kwlist, $kw_indb);
			//var_dump('<pre>', $kw_notindb , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			// all keywords already exists!
			if ( empty($kw_notindb) ) {
				$msg = sprintf( __('All keywords already exists in db %s.', 'psp'), $msg_engine );
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}

			// only some keywords already exists!
			if ( ! empty($kw_indb) ) {
				$msg = sprintf( __('The following keywords already exists in db %s.', 'psp'), implode(', ', $kw_indb), $msg_engine );
				$msg = $this->build_operation_message( $msg );

				$msglist[] = $msg;
			}

			//:: try to add your website as competitor if not already added
			$competitor = $this->get_your_website();
			//var_dump('<pre>', $competitor , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: is competitor already added?
			$is_found = $this->competitor_exists_in_db( array(
				'engine'		=> $engine,
				'competitor'	=> $competitor,
			));

			//:: competitor IS found / already exists
			if ( $is_found ) {
				$competitor_id = $is_found;

				$msg = sprintf( __('Your website %s already exists in db %s.', 'psp'), $competitor, $msg_engine );
				$msg = $this->build_operation_message( $msg, 'success' );

				$msglist[] = $msg;
			}
			//:: competitor NOT found => try to insert it to db
			else {
				$competitor_id = $this->competitor_insert_in_db( array(
					'engine'		=> $engine,
					'competitor'	=> $competitor,
					'is_competitor' => 'N',
				));
				if ( ! $competitor_id ) {
					$msg = sprintf( __('Your website %s could not be inserted in db %s.', 'psp'), $competitor, $msg_engine );
					$msg = $this->build_operation_message( $msg );

					$ret = array_replace_recursive($ret, array(
						'msg'	=> $msg,
					));
					return $ret;
				}
				else {
					$msg = sprintf( __('Your website %s was successfully inserted in db %s.', 'psp'), $competitor, $msg_engine );
					$msg = $this->build_operation_message( $msg, 'success' );

					$msglist[] = $msg;
				}
			}

			//:: get all competitors associated to current engine
			$competitors = $this->competitor_get_competitors_fromdb( array(
				'engine'		=> $engine,
			));
			//var_dump('<pre>', $competitors , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			// NO competitors found => don't insert keywords
			// SHOULD NEVER ENTER THIS CODE BLOCK, BUT...
			if ( empty($competitors) || ! is_array($competitors) ) {
				$msg = sprintf( __('No websites found. To add keywords, you must have at least one website added %s.', 'psp'), $msg_engine );
				$msg = $this->build_operation_message( $msg );

				$msglist[] = $msg;
				$msglist = implode(PHP_EOL, $msglist);

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msglist,
				));
				return $ret;
			}

			//:: loop through keywords which don't already exists in db
			$stat = true;

			foreach ($kw_notindb as $keyword) {

				//:: keyword NOT found => try to insert it to db
				$keyword_id = $this->keywords_insert_in_db( array(
					'engine'		=> $engine,
					'kw'			=> $keyword,
				));
				$stat = $stat && ( ! $keyword_id ? false : true );
				if ( ! $keyword_id ) {
					$msg = sprintf( __('Keyword %s could not be inserted in db %s.', 'psp'), $keyword, $msg_engine );
					$msg = $this->build_operation_message( $msg );

					$msglist[] = $msg;
					continue 1;
				}
				else {
					$msg = sprintf( __('Keyword %s was successfully inserted in db %s.', 'psp'), $keyword, $msg_engine );
					$msg = $this->build_operation_message( $msg, 'success' );

					$msglist[] = $msg;
				}

				//:: add keyword relations to competitors
				$opRelations = $this->keywords_add_competitors_relations_db( array(
					'engine'		=> $engine,
					'keyword_id'	=> $keyword_id,
					'competitors'	=> $competitors,
				));

				$stat = $stat && ( 'invalid' == $opRelations['status'] ? false : true );

				$msglist[] = $opRelations['msg'];
				$ret = array_replace_recursive($ret, $opRelations);

			} // end foreach

			$msglist = implode(PHP_EOL, $msglist);

			$ret = array_replace_recursive($ret, array(
				'status' => $stat ? 'valid' : 'invalid',
				'msg'	=> $msglist,
			));
			$ret['status'] = 'valid';

			// engine used locations needs refresh
			$this->engine_locations_used_delete();

			$this->suggest_competitor_del( $engine );

			return $ret;
		}

		private function keywords_exists_in_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'kw' 			=> '',
			), $pms);
			extract($pms);

			$table = $this->serp_tables['keyword'];

			$sql = "select a.id from $table as a where 1=1 and a.keyword = %s and a.search_engine = %s limit 1;";
			$sql = $this->db->prepare( $sql, $kw, $engine);
			$res = $this->db->get_var( $sql );
			//var_dump('<pre>', $sql, $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $res;
		}

		private function keywords_exists_in_db_multi( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'kwlist' 		=> array(),
			), $pms);
			extract($pms);

			$table = $this->serp_tables['keyword'];

			$kwlist_ = $kwlist;
			$kwlist_ = is_array($kwlist_) && ! empty($kwlist_) ? $kwlist_ : array(0);
			$kwlist_ = $this->db->_escape($kwlist_); //esc_sql
			$kwlist_ = array_map( array($this->the_plugin, 'prepareForInList'), $kwlist_);
			$kwlist_ = implode(',', $kwlist_);

			$sql = "select a.id, a.keyword from $table as a where 1=1 and a.keyword in (" . $kwlist_ . ") and a.search_engine = %s order by a.id asc;";
			$sql = $this->db->prepare( $sql, $engine);
			$res = $this->db->get_results( $sql, OBJECT_K );
			//var_dump('<pre>', $sql, $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			if ( ! empty($res) ) {
				$__ = array();
				foreach ($res as $key => $val) {
					$__["$key"] = $val->keyword;
				}
				$res = $__;
			 }
			return $res;
		}

		private function keywords_insert_in_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'kw' 			=> '',
			), $pms);
			extract($pms);

			$table = $this->serp_tables['keyword'];

			$sql = "INSERT IGNORE INTO $table (keyword, search_engine) VALUES (%s, %s);";
			$sql = $this->db->prepare( $sql, $kw, $engine);
			$res = $this->db->query( $sql );
			if ( $res ) {
				$res = $this->db->insert_id;
			}
			return $res;
		}

		private function keywords_add_competitors_relations_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'keyword_id' 	=> 0,
				'competitors' 	=> array(),
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keywords_add_competitors_relations_db: unknown!',
			);

			$ret = $this->add_mainrank_relations( array(
				'engine'		=> $engine,
				'type'			=> 'competitors',
				'items'			=> $competitors,
				'rel_id'		=> $keyword_id,
			));
			return $ret;
		}

		private function keywords_delete_from_db( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'ids' 			=> array(),
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keywords_delete_from_db: unknown!',
			);

			$msg_engine = sprintf( __('| engine is %s', 'psp'), $engine );
			$msglist = array();

			$table = $this->serp_tables['keyword'];
			$table_mr = $this->serp_tables['mainrank'];
			$table_pr = $this->serp_tables['pagerank'];

			//:: loop through keywords which don't already exists in db
			$stat = true;
			foreach ($ids as $id) {

				//:: delete keyword from keyword table
				$sql = "DELETE ws FROM $table as ws WHERE 1=1 AND ws.id = %s and ws.search_engine = %s;";
				$sql = $this->db->prepare( $sql, $id, $engine);
				$res = $this->db->query( $sql );
				if ( false === $res ) {
					$msg = sprintf( __('Keyword ID #%s could not be deleted from db %s.', 'psp'), $id, $msg_engine );
					$msg = $this->build_operation_message( $msg );

					$msglist[] = $msg;

					$stat = false;
					continue 1;
				}
				else {
					$msg = sprintf( __('Keyword ID #%s was successfully deleted from db %s.', 'psp'), $id, $msg_engine );
					$msg = $this->build_operation_message( $msg, 'success' );

					$msglist[] = $msg;
				}

				//:: delete keyword rows from mainrank table
				$sql = "DELETE mr, pr FROM $table_mr as mr JOIN $table_pr as pr ON mr.id = pr.id_mainrank WHERE 1=1 AND mr.id_keyword = %s and mr.search_engine = %s;";
				$sql = $this->db->prepare( $sql, $id, $engine);
				$res = $this->db->query( $sql );
				if ( false === $res ) {
					$msg = sprintf( __('(id_keyword: %s, search_engine: %s) mainrank relation: could not delete any row from db %s.', 'psp'), $id, $engine, $msg_engine );
					$msg = $this->build_operation_message( $msg );
					$stat = false;
				}
				else {
					$msg = sprintf( __('(id_keyword: %s, search_engine: %s) mainrank relation: %s rows were successfully deleted from db %s.', 'psp'), $id, $engine, $res, $msg_engine );
					$msg = $this->build_operation_message( $msg, 'success' );
				}
				$msglist[] = $msg;

			} // end foreach

			$msglist = implode(PHP_EOL, $msglist);

			$ret = array_replace_recursive($ret, array(
				'status' => $stat ? 'valid' : 'invalid',
				'msg'	=> $msglist,
			));
			$ret['status'] = 'valid';

			// engine used locations needs refresh
			$this->engine_locations_used_delete();

			$this->suggest_competitor_del( $engine );

			return $ret;
		}


		/**
		 *
		 * Rank Related
		 */
		public function keyword_update_rank( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'kw_id' 		=> 0,
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keyword_update_rank: unknown!',
			);

			$msglist = array();

			if ( is_array($kw_id) ) {
				$pms_related = array(
					'kw_id' 		=> $kw_id,
				);
			}
			else {
				//var_dump('<pre>',$engine, $kw_id ,'</pre>');
				if ( ! isset($engine) ) {
					return $ret;
				}

				$pms_related = array(
					'engine' 		=> $engine,
					'kw_id' 		=> $kw_id,
				);
			}

			// get all keyword related info: associated websites
			$stat_kw_info = $this->keyword_get_related_info( $pms_related );
			//var_dump('<pre>', $stat_kw_info , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( 'invalid' == $stat_kw_info['status'] ) {
				$ret = array_replace_recursive($ret, $stat_kw_info);
				return $ret;
			}
			$kwlist = $stat_kw_info['kwlist'];

			// loop through found keywords and do the rank update
			$stat = true;
			$today = date('Y-m-d H:i:s');

			foreach ($kwlist as $key => $val) {

				$stat_update_rank = $this->keyword_update_websites_rank(array(
					'today' 	=> $today,
					'engine' 	=> $val['engine'],
					'kwinfo' 	=> $val,
				));

				if ( 'invalid' == $stat_update_rank['status'] ) {
					$msglist[] = $stat_update_rank['msg'];
					$stat = false;
					continue 1;
				}
				$msglist[] = $stat_update_rank['msg'];

			} // end foreach

			$msglist = implode(PHP_EOL, $msglist);

			$msg = $msglist;
			$msg = $stat ? $this->build_operation_message( $msg, 'success' ) : $this->build_operation_message( $msg );

			$ret = array_replace_recursive($ret, array(
				'status' => $stat ? 'valid' : 'invalid',
				'msg'	=> $msg,
			));
			$ret['status'] = 'valid';

			$this->suggest_competitor_del( $engine );

			return $ret;
		}

		public function keyword_get_related_info( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'kw_id' 		=> 0,
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keyword_get_related_info: unknown!',
			);

			if ( is_array($kw_id) ) {
				$kw_id_ = $kw_id;
				$kw_id_ = is_array($kw_id_) && ! empty($kw_id_) ? $kw_id_ : array(0);
				$kw_id_ = $this->db->_escape($kw_id_); //esc_sql
				$kw_id_ = array_map( array($this->the_plugin, 'prepareForInList'), $kw_id_);
				$kw_id_ = implode(',', $kw_id_);

				$sql_idkw = "and mr.id_keyword in ($kw_id_)";

				$msg_title = sprintf( __('[keyword id: %s] info : ', 'psp'), $kw_id_ );
			}
			else {
				//var_dump('<pre>',$engine, $kw_id ,'</pre>');
				if ( ! isset($engine) ) {
					return $ret;
				}

				$sql_idkw = "and mr.id_keyword = %s and mr.search_engine = %s";
				$sql_idkw = $this->db->prepare( $sql_idkw, $kw_id, $engine);

				$msg_title = sprintf( __('[keyword id: %s] info : ', 'psp'), $kw_id );
			}

			$table_kw = $this->serp_tables['keyword'];
			$table_mr = $this->serp_tables['mainrank'];
			$table_ws = $this->serp_tables['website'];

			$sql = "select kw.keyword, ws.website, ws.is_competitor, mr.* from $table_mr as mr left join $table_kw as kw on mr.id_keyword = kw.id left join $table_ws as ws on mr.id_website = ws.id where 1=1 $sql_idkw order by mr.id_keyword asc, mr.id asc;";
			//var_dump('<pre>', $sql , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$res = $this->db->get_results( $sql, ARRAY_A );

			if ( false === $res ) {
				$msg = $msg_title . 'db error!';
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
				));
				return $ret;
			}
			if ( empty($res) ) {
				$msg = $msg_title . 'no rows found!';
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
				));
				return $ret;
			}

			// build list of keyword & it's associated websites
			$kwlist = array();
			foreach ($res as $key => $val) {
				$search_engine_ = $val['search_engine'];
				$keyword_text = $val['keyword'];
				$keyword_id = $val['id_keyword'];
				$website_text = $val['website'];
				$website_id = $val['id_website'];
				$id_mr = $val['id'];

				if ( ! isset($kwlist["$keyword_id"]) ) {
					$kwlist["$keyword_id"] = array(
						'engine' 	=> $search_engine_,
						'keyword_id'=> $keyword_id,
						'keyword' 	=> $keyword_text,
						'websites' 	=> array(),
					);
				}
				$kwlist["$keyword_id"]['websites']["$website_id"] = $val;
			}
			//var_dump('<pre>', $kwlist , '</pre>'); echo __FILE__ . ":" . __LINE__; die . PHP_EOL;

			$msg = $msg_title . 'ok!';
			$msg = $this->build_operation_message( $msg, 'success' );

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg'		=> $msg,
				'kwlist' 	=> $kwlist,
			));
			return $ret;
		}

		public function keyword_update_websites_rank( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'today' 		=> date('Y-m-d H:i:s'),
				'engine' 		=> '',
				'kwinfo' 		=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keyword_update_websites_rank: unknown!',
			);

			$keyword = $kwinfo['keyword'];
			$keyword_id = $kwinfo['keyword_id'];

			$websites = $this->keyword_get_associated_websites( $kwinfo['websites'] );
			$websites_id = array_keys( $websites );

			$msglist = array();
			$msg_title_kw = sprintf( __('[keyword id: %s, keyword: %s] : ', 'psp'), $keyword_id, $keyword );

			// find keyword rank by API
			$is_valid_serp_config = $this->serp_api->api_set_config(array(
				'gl' => $engine,
			));
			if ( 'invalid' == $is_valid_serp_config['status'] ) {
				$ret = array_replace_recursive($ret, $is_valid_serp_config);
				return $ret;
			}

			$serp_api_pms = array(
				'engine'		=> $engine,
				'keyword'		=> $keyword,
				'domains' 		=> array_values($websites),
			);

			$websites_ranks = $this->serp_api->api_find_domains( $serp_api_pms );
			//var_dump('<pre>', $keyword, $websites_ranks , '</pre>');

			if ( 'invalid' == $websites_ranks['status'] ) {
				$ret = array_replace_recursive($ret, $websites_ranks);
				$ret['msg'] = $msg_title_kw . $ret['msg'];
				return $ret;
			}

			// add ranks for each website
			$stat = true;

			foreach ($kwinfo['websites'] as $key => $val) {

				$website = $val['website'];

				$msg_title = sprintf( __('(id_mr: %s) : ', 'psp'), $val['id'] );

				$pms_mr_rank = array(
					'today' 		=> $today,
					'msg_title' 	=> $msg_title,
					'website' 		=> $website,
					'wsinfo' 		=> $val,
					'wsranks' 		=> $websites_ranks,
				);

				$stat_main_rank = $this->keyword_update_main_rank( $pms_mr_rank );

				if ( 'invalid' == $stat_main_rank['status'] ) {
					$msglist[] = $stat_main_rank['msg'];
					$stat = false;
					continue 1;
				}
				$msglist[] = $stat_main_rank['msg'];

				$stat_page_rank = $this->keyword_update_page_rank( array_replace_recursive($pms_mr_rank, array(
					'position' 		=> $stat_main_rank['position'],
				)));

				if ( 'invalid' == $stat_page_rank['status'] ) {
					$msglist[] = $stat_page_rank['msg'];
					$stat = false;
					continue 1;
				}
				$msglist[] = $stat_page_rank['msg'];

			} // end foreach

			$msglist = implode(PHP_EOL, $msglist);

			$ret = array_replace_recursive($ret, array(
				'status' => $stat ? 'valid' : 'invalid',
				'msg'	=> $msg_title_kw . $msglist,
			));
			return $ret;
		}

		public function keyword_update_main_rank( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'today' 		=> date('Y-m-d H:i:s'),
				'msg_title' 	=> '',
				'website' 		=> '',
				'wsinfo' 		=> '',
				'wsranks' 		=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> $msg_title . 'keyword_update_main_rank: unknown!',
			);

			$table = $this->serp_tables['mainrank'];

			// row fields
			$id_mr = $wsinfo['id'];
			$check_data = $today;
			$check_status = $wsranks['status'];
			$check_msg = 'invalid' == $check_status ? $wsranks['msg'] : '';
			$website_nbpages = 0;
			$top100 = array();
			$position = 999;
			$position_worst = $wsinfo['position_worst'];
			$position_best = $wsinfo['position_best'];

			if ( 'valid' == $check_status ) {
				$top100 = isset($wsranks['top100']) ? $wsranks['top100'] : array();

				$website_rank = isset($wsranks['domains']["$website"]) ? $wsranks['domains']["$website"] : array();

				$website_nbpages = count( $website_rank );

				if ( ! empty($website_rank) ) {
					$position = min( $website_rank );
				}
			}

			$position_worst = $this->calc_position_worst( $position, $position_worst );
			$position_best = $this->calc_position_best( $position, $position_best );

			$top100 = serialize( $top100 );

			// update in table
			$sql = "UPDATE $table SET 
				website_nbpages = %s,
				position = %s,
				position_prev = position,
				position_worst = %s,
				position_best = %s,
				top100 = %s,
				last_check_status = %s,
				last_check_msg = %s,
				last_check_data = %s
			WHERE 1=1 and id = %s;";
			$sql = $this->db->prepare( $sql, 
				$website_nbpages, 
				$position, 
				$position_worst, 
				$position_best,
				$top100,
				$check_status,
				$check_msg,
				$check_data,
				$id_mr
			);

			$res = $this->db->query( $sql );
			if ( false === $res ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg_title . 'update_main_rank: db error on update!',
				));
				return $ret;
			}

			$ret = array_replace_recursive($ret, array(
				'status' => 'valid',
				'msg'	=> $msg_title . 'update_main_rank: ok!',
				'position' => $position,
			));
			return $ret;
		}

		public function keyword_update_page_rank( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'today' 		=> date('Y-m-d H:i:s'),
				'msg_title' 	=> '',
				'website' 		=> '',
				'wsinfo' 		=> '',
				'wsranks' 		=> '',
				'position' 		=> -2, // -2 = strange error, should not occur!
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> $msg_title . 'keyword_update_page_rank: unknown!',
			);

			$table = $this->serp_tables['pagerank'];

			$msglist = array();

			// row fields
			$id_mr = $wsinfo['id'];
			$rank_data = $today;
			$check_status = $wsranks['status'];

			if ( 'invalid' == $check_status ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg_title . 'update_page_rank: ' . $wsranks['msg'],
				));
				return $ret;
			}

			$top100 = isset($wsranks['top100']) ? $wsranks['top100'] : array();
			$top100 = serialize( $top100 );

			$pages = isset($wsranks['domains']["$website"]) ? $wsranks['domains']["$website"] : array();
			$pages = array_merge_recursive(array(
				'/' 	=> $position, // add main website row too, even when there are many | none pages
			), $pages);

			// if same day => delete today existent page ranks
			$sql = "DELETE pr FROM $table as pr WHERE 1=1 AND pr.id_mainrank = %s AND date(rank_date) = date(%s);";
			$sql = $this->db->prepare( $sql, $id_mr, $today);
			//var_dump('<pre>', $sql , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$res = $this->db->query( $sql );
			if ( false === $res ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg_title . 'update_page_rank: db error on delete today rows!',
				));
				return $ret;
			}
			else {
				$msglist[] = $msg_title . 'update_page_rank: successfull deleted today rows from db!';
			}

			// add page ranks for today
			$stat = true;
			foreach ($pages as $page_link => $page_position) {

				if ( '/' != $page_link ) {
					$top100 = '';
				}

				$sql = "INSERT IGNORE INTO $table (id_mainrank, page_link, rank_date, position, top100) VALUES (%d, %s, %s, %s, %s);";
				$sql = $this->db->prepare( $sql, $id_mr, $page_link, $today, $page_position, $top100 );
				$res = $this->db->query( $sql );
				if ( $res ) {
					$res = $this->db->insert_id;
				}
				$insert_id = $res;

				$stat = $stat && ( ! $insert_id ? false : true );
			} // end foreach

			if ( $stat ) {
				$msglist[] = $msg_title . 'update_page_rank: successfull inserted today rows in db!';
			}
			else {
				$msglist[] = $msg_title . 'update_page_rank: db error on insert rows!';	
			}

			$msglist = implode(PHP_EOL, $msglist);

			$msg = $msglist;

			$ret = array_replace_recursive($ret, array(
				'status' => $stat ? 'valid' : 'invalid',
				'msg'	=> $msg,
			));
			return $ret;
		}

		public function keyword_get_associated_websites( $ws_list=array() ) {
			$ret = array();
			foreach ($ws_list as $key => $val) {
				$ret["$key"] = $val['website'];
			}
			return $ret;
		}


		/**
		 *
		 * Keyword Details: top100 & evolution chart
		 */
		public function keyword_get_details( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'kw_id' 		=> 0,
				'interval' 		=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keyword_get_details: unknown!',
			);

			$msglist = array();

			//:: get all keyword related info: associated websites
			$stat_kw_info = $this->keyword_get_related_info(array(
				'engine' 		=> $engine,
				'kw_id' 		=> $kw_id,
			));

			if ( 'invalid' == $stat_kw_info['status'] ) {
				$ret = array_replace_recursive($ret, $stat_kw_info);
				return $ret;
			}
			$kwlist = $stat_kw_info['kwlist'];
			$kwlist = isset($kwlist["$kw_id"]) ? $kwlist["$kw_id"] : array();
			$keyword = isset($kwlist["keyword"]) ? $kwlist["keyword"] : '';

			//:: top100
			$websites = isset($kwlist["websites"]) ? $kwlist["websites"] : array();
			if ( empty($websites) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 	=> 'keyword_get_details: no websites associated',
				));
				return $ret;
			}

			// find top100 from first row
			$last_check_data = null;
			$top100 = array();
			$top100_ = array();
			$domains = array();
			$domains_mr = array();

			$cc = 0;
			foreach ($websites as $website_id => $theinfo) {

				$id_mr = $theinfo['id'];

				if ( empty($last_check_data) ) {
					if ( $theinfo['last_check_data'] && ! empty($theinfo['last_check_data']) ) {
						$last_check_data = $theinfo['last_check_data'];
					}
				}

				$t100 = isset($theinfo['top100']) && ! empty($theinfo['top100']) ? $theinfo['top100'] : '';

				if ( empty($top100_) && ! empty($t100) ) {
					$top100_ = $t100;
				}
				if ( 'Y' == $theinfo['is_competitor'] ) {
					if ( ! empty($t100) ) {
						$top100 = $t100;
					}
				}

				$domains["$website_id"] = $theinfo['website'];
				$domains_mr["$id_mr"] = array(
					'id' 	=> $website_id,
					'name' 	=> $theinfo['website'],
				);

				$cc++;
			} // end foreach

			if ( empty($top100) ) {
				$top100 = $top100_;
			}
			$top100 = maybe_unserialize( $top100 );
			//var_dump('<pre>', $top100 , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$stat_top100 = $this->keyword_details_build_top100(array(
				'top100' 		=> $top100,
				'domains' 		=> $domains,
			));
			if ( 'invalid' == $stat_top100['status'] ) {
				$ret = array_replace_recursive($ret, $stat_top100);
				return $ret;
			}

			// last check data
			if ( ! empty($last_check_data) ) {
				$last_check_data = date('M d, Y', strtotime($last_check_data)); //'July 19, 2017'
			}
			else {
				$last_check_data = __('never', 'psp');
			}

			//:: evolution chart
			$stat_evolution_chart = $this->keyword_details_build_evolution_chart(array(
				'engine' 		=> $engine,
				'kw_id' 		=> $kw_id,
				'interval' 		=> $interval,
				'domains_mr' 	=> $domains_mr,
			));
			if ( 'invalid' == $stat_evolution_chart['status'] ) {
				$ret = array_replace_recursive($ret, $stat_evolution_chart);
				return $ret;
			}

			//:: HTML
			$html = array();

			$html[] = '<div class="psp-keyword-details">';
			$html[] = 	'<div class="psp-keyword-details-header">';
			$html[] = 		'<ul>';
			$html[] = 			'<li><a class="psp-btn-serp" href="#serp-tabs-details-1">' . __('Ranking Evolution', $this->the_plugin->localizationName) . '</a></li>';
			$html[] = 			'<li><a class="psp-btn-serp" href="#serp-tabs-details-2">' . __('Ranking Table', $this->the_plugin->localizationName) . '</a></li>';
			$html[] = 		'</ul>';
			$html[] = 		'<div class="psp-last-checked"> <p>'. sprintf( __('Last Checked: %s', $this->the_plugin->localizationName), $last_check_data ) . '</p></div>';
			$html[] = 	'</div>';

			// evolution chart
			$html[] = 	'<div class="psp-keyword-details-content" id="serp-tabs-details-1">';
			//$html[] = 		'<p><b> keyword :<strong> sergey kovalev </strong></b></p>';
			$html[] = 		'<div class="psp-kwd-menu" data-keywordid="' . $kw_id . '">';
			$html[] = 			'<p><b> ' . sprintf( __('keyword :<strong> %s </strong>', 'psp'), $keyword ) . '</b></p>';
			$html[] = 			'<div><a href="#" data-interval="last-1-month">' . __('Last month', 'psp') . '</a></div>';
			$html[] = 			'<div><a class="on" href="#" data-interval="last-3-month">' . __('Last 3 months', 'psp') . '</a></div>';
			$html[] = 			'<div><a href="#" data-interval="last-6-month">' . __('Last 6 months', 'psp') . '</a></div>';
			$html[] = 			'<div><a href="#" data-interval="last-1-year">' . __('Last year', 'psp') . '</a></div>';
			$html[] = 			'<div><a href="#" data-interval="last-3-year">' . __('Last 3 years', 'psp') . '</a></div>';
			$html[] = 			'<div><a href="#" data-interval="anytime">' . __('Anytime', 'psp') . '</a></div>';
			$html[] = 		'</div>';
			$html[] = 		'<div class="psp-wrapper-serp-chart-evolution">';
			$html[] = 			'<canvas class="serp-chart-evolution"></canvas>';
			$html[] = 		'</div>';
			$html[] = 	'</div>';
			// end evolution chart

			// top100
			$html[] = 	'<div class="psp-keyword-details-content" id="serp-tabs-details-2">';

			$html[] = 		$stat_top100['html'];

			$html[] = 	'</div>';
			// end top100

			$html[] = '</div>';

			$html = implode(PHP_EOL, $html);

			$ret = array_replace_recursive($ret, array(
				'status' 			=> 'valid',
				'msg'				=> 'ok',
				'evolution_data' 	=> $stat_evolution_chart['data'],
				'html_top100'		=> $html,
			));
			return $ret;
		}

		public function keyword_get_evolution_chart( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'kw_id' 		=> 0,
				'interval' 		=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keyword_get_evolution_chart: unknown!',
			);

			$msglist = array();

			//:: get all keyword related info: associated websites
			$stat_kw_info = $this->keyword_get_related_info(array(
				'engine' 		=> $engine,
				'kw_id' 		=> $kw_id,
			));

			if ( 'invalid' == $stat_kw_info['status'] ) {
				$ret = array_replace_recursive($ret, $stat_kw_info);
				return $ret;
			}
			$kwlist = $stat_kw_info['kwlist'];
			$kwlist = isset($kwlist["$kw_id"]) ? $kwlist["$kw_id"] : array();
			$keyword = isset($kwlist["keyword"]) ? $kwlist["keyword"] : '';

			//:: top100
			$websites = isset($kwlist["websites"]) ? $kwlist["websites"] : array();
			if ( empty($websites) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 	=> 'keyword_get_details: no websites associated',
				));
				return $ret;
			}

			// domains
			$domains = array();
			$domains_mr = array();

			$cc = 0;
			foreach ($websites as $website_id => $theinfo) {

				$id_mr = $theinfo['id'];

				$domains["$website_id"] = $theinfo['website'];
				$domains_mr["$id_mr"] = array(
					'id' 	=> $website_id,
					'name' 	=> $theinfo['website'],
				);

				$cc++;
			} // end foreach

			//:: evolution chart
			$stat_evolution_chart = $this->keyword_details_build_evolution_chart(array(
				'engine' 		=> $engine,
				'kw_id' 		=> $kw_id,
				'interval' 		=> $interval,
				'domains_mr' 	=> $domains_mr,
			));
			if ( 'invalid' == $stat_evolution_chart['status'] ) {
				$ret = array_replace_recursive($ret, $stat_evolution_chart);
				return $ret;
			}

			$ret = array_replace_recursive($ret, array(
				'status' 			=> 'valid',
				'msg'				=> 'ok',
				'evolution_data' 	=> $stat_evolution_chart['data'],
			));
			return $ret;
		}

		private function keyword_details_build_top100( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'top100' 		=> array(),
				'domains' 		=> array(),
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keyword_details_build_top100: unknown!',
			);

			$domains_idx = psp()->serp_competitor_idx_get();
			//var_dump('<pre>', $domains_idx , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$html = array();

			$html[] = 		'<div class="psp-serp-kw-show-howmany" data-show_all_text="' . __('show all', 'psp') . '" data-show_less_text="' . __('show less', 'psp') . '"><a class="on" href="#">' . __('show all', 'psp') . '</a></div>';

			$html[] = 		'<table class="serp-table-rank">';
			$html[] =			'<thead>';
			$html[] = 				'<tr>';
			$html[] = 					'<th style="width: 10%;"> ' . __('Rank', 'psp') . ' </th>';
			$html[] = 					'<th style="width: 40%;"> ' . __('Website', 'psp') . ' </th>';
			$html[] = 					'<th style="width: 50%;"> ' . __('Page', 'psp') . ' </th>';
			$html[] = 				'</tr>';
			$html[] =			'</thead>';
			$html[] =			'<tbody>';

			// find all pages (for this domains) in content
			$domainsToFind = array();
			foreach ($domains as $domain_id => $domain_name) {
				$domainsToFind["$domain_name"] = array(
					'idx' 		=> isset($domains_idx['ids']["$domain_id"]) ? $domains_idx['ids']["$domain_id"] : -1,
					'domain_id' => $domain_id,
					'pages' 	=> array(),
				);
			}
			//var_dump('<pre>', $domainsToFind , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			// main loop through top100 pages
			$cc = 1;
			foreach ($top100 as $page_position => $page_info) {

				$td_class = array();
				$tr_class = array();
				if ( $cc <= 10 ) {
					$tr_class[] = 'psp-serp-kwdetails-show on';
				}

				$page_link = $page_info['link'];

				$cleanUrl = $this->the_plugin->clean_website_url( $page_link );

				//$page_base = '';
				$page_base = $page_link;
				$page_path = '';

				$page_link__ = parse_url($page_link);
				if ( isset($page_link__['path']) && ! empty($page_link__['path']) ) {

					$page_path = $page_link__['path'];
					if ( isset($page_link__['query']) && ! empty($page_link__['query']) ) {
						$page_path .= '?' . $page_link__['query'];
					}
					if ( isset($page_link__['fragment']) && ! empty($page_link__['fragment']) ) {
						$page_path .= '#' . $page_link__['fragment'];
					}

					$page_base = preg_replace("/" . preg_quote($page_path, '/') . "$/im", "", $page_link);
				}

				// verify to which domain it belongs
				foreach ($domainsToFind as $key => $val) {

					$cleanLinkToFind = $this->clean_website_url( $key );

					$found = preg_match("/^" . preg_quote($cleanLinkToFind, '/') . "/im", $cleanUrl, $matches);

					if ( $found ) {
						$domainsToFind["$key"]['pages']["$page_link"] = $page_position;

						$page_path = preg_replace("/^" . preg_quote($cleanLinkToFind, '/') . "/im", "", $cleanUrl);
						$page_base = preg_replace("/" . preg_quote($page_path, '/') . "\/?$/im", "", $page_link);

						if ( -1 !== $val['idx'] ) {
							$tr_class[] = 'psp-serp-kwdetails-show on';
							$td_class[] = 'psp-serp-competitor-color-link psp-serp-competitor-color-link-' . $val['idx'];
						}
					}
				} // end foreach

				$tr_class = array_filter( array_unique( $tr_class ) );
				$tr_class = implode(' ', $tr_class);

				$td_class = array_filter( array_unique( $td_class ) );
				$td_class = implode(' ', $td_class);

				$html[] = 		'<tr class="' . $tr_class . '">';
				$html[] = 			'<td><span class="' . $td_class . '">' . $page_position . '</span></td>';
				$html[] = 			'<td><a class="' . $td_class . '" href="' . $page_base . '" target="_blank"> ' . $page_base . '</a></td>';
				$html[] = 			'<td><a class="' . $td_class . '" href="' . $page_path . '" target="_blank"> ' . $page_path . ' </a></td>';
				$html[] = 		'</tr>';

				$cc++;
			} // end main loop

			$html[] =			'</tbody>';
			$html[] = 		'</table>';

			$html = implode(PHP_EOL, $html);

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg'		=> 'ok',
				'html' 		=> $html,
			));
			return $ret;
		}

		private function keyword_details_build_evolution_chart( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'kw_id' 		=> 0,
				'interval' 		=> '',
				'domains_mr'	=> array(),
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'keyword_details_build_evolution_chart: unknown!',
			);

			$dateFormat = 'Y-m-d';
			$msg_title = sprintf( __('keyword_details_build_evolution_chart [keyword id: %s] : ', 'psp'), $kw_id );

			// mysql - first day of previous month
			//   DATE( DATE_SUB( DATE_SUB( NOW(), INTERVAL 1 MONTH ), INTERVAL DAYOFMONTH( DATE_SUB( NOW(), INTERVAL 1 MONTH ) ) - 1 DAY ) )
			// mysql - first day of current month
			//   DATE( DATE_SUB( NOW(), INTERVAL DAYOFMONTH( NOW() ) - 1 DAY ) )
			$serp_evolution_chart_last = array(
				'last-1-month' 		=> 'INTERVAL 1 MONTH',
				'last-3-month'		=> 'INTERVAL 3 MONTH',
				'last-6-month' 		=> 'INTERVAL 6 MONTH',
				'last-1-year' 		=> 'INTERVAL 1 YEAR',
				'last-3-year' 		=> 'INTERVAL 3 YEAR',
				'anytime' 			=> '',
			);

			$sql_where = '';
			if ( isset($serp_evolution_chart_last["$interval"]) ) {
				if ( ! empty($serp_evolution_chart_last["$interval"]) ) {

					$date_prev_start = "DATE( DATE_SUB( DATE_SUB( NOW(), {interval} ), INTERVAL DAYOFMONTH( DATE_SUB( NOW(), {interval} ) ) - 1 DAY ) )";
					$date_prev_start = str_replace('{interval}', $serp_evolution_chart_last["$interval"], $date_prev_start);
					
					$sql_where = "AND date(pr.rank_date) >= %s";
					$sql_where = sprintf( $sql_where, $date_prev_start );
				}
			}

			if ( empty($domains_mr) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 	=> $msg_title . 'no websites (domains mr) associated',
				));
				return $ret;
			}

			$domains_idx = psp()->serp_competitor_idx_get();

			$table_pr = $this->serp_tables['pagerank'];

			$kwlist_ = array_keys( $domains_mr );
			$kwlist_ = is_array($kwlist_) && ! empty($kwlist_) ? $kwlist_ : array(0);
			$kwlist_ = $this->db->_escape($kwlist_); //esc_sql
			$kwlist_ = array_map( array($this->the_plugin, 'prepareForInList'), $kwlist_);
			$kwlist_ = implode(',', $kwlist_);

			$sql = "select pr.*, date(pr.rank_date) as _rank_date from $table_pr as pr where 1=1 and pr.id_mainrank in (" . $kwlist_ . ") and pr.page_link = '/' $sql_where order by pr.id_mainrank asc, date(rank_date) asc;";
			//$sql = $this->db->prepare( $sql);
			$res = $this->db->get_results( $sql, ARRAY_A );
			//var_dump('<pre>', $sql, $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			if ( false === $res ) {
				$msg = $msg_title . 'db error!';
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
				));
				return $ret;
			}
			if ( empty($res) ) {
				$msg = $msg_title . 'no rows found!';
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
				));
				return $ret;
			}

			// loop through rows and build the chart
			$height = 400;
			$min = 1; $max = 100;
			$positions = array();
			$datasets = array();

			foreach ($res as $val) {
				$id_mr = $val['id_mainrank'];
				$rank_data = $val['_rank_date'];

				//debug!
				//if ( 3 == $id_mr ) { continue 1; }

				$position = $val['position'];
				if ( 999 == $position ) {
					$position = 110; // scale the chart canvas
				}
				$positions[] = $position;

				$website_id = isset($domains_mr["$id_mr"], $domains_mr["$id_mr"]['id'])
					? $domains_mr["$id_mr"]['id'] : 0;

				$website = isset($domains_mr["$id_mr"], $domains_mr["$id_mr"]['name'])
					? $domains_mr["$id_mr"]['name'] : '';

				$idx = isset($domains_idx['ids']["$website_id"]) ? $domains_idx['ids']["$website_id"] : -1;
				$color = $this->the_plugin->serp_competitor_colors( $idx );

				if ( ! isset($datasets["$id_mr"]) ) {
					$datasets["$id_mr"] = array(
						'label' 			=> $website,
						'backgroundColor' 	=> $color,
						'borderColor' 		=> $color,
						'fill' 				=> 'boundary',
						'pointRadius' 		=> 5,
						'data' 				=> array(),
					);
				}

				$datasets["$id_mr"]['data'][] = array(
					'x' => date( $dateFormat, strtotime($rank_data) ),
					'y' => $position,
				);
			} // end foreach

			/*
			$datasets = array(
						'height' => 400,
						'labels' => array( "January", "February", "March", "April", "May" ),
						'datasets' => array(
								array(
									'label' => "Your Site",
									'backgroundColor' => '#fe9696',
									'borderColor' => '#fe9696',
									'data' => array( rand(1, 10), 44, rand(1, 5), 100, 55 ),
									'fill' => 'boundary',
									'pointRadius' => 5 
								),

								array(
									'label' => 'gentianaguest-house',
									'backgroundColor' => '#f4d1d1',
									'borderColor' => '#f4d1d1',
									'data' => array( rand(1, 3), rand(1, 5), rand(1, 5), 120, 15 ),
									'fill' => 'boundary',
									'pointRadius' => 3 
								),

								array(
									'label' => 'cazare-bran.ro',
									'backgroundColor' => '#efd8bd',
									'borderColor' => '#efd8bd',
									'data' => array( rand(2, 10), rand(2, 3), rand(2, 10), rand(2,6), 55 ),
									'fill' => 'boundary',
									'pointRadius' => 3 
								),

								array(
									'label' => 'bran-moeciu.ro',
									'backgroundColor' => '#e5c8f3',
									'borderColor' => '#e5c8f3',
									'data' => array( rand(2, 10), rand(3, 3), rand(2, 10), rand(2, 10), 22 ),
									'fill' => 'boundary',
									'pointRadius' => 3 
								)
						)
			);
			*/

			//var_dump('<pre>', $datasets , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$min = (int) min( $positions );
			$max = (int) max( $positions );
			if ( $max > 100 ) {
				$max = 100;
			}

			$diff = (int) ($max - $min);
			if ( $diff <= 35 ) {
				$height = 400;
			}
			else if ( $diff <= 70 ) {
				$height = 600;
			}
			else {
				$height = 800;
			}

			$ret['data'] = array(
				'height' 		=> $height,
				'min' 			=> $min,
				'max' 			=> $max,
				//'labels' 		=> array(),
				'datasets'  	=> array_values( $datasets ),
				//'sql' 			=> $sql,
			);

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg'		=> 'ok',
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $ret;
		}


		/**
		 *
		 * UTILS
		 */
		private function ajax_list_table_rows() {
			return pspAjaxListTable::getInstance( $this->the_plugin )->list_table_rows( 'return', array() );
		}

		private function build_operation_message( $msg, $type='error' ) {
			$ret = '';
			switch ($type) {
				case 'error':
					$ret = '<span class="psp-serp-opmsg psp-serp-opmsg-error">%s</span>';
					break;

				case 'success':
					$ret = '<span class="psp-serp-opmsg psp-serp-opmsg-success">%s</span>';
					break;
			}
			$ret = sprintf($ret, $msg);
			return $ret;
		}

		private function load_inc() {
			require( $this->module_folder_path . 'list.inc.php' );

			$this->vars['google_locations'] = $google_locations;
		}

		private function get_your_website() {
			return $this->the_plugin->get_your_website();
		}

		private function clean_website_url( $website ) {
			return $this->the_plugin->clean_website_url( $website );
		}

		private function search_engine__( $location='', $with_dot=true ) {
			$engine = 'google';
			$engine .= $with_dot ? '.' : '';
			$engine .= $location;
			return $engine;
		}

		private function convert_delimiter__( $delimiter="newline" ) {
			$new = '';
			switch ($delimiter) {
				case 'newline':
					$new = "\n";
					break;

				case 'comma':
					$new = ",";
					break;
			}
			return $new;
		}

		private function kw_by_delimiter__( $kw, $delimiter="newline" ) {
			if ( is_array($kw) ) {
				return $kw;
			}

			$kw = trim( $kw );
			if ( '' == $kw ) {
				return array();
			}

			$delimiter = $this->convert_delimiter__( $delimiter );

			$kw2 = explode($delimiter, $kw);

			// clean
			$kw2 = array_map('trim', $kw2);
			$kw2 = array_filter($kw2);
			$kw2 = array_unique($kw2);
			return $kw2;
		}

		private function add_mainrank_relations( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
				'type' 			=> '',
				'rel_id' 		=> 0,
				'items' 		=> array(),
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'add_mainrank_relations: unknown!',
			);

			if ( empty($items) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 	=> '<span>' . __('add_mainrank_relations: no items found.', 'psp') . '</span>',
				));
			}

			$table = $this->serp_tables['mainrank'];

			$msg_engine = sprintf( __('| engine is %s', 'psp'), $engine );
			$msglist = array();
			$stat = true;

			$mr2kw = array();

			foreach ($items as $item_id => $item_text) {

				switch ( $type ) {
					case 'keywords':
						$keyword_id = $item_id;
						$competitor_id = $rel_id;
						break;

					case 'competitors':
						$competitor_id = $item_id;
						$keyword_id = $rel_id;
						break;
				}

				$sql = "INSERT IGNORE INTO $table (id_website, id_keyword, search_engine) VALUES (%d, %d, %s);";
				$sql = $this->db->prepare( $sql, $competitor_id, $keyword_id, $engine );
				$res = $this->db->query( $sql );
				if ( $res ) {
					$res = $this->db->insert_id;
				}
				$insert_id = $res;

				$stat = $stat && ( ! $insert_id ? false : true );

				if ( ! $insert_id ) {
					$msg = sprintf( __('(id_website: %s, id_keyword: %s, search_engine: %s) mainrank relation row could not be inserted in db %s.', 'psp'), $competitor_id, $keyword_id, $engine, $msg_engine );
					$msg = $this->build_operation_message( $msg );
				}
				else {
					$msg = sprintf( __('(id_website: %s, id_keyword: %s, search_engine: %s) mainrank relation row was successfully inserted in db %s.', 'psp'), $competitor_id, $keyword_id, $engine, $msg_engine );
					$msg = $this->build_operation_message( $msg, 'success' );

					$mr2kw["$insert_id"] = $keyword_id;
				}
				$msglist[] = $msg;
			} // end foreach

			$msglist = implode(PHP_EOL, $msglist);

			$ret = array_replace_recursive($ret, array(
				'status' 	=> $stat ? 'valid' : 'invalid',
				'msg' 		=> $msglist,
				'mr2kw' 	=> $mr2kw,
			));
			return $ret;
		}

		private function calc_position_worst( $position, $position_worst ) {
			$new_position_worst = $position_worst;

			if ( -1 == $position_worst ) {
				$new_position_worst = $position;
			}
			else if ( 999 == $position_worst ) {
				$new_position_worst = 999;
			}
			else {
				if ( 999 == $position ) {
					$new_position_worst = 999;
				}
				else if ( $position > $position_worst ) {
					$new_position_worst = $position;
				}
				else {
					$new_position_worst = $position_worst;	
				}
			}
			return $new_position_worst;
		}

		private function calc_position_best( $position, $position_best ) {
			$new_position_best = $position_best;

			if ( -1 == $position_best ) {
				$new_position_best = $position;
			}
			else if ( 999 == $position_best ) {
				$new_position_best = $position;
			}
			else {
				if ( 999 == $position ) {
					$new_position_best = $position_best;
				}
				else if ( $position < $position_best ) {
					$new_position_best = $position;
				}
				else {
					$new_position_best = $position_best;	
				}
			}
			return $new_position_best;
		}

		private function verify_allowed_max_limits( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'what' 			=> '', // keyword | website
				'engine' 		=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'verify_allowed_max_limits: unknown!',
			);

			if ( 'keyword' == $what ) {
				$nb_current = $this->find_nb_keywords_get(array(
					'engine' 	=> $engine,
				));
				$max_global = $this->serp_settings['keywords_max_global'];
				$max_per_engine = $this->serp_settings['keywords_max_per_engine'];
				$what_text = 'keywords';
			}
			else if ( 'website' == $what ) {
				$nb_current = $this->find_nb_websites_get(array(
					'engine' 	=> $engine,
				));
				$max_global = $this->serp_settings['websites_max_global'];
				$max_per_engine = $this->serp_settings['websites_max_per_engine'];
				$what_text = 'competitors';
			}

			//:: verify max allowed engines
			$nb_engines_current = $this->find_nb_engines_get();
			if (
				( $nb_engines_current['nb'] >= $this->serp_settings['engines_max'] )
				&& ( ! in_array( $engine, $nb_engines_current['list'] ) )
			) {
				$msg = sprintf( __('You\'ve reached the maximum allowed number of engine locations you can use (max = %s)', 'psp'), $this->serp_settings['engines_max'] );
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}

			//:: verify max allowed websites
			$nb_current = $this->find_nb_websites_get(array(
				'engine' 	=> $engine,
			));
			if ( $nb_current['global'] >= $max_global ) {
				$msg = sprintf( __('You\'ve reached the maximum allowed global number of %s you can use (max = %s)', 'psp'), $what_text, $max_global );
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}
			if ( $nb_current['per_engine'] >= $max_per_engine ) {
				$msg = sprintf( __('You\'ve reached the maximum allowed number of %s per engine you can use (max = %s)', 'psp'), $what_text, $max_per_engine );
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
			));
			return $ret;
		}

		public function calc_page_points_by_position( $position=0 ) {
			$points = 0;
			if ( $position <=0 || $position > 100 ) {
				return $points;
			}

			if ( 1 == $position ) {
				$points = 100; //best postion
			}
			else if ( $position <= 3 ) {
				$points = 80; //very fine too
			}
			else if ( $position <= 5 ) {
				$points = 65; //fine too
			}
			else if ( $position <= 10 ) {
				$points = 45; //ok
			}
			else if ( $position <= 30 ) {
				$points = 12; //just that is there
			}
			else if ( $position <= 50 ) {
				$points = 7; //just that is there
			}
			else if ( $position <= 100 ) {
				$points = 2; //just that is there
			}
			return $points;
		}

		public function what_top_by_position( $position=0 ) {
			$key = 999;
			if ( $position <=0 || $position > 100 ) {
				return $key;
			}

			if ( 1 == $position ) {
				$key = 1; //best postion
			}
			else if ( $position <= 3 ) {
				$key = 3; //very fine too
			}
			else if ( $position <= 5 ) {
				$key = 5; //fine too
			}
			else if ( $position <= 10 ) {
				$key = 10; //ok
			}
			else if ( $position <= 30 ) {
				$key = 30; //just that is there
			}
			else if ( $position <= 50 ) {
				$key = 50; //just that is there
			}
			else if ( $position <= 100 ) {
				$key = 100; //just that is there
			}
			return $key;
		}


		/**
		 *
		 * Focus Keywords
		 */
		public function build_focus_keywords_table() {
			ob_start();

											//pspAjaxListTable::getInstance( $this->the_plugin )
											$ajaxTable = new pspAjaxListTable( $this->the_plugin );
											$ajaxTable
												->setup(array(
													'id' 				=> 'pspListFocusKeywords',
													'show_header' 		=> true,
													'show_header_buttons' => true,
													'items_per_page' 	=> '10',
													'post_statuses' 	=> 'all',
													'columns'			=> array(
														'checkbox'	=> array(
															'th'	=>  'checkbox',
															'td'	=>  'checkbox',
														),

														'id'		=> array(
															'th'	=> __('ID', 'psp'),
															'td'	=> '%ID%',
															'width' => '40'
														),

														'title'		=> array(
															'th'	=> __('Title', 'psp'),
															'td'	=> '%title_mini_actions%',
															'align' => 'left',
															'width' => '250'
														),

														/*'score'		=> array(
															'th'	=> __('Score', 'psp'),
															'td'	=> '%score%',
															'width' => '120'
														),*/
														'multi_focus_kw'	=> array(
															'th'	=> __('Focus Keywords', 'psp'),
															'td'	=> '%multi_focus_keyword%',
															'width' => '300'
														),
													),
													'mass_actions' 		=> false,
												))
												->print_html();

			$html = ob_get_clean();
			return $html;
		}


		/**
		 *
		 * Stats
		 */
		private function get_stats_kw_rank( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 	=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'unknown!',
				'total' 	=> 0,
			);

			$status = true;
			$msglist = array();

			//:: the number of keywords updated today (for current engine and per all engines)
			$table_mr = $this->serp_tables['mainrank'];

			$sql = "select mr.search_engine, mr.id_keyword from $table_mr as mr where 1=1 and date(mr.last_check_data) >= curdate() group by mr.id_keyword order by mr.search_engine asc, mr.id_keyword asc;";
			//var_dump('<pre>', $sql , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$res = $this->db->get_results( $sql, ARRAY_A );
			//var_dump('<pre>', $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( false === $res ) {
				$msglist[] = '<span>' . 'get_stats_kw_rank: db error!' . '</span>';
			}
			if ( empty($res) ) {
				$msglist[] = '<span>' . __('No keywords updated today.', 'psp') . '</span>';
			}

			$engine_total = 0;
			$engine_current = 0;
			if ( ! empty($res) ) {

				$kw_found = array();
				foreach ($res as $key => $val) {

					$kw_engine = $val['search_engine'];
					$kw_id = $val['id_keyword'];

					if ( ! isset($kw_found["$kw_engine"]) ) {
						$kw_found["$kw_engine"] = array();
					}
					if ( ! in_array($kw_id, $kw_found["$kw_engine"]) ) {
						$kw_found["$kw_engine"][] = $kw_id;
					}

					$engine_total++;
					if ( $engine == $kw_engine ) {
						$engine_current++;
					}
				}
				//var_dump('<pre>', $kw_found , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				$msglist[] = '<span>' . sprintf( __('<strong>%s</strong> keywords updated today on this search engine ( <strong>%s</strong> keywords updated today on all your chosen search engines ).', 'psp'), $engine_current, $engine_total ) . '</span>';
			}

			//:: can we do a request today?
			$can_make_request = $this->serp_api->api_can_make_request();
			if ( 'invalid' == $can_make_request['status'] ) {
				//$ret = array_replace_recursive($ret, $can_make_request);
				//return $ret;

				$msglist[] = '<span>' . $can_make_request['msg'] . '</span>';
				$status = false;
			}

			$msglist = implode('&nbsp;&nbsp;&nbsp;&nbsp;', $msglist);

			if ( $status ) {
				$msg = '<span class="psp-message psp-success">%s</span>';
			}
			else {
				$msg = '<span class="psp-message psp-error">%s</span>';
			};
			$msg = sprintf( $msg, $msglist );

			$ret = array_replace_recursive($ret, array(
				'status' 	=> $status ? 'valid' : 'invalid',
				'msg' 		=> $msg,
				'total' 	=> $engine_total,
			));
			return $ret;
		}


		/**
		 *
		 * Limits
		 */
		public function find_nb_engines_fromdb( $pms=array() ) {
			return $this->engine_locations_used_fromdb();
		}

		public function find_nb_engines_get( $pms=array() ) {
			$pms = array_replace_recursive(array(
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'unknown!',
				'list' 		=> array(),
				'nb' 		=> 0,
			);

			$nb = 0;
			$list = $this->engine_locations_used_get( $pms );
			if ( is_array($list) ) {
				$nb = count( $list );
			}

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
				'list' 		=> $list,
				'nb' 		=> $nb,
			));
			return $ret;
		}

		public function find_nb_engines_del() {
			$this->engine_locations_used_delete();
		}

		public function find_nb_keywords_fromdb( $pms=array() ) {
			return $this->find_nb_what_fromdb( array_replace_recursive(array(
				'what' 	=> 'keyword',
			), $pms));
		}

		public function find_nb_keywords_get( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 	=> '',
			), $pms);
			extract($pms);

			$ret_cached = get_transient( 'psp-serp-nb-keywords' );
	
			if ( $ret_cached && is_array($ret_cached) && isset($ret_cached['status']) && ('valid' == $ret_cached['status']) ) {
				return $ret_cached;
			}

			$fromdb = $this->find_nb_keywords_fromdb( $pms );
			set_transient( 'psp-serp-nb-keywords', $fromdb, (3600 * 24) ); // expire in 1 day
			return $fromdb;
		}

		public function find_nb_keywords_del() {
			delete_transient( 'psp-serp-nb-keywords' );
		}

		public function find_nb_websites_fromdb( $pms=array() ) {
			return $this->find_nb_what_fromdb( array_replace_recursive(array(
				'what' 	=> 'website',
			), $pms));
		}

		public function find_nb_websites_get( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 	=> '',
			), $pms);
			extract($pms);

			$ret_cached = get_transient( 'psp-serp-nb-websites' );
	
			if ( $ret_cached && is_array($ret_cached) && isset($ret_cached['status']) && ('valid' == $ret_cached['status']) ) {
				return $ret_cached;
			}

			$fromdb = $this->find_nb_websites_fromdb( $pms );
			set_transient( 'psp-serp-nb-websites', $fromdb, (3600 * 24) ); // expire in 1 day
			return $fromdb;
		}

		public function find_nb_websites_del() {
			delete_transient( 'psp-serp-nb-websites' );
		}

		public function find_nb_what_fromdb( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'what' 			=> '', // keyword | website
				'engine' 		=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'		=> 'invalid',
				'msg'			=> 'unknown!',
				'list' 			=> array(),
				'global' 		=> 0,
				'per_engine' 	=> 0,
			);

			if ( 'keyword' == $what ) {
				$table = $this->serp_tables['keyword'];
				$sql = "select a.search_engine, count(a.id) as nb from $table as a where 1=1 group by a.search_engine order by a.search_engine asc;";
			}
			else if ( 'website' == $what ) {
				// and a.is_competitor='Y'
				$table = $this->serp_tables['website'];
				$sql = "select a.search_engine, count(a.id) as nb from $table as a where 1=1 and a.is_competitor='Y' group by a.search_engine order by a.search_engine asc;";
			}
			//$sql = $this->db->prepare( $sql );

			$res = $this->db->get_results( $sql, ARRAY_A );
			//var_dump('<pre>', $sql, $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			if ( empty($res) ) {
				$ret = array_replace_recursive($ret, array(
					'status' 	=> 'valid',
					'msg' 		=> 'no rows found in db.',
				));
				return $ret;
			}

			$_global = 0;
			$_per_engine = 0;
			$list = array();
			foreach ($res as $key => $val) {
				$_engine = $val['search_engine'];
				$_nb = $val['nb'];

				$list["$_engine"] = $_nb;

				$_global += $_nb;
				if ( $engine == $_engine ) {
					$_per_engine = $_nb;
				}
			}

			$ret = array_replace_recursive($ret, array(
				'status' 		=> 'valid',
				'msg' 			=> 'ok!',
				'list' 			=> $list,
				'global' 		=> $_global,
				'per_engine' 	=> $_per_engine,
			));
			return $ret;
		}


		/**
		 *
		 * Suggest competitors
		 */
		public function suggest_competitor_fromdb( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'suggest_competitor_get_list: unknown!',
			);

			//$msglist = array();

			//:: retrieve all keywords for this engine which had rank updated at least once
			$table_mr = $this->serp_tables['mainrank'];

			//mr.top100, mr.last_check_status, mr.last_check_msg, mr.last_check_data
			$sql = "select mr.* from $table_mr as mr where 1=1 and mr.search_engine = %s and ( mr.last_check_status = 'valid' or mr.top100 regexp '^a:' ) group by mr.id_keyword order by mr.id asc;";
			$sql = $this->db->prepare( $sql, $engine );
			//var_dump('<pre>', $sql , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$res = $this->db->get_results( $sql, OBJECT_K );
			//var_dump('<pre>', $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( false === $res ) {
				$msg = __('suggest_competitor_get_list: db error!', 'psp');
				$msg = $this->build_operation_message( $msg );
				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}
			if ( empty($res) ) {
				$msg = __('suggest_competitor_get_list: no rows found.', 'psp');
				$msg = $this->build_operation_message( $msg );
				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}
			$nb_kw = count($res);

			//:: retrieve first Best X competitors - by score
			$stat = true;
			$today = date('Y-m-d H:i:s');

			$suggest = array(
				'points' 	=> array(),
				'list' 		=> array(),
			);
			foreach ($res as $id_mr => $mr_row_found) {

				//!!! SHOULD BE THE KEYWORD TEXT, BUT IT DOESN'T REALLY MATTER, IT'S JUST INFORMATIVE
				$keyword = $mr_row_found->id_keyword;

				$top100 = $mr_row_found->top100;
				$top100 = maybe_unserialize( $top100 );
				$top100_ = array(
					'request' 	=> array(
						'top_type' 		=> 100,
						'engine' 		=> $engine,
						'keyword' 		=> $keyword,
					),
					'items' 	=> array_values( $top100 ),
				);

				$wsinfo = array(
					'id' 				=> $id_mr,
					'position' 			=> -1,
					'position_worst' 	=> -1,
					'position_best' 	=> -1,

				);
				//var_dump('<pre>', $mr_row_found, $wsinfo , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				//:: find keyword rank by API
				$is_valid_serp_config = $this->serp_api->api_set_config(array(
					'gl' => $engine,
				));
				if ( 'invalid' == $is_valid_serp_config['status'] ) {
					continue 1;
				}

				$serp_api_pms = array(
					//'startPos' 		=> 0, // where to start (index position in content)
					'content' 		=> json_encode( $top100_ ), // a json which will be converted to an array
					'domains' 		=> array(),
				);

				$websites_ranks = $this->serp_api->api_parse_response( $serp_api_pms );
				//var_dump('<pre>', $keyword, $websites_ranks , '</pre>');

				if ( 'invalid' == $websites_ranks['status'] ) {
					continue 1;
				}

				$top_by_domain = $websites_ranks['top_by_domain'];
				//var_dump('<pre>', $top_by_domain , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

				foreach ($top_by_domain as $_domain => $_domain_pages) {
					if ( ! isset($suggest['points']["$_domain"]) ) {
						$suggest['points']["$_domain"] = 0;
					}
					if ( ! isset($suggest['list']["$_domain"]) ) {
						$suggest['list']["$_domain"] = array();
					}

					foreach ($_domain_pages as $_page_link => $_page_pos) {
						$suggest['points']["$_domain"] += $this->calc_page_points_by_position( $_page_pos );

						if ( ! isset($suggest['list']["$_domain"]["$_page_link"]) ) {
							$suggest['list']["$_domain"]["$_page_link"] = array();
						}
						$suggest['list']["$_domain"]["$_page_link"]["$keyword"] = $_page_pos;
					} // end foreach
				} // end foreach
			} // end foreach
			//var_dump('<pre>', $suggest , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: get first Best X ones
			arsort( $suggest['points'], SORT_NUMERIC );
			$suggest['points'] = array_slice( $suggest['points'], 0, 50 );
			foreach ($suggest['list'] as $kk => $vv) {
				if ( ! isset($suggest['points']["$kk"]) ) {
					unset( $suggest['list']["$kk"] );
				}
			}
			//var_dump('<pre>', $suggest , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: get competitors score as percent
			$suggest['percent'] = array();
			foreach ($suggest['points'] as $kk => $vv) {
				$suggest['percent']["$kk"] = number_format( ($vv / $nb_kw), 1 );
				if ( (float) $suggest['percent']["$kk"] > 100.0 ) {
					$suggest['percent']["$kk"] = "100.0";
				}
			}
			//var_dump('<pre>', $nb_kw, $suggest['percent'] , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//$msglist = implode(PHP_EOL, $msglist);

			$ret = array_replace_recursive($ret, $suggest, array(
				'status' => 'valid',
				'msg'	=> 'ok!',
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $ret;
		}

		public function suggest_competitor_get( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 	=> '',
			), $pms);
			extract($pms);

			$ret_cached = get_transient( 'psp-serp-suggested-competitors-' . $engine );
	
			if ( $ret_cached && is_array($ret_cached) && isset($ret_cached['status']) && ('valid' == $ret_cached['status']) ) {
				return $ret_cached;
			}

			$fromdb = $this->suggest_competitor_fromdb( $pms );
			set_transient( 'psp-serp-suggested-competitors-' . $engine, $fromdb, (3600 * 24) ); // expire in 1 day
			return $fromdb;
		}

		public function suggest_competitor_del( $engine ) {
			delete_transient( 'psp-serp-suggested-competitors-' . $engine );
		}

		public function suggest_competitor_box( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'engine' 		=> '',
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'suggest_competitor_box: unknown!',
				'html' 		=> '',
			);

			$opStatus = $this->suggest_competitor_get( array_replace_recursive(array(
				'engine' 	=> $engine,
			)));
			if ( 'invalid' == $opStatus['status'] ) {
				$ret = array_replace_recursive($ret, $opStatus);
				return $ret;
			}
			$_domains = $opStatus['percent'];

			$all = $this->competitor_get_competitors_fromdb( array_replace_recursive(array(
				'engine' 	=> $engine,
			)));
			$all_ = array();
			if ( ! empty($all) ) {
				foreach ($all as $kk => $vv) {
					$all_["$kk"] = $vv->website;
				}
			}

			$html = array();

			foreach ($_domains as $_host => $_percent) {
				$score = $this->the_plugin->serp_build_score_html( (float) $_percent, array(
					'show_score' 	=> true,
					'css_style'		=> 'style="margin-right:4px"',
				));

				$html[] = '<li class="psp-serp-suggest-item">';
				$html[] = 	'<span class="psp-serp-suggest-host">' . $_host . '</span>';
				$html[] = 	$score;

				if ( ! in_array($_host, $all_) ) {
					$html[] = 	'<input type="button" value="add" class="psp-serp-suggest-add psp-form-button-small psp-form-button-success">';
				}
				else {
					$html[] = 	'<span class="psp-serp-suggest-already">' . __('added', 'psp') . '</span>';	
				}
				$html[] = '</li>';
			}

			$html = implode(PHP_EOL, $html);

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
				'html' 		=> $html,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $ret;
		}


		/**
		 *
		 * Email rank changes
		 */


		/**
		 * Interface
		 */
		public function moduleValidation() {
			$ret = array(
				'status'			=> false,
				'html'				=> ''
			);
			
			// find if user makes the setup
			$module_settings = $serp_settings = $this->the_plugin->get_theoption( $this->the_plugin->alias . "_serp" );

			$serp_mandatoryFields = array(
				'developer_key'			=> false,
				'custom_search_id'		=> false,
				//'google_country'		=> false
			);
			if ( isset($serp_settings['developer_key']) && !empty($serp_settings['developer_key']) ) {
				$serp_mandatoryFields['developer_key'] = true;
			}
			if ( isset($serp_settings['custom_search_id']) && !empty($serp_settings['custom_search_id']) ) {
				$serp_mandatoryFields['custom_search_id'] = true;
			}
			//if ( isset($serp_settings['google_country']) && !empty($serp_settings['google_country']) ) {
			//	$serp_mandatoryFields['google_country'] = true;
			//}
			$mandatoryValid = true;
			foreach ($serp_mandatoryFields as $k=>$v) {
				if ( !$v ) {
					$mandatoryValid = false;
					break;
				}
			}
			if ( !$mandatoryValid ) {
				$error_number = 1; // from config.php / errors key
				
				$ret['html'] = $this->the_plugin->print_module_error( $this->module, $error_number, 'Error: Unable to use Google Serp module, yet!' );
				return $ret;
			}
			$ret['status'] = true;
			return $ret;
		}

		public function print_interface_main()
		{
?>
		<script type="text/javascript" src="<?php echo $this->module_folder;?>md5.min.js" ></script>
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.class.js" ></script>
		<div class="<?php echo $this->the_plugin->alias; ?> psp-mod-serp">
			
			<div class="psp-serp <?php echo $this->the_plugin->alias; ?>-content"> 

				<?php
				// show the top menu
				pspAdminMenu::getInstance()->make_active('monitoring|serp')->show_menu();
				?>

				<!-- Content -->
				<section class="<?php echo $this->the_plugin->alias; ?>-main">

					<?php
					echo psp()->print_section_header(
						$this->module['serp']['menu']['title'],
						$this->module['serp']['description'],
						$this->module['serp']['help']['url']
					);
					?>

					<?php 
					// find if user makes the setup
					$moduleValidateStat = $this->moduleValidation();
					if ( !$moduleValidateStat['status'] ) {
						echo $moduleValidateStat['html'];
					}
					// moduleValidation
					else
					{
					?>
					<div class="panel panel-default <?php echo $this->the_plugin->alias; ?>-panel">

						<!-- main serp lightbox -->
						<div id="psp-lightbox-overlay" class="psp-serp-lightbox">
							<div id="psp-lightbox-container">

								<!-- add keyword -->
								<div class="psp-serp-lbx-sections psp-serp-lbx-addkeyword" style="display: none;">
								<form>

									<h1 class="psp-lightbox-headline">
										<span><?php _e('Add Keywords', 'psp');?></span>
										<a href="#" class="psp-close-btn" title="<?php _e('Close Lightbox', 'psp'); ?>"><i class="psp-checks-cross2"></i></a>
									</h1>
				
									<div class="psp-seo-status-container">

										<?php /*<div id="psp-lightbox-seo-report-response"></div>*/ ?>

										<div class="psp-keywords-container">
											<div class="psp-keyword-tabs">
												<ul>
													<li><a class="psp-btn-serp btn-keys" href="#serp-tabs-keys1"><?php _e('Select from your Focus Keywords', 'psp'); ?>  </a></li>
													<li><a class="psp-btn-serp btn-keys" href="#serp-tabs-keys2"><?php _e('Add Manually', 'psp'); ?> </a></li>
												</ul>
											</div>

											<div class="psp-keyword-content" id="serp-tabs-keys1">

												<?php /*<form class="psp-form" action="#save_with_ajax">*/ ?>
												<div class="psp-form-row psp-table-ajax-list psp-fkw-ajax-table" id="psp-table-ajax-response">
												<?php
													//!!! moved to Load by Ajax!
													//echo $this->build_focus_keywords_table();
												?>
												</div>
												<?php /*</form>*/ ?>

												<div class="psp-selected-keywords">
													<h2><?php _e('Selected Keywords', 'psp'); ?></h2>
													<div class="psp-selected-keywords-list">
													</div>
												</div>

												<button class="psp-form-button-small psp-form-button-success psp-save_keywords_select"><?php _e('Add Keywords', 'psp'); ?></button>
											</div>

											<div class="psp-keyword-content" id="serp-tabs-keys2">
												<textarea name="psp-add-keywords-manual" id="psp-add-keywords-manual" class="psp-keywords-textarea" placeholder="<?php _e('add your keywords here, separated by line or comma', 'psp'); ?>"></textarea>	

												<div class="psp-serp-keywords-delimiters">
															<span><?php _e('keywords delimiter is', 'psp');?>:</span>
															<p>
																<input type="radio" value="newline" name="psp-csv-delimiter" id="psp-csv-radio-newline" checked="checked">
																<label for="psp-csv-radio-newline"><?php _e('New line', 'psp');?> 
																	<code>\n</code>
																</label>
															</p>
															<p>
																<input type="radio" value="comma" name="psp-csv-delimiter" id="psp-csv-radio-comma">
																<label for="psp-csv-radio-comma"><?php _e('Comma', 'psp');?> 
																	<code>,</code>
																</label>
															</p>
															<?php /*<p>
																<input type="radio" value="tab" name="psp-csv-delimiter" id="psp-csv-radio-tab">
																<label for="psp-csv-radio-tab"><?php _e('TAB', 'psp');?> 
																	<code>TAB</code>
																</label>
															</p>*/ ?>
												</div>

												<button class="psp-form-button-small psp-form-button-success psp-save_keywords_manual"><?php _e('Add Keywords', 'psp'); ?></button>
											</div>
										</div>
										<!-- end psp-keywords-container -->

										<div style="clear:both"></div>
									</div>

								</form>
								</div>
								<!-- end add keyword -->

								<!-- add competitor -->
								<div class="psp-serp-lbx-sections psp-serp-lbx-addcompetitor" style="display: none;">
								<form>

									<h1 class="psp-lightbox-headline">
										<span><?php _e('Add Competitor', 'psp');?></span>
										<a href="#" class="psp-close-btn" title="<?php _e('Close Lightbox', 'psp'); ?>"><i class="psp-checks-cross2"></i></a>
									</h1>
				
									<div class="psp-seo-status-container">

										<?php /*<div id="psp-lightbox-seo-report-response"></div>*/ ?>

										<div class="psp-keywords-container">
											<div class="psp-keyword-content">
												<input name="psp-add-competitor-name" id="psp-add-competitor-name" class="psp-keywords-input-text" />
												<button class="psp-form-button-small psp-form-button-warning psp-save_competitor"><?php _e('Add Competitor', 'psp'); ?></button>
											</div>
										</div>
										<!-- end psp-keywords-container -->

										<div style="clear:both"></div>
									</div>

								</form>
								</div>
								<!-- end add competitor -->

							</div>
						</div>
						<!-- END main serp lightbox -->
						
						<div class="panel-heading psp-panel-heading">
							<h2><?php _e('Keyword Tool - Drive more traffic to your website!', 'psp');?></h2>
						</div>
			
						<div class="panel-body <?php echo $this->the_plugin->alias; ?>-panel-body" id="psp-main-ajax-table">
							
							<!-- Container -->
							<div class="psp-container clearfix">
			
								<!-- Main Content Wrapper -->
								<div id="psp-content-wrap" class="clearfix">
												
									<div class="psp-panel">

										<!-- Cronjob stats -->
										<div class="psp-serp-cron-stats" data-what="kw_rank" style="display: none;">
											<h3><?php _e('Stats', $this->the_plugin->localizationName); ?></h3>
											<?php //echo $this->get_stats_kw_rank(); ?>
										</div>

										<!-- Log Messages -->
										<div class="" id="psp-serp-debug-log"></div>
											
										<!-- setup google location -->
										<div class="" id="psp-serp-engine-response">
											<?php echo $this->engine_html_reponse( get_option( 'psp-serp-engine-only-used', 0 ) ); ?>
										</div>

										<!-- Suggest Competitors -->
										<div class="psp-serp-suggest" style="display: none;">

											<?php
											$html_howmany = array();
											$html_howmany[] = '<div class="psp-serp-suggest-show-howmany" data-show_all_text="' . __('show more', 'psp') . '" data-show_less_text="' . __('show less', 'psp') . '">';
											//$html_howmany = 	'<a class="on" href="#">' . __('show all', 'psp') . '</a>';
											$html_howmany[] = 	'<span>' . __('We\'ve searched through your keywords for this engine and based on the pages found and their positions in top 100, we\'ve selected the following top competitors', 'psp') . '</span>';
											$html_howmany[] = '</div>';
											echo implode(PHP_EOL, $html_howmany);
											?>

											<ol class="psp-serp-suggest-list"></ol>

										</div>

										<form class="psp-form" action="#save_with_ajax">
											<div class="psp-form-row psp-table-ajax-list" id="psp-table-ajax-response">
											<?php

											pspAjaxListTable::getInstance( $this->the_plugin )
												->setup(array(
													'id' 				=> 'pspSERPKeywords',
													'custom_table'		=> "psp_serp_reporter",
													//'deleted_field'		=> true,
													//'force_publish_field' 	=> false,
													'show_header' 		=> true,
													'show_header_buttons' => true,
													'items_per_page' 	=> '100',
													//'post_statuses' 	=> 'all',
													/*'filter_fields'		=> array(
														'publish'  => array(
															'title' 			=> __('Published', $this->the_plugin->localizationName),
															'options_from_db' 	=> false,
															'include_all'		=> true,
															'options'			=> array(
																'Y'			=> __('Published', $this->the_plugin->localizationName),
																'N'			=> __('Unpublished', $this->the_plugin->localizationName),
															),
															'display'			=> 'links',
														),
													),*/

													'orderby'			=> 'id',
													'order'				=> 'ASC',

													'columns'			=> array(

														'checkbox'	=> array(
															'th'	=>  'checkbox',
															'td'	=>  'checkbox',
														),

														'id'		=> array(
															'th'	=> __('ID', 'psp'),
															'td'	=> '%id%',
															'width' => '20'
														),

														'serp_keyword' => array(
															'th'	=> __('Keyword', 'psp'),
															'td'	=> '%serp_keyword%',
															'align' => 'left',
															'width' => '150',
															'class'	=> 'psp-phrase',
														),

														'last_check' => array(
															'th'	=> __('Last check', 'psp'),
															'td'	=> '%serp_last_check%',
															'align' => 'left',
															'width' => '200',
															'class'	=> '',
														),

														//:: the ranks columns are generated in /aa-framework/ajax-list-table.php
														
													),
													'mass_actions' 	=> array(
														'add_keyword' => array(
															'value' => __('Add Keyword', 'psp'),
															'action' => 'do_add_keyword',
															'color' => 'success'
														),
														'add_competitor' => array(
															'value' => __('Add Competitor', 'psp'),
															'action' => 'do_add_competitor',
															'color' => 'warning'
														),
														'delete_all_rows' => array(
															'value' => __('Delete selected rows', 'psp'),
															'action' => 'do_custom_bulk_delete_rows',
															'color' => 'danger'
														)
													)
												))
												->print_html();
											?>
											</div>
										</form>
									</div>

									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
					<?php
					} // end moduleValidation
					?>

				</section>
			</div>
		</div>
<?php
		}

		public function print_interface_stats()
		{
?>
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.class.js" ></script>
		<div class="<?php echo $this->the_plugin->alias; ?> psp-mod-serp">
			
			<div class="psp-serp <?php echo $this->the_plugin->alias; ?>-content"> 

				<?php
				// show the top menu
				pspAdminMenu::getInstance()->make_active('monitoring|serp')->show_menu();
				?>

				<!-- Content -->
				<section class="<?php echo $this->the_plugin->alias; ?>-main">
					
					<?php 
					echo psp()->print_section_header(
						$this->module['serp']['menu']['title'],
						$this->module['serp']['description'],
						$this->module['serp']['help']['url']
					);
					?>
					
					<?php 
					// find if user makes the setup
					$moduleValidateStat = $this->moduleValidation();
					if ( !$moduleValidateStat['status'] ) {
						echo $moduleValidateStat['html'];
					}
					// moduleValidation
					else
					{
					?>
					<div class="panel panel-default <?php echo $this->the_plugin->alias; ?>-panel">

						<div class="panel-heading psp-panel-heading">
							<h2><?php _e('SERP - Your Website Stats!', 'psp');?></h2>
						</div>
			
						<div class="panel-body <?php echo $this->the_plugin->alias; ?>-panel-body" id="psp-main-ajax-table">
							
							<!-- Container -->
							<div class="psp-container clearfix">
			
								<!-- Main Content Wrapper -->
								<div id="psp-content-wrap" class="clearfix">
												
									<div class="psp-panel">

										<!-- Box with website filters-->
										<div class="psp-serp-ws-filters" style="display: block;">
											<?php
												$date_from_def = date( 'm/d/Y', strtotime('-1 month', time()) );
												$date_to_def = date( 'm/d/Y', time() );

												$date_from = '<input class="datepicker" type="text" id="date_from" value="' . $date_from_def . '">';
												$date_to = '<input class="datepicker" type="text" id="date_to" value="' . $date_to_def . '">';
											?>
											<span>
											<?php
												echo sprintf( __('Compare your website evolution between %s and %s', 'psp'), $date_from, $date_to );
											?>
											</span>
											<select id="psp-serp-ws-include-competitors">
												<option value="" disabled="disabled"><?php _e('Include competitors', 'psp'); ?></option>
												<option value="yes"><?php _e('Yes', 'psp'); ?></option>
												<option value="no" selected="selected"><?php _e('No', 'psp'); ?></option>
											</select>
											<input type="button" value="Apply Filters" class="psp-form-button-small psp-form-button-success">
										</div>

										<!-- Box with website stats-->
										<div class="psp-serp-ws-stats" style="display: none;">
										</div>

									</div>

									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
					<?php
					} // end moduleValidation
					?>

				</section>
			</div>
		</div>
<?php
		}


		/**
		 *
		 * Report - SERP Keywords Ranks Changes
		 */
		// Saved as Report & Send Email
		public function serp_rank_changes_report_html( $pms=array() ) {
			$debug = isset($_REQUEST['debug']) ? (bool) $_REQUEST['debug'] : false;

			$pms = array_replace_recursive(array(
				'device' 			=> '',
				'view_in_browser' 	=> '',
				'view_type' 		=> '',
				'log_data' 			=> array(),
				'date_add' 			=> null,
			), $pms);
			extract( $pms );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'html' 		=> '',
			);

			$html = array();

			$html[] = $this->serp_rank_changes_report_html_index( $pms );

			//DEBUG
			if ( $debug ) {
				$html = implode(PHP_EOL, $html);
				header('Content-Type: text/html; charset=utf-8');
				echo $html;
				die;
			}

			$html = implode(PHP_EOL, $html);

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
				'html' 		=> $html,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $ret;
		}

		private function serp_rank_changes_report_html_index( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'device' 			=> '',
				'view_in_browser' 	=> '',
				'view_type' 		=> '',
				'log_data' 			=> array(),
				'date_add' 			=> null,
			), $pms);
			extract( $pms );

			//:: some inits
			$log_data = $this->serp_rank_changes_set_log_data( $log_data );

			$last_date = isset($log_data['last_date']) ? $log_data['last_date'] : null;

			$lang = array(
				'no_products'       => __('no keywords ranks changes found!', $this->localizationName),
				'total_products' 	=> __('Total keyword changes :', $this->localizationName),
				'your_website_text' => __('Your website :', $this->localizationName),
			);

			if ( ! empty($last_date) ) {
				$lang['no_products'] = sprintf(
					__('no keywords ranks changes found since %s!', $this->localizationName),
					$this->the_plugin->serp_last_check_date( $last_date )
				);
				$lang['total_products'] = sprintf(
					__('Total keyword changes since %s:', $this->localizationName),
					$this->the_plugin->serp_last_check_date( $last_date )
				);
			}

			$parts = array(
				'header' 			=> file_get_contents( $this->module_folder_path . 'reports/serp_rank_changes/header.html' ),
				'content' 			=> file_get_contents( $this->module_folder_path . 'reports/serp_rank_changes/content.html' ),
				'content_main' 		=> file_get_contents( $this->module_folder_path . 'reports/serp_rank_changes/_main_section' . ( $device ) . '.html' ),
			);

			if ( $view_type == 'email' ) {
				$html = file_get_contents( $this->module_folder_path . 'reports/serp_rank_changes/index.html' );
				$html = str_replace("{{__header__}}", $parts['header'], $html);
				$html = str_replace("{{__content__}}", $parts['content'], $html);
			}
			//else if ( $view_type == 'view_log' ) {
			else {
				$html = $parts['header'] . "\n" . $parts['content'];
			}

			$html = str_replace("{{subtitle}}", __('SERP Keywords Ranks Changes', $this->localizationName), $html);

			//:: main section
			$has_prods = false;
			if ( isset($log_data['status'], $log_data['rows'])
				&& ( 'valid' == $log_data['status'] )
				&& !empty($log_data['rows'])
			) {
				$has_prods = true;
				$html = str_replace("{{__main_section__}}", $parts['content_main'], $html);
			} else {
				$html = str_replace("{{__main_section__}}", "<tr><td style='text-align: center;'>{$lang['no_products']}</td></tr>", $html);
			}

			if ( $has_prods ) {
				$pms_ = $pms;
				$pms_['log_data'] = $log_data;
				$opMainSection = $this->serp_rank_changes_report_html_main( $pms_ );

				$html = str_replace("{{total_changes_text}}", $lang['total_products'], $html);
				$html = str_replace("{{total_changes_nb}}", sprintf( __('%s', $this->localizationName), $log_data['total_changes'] ), $html);

				$html = str_replace("{{your_website_text}}", $lang['your_website_text'], $html);
				$html = str_replace("{{your_website_url}}", sprintf( __('%s', $this->localizationName), get_site_url() ), $html);

				$html = str_replace("{{main_body}}", $opMainSection['main_body'], $html);
			}

			//:: header & general
			$date_add = $this->the_plugin->serp_last_check_date( strtotime( $date_add ) );
			$title = sprintf( __('PSP Report - %s', $this->localizationName), $date_add );

			$html = str_replace("{{title}}", $title, $html);
			$html = str_replace("{{images_base_url_gen}}", $this->module_folder . 'reports/', $html);
			$html = str_replace("{{images_base_url}}", $this->module_folder . 'reports/serp_rank_changes/', $html);

			//:: footer
			$html = str_replace("{{section_notice}}", __('<span>It contains all keywords rank changes from the time of the last report.</span>', $this->localizationName), $html);
			$html = str_replace("{{aateam_notice}}", __('© AA-Team, 2016 <br />You are receiving this email because<br /> you\'re an awesome customer of AA-Team.', $this->localizationName), $html);

			return $html;
		}

		private function serp_rank_changes_report_html_main( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'device' 			=> '',
				'view_in_browser' 	=> '',
				'view_type' 		=> '',
				'log_data' 			=> array(),
				'date_add' 			=> null,
			), $pms);
			extract( $pms );
			extract( $this->get_report_styles( array(
				'report_type' 	=> 'serp_rank_changes',
			)) );

			//:: some inits
			//$log_data = $this->serp_rank_changes_set_log_data( $log_data );

			$column_last_check_show = true;
			if ( $device == '_email' ) {
				$column_last_check_show = false;
			}

			$colors = $this->the_plugin->serp_competitor_colors( 'all' );

			$rows = isset($log_data['rows']) ? $log_data['rows'] : array();
			$keywords = isset($log_data['keywords']) ? $log_data['keywords'] : array();
			$engine_ws = isset($log_data['engine_ws']) ? $log_data['engine_ws'] : array();

			$limit = $device == '_email' ? 5 : 0;

			// loop through rows
			$html = array();
			$cc = 0;

			// engines foreach
			foreach ( $rows as $search_engine => $info ) {

				if( $limit > 0 && ( $cc >= $limit ) ){
					break 1;
				}

				//:: vertical space
				if ( $cc ) {
					$html[] = trim('
							<table style="width: 100%; height: 30px;">
								<tbody>
									<tr><td></td></tr>
								</tbody>
							</table>
					');
				}

				$nb_cols = (int) ( count($engine_ws["$search_engine"]) + 2 ); // add 2 columns: keyword & last check
				$col_width = number_format( ( $column_last_check_show ? 73 : 84 ) / ($nb_cols - 2), 2 );
				if ( 1 == count($engine_ws["$search_engine"]) ) {
					$col_width = 30;
				}

				//:: start per each engine main table
				$html[] = '<table ' . $css_maintable . '>';
				$html[] = 	'<thead>';
				$html[] = 		'<tr>';
				$html[] = 			'<th colspan="' . $nb_cols . '" ' . $css_maintitle . '>';
				$html[] = 				strtoupper( $search_engine );
				$html[] = 			'</th>';
				$html[] = 		'</tr>';

				$html[] = 		'<tr>';

				// other columns
				//					<th>
				$html[] = 			$this->replace_report_style( "<th $css_headcols_style>", array(
					'width' => '16%'
				));
				$html[] = 				__('Keyword', $this->localizationName);
				$html[] = 			'</th>';

				if ( $column_last_check_show ) {
				//					<th>
				$html[] = 			$this->replace_report_style( "<th $css_headcols_style>", array(
					'width' => '11%'
				));
				$html[] = 				__('Last check', $this->localizationName);
				$html[] = 			'</th>';
				}

				// websites as columns
				$ii = 0;
				foreach ( $engine_ws["$search_engine"] as $id_website => $website ) {
					$ws_color = $colors["$ii"];

					// 				<th>
					$html[] = 		$this->replace_report_style( "<th $css_headcols_style>", array(
						'width' => $col_width.'%',
						'align' => 'center',
						//'bgcolor' => $ws_color,
						'color' => $ws_color,
					));
					$html[] = 			$website;
					$html[] = 		'</th>';

					$ii++;
				}
				// end websites as columns

				$html[] = 		'</tr>';

				$html[] = 	'</thead>';
				$html[] = 	'<tbody>';

				// keywords foreach
				foreach ( $info as $id_keyword => $info2 ) {

					if( $limit > 0 && ( $cc >= $limit ) ){
						break 2;
					}

					$last_check_data = '';
					if ( isset($keywords["$id_keyword"]) ) {
						$last_check_data = strtotime( $keywords["$id_keyword"]['last_check_data'] );
						$last_check_data = $this->the_plugin->serp_last_check_date( $last_check_data );
					}

					$last_check_status = isset($keywords["$id_keyword"]) ? $keywords["$id_keyword"]['last_check_status'] : '';

					$html[] = 	'<tr>';
					//				<td>
					$html[] = 		$this->replace_report_style( "<td $css_headcols_style>", array());
					$html[] = 			isset($keywords["$id_keyword"]) ? $keywords["$id_keyword"]['text'] : '--unknown';
					$html[] = 		'</td>';

					if ( $column_last_check_show ) {
					//				<td>
					$html[] = 		$this->replace_report_style( "<td $css_headcols_style>", array());
					$html[] = 			sprintf( $css_last_check, $last_check_status, $last_check_data );
					$html[] = 		'</td>';
					}

					// websites foreach
					$ii = 0;
					foreach ( $engine_ws["$search_engine"] as $id_website => $website ) {
						$ws_color = $colors["$ii"];

						$rank_column = '';
						if ( isset($info2["$id_website"]) ) {
							$rank_column = $this->the_plugin->serp_build_column_rank( array_replace_recursive($info2["$id_website"], array(
								'_display' 			=> 'email',
								'_exclude_nbpages' 	=> true,
								'position_prev' 	=> $info2["$id_website"]['position_last_report'],
							)));
						}

						//			<td>
						$html[] = 	$this->replace_report_style( "<td $css_headcols_style>", array(
							//'bgcolor' => $ws_color,
							'color' => $ws_color,
							'align' => 'center',
						));
						$html[] = 		$rank_column;
						$html[] = 	'</td>';

						$ii++;

					} // end websites foreach

					$html[] = 	'</tr>';

					$cc++;

				} // end keywords foreach

				$html[] = 	'</tbody>';
				$html[] = '</table>';
				//:: end per each engine main table

			} // end engines foreach

			if( $limit ){
				// link to display all details in browser (in email we don't always send all details)
				$html[] = trim('
						<table style="width: 100%;">
							<tbody>
								<tr>
									<td style="text-align: right;" align="right">
										<a href="' . ( $view_in_browser ) . '" style="background: #bdc3c7; padding: 2px 10px 2px 10px; color: #fff; text-decoration: none; border-radius: 4px;">' .
											__('View all statistics on Web Browser', $this->localizationName) .
										'</a>
									</td>
								</tr>
							</tbody>
						</table>
				');
			}

			$html = implode(PHP_EOL, $html);

			$ret = array(
				'main_body' 	=> $html,
			);
 
			return $ret;
		}

		public function serp_rank_changes_report_save( $pms=array() ) {
			$debug = isset($_REQUEST['debug']) ? (bool) $_REQUEST['debug'] : false;

			$pms = array_replace_recursive(array(
				'last_date' 	=> 0,
				'date_add' 		=> 0,
			), $pms);
			extract( $pms );

			$do_update = true;
			//:: DEBUG
			//$last_date = strtotime('-1 year', time());
			//$do_update = false;
			//:: end DEBUG

			$include_competitors = is_array($this->settings_report) && isset($this->settings_report['include_competitors_serp_rank_changes'])
				? $this->settings_report['include_competitors_serp_rank_changes'] : 'yes';

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'Report - SERP Keywords Ranks Changes / Save : unknown error!',
				'last_date' => $last_date,
				'status_code' => -1, // to identify different error types
				'include_competitors' => $include_competitors,
				'items' 	=> array(),
				//'html' 		=> '',
			);

			$msg_title = __('Report - SERP Keywords Ranks Changes / Save : ', 'psp');

			//$html = array();

			//:: find rows that had position changes
			$table_kw = $this->serp_tables['keyword'];
			$table_ws = $this->serp_tables['website'];
			$table_mr = $this->serp_tables['mainrank'];

			$where_cond = "and mr.position != mr.position_last_report and mr.last_check_data > from_unixtime( '$last_date' )";
			if ( 'no' == $include_competitors ) {
				$where_cond .= " and ws.is_competitor='N'";
			}
			$sql = "select kw.keyword, ws.website, ws.is_competitor, mr.* from $table_mr as mr left join $table_kw as kw on mr.id_keyword = kw.id left join $table_ws as ws on mr.id_website = ws.id where 1=1 $where_cond order by mr.search_engine asc, mr.id_keyword asc, mr.id_website asc;";
			//var_dump('<pre>', $sql , '</pre>');
			$res = $this->db->get_results( $sql, ARRAY_A );
			//var_dump('<pre>', $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( false === $res ) {
				$msg = $msg_title . 'get rows db error!';
				//$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
					'status_code' => 1,
				));
				return $ret;
			}
			if ( empty($res) ) {
				$msg = $msg_title . 'no rows found!';
				//$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
					'status_code' => 2,
				));
				return $ret;
			}

			$mrids = array();
			$items = array();

			// main foreach
			foreach ($res as $key => $val) {

				$mrids[] = $val['id'];

				$items["$key"] = $val;

				foreach ( array('position_prev', 'position_worst', 'position_best', 'top100', 'created', 'publish', 'last_check_msg') as $tounset ) {
					if ( isset($items["$key"]["$tounset"]) ) {
						unset( $items["$key"]["$tounset"] );
					}
				}

			} // end main foreach

			//var_dump('<pre>', $mrids, $items , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: return info
			$ret = array_replace_recursive($ret, array(
				'items' => $items
			));

			//DEBUG
			if ( $debug ) {
				$msg = $msg_title . 'ok!';
				$ret = array_replace_recursive($ret, array(
					'status' 	=> 'valid',
					'msg' 		=> $msg,
					'status_code' => 0, //success
					//'html' 		=> $html,
				));

				$this->serp_rank_changes_report_html(array(
					'device' 			=> '',
					'view_in_browser' 	=> '',
					'view_type' 		=> 'view_log', // email| view_log
					'log_data' 			=> $ret,
					'date_add' 			=> time(),
				));
				echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			}

			//:: update position_last_report with position value for all found rows
			if ( $do_update ) {
				$kwlist_ = array_values( $mrids );
				$kwlist_ = is_array($kwlist_) && ! empty($kwlist_) ? $kwlist_ : array(0);
				$kwlist_ = $this->db->_escape($kwlist_); //esc_sql
				$kwlist_ = array_map( array($this->the_plugin, 'prepareForInList'), $kwlist_);
				$kwlist_ = implode(',', $kwlist_);

				$where_cond = "and mr.id in (" . $kwlist_ . ")";
				$sql = "UPDATE $table_mr as mr SET 
					mr.position_last_report = mr.position
				WHERE 1=1 $where_cond;";
				$res = $this->db->query( $sql );

				if ( false === $res ) {
					$msg = $msg_title . 'update rows db error!';

					$ret = array_replace_recursive($ret, array(
						'msg' 		=> $msg,
						'status_code' => 3,
					));
					return $ret;
				}
			}

			//$html = implode(PHP_EOL, $html);

			$msg = $msg_title . 'ok!';
			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> $msg,
				'status_code' => 0, //success
				//'html' 		=> $html,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $ret;
		}

		private function serp_rank_changes_report_format_data( $items=array() ) {

			$ret = array(
				//'mrids' 		=> array(),
				'total_changes' => 0,
				'rows' 			=> array(), // list of all changes
				'engines' 		=> array(), // list of engines
				'keywords' 		=> array(), // list of keywords
				'websites' 		=> array(), // list of websites
				'engine_ws' 	=> array(), // list of per engine websites - to identify competitors easier
				'your_website' 	=> array(), // your own website per engine
			);

			if ( empty($items) ) {
				return $ret;
			}

			// main foreach
			foreach ($items as $key => $val) {

				//$ret['mrids'][] = $val['id'];

				$is_competitor = $val['is_competitor'];

				$search_engine = $val['search_engine'];
				$id_keyword = $val['id_keyword'];
				$id_website = $val['id_website'];

				if ( ! isset($ret['rows']["$search_engine"]) ) {
					$ret['rows']["$search_engine"] = array();
				}
				if ( ! isset($ret['rows']["$search_engine"]["$id_keyword"]) ) {
					$ret['rows']["$search_engine"]["$id_keyword"] = array();
				}

				// per keyword website
				$kwsite = array(
					//'keyword' 				=> $val['keyword'],
					//'website' 				=> $val['website'],
					//'website_nbpages' 		=> $val['website_nbpages'],
					'position' 				=> $val['position'],
					'position_last_report' 	=> $val['position_last_report'],
					'last_check_data' 		=> $val['last_check_data'],
					'last_check_status' 	=> $val['last_check_status'],
				);

				// make sure your website is alwasy first column
				if ( ( 'N' == $is_competitor ) && ! empty($ret['rows']["$search_engine"]["$id_keyword"]) ) {
					$ret['rows']["$search_engine"]["$id_keyword"] = array(
						"$id_website" => $kwsite,
					) + $ret['rows']["$search_engine"]["$id_keyword"];
				}
				// your website is already first column
				else {
					$ret['rows']["$search_engine"]["$id_keyword"]["$id_website"] = $kwsite;
				}

				if ( ! in_array($search_engine, $ret['engines']) ) {
					$ret['engines'][] = $search_engine;
				}

				$ret['websites']["$id_website"] = $val['website'];
				$ret['keywords']["$id_keyword"] = array(
					'text' 				=> $val['keyword'],
					'last_check_data' 	=> $val['last_check_data'],
					'last_check_status' => $val['last_check_status'],
				);

				if ( ! isset($ret['engine_ws']["$search_engine"]) ) {
					$ret['engine_ws']["$search_engine"] = array();
				}

				// make sure your website is alwasy first column
				if ( ( 'N' == $is_competitor ) && ! empty($ret['engine_ws']["$search_engine"]) ) {
					$ret['engine_ws']["$search_engine"] = array(
						"$id_website" => $val['website'],
					) + $ret['engine_ws']["$search_engine"];
				}
				// your website is already first column
				else {
					$ret['engine_ws']["$search_engine"]["$id_website"] = $val['website'];
				}

				if ( 'N' == $is_competitor ) {
					if ( ! isset($ret['your_website']["$search_engine"]) ) {
						$ret['your_website']["$search_engine"] = array();
					}
					$ret['your_website']["$search_engine"]["$id_website"] = $val['website'];
				}

			} // end main foreach

			$ret['total_changes'] = count( $ret['keywords'] );
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			return $ret;
		}

		private function serp_rank_changes_set_log_data( $log_data=array() ) {
			if ( isset($log_data['items']) && is_array($log_data['items']) && ! empty($log_data['items']) ) {
				$log_data = array_replace_recursive(
					$log_data,
					$this->serp_rank_changes_report_format_data( $log_data['items'] )
				);
				unset( $log_data['items'] );
			}
			return $log_data;
		}


		/**
		 *
		 * Report - SERP Your Website Stats
		 */
		// get ranks (all pages) for date closest to date_max
		private function ranks_closest_todate_get( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'date_max' 		=> null,
				'include_competitors' => 'yes',
				'engine' 		=> 'all',
				'id_website' 	=> 'all',
			), $pms);
			extract( $pms );

			//:: DEBUG
			//$date_max = strtotime('-1 week', time());
			//:: end DEBUG

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'Report - SERP Your Website Stats / Get Ranks Closest To Date: unknown error!',
				'date_max' 	=> $date_max,
				'status_code' => -1, // to identify different error types
				'include_competitors' => $include_competitors,
				'items' 	=> array(),
			);

			$msg_title = __('Report - SERP Your Website Stats / Get Ranks Closest To Date: ', 'psp');

			$html = array();

			$table_kw = $this->serp_tables['keyword'];
			$table_ws = $this->serp_tables['website'];
			$table_mr = $this->serp_tables['mainrank'];
			$table_pr = $this->serp_tables['pagerank'];

			$where_cond = '';
			if ( 'no' == $include_competitors ) {
				$where_cond .= " and ws.is_competitor = 'N'";
			}
			if ( 'all' !== $engine ) {
				$where_cond .= " and mr.search_engine = '$engine'";
			}
			if ( 'all' !== $id_website ) {
				$id_website = (int) $id_website;
				$where_cond .= " and mr.id_website = '$id_website'";
			}
			$where_cond = trim( $where_cond );

			$sql = trim("
select
	pr.id_mainrank, pr.page_link, pr.rank_date, pr.position,
	mr.search_engine, mr.id_keyword, mr.id_website, kw.keyword, ws.website, ws.is_competitor,
	date( pr.rank_date ) as last_check_data, mr.last_check_status as last_check_status
	from $table_pr as pr
	left join $table_mr as mr on pr.id_mainrank = mr.id
	left join $table_kw as kw on mr.id_keyword = kw.id
	left join $table_ws as ws on mr.id_website = ws.id
	where 1=1
	$where_cond
	and date( pr.rank_date ) = (
		select max( date( pr2.rank_date ) ) from wp_psp_serprank_pagerank as pr2 where 1=1 and pr2.id_mainrank = pr.id_mainrank and date( pr2.rank_date ) <= date( from_unixtime( %s ) ) limit 1
	)
	order by mr.search_engine asc, ws.website asc, kw.keyword asc
;
			");
			//order by pr.id_mainrank asc, pr.id asc
			//order by mr.search_engine asc, ws.website asc, kw.keyword asc
			//order by mr.search_engine asc, ws.website asc, mr.position asc, pr.position asc

			$sql = $this->db->prepare( $sql, $date_max );
			//var_dump('<pre>', $sql , '</pre>');
			$res = $this->db->get_results( $sql, ARRAY_A );
			//var_dump('<pre>', $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( false === $res ) {
				$msg = $msg_title . 'get rows db error!';
				//$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
					'status_code' => 1,
				));
				return $ret;
			}
			if ( empty($res) ) {
				$msg = $msg_title . 'no rows found!';
				//$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
					'status_code' => 2,
				));
				return $ret;
			}

			$items = array();

			// main foreach
			foreach ($res as $key => $val) {

				$items["$key"] = $val;

				//foreach ( array() as $tounset ) {
				//	if ( isset($items["$key"]["$tounset"]) ) {
				//		unset( $items["$key"]["$tounset"] );
				//	}
				//}

			} // end main foreach

			//var_dump('<pre>', $items , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: return info
			$ret = array_replace_recursive($ret, array(
				'items' => $items
			));

			//$html = implode(PHP_EOL, $html);

			$msg = $msg_title . 'ok!';
			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> $msg,
				'status_code' => 0, //success
				//'html' 		=> $html,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			return $ret;
		}

		private function ranks_closest_todate_format( $items=array() ) {

			$ret = array(
				'rows' 			=> array(), // list of all changes
				'engines' 		=> array(), // list of engines
				'keywords' 		=> array(), // list of keywords
				'websites' 		=> array(), // list of websites
				'engine_ws' 	=> array(), // list of per engine websites - to identify competitors easier
				'your_website' 	=> array(), // your own website per engine

				// number of keywords in different tops (top1, top3, top5, top10...) per engine websites
				// includes also the score
				'calc' 			=> array(),
			);

			if ( empty($items) ) {
				return $ret;
			}

			// main foreach
			foreach ($items as $key => $val) {

				$is_competitor = $val['is_competitor'];

				$search_engine = $val['search_engine'];
				$id_keyword = $val['id_keyword'];
				$id_website = $val['id_website'];
				$page_link = $val['page_link'];

				if ( ! isset($ret['rows']["$search_engine"]) ) {
					$ret['rows']["$search_engine"] = array();
				}
				if ( ! isset($ret['rows']["$search_engine"]["$id_keyword"]) ) {
					$ret['rows']["$search_engine"]["$id_keyword"] = array();
				}

				// pages
				if ( ! isset($pages["$search_engine"]["$id_keyword"]["$id_website"]) ) {
					$pages["$search_engine"]["$id_keyword"]["$id_website"] = array();
				}
				if ( '/' != $page_link ) {
					$pages["$search_engine"]["$id_keyword"]["$id_website"]["$page_link"] = $val['position'];
					continue 1;
				}

				// per keyword website
				$kwsite = array(
					'position' 				=> $val['position'],
					'pages' 				=> array(),
				);

				// make sure your website is alwasy first column
				if ( ( 'N' == $is_competitor ) && ! empty($ret['rows']["$search_engine"]["$id_keyword"]) ) {
					$ret['rows']["$search_engine"]["$id_keyword"] = array(
						"$id_website" => $kwsite,
					) + $ret['rows']["$search_engine"]["$id_keyword"];
				}
				// your website is already first column
				else {
					$ret['rows']["$search_engine"]["$id_keyword"]["$id_website"] = $kwsite;
				}

				if ( ! in_array($search_engine, $ret['engines']) ) {
					$ret['engines'][] = $search_engine;
				}

				$ret['websites']["$id_website"] = $val['website'];
				$ret['keywords']["$id_keyword"] = array(
					'text' 					=> $val['keyword'],
					'last_check_data' 		=> $val['last_check_data'],
					'last_check_status' 	=> $val['last_check_status'],
				);

				if ( ! isset($ret['engine_ws']["$search_engine"]) ) {
					$ret['engine_ws']["$search_engine"] = array();
				}
				// make sure your website is alwasy first column
				if ( ( 'N' == $is_competitor ) && ! empty($ret['engine_ws']["$search_engine"]) ) {
					$ret['engine_ws']["$search_engine"] = array(
						"$id_website" => $val['website'],
					) + $ret['engine_ws']["$search_engine"];
				}
				// your website is already first column
				else {
					$ret['engine_ws']["$search_engine"]["$id_website"] = $val['website'];
				}

				if ( 'N' == $is_competitor ) {
					if ( ! isset($ret['your_website']["$search_engine"]) ) {
						$ret['your_website']["$search_engine"] = array();
					}
					$ret['your_website']["$search_engine"]["$id_website"] = $val['website'];
				}

			} // end main foreach
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			// some calculations
			$tmp__ = array( 'kw' => array(), 'calc' => array() );
			foreach ($ret['rows'] as $search_engine => $val) { // engine foreach
				foreach ($val as $id_keyword => $val2) { // keyword foreach
					foreach ($val2 as $id_website => $val3) { // website foreach

						//:: add found pages
						if ( isset($pages["$search_engine"]["$id_keyword"]["$id_website"])
							&& ! empty($pages["$search_engine"]["$id_keyword"]["$id_website"])
						) {
							$val3['pages'] = $pages["$search_engine"]["$id_keyword"]["$id_website"];
							$ret['rows']["$search_engine"]["$id_keyword"]["$id_website"]['pages']
								= $pages["$search_engine"]["$id_keyword"]["$id_website"];
						}

						//:: init calculations array
						if ( ! isset($ret['calc']["$search_engine"]) ) {
							$ret['calc']["$search_engine"] = array();
						}
						if ( ! isset($ret['calc']["$search_engine"]["$id_website"]) ) {
							$ret['calc']["$search_engine"]["$id_website"] = array(
								'kw_total' 	=> 0,
								'score' 	=> 0,
								'percent' 	=> '0.0',
								'tops' 		=> array_fill_keys( $this->the_plugin->serp_stats_get_top_types(), 0 ),
								'tops_kw'	=> array_fill_keys( $this->the_plugin->serp_stats_get_top_types(), array() ),
							);
						}

						//:: to what top this keyword belongs (top1, top3, top5...)
						$whattop = $this->what_top_by_position( $val3['position'] );

						// we need to increase the nb of kw like this
						// top3 also includes kw from top1, top5 also includes top1 and top3 etc...
						foreach ( $this->the_plugin->serp_stats_get_top_types() as $toptype ) {

							if ( 999 == $whattop ) {
								break;
							}
							if ( $toptype < $whattop ) {
								continue 1;
							}

							$ret['calc']["$search_engine"]["$id_website"]['tops']["$toptype"]++;

							if ( ! in_array($id_keyword, $ret['calc']["$search_engine"]["$id_website"]['tops_kw']["$toptype"]) ) {
								$ret['calc']["$search_engine"]["$id_website"]['tops_kw']["$toptype"][] = $id_keyword;
							}
						}

						//:: score
						if ( ! empty($val3['pages']) ) {
							foreach ( $val3['pages'] as $page_link => $page_position ) {
								
								$ret['calc']["$search_engine"]["$id_website"]['score']
									+= $this->calc_page_points_by_position( $page_position );
							}
						}

						//:: total number of keywords
						$ret['calc']["$search_engine"]["$id_website"]['kw_total']++;

						// good for testing: 5, 37
						//if ( 5 == $id_website ) {
						//	$tmp__['kw']["$id_keyword"] = $ret['rows']["$search_engine"]["$id_keyword"]["$id_website"];
						//	$tmp__['calc'] = $ret['calc']["$search_engine"]["$id_website"];
						//}
					}
				}
			} // end engine foreach
			//var_dump('<pre>', $tmp__, $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: percent based on score
			foreach ($ret['calc'] as $search_engine => $val) { // engine foreach
				foreach ($val as $id_website => $val2) { // website foreach

					$percent = number_format( ($val2['score'] / $val2['kw_total']), 1 );
					if ( (float) $percent > 100.0 ) {
						$percent = "100.0";
					}
					$ret['calc']["$search_engine"]["$id_website"]['percent'] = $percent;
				}
			}
			//var_dump('<pre>', $ret['calc'] , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			return $ret;
		}

		private function ranks_closest_todate_compare( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'items_from' 		=> array(),
				'items_to' 			=> array(),
			), $pms);
			extract( $pms );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'items' 	=> array(),
			);

			if ( empty($items_to) || ! isset($items_to['calc']) || empty($items_to['calc']) ) {
				return $ret;
			}

			foreach ($items_to['calc'] as $search_engine => $val) { // engine foreach

				foreach ($val as $id_website => $val2) { // website foreach

					$items_to['calc']["$search_engine"]["$id_website"] = array_replace_recursive(
						$items_to['calc']["$search_engine"]["$id_website"],
						array(
							'_prev' 	=> array(),
							'_moved' 	=> array_fill_keys( array('up', 'down', 'same'), 0 ),
							'_moved_kw' => array_fill_keys( array('up', 'down', 'same'), array() ),
						)
					);

					$prev = array();

					//:: set previous info
					if (
						isset(
							$items_from['calc'],
							$items_from['calc']["$search_engine"],
							$items_from['calc']["$search_engine"]["$id_website"]
						)
					) {
						$prev = $items_from['calc']["$search_engine"]["$id_website"];
					}

					$items_to['calc']["$search_engine"]["$id_website"]['_prev'] = $prev;

				} // end website foreach

			} // end engine foreach

			foreach ($items_to['rows'] as $search_engine => $val) { // engine foreach
				foreach ($val as $id_keyword => $val2) { // keyword foreach
					foreach ($val2 as $id_website => $val3) { // website foreach

						$prev = array();

						//:: set previous info
						if (
							isset(
								$items_from['rows'],
								$items_from['rows']["$search_engine"],
								$items_from['rows']["$search_engine"]["$id_keyword"],
								$items_from['rows']["$search_engine"]["$id_keyword"]["$id_website"]
							)
						) {
							$prev = $items_from['rows']["$search_engine"]["$id_keyword"]["$id_website"];
						}

						$items_to['rows']["$search_engine"]["$id_keyword"]["$id_website"]['_prev'] = $prev;

						//:: keywords up | down | same
						if (
							isset(
								$items_to['calc'],
								$items_to['calc']["$search_engine"],
								$items_to['calc']["$search_engine"]["$id_website"]
							)
						) {
							$pos_to = $val3['position'];
							$pos_from = isset($prev['position']) ? $prev['position'] : -1;

							$rank_column = $this->the_plugin->serp_build_column_rank( array(
								'_return' 			=> 'array',
								'_display' 			=> 'email',
								'_exclude_nbpages' 	=> true,
								'position' 			=> $pos_to,
								'position_prev' 	=> $pos_from,
							));
							$movedhow = $rank_column['moved'];

							if ( ! empty($movedhow) ) {
								$items_to['calc']["$search_engine"]["$id_website"]['_moved']["$movedhow"]++;

								if ( ! in_array($id_keyword, $items_to['calc']["$search_engine"]["$id_website"]['_moved_kw']["$movedhow"]) ) {
									$items_to['calc']["$search_engine"]["$id_website"]['_moved_kw']["$movedhow"][] = $id_keyword;
								}
							}
						}

					} // end engine foreach
				} // end keyword foreach
			} // end website foreach

			//:: return info
			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
				'items' 	=> $items_to
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			return $ret;
		}


		private function serp_website_stats_make_report( $pms=array() ) {
			$pms = array_replace_recursive(array(
				//'engine' 		=> 'all',
				//'website_id'	=> 'all',
				'include_competitors' 	=> 'no',
				'date_from' 			=> '',
				'date_to' 				=> '',
			), $pms);
			extract( $pms );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'items'		=> array(),
			);

			$dates = array(
				'from' 	=> $date_from,
				'to' 	=> $date_to,
			);
			$ranks = array();

			foreach ( $dates as $data_key => $data_val ) {
				$opRank = $this->ranks_closest_todate_get( array(
					'date_max' 				=> $data_val,
					'include_competitors' 	=> $include_competitors,
				));
				$opRank2 = $this->ranks_closest_todate_format( $opRank['items'] );
				$ranks["$data_key"] = $opRank2;
				//var_dump('<pre>', $data_key, $opRank2['calc'] , '</pre>');
			} // end foreach

			// compare
			$opCompare = $this->ranks_closest_todate_compare( array(
				'items_from' 		=> $ranks['from'],
				'items_to'			=> $ranks['to'],
			));
			//var_dump('<pre>',$opCompare ,'</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: return info
			$ret = array_replace_recursive($ret, $opCompare);
			return $ret;
		}

		private function serp_website_stats_box_load( $pms=array() ) {
			$pms = array_replace_recursive(array(
				//'engine' 		=> 'all',
				//'website_id'	=> 'all',
				'include_competitors' 	=> 'no',
				'date_from' 			=> '', //strtotime('-1 week', time()),
				'date_to' 				=> '', //time(),
			), $pms);
			extract( $pms );
			extract( $this->get_report_styles( array(
				'report_type' 	=> 'serp_website_stats',
			)) );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'html' 		=> '',
			);

			$opCompare = $this->serp_website_stats_make_report( array(
				'include_competitors' 	=> $include_competitors,
				'date_from' 			=> $date_from,
				'date_to' 				=> $date_to,
			));

			if ( ( 'invalid' == $opCompare['status'] ) || empty($opCompare['items']) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> __('Report - SERP Your Website Stats - No data available!', 'psp'),
					'html' 		=> __('Report - SERP Your Website Stats - No data available!', 'psp'),
				));
				return $ret;
			}

			// get all engine all boxes
			$html = $this->serp_website_stats_box_get_all( array(
				'items' 	=> $opCompare['items'],
			));

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
				'html' 		=> $html,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			return $ret;
		}

		private function serp_website_stats_box_get_all( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'device' 	=> '',
				'items' 	=> array(),
			), $pms);
			extract( $pms );
			extract( $this->get_report_styles( array(
				'report_type' 	=> 'serp_website_stats',
			)) );

			if ( empty($items) ) {
				return '';
			}

			// loop through rows
			$html = array();
			$cc = 0;

			// vertical space between website boxes
			$vertical_space_ws = trim('
							<table style="width: 100%; height: 5px;">
								<tbody>
									<tr><td></td></tr>
								</tbody>
							</table>
			');

			foreach ($items['calc'] as $search_engine => $val) { // engine foreach

				// if email => only one engine (some display issues results if we add multiple tables)
				if ( ($device == '_email') && $cc ) {
					break 1;
				}

				//:: vertical space between engines
				if ( $cc ) {
					$html[] = trim('
							<table style="width: 100%; height: 50px;">
								<thead><tr><th></th></tr></thead>
								<tbody>
									<tr><td></td></tr>
								</tbody>
							</table>
					');
				}

				//:: engine header
				if (1) {
					$html[] = trim('
							<table ' . $css_engine_header . '>
								<thead><tr><th colspan="2"></th></tr></thead>
								<tbody>
									<tr><td style="width: 10px;" align="left"></td><td>' . strtoupper( $search_engine ) . '</td></tr>
								</tbody>
							</table>
					');
				}

				//:: website score
				$opGetScores = $this->serp_website_stats_box_get_scores( array(
					'action' 	=> 'score_bar', // score | score_bar
					'engine' 	=> $search_engine,
					'websites' 	=> $items['engine_ws']["$search_engine"],
					'items' 	=> $val,
				));
				$html[] = $vertical_space_ws;
				$html[] = $opGetScores['html'];

				//:: keywords rankings - summary
				$opGetStats = $this->serp_website_stats_box_get_stats( array(
					'action' 	=> array('moved_updown', 'tops'), // score | score_bar | moved_updown | tops
					'engine' 	=> $search_engine,
					'websites' 	=> $items['engine_ws']["$search_engine"],
					'items' 	=> $val,
				));
				$html[] = $vertical_space_ws;
				$html[] = $opGetStats['html'];

				//:: keywords rankings - details
				$opGetKeywords = $this->serp_website_stats_box_get_keywords( array(
					'engine' 	=> $search_engine,
					'websites' 	=> $items['engine_ws']["$search_engine"],
					'keywords' 	=> $items['keywords'],
					'items' 	=> $items['rows']["$search_engine"],
				));
				$html[] = $vertical_space_ws;
				$html[] = $opGetKeywords['html'];

				$cc++;

			} // end engine foreach

			$html = implode(PHP_EOL, $html);
			return $html;
		}

		private function serp_website_stats_box_get_stats( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'action' 		=> array(), // score | score_bar | moved_updown | tops
				'engine' 		=> '',
				'websites' 		=> array(),
				'items' 		=> array(),
			), $pms);
			extract( $pms );
			extract( $this->get_report_styles( array(
				'report_type' 	=> 'serp_website_stats',
			)) );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'html' 		=> '',
			);

			if ( empty($items) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> __('Report - SERP Your Website Stats / Main Stats - No data available!', 'psp'),
				));
				return $ret;
			}

			$colors = $this->the_plugin->serp_competitor_colors( 'all' );

			$toptypes = $this->the_plugin->serp_stats_get_top_types();

			$movedhow = array( 'up' => __('Moved Up', 'psp'), 'down' => __('Moved Down', 'psp') );
			$imgHtml = '<img class="" src="{{images_base_url_gen}}images/icon_{dir}.png" alt="{dir}" style="vertical-align: top;" />';
			//$movedhow = array(
			//	'up' 	=> sprintf( __('Moved %s', 'psp'), str_replace('{dir}', 'up', $imgHtml) ),
			//	'down' 	=> sprintf( __('Moved %s', 'psp'), str_replace('{dir}', 'down', $imgHtml) ),
			//);

			$is_score_column = in_array('score', $action) || in_array('score_bar', $action);

			// loop through rows
			$html = array();

			if (1) {
				$nb_cols = 1; $colspan_ = 1;
				if ( $is_score_column ) {
					$nb_cols += 1;
					$colspan_ = 2;
				}
				if ( in_array('moved_updown', $action) ) {
					$nb_cols += count($movedhow);
				}
				if ( in_array('tops', $action) ) {
					$nb_cols += count($toptypes) + 1; // consider +100 top also
				}

				if ( $is_score_column ) {
					$col_width = number_format( 70 / ($nb_cols - 2), 2 );
				}
				else {
					$col_width = number_format( 80 / ($nb_cols - 1), 2 );
				}

				//:: start main table
				$html[] = '<table ' . $css_maintable . '>';
				$html[] = 	'<thead>';
				$html[] = 		'<tr>';
				$html[] = 			'<th colspan="' . $nb_cols . '" ' . $css_maintitle . '>';
				//ucfirst( $engine ) . ' | ' . 
				$html[] = 				__('Keywords Rankings - Summary', 'psp');
				$html[] = 			'</th>';
				//$html[] = 			'<th colspan="' . $colspan_ . '" ' . $css_maintitle . '>';
				//$html[] = 				__('Website Rankings', 'psp');
				//$html[] = 			'</th>';
				//$html[] = 			'<th colspan="' . ($nb_cols - $colspan_) . '" ' . $css_maintitle . '>';
				//$html[] = 				__('Keywords Rankings - Summary', 'psp');
				//$html[] = 			'</th>';
				$html[] = 		'</tr>';

				$html[] = 		'<tr>';

				// other columns
				//					<th>
				$html[] = 			$this->replace_report_style( "<th $css_headcols_style>", array(
					'width' => '20%',
				));
				$html[] = 				__('Website', $this->localizationName);
				$html[] = 			'</th>';

				// score as column
				if ( $is_score_column ) {
					//				<th>
					$html[] = 		$this->replace_report_style( "<th $css_headcols_style>", array(
						'width' => '10%',
					));
					$html[] = 			__('Website Score', 'psp');
					$html[] = 		'</th>';
				}
				// end score as column

				// moved up or down as columns
				if ( in_array('moved_updown', $action) ) {
					$ii = 0;
					foreach ( $movedhow as $moved_key => $moved_title ) {

						//			<th>
						$html[] = 	$this->replace_report_style( "<th $css_headcols_style>", array(
							'width' => $col_width.'%',
							'align' => 'center',
						));
						$html[] = 		$moved_title;
						$html[] = 	'</th>';

						$ii++;
					}
				}
				// end moved up or down as columns

				// top types as columns
				if ( in_array('tops', $action) ) {
					$ii = 0;
					foreach ( $toptypes as $toptype ) {

						//			<th>
						$html[] = 	$this->replace_report_style( "<th $css_headcols_style>", array(
							'width' => $col_width.'%',
							'align' => 'center',
						));
						//$html[] = 		1 === $toptype ? __('First position') : sprintf( __('In Top %s', 'psp'), $toptype );
						$html[] = 		sprintf( __('In Top %s', 'psp'), $toptype );
						$html[] = 	'</th>';

						$ii++;
					}

					// outside biggest top
					//				<th>
					$html[] = 		$this->replace_report_style( "<th $css_headcols_style>", array(
						'width' => $col_width.'%',
						'align' => 'center',
					));
					$html[] = 			sprintf( __('%s', $this->localizationName), '+'.$toptype );
					$html[] = 		'</th>';
				}
				// end top types as columns

				$html[] = 		'</tr>';

				$html[] = 	'</thead>';
				$html[] = 	'<tbody>';
			}

			// websites as rows foreach
			$cc = 0;
			foreach ($items as $id_website => $val) {

				$ws_color = $colors["$cc"];

				$kw_total = isset($val['kw_total']) ? $val['kw_total'] : -1;
				$website = isset($websites["$id_website"]) ? $websites["$id_website"] : '--';

				if ( $is_score_column ) {
					$score_points = isset($val['score']) ? $val['score'] : -1;
					$score_percent = isset($val['percent']) ? (float) $val['percent'] : -1;

					$score_points_prev = isset($val['_prev'], $val['_prev']['score']) ? $val['_prev']['score'] : -1;
					$score_percent_prev = isset($val['_prev'], $val['_prev']['percent']) ? (float) $val['_prev']['percent'] : -1;					
				}

				if ( in_array('moved_updown', $action) ) {
					$moved = isset($val['_moved']) ? $val['_moved'] : array();
				}

				if ( in_array('tops', $action) ) {
					$tops = isset($val['tops']) ? $val['tops'] : array();
					$tops_prev = isset($val['_prev'], $val['_prev']['tops']) ? $val['_prev']['tops'] : array();
				}

				// start each website as row
				// 				<tr>
				//$html[] = 	str_replace( '{bgcolor}', $ws_color, '<tr style="background-color: {bgcolor};">' );
				$html[] = 	str_replace( '{color}', $ws_color, '<tr style="color: {color};">' );

				// other columns
				//					<td>
				$html[] = 			$this->replace_report_style( "<td $css_headcols_style>", array());
				$html[] = 				$website;
				$html[] = 			'</td>';

				// score as column
				if ( $is_score_column ) {
					$rank_column = '';
					if (1) {
						$rank_column = $this->the_plugin->serp_build_column_rank( array_replace_recursive( array(), array(
							'_direction' 		=> 'asc',
							'_compare' 			=> 'score',
							'_display' 			=> 'email',
							'_exclude_nbpages' 	=> true,
							'position' 			=> $score_percent,
							'position_prev' 	=> $score_percent_prev,
						)));

						if ( in_array('score_bar', $action) ) {
							$rank_column = $this->the_plugin->serp_build_score_html( (float) $score_percent, array(
								'_display' 		=> 'stats',
								'show_score' 	=> true,
								'css_style'		=> 'style="margin-right:4px"',
								'score_html' 	=> $rank_column,
							));
						}
					}

					//				<td>
					$html[] = 		$this->replace_report_style( "<td $css_headcols_style>", array());
					$html[] = 			$rank_column;
					$html[] = 		'</td>';
				}
				// end score as column

				// moved up or down as columns
				if ( in_array('moved_updown', $action) ) {
					foreach ( $movedhow as $moved_key => $moved_title ) {

						$rank_column = '';
						if (1) {
							$rank_column = $this->the_plugin->serp_build_column_rank( array_replace_recursive( array(), array(
								'_direction' 		=> 'asc',
								'_display' 			=> 'email',
								'_exclude_nbpages' 	=> true,
								'position' 			=> isset($moved["$moved_key"]) ? $moved["$moved_key"] : -1,
								'position_prev' 	=> -1,
							)));
						}

						//			<td>
						$html[] = 	$this->replace_report_style( "<td $css_headcols_style>", array(
							'align' 	=> 'center',
						));
						$html[] = 		$rank_column;
						$html[] = 	'</td>';

					}
				}
				// end moved up or down as columns

				// top types as columns
				if ( in_array('tops', $action) ) {
					foreach ( $toptypes as $toptype ) {

						$rank_column = '';
						if (1) {
							$rank_column = $this->the_plugin->serp_build_column_rank( array_replace_recursive( array(), array(
								'_direction' 		=> 'asc',
								'_display' 			=> 'email',
								'_exclude_nbpages' 	=> true,
								'position' 			=> isset($tops["$toptype"]) ? $tops["$toptype"] : -1,
								'position_prev' 	=> isset($tops_prev["$toptype"]) ? $tops_prev["$toptype"] : -1,
							)));
						}

						//			<td>
						$html[] = 	$this->replace_report_style( "<td $css_headcols_style>", array(
							'align' 	=> 'center',
						));
						$html[] = 		$rank_column;
						$html[] = 	'</td>';

					}

					// outside biggest top
					//				<td>
					$html[] = 		$this->replace_report_style( "<td $css_headcols_style>", array(
						'align' 	=> 'center',
					));
					$html[] = 			isset($tops["$toptype"]) ? (int) ( $kw_total - $tops["$toptype"] ) : $kw_total;
					$html[] = 		'</td>';
				}
				// end top types as columns

				$html[] = 		'</tr>';
				// end each website as row

				$cc++;
			}
			// end websites as rows foreach

			if (1) {
				$html[] = 	'</tbody>';
				$html[] = '</table>';
				//:: end main table
			}

			$html = implode(PHP_EOL, $html);
			$html = str_replace("{{images_base_url_gen}}", $this->module_folder . 'reports/', $html);

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
				'html' 		=> $html,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			return $ret;
		}

		private function serp_website_stats_box_get_scores( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'action' 		=> '', // score | score_bar
				'engine' 		=> '',
				'websites' 		=> array(),
				'items' 		=> array(),
			), $pms);
			extract( $pms );
			extract( $this->get_report_styles( array(
				'report_type' 	=> 'serp_website_stats',
			)) );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'html' 		=> '',
			);

			if ( empty($items) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> __('Report - SERP Your Website Stats / Websites Score - No data available!', 'psp'),
				));
				return $ret;
			}

			$colors = $this->the_plugin->serp_competitor_colors( 'all' );

			// loop through rows
			$html = array();

			if (1) {
				$nb_cols = count($items);
				$col_width = number_format( 100 / $nb_cols, 2 );

				//:: start main table
				$html[] = '<table ' . $css_maintable . '>';
				$html[] = 	'<thead>';
				//$html[] = 		'<tr>';
				//$html[] = 			'<th colspan="' . $nb_cols . '" ' . $css_maintitle . '>';
				//$html[] = 				__('Websites Rankings - Summary', 'psp');
				$html[] = 			'</th>';
				$html[] = 		'</tr>';

				//:: first row: websites as columns foreach
				$html[] = 		'<tr>';
				$cc = 0;
				foreach ($items as $id_website => $val) {

					$ws_color = $colors["$cc"];

					$website = isset($websites["$id_website"]) ? $websites["$id_website"] : '--';

					//				<th>
					$html[] = 		$this->replace_report_style( "<th $css_headcols_style>", array(
						'width' 	=> $col_width.'%',
						//'bgcolor' 	=> $ws_color,
						'color' => $ws_color,
					));
					$html[] = 			$website;
					$html[] = 		'</th>';

					$cc++;
				}
				$html[] = 		'</tr>';
				// end first row: websites as columns foreach

				$html[] = 	'</thead>';

				$html[] = 	'<tbody>';

				//:: second row: websites as columns foreach
				$html[] = 		'<tr>';
				$cc = 0;
				foreach ($items as $id_website => $val) {

					$ws_color = $colors["$cc"];

					if (1) {
						$score_points = isset($val['score']) ? $val['score'] : -1;
						$score_percent = isset($val['percent']) ? (float) $val['percent'] : -1;

						$score_points_prev = isset($val['_prev'], $val['_prev']['score']) ? $val['_prev']['score'] : -1;
						$score_percent_prev = isset($val['_prev'], $val['_prev']['percent']) ? (float) $val['_prev']['percent'] : -1;					
					}

					// score as column
					if (1) {
						$rank_column = '';
						if (1) {
							$rank_column = $this->the_plugin->serp_build_column_rank( array_replace_recursive( array(), array(
								'_direction' 		=> 'asc',
								'_compare' 			=> 'score',
								'_display' 			=> 'email',
								'_exclude_nbpages' 	=> true,
								'position' 			=> $score_percent,
								'position_prev' 	=> $score_percent_prev,
							)));

							if ( 'score_bar' == $action ) {
								$rank_column = $this->the_plugin->serp_build_score_html( (float) $score_percent, array(
									'_display' 		=> 'stats',
									'show_score' 	=> true,
									'css_style'		=> 'style="margin-right:4px"',
									'score_html' 	=> $rank_column,
								));
							}
						}

						//				<td>
						$html[] = 		$this->replace_report_style( "<td $css_headcols_style>", array(
							//'bgcolor' 	=> $ws_color,
							'color' => $ws_color,
						));
						$html[] = 			$rank_column;
						$html[] = 		'</td>';
					}
					// end score as column

					$cc++;
				}
				$html[] = 		'</tr>';
				// end second row: websites as columns foreach

				$html[] = 	'</tbody>';
				$html[] = '</table>';
				//:: end main table
			}

			$html = implode(PHP_EOL, $html);
			$html = str_replace("{{images_base_url_gen}}", $this->module_folder . 'reports/', $html);

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
				'html' 		=> $html,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			return $ret;
		}

		private function serp_website_stats_box_get_keywords( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'action' 		=> array(), // score | score_bar | moved_updown | tops
				'engine' 		=> '',
				'websites' 		=> array(),
				'keywords' 		=> array(),
				'items' 		=> array(),
				'last_check' 	=> true,
			), $pms);
			extract( $pms );
			extract( $this->get_report_styles( array(
				'report_type' 	=> 'serp_website_stats',
			)) );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'html' 		=> '',
			);

			if ( empty($items) ) {
				$ret = array_replace_recursive($ret, array(
					'msg' 		=> __('Report - SERP Your Website Stats / Keywords - No data available!', 'psp'),
				));
				return $ret;
			}

			$colors = $this->the_plugin->serp_competitor_colors( 'all' );

			$column_last_check_show = $last_check;

			// loop through rows
			$html = array();
			$cc = 0;

			if (1) {
				$nb_cols = (int) ( count($websites) + 2 );
				$col_width = number_format( 70 / ($nb_cols - 2), 2 );
				if ( 1 == count($websites) ) {
					$col_width = 20;
				}

				//:: start main table
				$html[] = '<table ' . $css_maintable . '>';
				$html[] = 	'<thead>';
				$html[] = 		'<tr>';
				$html[] = 			'<th colspan="' . $nb_cols . '" ' . $css_maintitle . '>';
				$html[] = 				__('Keywords Rankings ', 'psp');
				$html[] = 				sprintf( _n( '( %s item )', '( %s items )', count($items), 'psp' ) , count($items) );
				$html[] = 			'</th>';
				$html[] = 		'</tr>';

				$html[] = 		'<tr>';

				// other columns
				//					<th>
				$html[] = 			$this->replace_report_style( "<th $css_headcols_style>", array(
					'width' => '20%',
				));
				$html[] = 				__('Keyword', $this->localizationName);
				$html[] = 			'</th>';

				if ( $column_last_check_show ) {
				//					<th>
				$html[] = 			$this->replace_report_style( "<th $css_headcols_style>", array(
					'width' => '10%',
				));
				$html[] = 				__('Last check', $this->localizationName);
				$html[] = 			'</th>';
				}

				// websites as columns
				$ii = 0;
				foreach ( $websites as $id_website => $website ) {
					$ws_color = $colors["$ii"];

					//				<th>
					$html[] = 		$this->replace_report_style( "<th $css_headcols_style>", array(
						'width' => $col_width.'%',
						'align' => 'center',
						//'bgcolor' => $ws_color,
						'color' => $ws_color,
					));
					$html[] = 			$website;
					$html[] = 		'</th>';

					$ii++;
				}
				// end websites as columns

				$html[] = 		'</tr>';

				$html[] = 	'</thead>';
				$html[] = 	'<tbody>';

				// keywords foreach
				foreach ( $items as $id_keyword => $info2 ) {

					$last_check_data = '';
					if ( isset($keywords["$id_keyword"]) ) {
						$last_check_data = strtotime( $keywords["$id_keyword"]['last_check_data'] );
						$last_check_data = $this->the_plugin->serp_last_check_date( $last_check_data );
					}

					$last_check_status = isset($keywords["$id_keyword"]) ? $keywords["$id_keyword"]['last_check_status'] : '';

					$html[] = 	'<tr>';
					//				<td>
					$html[] = 		$this->replace_report_style( "<td $css_headcols_style>", array());
					$html[] = 			isset($keywords["$id_keyword"]) ? $keywords["$id_keyword"]['text'] : '--unknown';
					$html[] = 		'</td>';

					if ( $column_last_check_show ) {
					//				<td>
					$html[] = 		$this->replace_report_style( "<td $css_headcols_style>", array());
					$html[] = 			sprintf( $css_last_check, $last_check_status, $last_check_data );
					$html[] = 		'</td>';
					}

					// websites foreach
					$ii = 0;
					foreach ( $websites as $id_website => $website ) {
						$ws_color = $colors["$ii"];

						$rank_column = '';
						if ( isset($info2["$id_website"]) ) {

							$pos_current = isset($info2["$id_website"]['position']) ? $info2["$id_website"]['position'] : -1;

							$pos_prev = isset($info2["$id_website"]['_prev'], $info2["$id_website"]['_prev']['position'])
								? $info2["$id_website"]['_prev']['position'] : -1;

							$rank_column = $this->the_plugin->serp_build_column_rank( array_replace_recursive( array(), array(
								'_display' 			=> 'email',
								'_exclude_nbpages' 	=> true,
								'position' 			=> $pos_current,
								'position_prev' 	=> $pos_prev,
							)));
						}

						//			<td>
						$html[] = 	$this->replace_report_style( "<td $css_headcols_style>", array(
							//'bgcolor' => $ws_color,
							'color' => $ws_color,
							'align' => 'center',
						));
						$html[] = 		$rank_column;
						$html[] = 	'</td>';

						$ii++;

					} // end websites foreach

					$html[] = 	'</tr>';

					$cc++;

				} // end keywords foreach

				$html[] = 	'</tbody>';
				$html[] = '</table>';
				//:: end main table
			}

			$html = implode(PHP_EOL, $html);
			$html = str_replace("{{images_base_url_gen}}", $this->module_folder . 'reports/', $html);

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
				'html' 		=> $html,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			return $ret;
		}

		// Saved as Report & Send Email
		public function serp_website_stats_report_html( $pms=array() ) {
			$debug = isset($_REQUEST['debug']) ? (bool) $_REQUEST['debug'] : false;

			$pms = array_replace_recursive(array(
				'device' 			=> '',
				'view_in_browser' 	=> '',
				'view_type' 		=> '',
				'log_data' 			=> array(),
				'date_add' 			=> null,
			), $pms);
			extract( $pms );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'html' 		=> '',
			);

			$html = array();

			$html[] = $this->serp_website_stats_report_html_index( $pms );

			//DEBUG
			if ( $debug ) {
				$html = implode(PHP_EOL, $html);
				header('Content-Type: text/html; charset=utf-8');
				echo $html;
				die;
			}

			$html = implode(PHP_EOL, $html);

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> 'ok!',
				'html' 		=> $html,
			));
			//var_dump('<pre>', $ret , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			return $ret;
		}

		private function serp_website_stats_report_html_index( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'device' 			=> '',
				'view_in_browser' 	=> '',
				'view_type' 		=> '',
				'log_data' 			=> array(),
				'date_add' 			=> null,
			), $pms);
			extract( $pms );

			//:: some inits
			$log_data = $this->serp_website_stats_set_log_data( $log_data );

			$date_from = isset($log_data['date_from']) ? $log_data['date_from'] : null;
			$date_to = isset($log_data['date_to']) ? $log_data['date_to'] : null;

			$lang = array(
				'no_products'       => __('no information found!', $this->localizationName),
				'your_website_text' => __('Your website :', $this->localizationName),
			);

			if ( ! empty($date_from) && ! empty($date_to) && ($date_from != $date_to) ) {
				$lang['report_interval'] = sprintf(
					__('Compare between %s and %s', $this->localizationName),
					$this->the_plugin->serp_last_check_date( $date_from ),
					$this->the_plugin->serp_last_check_date( $date_to )
				);
			}
			else if ( ! empty($date_from) ) {
				$lang['report_interval'] = sprintf(
					__('Report for %s', $this->localizationName),
					$this->the_plugin->serp_last_check_date( $date_from )
				);
			}
			else if ( ! empty($date_to) ) {
				$lang['report_interval'] = sprintf(
					__('Report for %s', $this->localizationName),
					$this->the_plugin->serp_last_check_date( $date_to )
				);
			}

			$parts = array(
				'header' 			=> file_get_contents( $this->module_folder_path . 'reports/serp_website_stats/header.html' ),
				'content' 			=> file_get_contents( $this->module_folder_path . 'reports/serp_website_stats/content.html' ),
				'content_main' 		=> file_get_contents( $this->module_folder_path . 'reports/serp_website_stats/_main_section' . ( $device ) . '.html' ),
			);

			if ( $view_type == 'email' ) {
				$html = file_get_contents( $this->module_folder_path . 'reports/serp_website_stats/index.html' );
				$html = str_replace("{{__header__}}", $parts['header'], $html);
				$html = str_replace("{{__content__}}", $parts['content'], $html);
			}
			//else if ( $view_type == 'view_log' ) {
			else {
				$html = $parts['header'] . "\n" . $parts['content'];
			}

			$html = str_replace("{{subtitle}}", __('SERP Your Website Stats', $this->localizationName), $html);

			//:: main section
			$has_prods = false;
			if ( isset($log_data['status'], $log_data['items'], $log_data['items']['rows'])
				&& ( 'valid' == $log_data['status'] )
				&& !empty($log_data['items'])
				&& !empty($log_data['items']['rows'])
			) {
				$has_prods = true;
				$html = str_replace("{{__main_section__}}", $parts['content_main'], $html);
			} else {
				$html = str_replace("{{__main_section__}}", "<tr><td style='text-align: center;'>{$lang['no_products']}</td></tr>", $html);
			}

			if ( $has_prods ) {
				$pms_ = $pms;
				$pms_['log_data'] = $log_data;
				$opMainSection = $this->serp_website_stats_report_html_main( $pms_ );

				$html = str_replace("{{report_interval}}", $lang['report_interval'], $html);

				$html = str_replace("{{your_website_text}}", $lang['your_website_text'], $html);
				$html = str_replace("{{your_website_url}}", sprintf( __('%s', $this->localizationName), get_site_url() ), $html);

				$html = str_replace("{{main_body}}", $opMainSection['main_body'], $html);
			}

			//:: header & general
			$date_add = $this->the_plugin->serp_last_check_date( strtotime( $date_add ) );
			$title = sprintf( __('PSP Report - %s', $this->localizationName), $date_add );
			$html = str_replace("{{title}}", $title, $html);
			$html = str_replace("{{images_base_url_gen}}", $this->module_folder . 'reports/', $html);
			$html = str_replace("{{images_base_url}}", $this->module_folder . 'reports/serp_website_stats/', $html);

			//:: footer
			$html = str_replace("{{section_notice}}", __('<span>It contains all changes from the time of the last report.</span>', $this->localizationName), $html);
			$html = str_replace("{{aateam_notice}}", __('© AA-Team, 2016 <br />You are receiving this email because<br /> you\'re an awesome customer of AA-Team.', $this->localizationName), $html);

			return $html;
		}

		private function serp_website_stats_report_html_main( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'device' 			=> '',
				'view_in_browser' 	=> '',
				'view_type' 		=> '',
				'log_data' 			=> array(),
				'date_add' 			=> null,
			), $pms);
			extract( $pms );
			extract( $this->get_report_styles( array(
				'report_type' 	=> 'serp_website_stats',
			)) );

			//:: some inits
			//$log_data = $this->serp_website_stats_set_log_data( $log_data );

			$column_last_check_show = true;
			if ( $device == '_email' ) {
				$column_last_check_show = false;
			}

			$limit = $device == '_email' ? 5 : 0;

			$html = array();

			// get all engine all boxes
			$html[] = $this->serp_website_stats_box_get_all( array(
				'device' 	=> $device,
				'items' 	=> $log_data['items'],
			));

			if( $limit ){
				// link to display all details in browser (in email we don't always send all details)
				$html[] = trim('
						<table style="width: 100%;">
							<tbody>
								<tr>
									<td style="text-align: right;" align="right">
										<a href="' . ( $view_in_browser ) . '" style="background: #bdc3c7; padding: 2px 10px 2px 10px; color: #fff; text-decoration: none; border-radius: 4px;">' .
											__('View all statistics on Web Browser', $this->localizationName) .
										'</a>
									</td>
								</tr>
							</tbody>
						</table>
				');
			}

			$html = implode(PHP_EOL, $html);

			$ret = array(
				'main_body' 	=> $html,
			);
 
			return $ret;
		}

		public function serp_website_stats_report_save( $pms=array() ) {
			$debug = isset($_REQUEST['debug']) ? (bool) $_REQUEST['debug'] : false;

			$pms = array_replace_recursive(array(
				'last_date' 	=> 0,
				'date_add' 		=> 0,
			), $pms);
			extract( $pms );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
			);

			$include_competitors = is_array($this->settings_report) && isset($this->settings_report['include_competitors_serp_website_stats'])
				? $this->settings_report['include_competitors_serp_website_stats'] : 'yes';

			$dates = array(
				'from' 	=> $last_date,
				'to' 	=> $date_add,
			);
			$ranks = array();

			foreach ( $dates as $data_key => $data_val ) {
				$opRank = $this->ranks_closest_todate_get( array(
					'date_max' 				=> $data_val,
					'include_competitors' 	=> $include_competitors,
				));
				$ranks["$data_key"] = $opRank;
				//var_dump('<pre>', $data_key, $opRank, '</pre>');
			} // end foreach

			$ret = array_replace_recursive(array(), $ranks, array(
				'status' 		=> 'valid',
				'msg'			=> 'You need to verify from & to array to retrieve the real status!',
				'date_from' 	=> $last_date,
				'date_to' 		=> $date_add,
				'include_competitors' => $include_competitors,
			));
			return $ret;
		}

		private function serp_website_stats_report_format_data( $items=array() ) {
			return $items; // just to maintain structure!
		}

		private function serp_website_stats_set_log_data( $log_data=array() ) {

			$dates = array(
				'from' 	=> isset($log_data['from']) ? $log_data['from'] : array(),
				'to' 	=> isset($log_data['to']) ? $log_data['to'] : array(),
			);
			$ranks = array();

			foreach ( $dates as $data_key => $data_val ) {

				$opRank2 = $this->ranks_closest_todate_format( $data_val['items'] );
				$ranks["$data_key"] = $opRank2;
				//var_dump('<pre>', $data_key, $opRank2['calc'] , '</pre>');
			} // end foreach

			// compare
			$opCompare = $this->ranks_closest_todate_compare( array(
				'items_from' 		=> $ranks['from'],
				'items_to'			=> $ranks['to'],
			));
			//var_dump('<pre>',$opCompare ,'</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: return info
			$ret = array(
				'date_from' 	=> isset($log_data['date_from']) ? $log_data['date_from'] : array(),
				'date_to' 		=> isset($log_data['date_to']) ? $log_data['date_to'] : array(),
			);
			$ret = array_replace_recursive($ret, $opCompare);
			return $ret;
		}

		// reports styles
		private function get_report_styles( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'report_type' 	=> '', // serp_rank_changes | serp_website_stats
			), $pms);
			extract( $pms );

			if ( 'serp_rank_changes' == $report_type ) {

				$css_last_check = '%s <br /> <font style="font-style: italic;">%s</font>';

				$css_maintable = 'style="width: 100%; border: none; border-collapse: collapse; margin: 0 auto; padding: 7px; font-size: 14px; background-color: #EEEEEE; color: #616161;" bgcolor="#EEEEEE" align="left" border="0" cellpadding="7" cellspacing="0"';

				$css_maintitle = 'style="background-color: #af3204; color: #F5F5F5; padding: 7px;" align="left"';

				$css_headcols_style = 'style="width: {width}; background-color: {bgcolor}; color: {color}; padding: 7px; word-wrap: break-word; word-break: break-all; text-align: {align};" align="{align}"';
			}
			else if ( 'serp_website_stats' == $report_type ) {

				$css_last_check = '%s <br /> <font style="font-style: italic;">%s</font>';

				$css_maintable = 'style="width: 100%; border: none; border-collapse: collapse; margin: 0 auto; padding: 7px; font-size: 14px; background-color: #EEEEEE; color: #616161;" bgcolor="#EEEEEE" align="left" border="0" cellpadding="7" cellspacing="0"';

				$css_maintitle = 'style="background-color: #EEEEEE; color: #616161; padding: 7px; font-weight: normal; text-decoration: underline; border-right: 0px solid #E0E0E0;" align="left"';

				$css_headcols_style = 'style="width: {width}; background-color: {bgcolor}; color: {color}; padding: 7px; word-wrap: break-word; word-break: break-all; font-weight: normal; border-right: 0px solid #9E9E9E; text-align: {align};" align="{align}"';

				//:: extra
				$css_engine_header = 'style="width: 100%; height: 40px; background-color: #af3204; color: #F5F5F5; font-weight: bold; font-size: 16px;" align="left"';
			}

			$ret = compact(
				'css_last_check',
				'css_maintable',
				'css_maintitle',
				'css_headcols_style'
			);

			if ( 'serp_rank_changes' == $report_type ) {
				$ret = array_replace_recursive($ret, array());
			}
			else if ( 'serp_website_stats' == $report_type ) {
				$ret = array_replace_recursive($ret, compact(
					'css_engine_header'
				));
			}
			return $ret;
		}

		private function replace_report_style( $css, $pms=array() ) {
			$pms = array_replace_recursive(array(
				'width' 	=> '',
				'bgcolor' 	=> '',
				'color' 	=> '',
				'align' 	=> 'left',
			), $pms);
			extract( $pms );

			foreach ( $pms as $key => $val ) {
				if ( '' === $val ) {
					if ( 'width' == $key ) {
						$css = str_replace( "width: {width};", '', $css );
					}
					else if ( 'bgcolor' == $key ) {
						$css = str_replace( "background-color: {bgcolor};", '', $css );
					}
					else if ( 'color' == $key ) {
						$css = str_replace( "color: {color};", '', $css );
					}
					else if ( 'align' == $key ) {
						$css = str_replace( "text-align: {align};", '', $css );
						$css = str_replace( 'align="{align}"', '', $css );
					}
				}
				$css = str_replace( '{'.$key.'}', $val, $css );
			}
			return $css;
		}


		/**
		 *
		 * Cronjobs
		 */
		// cycle to update keyword ranks
		public function oldcronjob_keyword_update_ranks__() {
			@ini_set('max_execution_time', 0);
			@set_time_limit(0); // infinte

			$this->cronjob_keyword_update_ranks_doit();

			// return for ajax
			die(json_encode( array(
				'status' => 'valid',
				'msg' => ''
			)));
		}

		public function cronjob_keyword_update_ranks( $pms, $return='die' ) {
			$ret = array('status' => 'failed');

			//$current_cron_status = $pms['status']; //'new'; //

			$stat = $this->cronjob_keyword_update_ranks_doit();

			$ret = array_merge($ret, $stat, array(
				'status'            => 'done',
			));
			var_dump('<pre>', $ret , '</pre>');
			return $ret;
		}

		public function cronjob_keyword_update_ranks_doit( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'kw_id' 	=> array(0),
			), $pms);
			extract($pms);

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> 'cronjob_keyword_update_ranks_doit: unknown!',
			);

			$limit_max = 3; // how many keywords to update (their rank) per cron request
			//$how_often = 'INTERVAL 1 HOUR'; //DEBUG
			$how_often = 'INTERVAL 3 WEEK'; //every 3 weeks we try to update keywords cyclic | INTERVAL 1 MONTH

			$table_kw = $this->serp_tables['keyword'];
			$table_mr = $this->serp_tables['mainrank'];

			$msg_title = __('cronjob_keyword_update_ranks_doit: ', 'psp');

			// select the first number of keywords to be updated
			$sql = "select mr.id_keyword from $table_mr as mr where 1=1 and date( mr.last_check_data ) <= DATE( DATE_SUB( NOW(), $how_often ) ) group by mr.id_keyword order by date( mr.last_check_data ) asc, mr.id_keyword asc, mr.id asc limit $limit_max;";
			//var_dump('<pre>', $sql , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			$res = $this->db->get_results( $sql, OBJECT_K );
			//var_dump('<pre>', $res , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( false === $res ) {
				$msg = $msg_title . 'db error!';
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
				));
				return $ret;
			}
			if ( empty($res) ) {
				$msg = $msg_title . 'no rows found!';
				$msg = $this->build_operation_message( $msg );

				$ret = array_replace_recursive($ret, array(
					'msg' 		=> $msg,
				));
				return $ret;
			}

			$opStat = $this->keyword_update_rank(array(
				'kw_id' 	=> array_keys( $res ),
			));
			//var_dump('<pre>',$opStat ,'</pre>');

			$ret = array_replace_recursive($ret, $opStat);
			return $ret;
		}
	}
}

function pspSERP_cron_keyword_update_ranks() {
	// Initialize the pspSERP class
	$pspSERP = new pspSERP();
	$pspSERP->oldcronjob_keyword_update_ranks__();
}

// Initialize the pspSERP class
//$pspSERP = new pspSERP();
$pspSERP = pspSERP::getInstance();