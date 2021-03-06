<?php
/**
 * Social_Stats Config file, return as json_encode
 * http://www.aa-team.com
 * ======================
 *
 * @author		Andrei Dinca, AA-Team
 * @version		1.0
 */
global $psp;
 echo json_encode(
	array(
		'Alexa_Rank' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 11,
				'title' => __('Alexa Rank', 'psp')
				,'icon' => '<i class="' . ( $psp->alias ) . '-checks-alexa"></i>'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $psp->alias . "_Alexa_Rank")
			),
			'description' => __("Alexa Rank is a ranking system set by alexa.com (a subsidiary of amazon.com) that basically audits and makes public the frequency of visits on various Web sites. ", 'psp'),
			'module_init' => 'init.php',
      	  	'help' => array(
				'type' => 'remote',
				'url' => ''
			),
			'load_in' => array(
				'backend' => array(
					//'@all',
					'admin.php?page=psp_Alexa_Rank',
					'admin-ajax.php'
				),
				'frontend' => true
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
				'jquery-ui-core',
				'jquery-ui-datepicker',
				'percentageloader-0.1',
				'chart-bundle',
				'sweetalert',
				'Alexa_Rank'
			),
			'css' => array(
				'admin'
			),
			'errors' => array(
				1 => __('
					You configured Google Analytics Service incorrectly. See 
					' . ( $psp->convert_to_button ( array(
						'color' => 'info psp-show-docs-shortcut',
						'url' => 'javascript: void(0)',
						'title' => 'here'
					) ) ) . ' for more details on fixing it. <br />
					Module Google Analytics verification section: click Verify button and read status 
					' . ( $psp->convert_to_button ( array(
						'color' => 'info',
						'url' => admin_url( 'admin.php?page=psp_server_status#sect-Alexa_Rank' ),
						'title' => 'here',
						'target' => '_blank'
					) ) ) . '<br />
					Setup the Google Analytics module 
					' . ( $psp->convert_to_button ( array(
						'color' => 'info',
						'url' => admin_url( 'admin.php?page=psp#Alexa_Rank' ),
						'title' => 'here'
					) ) ) . '
					', 'psp'),
				2 => __('
					You don\'t have the cURL library installed! Please activate it!
					', 'psp')
			)
		)
	)
 );