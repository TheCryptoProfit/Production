<?php
global $newslite_sections;
global $newslite_settings_controls;
global $newslite_repeated_settings_controls;
global $newslite_customizer_defaults;

/*defaults values*/
$newslite_customizer_defaults['newslite-enable-static-page'] = 1;

$newslite_customizer_defaults['newslite-alternate-layout'] = 1;
$newslite_customizer_defaults['newslite-default-layout'] = 'right-sidebar';
$newslite_customizer_defaults['newslite-single-post-image-align'] = 'full';
$newslite_customizer_defaults['newslite-excerpt-length'] = '50';
$newslite_customizer_defaults['newslite-archive-layout'] = 'thumbnail-and-excerpt';
$newslite_customizer_defaults['newslite-archive-image-align'] = 'full';

$newslite_sections['newslite-layout-options'] =
    array(
        'priority'       => 20,
        'title'          => __( 'Layout Options', 'newslite' ),
        'panel'          => 'newslite-theme-options',
    );

    /*home page static page display*/
$newslite_settings_controls['newslite-enable-static-page'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-enable-static-page'],
        ),
        'control' => array(
            'label'                 =>  __( 'Enable Static Front Page', 'newslite' ),
            'description'           =>  __( 'If you disable this the static page will be disappear from the home page and other section from customizer will remain as it is', 'newslite' ),
            'section'               => 'newslite-layout-options',
            'type'                  => 'checkbox',
            'priority'              => 10,
        )
    );

    /*home page static page display*/
$newslite_settings_controls['newslite-alternate-layout'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-alternate-layout'],
        ),
        'control' => array(
            'label'                 =>  __( 'Alternate Archive Image Alignment', 'newslite' ),
            'section'               => 'newslite-layout-options',
            'type'                  => 'checkbox',
            'priority'              => 10,
        )
    );
/*layout-options option responsive lodader start*/

$newslite_settings_controls['newslite-default-layout'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-default-layout'],
        ),
        'control' => array(
            'label'                 =>  __( 'Default Layout', 'newslite' ),
            'description'           =>  __( 'Please note that this section can be overridden for individual page/posts', 'newslite' ),
            'section'               => 'newslite-layout-options',
            'type'                  => 'select',
            'choices'               => array(
                'right-sidebar' => __( 'Content - Primary Sidebar', 'newslite' ),
                'left-sidebar' => __( 'Primary Sidebar - Content', 'newslite' ),
                'no-sidebar' => __( 'No Sidebar', 'newslite' )
            ),
            'priority'              => 10,
            'active_callback'       => ''
        )
    );


$newslite_settings_controls['newslite-single-post-image-align'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-single-post-image-align'],
        ),
        'control' => array(
            'label'                 =>  __( 'Alignment Of Image In Single Post/Page', 'newslite' ),
            'section'               => 'newslite-layout-options',
            'type'                  => 'select',
            'choices'               => array(
                'full' => __( 'Full', 'newslite' ),
                'right' => __( 'Right', 'newslite' ),
                'left' => __( 'Left', 'newslite' ),
                'no-image' => __( 'No image', 'newslite' )
            ),
            'priority'              => 40,
            'description'           =>  __( 'Please note that this setting can be override from individual post/page', 'newslite' ),
        )
    );

    $newslite_settings_controls['newslite-excerpt-length'] =
        array(
            'setting' =>     array(
                'default'              => $newslite_customizer_defaults['newslite-excerpt-length'],
            ),
            'control' => array(
                'label'                 =>  __( 'Excerpt Length (in words)', 'newslite' ),
                'section'               => 'newslite-layout-options',
                'type'                  => 'number',
                'priority'              => 40,
            )
        );

        $newslite_settings_controls['newslite-archive-layout'] =
            array(
                'setting' =>     array(
                    'default'              => $newslite_customizer_defaults['newslite-archive-layout'],
                ),
                'control' => array(
                    'label'                 =>  __( 'Archive Layout', 'newslite' ),
                    'section'               => 'newslite-layout-options',
                    'type'                  => 'select',
                    'choices'               => array(
                        'excerpt-only' => __( 'Excerpt Only', 'newslite' ),
                        'thumbnail-and-excerpt' => __( 'Thumbnail and Excerpt', 'newslite' ),
                        'full-post' => __( 'Full Post', 'newslite' ),
                        'thumbnail-and-full-post' => __( 'Thumbnail and Full Post', 'newslite' ),
                    ),
                    'priority'              => 55,
                )
            );