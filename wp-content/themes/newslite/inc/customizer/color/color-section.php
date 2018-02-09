<?php
global $newslite_sections;
global $newslite_settings_controls;
global $newslite_customizer_defaults;

/*defaults values*/
$newslite_customizer_defaults['newslite-site-identity-color'] = '#313131';
$newslite_customizer_defaults['newslite-primary-color'] = '#2f93cd';
$newslite_customizer_defaults['newslite-color-reset'] = '';

/**
 * Reset color settings to default
 *
 * @since newslite 1.0.0
 */
if ( ! function_exists( 'newslite_color_reset' ) ) :
    function newslite_color_reset( ) {
        
            global $newslite_customizer_saved_values;
           $newslite_customizer_saved_values = newslite_get_all_options();
        if ( $newslite_customizer_saved_values['newslite-color-reset'] == 1 ) {
            global $newslite_customizer_defaults;
            global $newslite_customizer_saved_values;
            /*getting fields*/

            /*setting fields */
            $newslite_customizer_saved_values['newslite-site-identity-color'] = $newslite_customizer_defaults['newslite-site-identity-color'] ;
            $newslite_customizer_saved_values['newslite-primary-color'] = $newslite_customizer_defaults['newslite-primary-color'] ;
            remove_theme_mod( 'background_color' );
            $newslite_customizer_saved_values['newslite-color-reset'] = '';
            /*resetting fields*/
            newslite_reset_options( $newslite_customizer_saved_values );
        }
        else {
            return '';
        }
    }
endif;
add_action( 'customize_save_after','newslite_color_reset' );

$newslite_settings_controls['newslite-site-identity-color'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-site-identity-color'],
        ),
        'control' => array(
            'label'                 =>  __( 'Site Identity Color', 'newslite' ),
            'description'           =>  __( 'Site title and tagline color', 'newslite' ),
            'section'               => 'colors',
            'type'                  => 'color',
            'priority'              => 20,
            'active_callback'       => ''
        )
    );

$newslite_settings_controls['newslite-primary-color'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-primary-color'],
        ),
        'control' => array(
            'label'                 =>  __( 'Primary Color', 'newslite' ),
            'section'               => 'colors',
            'type'                  => 'color',
            'priority'              => 30,
            'active_callback'       => ''
        )
    );

$newslite_settings_controls['newslite-color-reset'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-color-reset'],
            'transport'            => 'postmessage',
        ),
        'control' => array(
            'label'                 =>  __( 'Reset', 'newslite' ),
            'description'           =>  __( 'Caution: Reset all color settings above to default. Refresh the page after saving to view the effects', 'newslite' ),
            'section'               => 'colors',
            'type'                  => 'checkbox',
            'priority'              => 220,
            'active_callback'       => ''
        )
    );