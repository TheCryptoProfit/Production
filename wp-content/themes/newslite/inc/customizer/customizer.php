<?php
/**
 * evision themes Theme Customizer
 *
 * @package eVision themes
 * @subpackage newslite
 * @since newslite 1.0.0
 */
/*Define Url for including css and js*/
if ( !defined( 'EVISION_CUSTOMIZER_URL' ) ) {
    define( 'EVISION_CUSTOMIZER_URL', trailingslashit( get_template_directory_uri() ) . 'inc/frameworks/evision-customizer/' );
}
/*Define path for including php files*/
if ( !defined( 'EVISION_CUSTOMIZER_PATH' ) ) {
    define( 'EVISION_CUSTOMIZER_PATH', trailingslashit( get_template_directory() ) . 'inc/frameworks/evision-customizer/' );
}

/*define constant for evision customizer name*/
if(!defined('EVISION_CUSTOMIZER_NAME')){
    define( 'EVISION_CUSTOMIZER_NAME', 'newslite_options' );
}

/**
 * reset options
 * @param  array $reset_options
 * @return void
 *
 * @since newslite 1.0.0
 */
if ( ! function_exists( 'newslite_reset_options' ) ) :
    function newslite_reset_options( $reset_options ) {
        set_theme_mod( EVISION_CUSTOMIZER_NAME, $reset_options );
    }
endif;

/**
 * Customizer framework added.
 */
require get_template_directory() . '/inc/frameworks/evision-customizer/evision-customizer.php';
global $newslite_panels;
global $newslite_sections;
global $newslite_settings_controls;
global $newslite_repeated_settings_controls;
global $newslite_customizer_defaults;

/******************************************
Modify Site Color Options
 *******************************************/
require get_template_directory().'/inc/customizer/color/color-section.php';

/******************************************
Modify Site Font Options
 *******************************************/
require get_template_directory().'/inc/customizer/font/font-section.php';

/******************************************
Modify Slider Section Options
 *******************************************/
require get_template_directory().'/inc/customizer/top-header/banner-add.php';

/******************************************
Modify Theme Option Section Options
 *******************************************/
require get_template_directory().'/inc/customizer/theme-option/option-panel.php';



/*Resetting all Values*/
/**
 * Reset color settings to default
 *
 * @since newslite 1.0.0
 */
global $newslite_customizer_defaults;
$newslite_customizer_defaults['newslite-customizer-reset-settings'] = '';
if ( ! function_exists( 'newslite_customizer_reset' ) ) :
    function newslite_customizer_reset( ) {
        global $newslite_customizer_saved_values;
        $newslite_customizer_saved_values = newslite_get_all_options();
        if ( $newslite_customizer_saved_values['newslite-customizer-reset-settings'] == 1 ) {
            global $newslite_customizer_defaults;
            /*getting fields*/
            $newslite_customizer_defaults['newslite-customizer-reset-settings'] = '';
            /*resetting fields*/
            newslite_reset_options( $newslite_customizer_defaults );
        }
        else {
            return '';
        }
    }
endif;
add_action( 'customize_save_after','newslite_customizer_reset' );

$newslite_sections['newslite-customizer-reset'] =
    array(
        'priority'       => 999,
        'title'          => __( 'Reset All Options', 'newslite' )
    );
$newslite_settings_controls['newslite-customizer-reset-settings'] =
    array(
        'setting' =>     array(
            'default'              => $newslite_customizer_defaults['newslite-customizer-reset-settings'],
            'transport'            => 'postmessage',
        ),
        'control' => array(
            'label'                 =>  __( 'Reset All Options', 'newslite' ),
            'description'           =>  __( 'Caution: Reset all options settings to default. Refresh the page after save to view the effects. ', 'newslite' ),
            'section'               => 'newslite-customizer-reset',
            'type'                  => 'checkbox',
            'priority'              => 10,
            'active_callback'       => ''
        )
    );

/******************************************
Aranging header image
 *******************************************/
$newslite_sections['custom_css'] =
    array(
        'title'          => __( 'Additional CSS', 'newslite' ),
        'priority'       => 400,
    );
    
$newslite_sections['header_image'] =
    array(
        'priority'       => 1999,
        'title'          => __( 'Header Image', 'newslite' )
    );

$newslite_customizer_args = array(
    'panels'            => $newslite_panels, /*always use key panels */
    'sections'          => $newslite_sections,/*always use key sections*/
    'settings_controls' => $newslite_settings_controls,/*always use key settings_controls*/
    'repeated_settings_controls' => $newslite_repeated_settings_controls,/*always use key sections*/
);

/*registering panel section setting and control start*/
function newslite_add_panels_sections_settings() {
    global $newslite_customizer_args;
    return $newslite_customizer_args;
}
add_filter( 'evision_customizer_panels_sections_settings', 'newslite_add_panels_sections_settings' );
/*registering panel section setting and control end*/

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function newslite_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
}
add_action( 'customize_register', 'newslite_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function newslite_customize_preview_js() {
    wp_enqueue_script( 'newslite_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'newslite_customize_preview_js' );


/**
 * get all saved options
 * @param  null
 * @return array saved options
 *
 * @since newslite 1.0.0
 */
if ( ! function_exists( 'newslite_get_all_options' ) ) :
    function newslite_get_all_options( $merge_default = 0 ) {
        $newslite_customizer_saved_values = evision_customizer_get_all_values( EVISION_CUSTOMIZER_NAME );
        if( 1 == $merge_default ){
            global $newslite_customizer_defaults;
            if(is_array( $newslite_customizer_saved_values )){
                $newslite_customizer_saved_values = array_merge($newslite_customizer_defaults, $newslite_customizer_saved_values );
            }
            else{
                $newslite_customizer_saved_values = $newslite_customizer_defaults;
            }
        }
        return $newslite_customizer_saved_values;
    }
endif;
