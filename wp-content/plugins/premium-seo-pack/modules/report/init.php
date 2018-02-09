<?php
/*
 * Define class pspReport
 * Make sure you skip down to the end of this file, as there are a few
 * lines of code that are very important.
 */
!defined('ABSPATH') and exit;
	  
if (class_exists('pspReport') != true) {
	class pspReport
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
		
		public $is_admin = false;
		
		public $alias = '';
		public $localizationName = '';
		
		static private $report_alias = '';
		static private $report_alias_act = '';
		
		static private $settings = array();
		
		//static private $sql_chunk_limit = 2000;
		static private $current_time = null;
		
		private $device = '';
		private $view_in_browser = '';
		
		private $log_ids = array();
		private $log_actions = array();

		private $objSERP = null;
		private $reportsList = array();


		/*
		 * Required __construct() function that initalizes the AA-Team Framework
		 */
		public function __construct()
		{
			global $psp;

			$this->the_plugin = $psp;

			$this->reportsList = $this->the_plugin->report_get_types();

			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/report/';
			$this->module_folder_path = $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/report/';
			//$this->module = $module; // gives warning undefined variable.
			
			$this->alias = $this->the_plugin->alias;
			$this->localizationName = $this->the_plugin->localizationName;
 
			$this->is_admin = $this->the_plugin->is_admin;
			
			self::$report_alias = $this->alias.'_report'; //$this->alias.'%s_report';
			self::$report_alias_act = $this->alias.'_report_act'; //$this->alias.'%s_report_act';
			
			$ss = get_option($this->alias . '_report', array());
			$ss = maybe_unserialize($ss);
			self::$settings = $ss !== false ? $ss : array();

			self::$current_time = time();
			
			$this->device = isset($_REQUEST['device']) ? '_' . $_REQUEST['device'] : '';
			
			$this->log_ids = array();
			$this->log_actions = array();
			foreach ($this->reportsList as $modid => $modinfo) {

				extract( $this->log_code_split($modid) );

				$this->log_ids["$log_id"] = array('title' => ucwords($log_id));
				$this->log_actions["$log_action"] = array('title' => str_replace('_', ' ', ucwords($log_action)));
			}
			//var_dump('<pre>', $this->log_ids, $this->log_actions , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if (is_admin()) {
				add_action('admin_menu', array( &$this, 'adminMenu' ));
			}

			// ajax helper
			add_action('wp_ajax_psp_report', array( &$this, 'ajax_request' ));
			add_action('wp_ajax_nopriv_psp_report', array( &$this, 'ajax_request' ));
			
			// ajax helper
			// ...see also /utils/action_admin_ajax.php

			//SERP module is active
			if ( $this->the_plugin->verify_module_status( 'serp' ) ) {
				require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . '/modules/serp/init.php' );
				$this->objSERP = pspSERP::getInstance(); //$pspSERP; //new pspSERP(); //pspSERP::getInstance()
			}
		}

		/**
		 * Singleton pattern
		 *
		 * @return pspReport Singleton instance
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
			add_submenu_page(
				$this->the_plugin->alias,
				$this->the_plugin->alias . " " . __('Report logs', $this->the_plugin->localizationName),
				__('Report logs'),
				'manage_options',
				$this->the_plugin->alias . "_report",
				array($this, 'display_index_page')
			);

			return $this;
		}

		public function display_index_page()
		{
			$this->printBaseInterface();
		}

		/*
		 * printBaseInterface, method
		 * --------------------------
		 *
		 * this will add the base DOM code for you options interface
		 */
		public function printBaseInterface( $module='report' ) {
			global $wpdb;
			
			$ss = self::$settings;

			$mod_vars = array();

			// Sync
			$mod_vars['mod_menu'] = 'monitoring|report';
			$mod_vars['mod_title'] = __('Report logs', $this->the_plugin->localizationName);

			extract($mod_vars);
			
			$module_data = $this->the_plugin->cfg['modules']["$module"];
			$module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . "modules/$module/";
?>
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.report.js" ></script>
		
		<div id="<?php echo psp()->alias?>" class="<?php echo $this->the_plugin->alias; ?> psp-report-log">
			
			<div class="<?php echo psp()->alias?>-content">
				
				<?php
				// show the top menu
				pspAdminMenu::getInstance()->make_active($mod_menu)->show_menu(); 
				?>
				
				<!-- Content -->
				<section class="psp-main">
					
					<?php
					echo psp()->print_section_header(
						$module_data["$module"]['menu']['title'],
						$module_data["$module"]['description'],
						$module_data["$module"]['help']['url']
					);
					?>
					
					<div class="panel panel-default psp-panel">
						<div class="panel-heading psp-panel-heading">
							<h2><?php echo $mod_title; ?></h2>
						</div>
						
						<div class="panel-body psp-panel-body">

							<div id="psp-report" class="psp-panel-content" data-module="<?php echo $module; ?>">

								<?php
								   $lang = array(
									   'no_products' 			=> __('No report logs available.', 'psp'),
									   'loading' 				=> __('Loading..', 'psp'),
									   'download_pdf_error' 	=> __('Error trying to generate the pdf file. No valid api key could be found. Please check if you\'ve reached your <a href="https://www.html2pdfrocket.com/#pricing" target="_blank">HTML 2 PDF Rocket</a> monthly credits limit.', 'psp'),
								   );
								?>
								<div id="psp-lang-translation" style="display: none;"><?php echo htmlentities(json_encode( $lang )); ?></div>

								<!-- Main loading box -->
								<div id="psp-main-loading">
									<div id="psp-loading-overlay"></div>
									<div id="psp-loading-box">
										<div class="psp-loading-text"><?php _e('Loading', $this->the_plugin->localizationName);?></div>
										<div class="psp-meter psp-animate" style="width:86%; margin: 34px 0px 0px 7%;"><span style="width:100%"></span></div>
									</div>
								</div>

								<?php
									//$op_stat = isset($_REQUEST['op_stat']) ? trim( (string) $_REQUEST['op_stat'] ) : '';
									$op_stat = isset($_SESSION['psp_report'], $_SESSION['psp_report']['op_stat'])
										? trim ( $_SESSION['psp_report']['op_stat'] ) : '';

									$html = array();
									if ( '' != $op_stat ) {
										$html[] = '<div class="psp-report-opstat" style="display: block;">';
									}

									if ( 'download_pdf_error' == $op_stat ) {
										$html[] = '<span class="psp-message psp-error">' . $lang['download_pdf_error'] . '</span>';
									}

									if ( '' != $op_stat ) {
										$html[] = '</div>';
									}
									echo implode(PHP_EOL, $html);
								?>

								<div class="psp-sync-filters">
									<?php
										$__pms = array(
											'log_id'		=> isset($_SESSION['psp_report']["log_id"])
												? $_SESSION['psp_report']["log_id"] : '',
											'log_action'	=> isset($_SESSION['psp_report']["log_action"])
												? $_SESSION['psp_report']["log_action"] : '',
										);
										if ( count($this->log_ids) > 1 ) {
											$html = array();
											$html[] = 	'<select name="psp-filter-log_id" class="psp-filter-log_id">';
											$html[] = 		'<option value="" disabled="disabled">';
											$html[] =			__('Log id', $this->the_plugin->localizationName);
											$html[] = 		'</option>';
											$html[] = 		'<option value="" >';
											$html[] =			__('Show All', $this->the_plugin->localizationName);
											$html[] = 		'</option>';
						
											foreach ( $this->log_ids as $id => $row ){
												$html[] = 	'<option ' . ( $id == $__pms['log_id'] ? 'selected' : '' ) . ' value="' . ( $id ) . '">';
												$html[] = 		( $row['title'] );
												$html[] = 	'</option>';
											}
						
											$html[] =	'</select>';
											echo implode(PHP_EOL, $html);
										}

										if ( count($this->log_actions) > 1 ) {
											$html = array();
											$html[] = 	'<select name="psp-filter-log_action" class="psp-filter-log_action">';
											$html[] = 		'<option value="" disabled="disabled">';
											$html[] =			__('Log action', $this->the_plugin->localizationName);
											$html[] = 		'</option>';
											$html[] = 		'<option value="" >';
											$html[] =			__('Show All', $this->the_plugin->localizationName);
											$html[] = 		'</option>';
						
											foreach ( $this->log_actions as $id => $row ){
												$html[] = 	'<option ' . ( $id == $__pms['log_action'] ? 'selected' : '' ) . ' value="' . ( $id ) . '">';
												$html[] = 		( $row['title'] );
												$html[] = 	'</option>';
											}
						
											$html[] =	'</select>';
											echo implode(PHP_EOL, $html);
										}
									?>
									<span>
										<?php _e('Total report logs', $this->the_plugin->localizationName);?>: <span class="count"></span>
									</span>
									<span class="right">
										<button class="load_rows"><?php _e('Reload report logs list', $this->the_plugin->localizationName);?></button>
									</span>
								</div>
								<div class="psp-sync-table <?php echo ( $module == 'report' ? 'report' : '' ); ?>">
								  <table cellspacing="0">
									<thead>
										<tr class="psp-sync-table-header">
											<th style="width:3%;"><?php _e('ID', $this->the_plugin->localizationName);?></th>
											<th style="width:10%;"><?php _e('Log Id', $this->the_plugin->localizationName);?></th>
											<th style="width:10%;"><?php _e('Log Action', $this->the_plugin->localizationName);?></th>
											<th style="width:43%;"><?php _e('Log Desc', $this->the_plugin->localizationName);?></th>
											<th style="width:14%;"><?php _e('Date Added', $this->the_plugin->localizationName);?></th>
											<th style="width:20%;"><?php _e('Action', $this->the_plugin->localizationName);?></th>
										</tr>
									</thead>
									<tbody>
									<?php
										//require_once( $this->module_folder_path . '_html.php');
									?>
									</tbody>
								  </table>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
<?php
		}


		/**
		 * General Report methods - build row listing interface & other utils
		 */
		private function get_rows( $pms=array() ) {
			global $wpdb;

			$pms = array_replace_recursive(array(
				'log_id' 		=> '',
				'log_action' 	=> '',
			), $pms);
			extract( $pms );
		   
			$table_name_report = $wpdb->prefix . "psp_report_log";
			$sql = "SELECT p.ID, p.log_id, p.log_action, p.desc, p.date_add FROM $table_name_report as p WHERE 1=1 %s ORDER BY p.ID DESC;";
			
			// dropdown filter fields
			$filter_where = '';
			$filter_fields = array('log_id', 'log_action');
			foreach ($filter_fields as $field) {
				$field_val = isset($pms["$field"]) && trim($pms["$field"]) != "" ? $pms["$field"] : '';
				if ( $field_val != '' ) {
					$filter_where .= " AND $field = '" . esc_sql($field_val) . "' ";
				}
			}
			$sql = sprintf( $sql, $filter_where );

			$res = $wpdb->get_results( $sql, OBJECT_K );
			
			if ( empty($res) ) return array();
			
			// build html table with products rows
			$default = array();
 
			$ret = array('status' => 'valid', 'html' => array(), 'nb' => 0);
			$nbprod = 0;
			foreach ($res as $id => $val) {
				
				$__p = $this->row_build(array_merge($default, array(
					'val' 	=> $val,
				)));
				$__p = array_merge($__p, array(
					'id'            => $id,
				));
				
				// product
				$ret['html'][] = $this->row_view_html($__p);
				
				$nbprod++;
			} // end products loop
			
			$ret = array_merge($ret, array(
				'nb'        => $nbprod,
			));
			
			return $ret;
		}

		private function row_build( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'val' 	=> (object) array(),
			), $pms);
			extract( $pms );

			$log_id = $val->log_id;
			$log_action = $val->log_action;
			$log_code = $this->log_code_build($log_id, $log_action);

			$desc = $val->desc;
			$desc = trim( $desc );
			if ( '' == $desc ) {
				if ( isset($this->reportsList["$log_code"]['desc']) ) {
					$desc = $this->reportsList["$log_code"]['desc'];
				}
			}
				
			$add_data = $val->date_add;
			$add_data = $this->the_plugin->last_update_date('true', strtotime($add_data), true);

			$module = '';
			//if ( $module == 'report' ) {
				$ret = compact('module', 'add_data', 'log_id', 'log_action', 'desc');
			//}
			return $ret;
		}

		private function row_view_html( $row=array() ) {
			//row is an array with the following keys: 'module', 'add_data', 'log_id', 'log_action', 'desc'
			$tr_css = '';
			
			//if ( $row['module'] == 'report' ) {
				$text_log_id = $this->log_nice_format( $row['log_id'] );
				$text_log_action = $this->log_nice_format( $row['log_action'] );
				$text_viewlog = __('View log', $this->the_plugin->localizationName);
				$text_pdf = __('Get PDF', $this->the_plugin->localizationName);
				$text_email = __('Send email', $this->the_plugin->localizationName);
			//}
				ob_start();
				function_exists('mpdf_pdfbutton') ? mpdf_pdfbutton() : '';
				$aa = ob_get_clean();
			
			//if ( $row['module'] == 'report' ) {
			$ret = '
					<tr class="psp-sync-table-row' . $tr_css . '" data-id=' . $row['id'] . ' data-log_id=' . $row['log_id'] . ' data-log_action=' . $row['log_action'] . '>
						<td><span>' . $row['id'] . '</span></td>
						<td>' . $text_log_id . '</td>
						<td>' . $text_log_action . '</td>
						<td>' . $row['desc'] . '</td>
						<td>' . $row['add_data'] . '</td>
						<td class="psp-sync-now">
							<button class="view_log">' . $text_viewlog . '</button>
							<button class="download_pdf">' . $text_pdf . '</button>
							<button class="send_email">' . $text_email . '</button>
						</td>
					</tr>
				';
			//}
			return $ret;
		}

		private function get_log_data( $id ) {
			global $wpdb;
			
			$table_name_report = $wpdb->prefix . "psp_report_log";
			$sql = "SELECT p.id, p.log_id, p.log_action, p.desc, p.log_data_type, p.log_data, p.source, p.date_add FROM $table_name_report as p WHERE 1=1 AND p.ID = '%s';";
			$sql = sprintf($sql, $id);
			$ret = $wpdb->get_row( $sql );
			if ( is_null($ret) || $ret === false ) {
				return array();
			}
			
			$ret = (array) $ret;
			
			// get report data - products
			$log_data = !empty($ret['log_data']) ? (array) maybe_unserialize($ret['log_data']) : array();
			$ret['log_data'] = (array) $log_data;

			return (array) $ret;
		}

		private function view_log( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'id' 		=> 0,
			), $pms);
			extract( $pms );

			// current report
			$row_data = (array) $this->get_log_data( $id );
			extract($row_data); // db row fields

			$log_id = $this->log_nice_format( $log_id );
			$log_action = $this->log_nice_format( $log_action );
			$date_add = $this->the_plugin->last_update_date('true', strtotime($date_add), true);

			$html = array();
			$html[] = '<div class="psp-report-log-lightbox">';
			$html[] =   '<div class="psp-download-in-progress-box">';
			$html[] =       '<h1>' . __('View log', $this->localizationName ) . '<a href="#" id="psp-close-btn"><i class="fa fa-times-circle" aria-hidden="true"></i></a></h1>';
			$html[] =       '<p class="psp-callout psp-callout-info">';
			$html[] =       sprintf( __('Log id: <strong>%s</strong> | Log action: <strong>%s</strong> | Date: <em>%s</em>', $this->localizationName ), $log_id, $log_action, $date_add );
			$html[] =       '</p>';

			$html[] = 		'<div class="psp-report-wrapper">';

			// current report content
			$log_code = $this->log_code_build($row_data['log_id'], $row_data['log_action']);
			$opStatus = $this->get_current_report__(array(
				'module' 	=> $log_code,
				'row_data' 	=> $row_data,
				'view_type' => 'view_log',
			));
			$html[] = $opStatus['html'];
							
			$html[] =   	'</div>';
			$html[] =   '</div>';
			$html[] = '</div>';

			return implode("\n", $html);
		}

		private function download_pdf( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'id' 		=> 0,
			), $pms);
			extract( $pms );

			// current report
			$row_data = (array) $this->get_log_data( $id );
			extract($row_data); // db row fields

			$log_id = $this->log_nice_format( $log_id );
			$log_action = $this->log_nice_format( $log_action );
			$date_add = $this->the_plugin->last_update_date('true', strtotime($date_add), true);

			$log_code = $this->log_code_build($row_data['log_id'], $row_data['log_action']);
			$log_name = $this->reportsList["$log_code"]['title'];

			$filename = "$log_name - $date_add.pdf";

			$page_url = admin_url( 'admin-ajax.php?action=psp_report_settings&subaction=view_in_browser&log_id=' . $id . '&is_pdf=1' );
			//var_dump('<pre>', $page_url , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$opStatus = $this->the_plugin->pdf_api_do_request_auto( array(
                'page_url' 		=> $page_url,
			));
			if ( 'invalid' == $opStatus['status'] ) {

				if ( ! isset($_SESSION['psp_report']) ) {
					$_SESSION['psp_report'] = array();
				}
				$_SESSION['psp_report']['op_stat'] = 'download_pdf_error';

				//wp_redirect( admin_url( 'admin.php?page=psp_report&op_stat=download_pdf_error' ) );
				wp_redirect( admin_url( 'admin.php?page=psp_report' ) );
				exit();
			}

			$this->the_plugin->print_download_file( array(
            	'content' 		=> $opStatus['file_content'],
                'filename' 		=> $filename,
			));
			die;
		}

		private function send_email( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'id' 		=> 0,
			), $pms);
			extract( $pms );

			// current report
			$row_data = (array) $this->get_log_data( $id );
			extract($row_data); // db row fields

			$log_id = $this->log_nice_format( $log_id );
			$log_action = $this->log_nice_format( $log_action );
			$date_add = $this->the_plugin->last_update_date('true', strtotime($date_add), true);

			$log_code = $this->log_code_build($row_data['log_id'], $row_data['log_action']);


			$this->device = '_email';

			$this->view_in_browser = admin_url( 'admin-ajax.php?action=psp_report_settings&subaction=view_in_browser&log_id=' . $id );
			$opStat = $this->report_send_mail(array(
				'module' 		=> $log_code,
				'row_id' 		=> $id,
				'content_data'	=> $this->get_current_report__(array(
					'module' 		=> $log_code,
					'row_data' 		=> $row_data,
					'view_type' 	=> 'email',
				)),
			));

			$ret = array(
				'status' 	=> $opStat['mailStat'] ? 'valid' : 'invalid',
				'msg' 		=> '',
			);
			return $ret;
		}

		private function save_current_report( $pms ) {
			global $wpdb;

			$pms = array_replace_recursive(array(
				'module' 			=> '',
                'log_data' 			=> array(),
                'date_add' 			=> null,
			), $pms);
			extract( $pms );

			extract( $this->log_code_split($module) );

			$desc = '';
			if ( isset($this->reportsList["$module"]['desc']) ) {
				$desc = $this->reportsList["$module"]['desc'];
			}

			$source = '';
			
			$table_name_report = $wpdb->prefix . "psp_report_log";
			if (1) {
				$log_data = maybe_serialize($log_data);
				$log_data_type = 'serialize';

				$fields = array(
					'log_id'            => $log_id,
					'log_action'        => $log_action,
					'desc'              => $desc,
					'log_data_type'     => $log_data_type,
					'log_data'          => $log_data,
					'source'            => $source,
					'date_add'          => date('Y-m-d h:i:s', $date_add),
				);

				$fields__ = $fields;
				unset( $fields__['desc'], $fields__['source'], $fields__['date_add'] );

				$fields_format = array();
				foreach ($fields__ as $fkey => $fval) {
					$fields_format[] = '%s';
				}

				$wpdb->insert( 
					$table_name_report, 
					$fields__, 
					$fields_format
				);
				$insert_id = $wpdb->insert_id;

				$ret = array_replace_recursive($fields, array(
					'id' 	=> $insert_id,
				));

				$log_data = !empty($log_data) ? (array) maybe_unserialize($log_data) : array();
				$ret['log_data'] = (array) $log_data;

				return $ret;
			}
		}

        private function build_current_report( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'module' 		=> '',
			), $pms);
			extract( $pms );

			extract( $this->log_code_split($module) );

            $now = self::$current_time;

            // report last date
			$report_last_date = get_option('psp_report_last_date', array());
			$last_date = isset($report_last_date["$module"], $report_last_date["$module"]['last_date'])
				? $report_last_date["$module"]['last_date'] : 0;

            $opStatus = $this->create_new_report__(array(
            	'module' 		=> $module,
                'last_date' 	=> $last_date,
                'date_add' 		=> $now,
            ));
            $log_data = $opStatus;
 
            // update report last date
            if ( ! isset($report_last_date["$module"]) ) {
            	$report_last_date["$module"] = array();
            }
            $report_last_date["$module"]['last_date'] = $now;
            update_option('psp_report_last_date', $report_last_date);
            
            // save report
            $opStatus = $this->save_current_report( array(
            	'module' 			=> $module,
                'log_data' 			=> $log_data,
                'date_add' 			=> $now,
            ));
            $ret = array_replace_recursive(array(), $opStatus);
            // return db row fields (from insert)
            return $ret;
        }

		private function report_send_mail( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'module' 		=> '',
				'row_id' 		=> 0,
				'content_data'	=> array(),
			), $pms);
			extract( $pms );

			extract( $this->log_code_split($module) );

			$unique_log_id = $row_id;
			$html = $content_data['html'];

			$this->view_in_browser = admin_url( 'admin-ajax.php?action=psp_report_settings&subaction=view_in_browser&log_id=' . $unique_log_id );

			// send email
			add_filter('wp_mail_content_type', array($this->the_plugin, 'set_content_type'));
			//add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

			$email_to = isset(self::$settings["email_to_{$log_action}"]) ? self::$settings["email_to_{$log_action}"] : '';
			if ( empty($email_to) ) {
				return array(
					'mailStat'          => false,
					'mailFields'        => array(),
				);
			}

			$subject_def = '';
			if ( isset($this->reportsList["$module"]['desc']) ) {
				$subject_def = $this->reportsList["$module"]['desc'];
			}

			$subject = isset(self::$settings["email_subject_{$log_action}"]) ? __(self::$settings["email_subject_{$log_action}"], $this->the_plugin->localizationName) : $subject_def;

			$details = array('plugin_name' => 'psp');
			$from_name = __($details['plugin_name'].' Report module | ', $this->the_plugin->localizationName) . get_bloginfo('name');
			$from_email = get_bloginfo('admin_email');
			$headers = array();
			$headers[] = __('From: ', $this->the_plugin->localizationName) . $from_name . " <" . $from_email . ">";
			$headers[] = "MIME-Version: 1.0";
			
			// wordpress mail function
			$sendStat = wp_mail( $email_to, $subject, $html, $headers );
			//var_dump('<pre>', $sendStat, $email_to, $subject, $html, $headers , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			// reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
			remove_filter('wp_mail_content_type', array($this->the_plugin, 'set_content_type'));

			// phpmailer fallback
			if ( !$sendStat ) {
				require_once( $this->the_plugin->cfg['paths']['scripts_dir_path'] . '/PHPMailer_5.2.9/class.phpmailer.php' );
			
				$mail = new PHPMailer();
				
				$mail->SetFrom( $from_email, $from_name );
				
				$mail->AddAddress( $email_to, $email_to );
				
				// add us as BCC of reply
				$mail->AddBCC( $from_email, $from_name );
		
				$mail->Subject = $subject;
				$mail->AltBody    = __("To view the message, please use an HTML compatible email viewer!", $this->the_plugin->localizationName); // optional, comment out and test
				
				// load the header 
				$body  = $html;     
				
				// append body html to email transporter
				$mail->MsgHTML( $body );
				
				$sendStat = (bool) $mail->Send();
				
				// Clear Addresses
				$mail->ClearAddresses();
			}
			
			return array(
				'mailStat'          => $sendStat,
				'mailFields'        => compact( 'email_to', 'subject' ), //compact( 'email_to', 'subject', 'html' ),
			);
		}


		/**
		 * Cronjobs
		 */
		public function cronjob( $pms, $return='die' ) {
			$ret = array('status' => 'failed');
			
			$current_cron_status = $pms['status']; //'new'; //
			$now = self::$current_time;
		   
			foreach ($this->reportsList as $repKey => $repInfo) {
				$log_action = $repInfo['alias'];
				$now = time();
				$recurrence = isset(self::$settings["recurrency_{$log_action}"]) ? (int) self::$settings["recurrency_{$log_action}"] : 12;
				$recurrence = (int) ( $recurrence * 3600 );
				$report_last_date = get_option('psp_report_last_date', array());
				$report_last_date = isset($report_last_date["$repKey"]) ? $report_last_date["$repKey"]['last_date'] : 0;
				//$diff  = (string)(( $report_last_date + $recurrence ) - $now);
				//var_dump('<pre>', $log_action, $now, $recurrence, $report_last_date, $diff, (string) ($recurrence - $diff), '</pre>'); die('debug...'); 
				
				// recurrence interval fulfilled
				if ( /*1 || */$now >= ( $report_last_date + $recurrence ) ) {
					
					// assurance verification: reset in any case after more than 3 times the current setted recurrence interval
					//$do_reset = $now >= ( $report_last_date + $recurrence * 3 ) ? true : false;
					
					$report_data = $this->build_current_report(array(
						'module' 	=> $repKey,
					));
					$this->view_in_browser = admin_url( 'admin-ajax.php?action=psp_report_settings&subaction=view_in_browser&log_id=' . $report_data['id'] );
					$this->report_send_mail(array(
						'module' 		=> $repKey,
						'row_id' 		=> $report_data['id'],
						'content_data'	=> $this->get_current_report__(array(
							'module' 		=> $repKey,
							'row_data' 		=> $report_data,
							'view_type' 	=> 'email',
						)),
					));
				}
				usleep(1500000); // pause for 1.5 seconds
			} // end foreach
   
			$ret = array_merge($ret, array(
				'status'            => 'done',
			));
			return $ret;
		}
		 

		/**
		 * Ajax requests
		 */
		public function ajax_request_settings() {
			$request = array(
				'action'                        => isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '',
				'module'                        => isset($_REQUEST['module']) ? $_REQUEST['module'] : '',
			);
			extract($request);
			
			$ret = array(
				'status'            => 'invalid',
				'current_date'      => date('Y-m-d H:i:s'),
				'html'              => '<span class="error">' . __('Invalid action!', $this->the_plugin->localizationName) . '</span>',
			);

			if ( empty($action) || !in_array($action, array('getStatus', 'send_report', 'view_in_browser')) ) {
				die(json_encode($ret));
			}
	
			if ( $action == 'getStatus' ) {
				
				//$notifyStatus = get_option( sprintf( self::$report_alias_act, $module_ ), array() );
				$notifyStatus = get_option( self::$report_alias_act, array() );
				$notifyStatus = isset($notifyStatus["$module"]) ? $notifyStatus["$module"] : false;
				if ( $notifyStatus === false || !isset($notifyStatus["report"]) ) {
					$ret = array_merge($ret, array(
						'html'      => '<span class="error">' . __('No status saved yet from Send Report Now!', $this->the_plugin->localizationName) . '</span>',
					));
				} else {
					$ret = array_merge($ret, array(
						'status'    => 'valid',
						'html'      => $notifyStatus["report"]["html"],
					));
				}
				die(json_encode($ret));
			
			}
			else if ( $action == 'view_in_browser' ) {
				
				$is_pdf = isset($_REQUEST['is_pdf']) ? (int) $_REQUEST['is_pdf'] : 0;

				$unique_log_id = isset($_REQUEST['log_id']) ? $_REQUEST['log_id'] : 0;
				$this->view_in_browser = admin_url( 'admin-ajax.php?action=psp_report_settings&subaction=view_in_browser&log_id=' . $unique_log_id );
				
				$row_data = (array) $this->get_log_data( $unique_log_id );
				
				// here we use the real <log_id> field from row
				$log_code = $this->log_code_build($row_data['log_id'], $row_data['log_action']);
				$opStatus = $this->get_current_report__(array(
					'module' 	=> $log_code,
					'row_data' 	=> $row_data,
					'view_type' => 'email',
				));
				$html = $opStatus['html'];

				if ( $is_pdf ) {
					$html = str_replace('class="currentTable"', 'class="currentTable is_pdf"', $html);
				}

				die( $html );
				
			}
			else if ( $action == 'send_report' ) {

				$this->device = '_email';
				
				// current report
				$report_data = $this->build_current_report(array(
					'module' 	=> $module
				));
				//var_dump('<pre>', $report_data , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				$this->view_in_browser = admin_url( 'admin-ajax.php?action=psp_report_settings&subaction=view_in_browser&log_id=' . $report_data['id'] );
				$this->report_send_mail(array(
					'module' 		=> $module,
					'row_id' 		=> $report_data['id'],
					'content_data'	=> $this->get_current_report__(array(
						'module' 		=> $module,
						'row_data' 		=> $report_data,
						'view_type' 	=> 'email',
					)),
				));

				//$notifyStatus = get_option( sprintf( self::$report_alias_act, $module_ ), array() );
				$notifyStatus = get_option( self::$report_alias_act, array() );
				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'html'      => '<span class="success">' . sprintf( __('last operation: <em>'.str_replace('_', ' ', $action).'</em> | execution date: <em>%s</em>.', $this->the_plugin->localizationName), $ret['current_date'] ) . '</span>',
				));
				
				//$notifyStatus["report"] = $ret;
				//update_option( sprintf( self::$report_alias_act, $module_ ), (array) $notifyStatus );
				$notifyStatus["$module"]["report"] = $ret;
				update_option( self::$report_alias_act, (array) $notifyStatus );
			}
			die(json_encode($ret));
		}

		public function ajax_request() {
			global $wpdb;
			$request = array(
				'action'                        => isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '',
				'filter'                        => isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '',
				'id'                            => isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0,
			);
			extract($request);
			
			$ret = array(
				'status'        => 'invalid',
				'msg'           => '<div class="psp-sync-settings-msg psp-message psp-error">' . __('Invalid action!', $this->the_plugin->localizationName) . '</div>',
			);
			
			if ( empty($action) || !in_array($action, array('load_logs', 'log_id', 'log_action', 'view_log', 'download_pdf', 'send_email')) ) {
				die(json_encode($ret));
			}
			else {
				$_SESSION['psp_report']['op_stat'] = '';
			}
   
			if ( in_array($action, array('load_logs', 'log_id', 'log_action')) ) {
				
				if ( in_array($action, array('log_id', 'log_action')) ) {
					$_SESSION['psp_report']["$action"] = $filter;
				}

				$__pms = array(
					'log_id'		=> isset($_SESSION['psp_report']["log_id"])
						? $_SESSION['psp_report']["log_id"] : '',
					'log_action'	=> isset($_SESSION['psp_report']["log_action"])
						? $_SESSION['psp_report']["log_action"] : '',
				);
				$productsList = $this->get_rows( $__pms );

				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'msg'       => '',
					'html'      => implode(PHP_EOL, isset($productsList['html']) ? $productsList['html'] : array()),
					'nb'        => isset($productsList['nb']) ? $productsList['nb'] : 0,
					'nbv'       => isset($productsList['nbv']) ? $productsList['nbv'] : 0,
				));

			}
			else if ( $action == 'view_log' ) {
				
				$html = $this->view_log( array(
					'id' 	=> $request['id'],
				));
				
				$ret = array_merge($ret, array(
					'status'    => 'valid',
					'msg'       => '',
					'html'      => $html,
				));
			}
			else if ( $action == 'download_pdf' ) {
				
				$this->download_pdf( array(
					'id' 	=> $request['id'],
				));
				//ALREADY IS A DIE IN THE FUNCTION
			}
			else if ( $action == 'send_email' ) {
				
				$opStat = $this->send_email( array(
					'id' 	=> $request['id'],
				));
				$ret = array_merge($ret, $opStat);
			}
			die(json_encode($ret));
		}


		/**
		 * Utils
		 */
		private function log_nice_format( $val ) {
			$ret = ucwords( str_replace('_', ' ', $val) );
			return $ret;
		}
		
		private function sort_hight_to_low( $a, $subkey ) {
			if ( empty($a) || !is_array($a) ) return array();

			$b = array();
			foreach($a as $k=>$v) {
				$b["$k"] = strtolower($v["$subkey"]);
			}
			arsort($b);
			foreach($b as $key=>$val) {
				$c["$key"] = $a["$key"];
			}
			return $c;
		}

		private function log_code_build( $log_id, $log_action ) {
			$log_code = "{$log_id}|{$log_action}";

			return $log_code;
		}

		private function log_code_split( $log_code='' ) {
			$modid_ = $log_code;
			$modid_ = explode('|', $log_code);
			$log_id = $modid_[0];
			$log_action = $modid_[1];

			return compact('log_id', 'log_action');
		}


		//::---------------------------------------------
		//:: THIS IS THE ONLY PLACE WHRE YOU EDIT
		// - add new report types & also add them in aa-framework/framework.class.php array 'reportsList'
		private function create_new_report__( $pms=array() ) {
			$pms = array_replace_recursive(array(
            	'module' 		=> '',
                'last_date' 	=> 0,
                'date_add' 		=> 0,
			), $pms);
			extract( $pms );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'html' 		=> '',
			);

			extract( $this->log_code_split($module) );

			$pmsMandatory = array(
				'last_date' 	=> $last_date,
				'date_add' 		=> $date_add,
			);

			//:: here are report types
			if ( 'serp_rank_changes' == $log_action ) {
            	$opStatus = $this->objSERP->serp_rank_changes_report_save( array_replace_recursive( $pmsMandatory, array(
            	)));

				$ret = array_replace_recursive($ret, $opStatus, array(
				));
			}
			else if ( 'serp_website_stats' == $log_action ) {
            	$opStatus = $this->objSERP->serp_website_stats_report_save( array_replace_recursive( $pmsMandatory, array(
            	)));

				$ret = array_replace_recursive($ret, $opStatus, array(
				));
			}

            return $ret;
		}

		private function get_current_report__( $pms=array() ) {
			$pms = array_replace_recursive(array(
				'module' 	=> '',
				'view_type' => '',
				'row_data' 	=> array(), // db row fields
			), $pms);
			extract( $pms );

			$ret = array(
				'status'	=> 'invalid',
				'msg'		=> '',
				'html' 		=> '',
			);

			extract( $this->log_code_split($module) );

			$pmsMandatory = array(
				'device' 			=> $this->device,
				'view_in_browser' 	=> $this->view_in_browser,
				'view_type' 		=> $view_type,
				'log_data' 			=> $row_data['log_data'],
				'date_add' 			=> $row_data['date_add'],
			);
			//var_dump('<pre>', $pmsMandatory , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			//:: here are report types
			if ( 'serp_rank_changes' == $log_action ) {
				$opStatus = $this->objSERP->serp_rank_changes_report_html( array_replace_recursive( $pmsMandatory, array(
				)));

				$ret = array_replace_recursive($ret, $opStatus, array(
				));
			}
			else if ( 'serp_website_stats' == $log_action ) {
				$opStatus = $this->objSERP->serp_website_stats_report_html( array_replace_recursive( $pmsMandatory, array(
				)));

				$ret = array_replace_recursive($ret, $opStatus, array(
				));
			}

            return $ret;
		}
		//::---------------------------------------------
	}
}
 
// Initialize the pspReport class
$pspReport = pspReport::getInstance();