<?php
/**
 * Config file, return as json_encode
 * http://www.aa-team.com
 * =======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
global $psp;
echo json_encode(
	array(
		'report' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 3,
				'title' => __('PSP Report', 'psp'),
				'icon' => '<span class="' . ( $psp->alias ) . '-icon-serp"><span class="path1"></span><span class="path2"></span></span>'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $psp->alias . "_report")
			),
			'description' => "PSP Report description",
			'help' => array(
				'type' => 'remote',
				'url' => 'http://docs.aa-team.com/premium-seo-pack/documentation/serp-tracking/'
			),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
				    'admin.php?page=psp_report',
					'admin-ajax.php'
				),
				'frontend' => false
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
				'sweetalert'
			),
			'css' => array(
				'admin',
				'tipsy'
			)
		)
	)
);