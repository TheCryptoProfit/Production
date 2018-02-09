<?php

global $psp;

function psp_report_recurrency__( $what ) {
	global $psp;

	if ( 'serp_rank_changes' == $what ) {
		$recurrency = array(
			//12      => __('Every 12 hours', $psp->localizationName),
			24      => __('Every single day', $psp->localizationName),
			48      => __('Every 2 days', $psp->localizationName),
			72      => __('Every 3 days', $psp->localizationName),
			96      => __('Every 4 days', $psp->localizationName),
			120     => __('Every 5 days', $psp->localizationName),
			144     => __('Every 6 days', $psp->localizationName),
			168     => __('Every 1 week', $psp->localizationName),
			336     => __('Every 2 weeks', $psp->localizationName),
			504     => __('Every 3 weeks', $psp->localizationName),
			720     => __('Every 1 month', $psp->localizationName), // ~ 4 weeks + 2 days
		);
	}
	else if ( 'serp_website_stats' == $what ) {
		$recurrency = array(
			168     => __('Every 1 week', $psp->localizationName),
			336     => __('Every 2 weeks', $psp->localizationName),
			504     => __('Every 3 weeks', $psp->localizationName),
			720     => __('Every 1 month', $psp->localizationName), // ~ 4 weeks + 2 days
		);
	}

	return $recurrency;
}

function psp_report_recurrency_html__( $module, $action='default', $istab = '', $is_subtab='' ) {
	global $psp;
	
	$req['action'] = $action;
	
	$ss = get_option('psp_report', array());
	$ss = maybe_unserialize($ss);
	$ss = $ss !== false ? $ss : array();

	$reportsList = $psp->report_get_types();

	$module_ = isset($reportsList["$module"]) ? $reportsList["$module"]['alias'] : '';
	$notifyStatus = get_option('psp_report_act', array());
	$notifyStatus = isset($notifyStatus["$module"]) ? $notifyStatus["$module"] : false;

	if ( $req['action'] == 'getStatus' ) {
		if ( $notifyStatus === false || !isset($notifyStatus["report"]) ) {
			return '';
		}
		return $notifyStatus["report"]["html"];
	}

	$html = array();

	$recurrency_def = '24';
	if ( 'serp_rank_changes' == $module_ ) {
		$recurrency_def = '96';
	}
	else if ( 'serp_website_stats' == $module_ ) {
		$recurrency_def = '504';
	}
	$recurrency_list = psp_report_recurrency__( $module_ );
	
	$vals = array('recurrency' => $recurrency_def);
	if ( isset($ss["recurrency_{$module_}"]) && !empty($ss["recurrency_{$module_}"]) ) {
		$vals = array('recurrency' => $ss["recurrency_{$module_}"]); // get from db
	}

	ob_start();
?>
<div class="psp-form-row psp-report-container <?php echo ($istab!='' ? ' '.$istab : ''); ?><?php echo ($is_subtab!='' ? ' '.$is_subtab : ''); ?> psp-mod-<?php echo $module_; ?> psp-report-opt-recurrency">

	<label class="psp-form-label">Recurrency</label>
	<div class="psp-form-item large">
	<?php /*<span class="formNote"><?php _e('report sending recurrency', 'psp'); ?></span>
	<span><?php _e('Recurrency:', 'psp'); ?></span>&nbsp;*/ ?>
	<select id="recurrency_<?php echo $module_; ?>" name="recurrency_<?php echo $module_; ?>" style="width: 180px;">
		<?php
			foreach ($recurrency_list as $kk => $vv){
				$vv = (string) $vv;
				echo '<option value="' . ( $kk ) . '" ' . ( $vals["recurrency"] == $kk ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
		?>
	</select>&nbsp;&nbsp;
	
	<input type="button" class="psp-form-button psp-form-button-info" style="width: 160px;" id="psp-report-now" value="<?php _e('Send Report NOW', 'psp'); ?>">
	<img id="ajaxLoading" src="<?php echo $psp->cfg['modules']['report']['folder_uri']; ?>/images/ajax-loader.gif" width="16" height="11" style="display:none; width:auto;"/>
	<span style="margin:0px 0px 0px 10px" class="response"><?php echo psp_report_recurrency_html__( $module, 'getStatus' ); ?></span>

	</div>
</div>
<?php
	$htmlRow = ob_get_contents();
	ob_end_clean();
	$html[] = $htmlRow;
	
	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
		
		$(document).ready(function() {
			$.post(ajaxurl, {
				'action'        : 'psp_report_settings',
				'subaction'     : 'getStatus',
				'module'		: '<?php echo $module; ?>'
			}, function(response) {

				var $box = $('.psp-report-container.psp-mod-<?php echo $module_; ?>'), $res = $box.find('.response');
				$res.html( response.html );
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});

		$("body").on("click", ".psp-mod-<?php echo $module_; ?> #psp-report-now", function(){
			$(this).hide();
			$('.psp-mod-<?php echo $module_; ?> #ajaxLoading').show();
			
			$.post(ajaxurl, {
				'action'        : 'psp_report_settings',
				'subaction'    : 'send_report',
				'module'		: '<?php echo $module; ?>'
			}, function(response) {
				$('.psp-mod-<?php echo $module_; ?> #ajaxLoading').hide();
				$('.psp-mod-<?php echo $module_; ?> #psp-report-now').show();
				
				var $box = $('.psp-report-container.psp-mod-<?php echo $module_; ?>'), $res = $box.find('.response');
				$res.html( response.html );
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;

	return implode( "\n", $html );
}

echo json_encode(array(
	$tryed_module['db_alias'] => array(
		
		/* define the form_sizes  box */
		'report' => array(
			'title' => 'psp Report',
			'icon' => '{plugin_folder_uri}images/16.png',
			'size' => 'grid_4', // grid_1|grid_2|grid_3|grid_4
			'header' => true, // true|false
			'toggler' => false, // true|false
			'buttons' => true, // true|false
			'style' => 'panel', // panel|panel-widget
			
			// create the box elements array
			'elements' => array(

				'__help_report_HTML2PDFRocket' => array(
					'type' => 'message',
					'status' => 'info',
					'html' => 'Convert HTML to PDF with <a href="https://www.html2pdfrocket.com/#pricing" target="_blank">HTML 2 PDF Rocket</a>'
				),

				'html2pdfrocket_key'     => array(
					'type'      => 'textarea',
					'std'       => '',
					'size'      => 'small',
					//'force_width'=> '400',
					'title'     => __('HTML 2 PDF Rocket API Key(s):', 'psp'),
					'desc'      => __('you can try to add multiple keys, separated by a new line, but each one must belong to a different account.', 'psp'),
					'height'    => '100px',
					'width'     => '600px',
				),

				//Report - SERP Keywords Ranks Changes
				'__help_report_serp_rank_changes' => array(
					'type' => 'message',
					'status' => 'info',
					'html' => 'Report - SERP Keywords Ranks Changes'
				),
				
				'__report_serp_rank_changes' => array(
					'type' => 'html',
					'html' => psp_report_recurrency_html__( 'serp|serp_rank_changes', 'default', '__tab1', '' )
				),
				
				'email_subject_serp_rank_changes' => array(
					'type' => 'text',
					'std' => 'Report - SERP Keywords Ranks Changes',
					'size' => 'large',
					'force_width' => '500',
					'title' => 'Email Subject',
					'desc' => 'the email subject - let the default one or choose one which can help you quickly identify it in your Inbox'
				),
				
				'email_to_serp_rank_changes' => array(
					'type' => 'text',
					'std' => '',
					'size' => 'large',
					'force_width' => '300',
					'title' => 'Email TO',
					'desc' => 'where to email the report - separate multiple addresses by comma'
				),

				'include_competitors_serp_rank_changes' => array(
					'type'      => 'select',
					'std'       => 'yes',
					'size'      => 'large',
					'force_width'=> '100',
					'title'     => __('Include competitors: ', 'psp'),
					'desc'      => __('Choose yes if you want to include competitors in this report type', 'psp'),
					'options'   => array(
						'yes'   => __('YES', 'psp'),
						'no'    => __('NO', 'psp')
					)
				),

				//Report - SERP Your Website Stats
				'__help_report_serp_website_stats' => array(
					'type' => 'message',
					'status' => 'info',
					'html' => 'Report - SERP Your Website Stats'
				),
				
				'__report_serp_website_stats' => array(
					'type' => 'html',
					'html' => psp_report_recurrency_html__( 'serp|serp_website_stats', 'default', '__tab1', '' )
				),
				
				'email_subject_serp_website_stats' => array(
					'type' => 'text',
					'std' => 'Report - SERP Your Website Stats',
					'size' => 'large',
					'force_width' => '500',
					'title' => 'Email Subject',
					'desc' => 'the email subject - let the default one or choose one which can help you quickly identify it in your Inbox'
				),
				
				'email_to_serp_website_stats' => array(
					'type' => 'text',
					'std' => '',
					'size' => 'large',
					'force_width' => '300',
					'title' => 'Email TO',
					'desc' => 'where to email the report - separate multiple addresses by comma'
				),

				'include_competitors_serp_website_stats' => array(
					'type'      => 'select',
					'std'       => 'yes',
					'size'      => 'large',
					'force_width'=> '100',
					'title'     => __('Include competitors: ', 'psp'),
					'desc'      => __('Choose yes if you want to include competitors in this report type', 'psp'),
					'options'   => array(
						'yes'   => __('YES', 'psp'),
						'no'    => __('NO', 'psp')
					)
				),
			)
		)
	)
));