<?php
global $newslite_sections;
global $newslite_settings_controls;
global $newslite_repeated_settings_controls;
global $newslite_customizer_defaults;

/*defaults values*/
$newslite_customizer_defaults['newslite-enable-back-to-top'] = 1;

$newslite_sections['newslite-back-to-top-options'] =
    array(
        'priority'       => 80,
        'title'          => __( 'Back To Top Options', 'newslite' ),
        'panel'          => 'newslite-theme-options'
    );

$newslite_settings_controls['newslite-enable-back-to-top'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-enable-back-to-top'],
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Back To Top', 'newslite' ),
            'section'               => 'newslite-back-to-top-options',
            'type'                  => 'checkbox',
            'priority'              => 50,
        )
    );