<?php
global $newslite_sections;
global $newslite_settings_controls;
global $newslite_repeated_settings_controls;
global $newslite_customizer_defaults;

/*defaults values*/
$newslite_customizer_defaults['newslite-enable-breadcrumb'] = 1;

$newslite_sections['newslite-breadcrumb-options'] =
    array(
        'priority'       => 50,
        'title'          => __( 'Breadcrumb Options', 'newslite' ),
        'panel'          => 'newslite-theme-options',
    );

$newslite_settings_controls['newslite-enable-breadcrumb'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-enable-breadcrumb'],
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Breadcrumb', 'newslite' ),
            'section'               => 'newslite-breadcrumb-options',
            'type'                  => 'checkbox',
            'priority'              => 10,
        )
    );