<?php
global $newslite_panels;
global $newslite_sections;
global $newslite_settings_controls;
global $newslite_repeated_settings_controls;
global $newslite_customizer_defaults;

/*creating panel for fonts-setting*/
$newslite_panels['newslite-top-header-section'] =
    array(
        'title'          => __( 'Header Section Settings', 'newslite' ),
        'priority'       => 190
    );

$newslite_sections['newslite-feature-header-section'] =
    array(
        'priority'       => 40,
        'title'          => __( 'Top Header Settings', 'newslite' ),
        'panel'         => 'newslite-top-header-section',
    );

$newslite_sections['newslite-feature-add'] =
    array(
        'priority'       => 50,
        'title'          => __( 'Header Advertisement Settings', 'newslite' ),
        'panel'         => 'newslite-top-header-section',
    );

$newslite_customizer_defaults['newslite-header-add'] = '';
$newslite_customizer_defaults['newslite-home-header-add-link'] = '#';
$newslite_customizer_defaults['newslite-header-enable-search'] = 1;
$newslite_customizer_defaults['newslite-header-enable-home-link'] = 1;
$newslite_customizer_defaults['newslite-header-enable-tinker'] = 1;
$newslite_customizer_defaults['newslite-header-tinker-title'] = __('Latest','newslite');
$newslite_customizer_defaults['newslite-header-no-of-tinker'] = 5;
$newslite_customizer_defaults['newslite-header-enable-date'] = 1;


$newslite_settings_controls['newslite-header-enable-search'] =
array(
    'setting' =>     array(
        'default'              => $newslite_customizer_defaults['newslite-header-enable-search'],
    ),
    'control' => array(
        'label'                 =>  __( 'Enable Search', 'newslite' ),
        'section'               => 'newslite-feature-header-section',
        'type'                  => 'checkbox',
        'priority'              => 40,
    )
);


$newslite_settings_controls['newslite-header-enable-home-link'] =
array(
    'setting' =>     array(
        'default'              => $newslite_customizer_defaults['newslite-header-enable-home-link'],
    ),
    'control' => array(
        'label'                 =>  __( 'Enable Home', 'newslite' ),
        'section'               => 'newslite-feature-header-section',
        'type'                  => 'checkbox',
        'priority'              => 45,
    )
);

$newslite_settings_controls['newslite-header-enable-date'] =
array(
    'setting' =>     array(
        'default'              => $newslite_customizer_defaults['newslite-header-enable-date'],
    ),
    'control' => array(
        'label'                 =>  __( 'Enable Date', 'newslite' ),
        'section'               => 'newslite-feature-header-section',
        'type'                  => 'checkbox',
        'priority'              => 35   ,
    )
);

$newslite_settings_controls['newslite-header-enable-tinker'] =
array(
    'setting' =>     array(
        'default'              => $newslite_customizer_defaults['newslite-header-enable-tinker'],
    ),
    'control' => array(
        'label'                 =>  __( 'Header Tiker Enable', 'newslite' ),
        'section'               => 'newslite-feature-header-section',
        'type'                  => 'checkbox',
        'priority'              => 20,
    )
);

$newslite_settings_controls['newslite-header-tinker-title'] =
array(
    'setting' =>     array(
        'default'              => $newslite_customizer_defaults['newslite-header-tinker-title'],
    ),
    'control' => array(
        'label'                 =>  __( 'Header Tinker Title', 'newslite' ),
        'section'               => 'newslite-feature-header-section',
        'type'                  => 'text',
        'priority'              => 25,
    )
);

$newslite_settings_controls['newslite-header-no-of-tinker'] =
array(
    'setting' =>     array(
        'default'              => $newslite_customizer_defaults['newslite-header-no-of-tinker'],
    ),
    'control' => array(
        'label'                 =>  __( 'No of Tinker to Display', 'newslite' ),
        'section'               => 'newslite-feature-header-section',
        'type'                  => 'number',
        'priority'              => 30,
        'active_callback'       => '',
        'input_attrs' => array( 'min' => 1, 'max' => 20),
    )
);

/*creating setting control for newslite-layout-options start*/
$newslite_settings_controls['newslite-header-add'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-header-add'],
        ),
        'control' => array(
            'label'                 =>  __( 'Header Banner Advertisement', 'newslite' ),
            'description'           =>  __( 'Upload Image to be on header banner', 'newslite' ),
            'section'               => 'newslite-feature-add',
            'type'                  => 'image',
            'priority'              => 20,
        )
    );


/*header banner url*/
$newslite_settings_controls['newslite-home-header-add-link'] =
array(
    'setting' =>     array(
        'default'              => $newslite_customizer_defaults['newslite-home-header-add-link']
    ),
    'control' => array(
        'label'                 =>  __( 'Redirect Advertisement URL', 'newslite' ),
        'section'               => 'newslite-feature-add',
        'type'                  => 'url',
        'priority'              => 60,
        'active_callback'       => ''
    )
);


