<?php
global $newslite_panels;
global $newslite_sections;
global $newslite_settings_controls;
global $newslite_repeated_settings_controls;
global $newslite_customizer_defaults;

/*font array*/
global $newslite_google_fonts;
$newslite_google_fonts = array(
    'Archivo+Narrow:400,400italic,700' => 'Archivo Narrow',
    'Bitter:400,400italic,700' => 'Bitter',
    'Cookie' => 'Cookie',
    'Roboto'=> 'Roboto',
    'Exo:400,300,400italic,600,800' => 'Exo',
    'Kreon:400,300,700' => 'Kreon',
    'Lato:400,300,400italic,900,700' => 'Lato',
    'Merriweather:400,400italic,300,900,700' => 'Merriweather',
    'News+Cycle:400,700' => 'News Cycle',
    'Oxygen:400,300,700' => 'Oxygen',
    'Playball' => 'Playball',
    'Ropa+Sans:400,400italic' => 'Ropa Sans',
    'Tangerine:400,700' => 'Tangerine',
    'Ubuntu:400,400italic,500,700' => 'Ubuntu',
    'Yanone+Kaffeesatz:400,300,700' => 'Yanone Kaffeesatz',
    'Raleway:100,100italic,400,700' => 'Raleway',
    'Magra:400,700' => 'Magra',
    'Gudea:400,400i,700' => 'Gudea',

);

/*defaults values*/
$newslite_customizer_defaults['newslite-font-family-Primary'] = 'Gudea:400,400i,700';
$newslite_customizer_defaults['newslite-font-family-site-identity'] = 'Roboto';
$newslite_customizer_defaults['newslite-font-family-title'] = 'Magra:400,700';


/*section*/
$newslite_sections['newslite-family'] =
    array(
        'priority'       => 20,
        'title'          => __( 'Font Family', 'newslite' ),
    );

$newslite_settings_controls['newslite-font-family-site-identity'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-font-family-site-identity'],
            
        ),
        'control' => array(
            'label'                 => __( 'Site Identity/Logo', 'newslite' ),
            'description'           => __( 'Site Identity font family', 'newslite' ),
            'section'               => 'newslite-family',
            'type'                  => 'select',
            'choices'               => $newslite_google_fonts,
            'priority'              => 10,
            'active_callback'       => ''
        )
    );

/*setting - controls*/
$newslite_settings_controls['newslite-font-family-Primary'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-font-family-Primary'],
            
        ),
        'control' => array(
            'label'                 => __( 'Body ( paragraph ) Primary', 'newslite' ),
            'description'           => __( 'change primary font family', 'newslite' ),
            'section'               => 'newslite-family',
            'type'                  => 'select',
            'choices'               => $newslite_google_fonts,
            'priority'              => 15,
            'active_callback'       => ''
        )
    );


$newslite_settings_controls['newslite-font-family-title'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-font-family-title'],
            
        ),
        'control' => array(
            'label'                 => __( 'Title', 'newslite' ),
            'description'           => __('title font will be changed', 'newslite'),
            'section'               => 'newslite-family',
            'type'                  => 'select',
            'choices'               => $newslite_google_fonts,
            'priority'              => 20,
            'active_callback'       => ''
        )
    );