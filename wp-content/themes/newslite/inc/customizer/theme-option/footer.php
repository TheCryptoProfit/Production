<?php
global $newslite_sections;
global $newslite_settings_controls;
global $newslite_repeated_settings_controls;
global $newslite_customizer_defaults;

/*defaults values*/
$newslite_customizer_defaults['newslite-footer-sidebar-number'] = 3;
$newslite_customizer_defaults['newslite-copyright-text'] = __('Copyright &copy; All right reserved.','newslite');
$newslite_customizer_defaults['newslite-enable-theme-name'] = 1;

$newslite_sections['newslite-footer-options'] =
    array(
        'priority'       => 150,
        'title'          => __( 'Footer Options', 'newslite' ),
        'panel'          => 'newslite-theme-options'
    );

$newslite_settings_controls['newslite-footer-sidebar-number'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-footer-sidebar-number'],
        ),
        'control' => array(
            'label'                 =>  __( 'Number of Sidebars In Footer Area', 'newslite' ),
            'section'               => 'newslite-footer-options',
            'type'                  => 'select',
            'choices'               => array(
                0 => __( 'Disable footer sidebar area', 'newslite' ),
                1 => __( '1', 'newslite' ),
                2 => __( '2', 'newslite' ),
                3 => __( '3', 'newslite' ),
                4 => __( '4', 'newslite' )
            ),
            'priority'              => 15,
            'description'           => '',
            'active_callback'       => ''
        )
    );

$newslite_settings_controls['newslite-copyright-text'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-copyright-text'],
        ),
        'control' => array(
            'label'                 =>  __( 'Copyright Text', 'newslite' ),
            'section'               => 'newslite-footer-options',
            'type'                  => 'text_html',
            'priority'              => 20,
        )
    );