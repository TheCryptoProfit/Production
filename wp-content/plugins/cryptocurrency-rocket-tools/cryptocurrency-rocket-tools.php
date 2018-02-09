<?php
/**
 * Plugin Name: Cryptocurrency Rocket Tools
 * Description: Price ticker, table, graph, converter, price list of all cryptocurrencies.
 * Version: 1.4.1
 * Author: Webstulle
 * Author URI: http://webstulle.com/
 * Text Domain: cryptocurrency-rocket-tools
 * Domain Path: /languages
 * License: GPL2 or later
 *
 * @package Cryptocurrency Rocket Tools
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


// Define plugin url global
define('CRTOOLS_URL', plugin_dir_url( __FILE__ ));

define('CRTOOLS_PATH', plugin_dir_path( __FILE__ ));

define('CRTOOLS_MAIN_FILE', __FILE__ );

define('CC_API_URL', 'https://www.cryptocompare.com/api/data');


// Define Redux Framework
if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/frameworks/ReduxFramework/ReduxCore/framework.php' ) ) {
    require_once( dirname( __FILE__ ) . '/frameworks/ReduxFramework/ReduxCore/framework.php' );
}
if ( !isset( $crtools_redux ) && file_exists( dirname( __FILE__ ) . '/includes/admin-config.php' ) ) {
    require_once( dirname( __FILE__ ) . '/includes/admin-config.php' );
}


// Localization
function crtools_textdomain() {
    load_plugin_textdomain( 'cryptocurrency-rocket-tools', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'crtools_textdomain' );

// Difine assets
function crtools() {

    global $crtoolsVars;
    include_once( dirname(__FILE__) . '/includes/messages.php');

    wp_enqueue_script( 'jquery');

    wp_enqueue_script( 'crtools', CRTOOLS_URL.'assets/js/crtools.min.js');
    wp_localize_script('crtools', 'crtoolsVars', $crtoolsVars );

    wp_enqueue_style( 'crtools-css', CRTOOLS_URL.'assets/css/main.css');
}
add_action( 'wp_enqueue_scripts', 'crtools' );




// Define supported shortcodes
require_once(dirname(__FILE__) . '/includes/shortcodes.php');
add_shortcode( 'crtools-table', 'crtools_table_shortcode' );
add_shortcode( 'crtools-converter', 'crtools_converter_shortcode' );
add_shortcode( 'crtools-graph', 'crtools_graph_shortcode' );
add_shortcode( 'crtools-pricelist', 'crtools_pricelist_shortcode' );


// Add custom CSS
add_action('wp_head', 'crtools_get_custom_css', 1000);


// Define ajaxurl variable
function crtools_ajaxurl()
{
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
add_action('wp_head', 'crtools_ajaxurl');


// Define ajax function
function get_coin_list_callback()
{
    header('Access-Control-Allow-Origin: *');
    echo file_get_contents(CC_API_URL . '/coinlist/');

    wp_die();
}
add_action( 'wp_ajax_get_coin_list', 'get_coin_list_callback' );
add_action( 'wp_ajax_nopriv_get_coin_list', 'get_coin_list_callback' );


function get_coin_list()
{
    header('Access-Control-Allow-Origin: *');
    $data = json_decode(file_get_contents(CC_API_URL . '/coinlist/'), true);

    if($data['Response'] == 'Success')
        foreach ($data['Data'] as $coin)
            $coinList[$coin['Symbol']] = $coin['FullName'];
    
    return $coinList;

}

// Define ajax function
function get_start_date_callback()
{
    $return = array(
        'Response' => 'Error',
        'Message' => '',
        'Date' => '',
    );

    if($_GET['coin'] != ''){

        $response =  json_decode(file_get_contents(CC_API_URL.'/coinlist/'));

        if($response->Response == 'Success')
        {
            $coinID = get_object_vars($response->Data)[$_GET['coin']]->Id;

            $response =  json_decode(file_get_contents(CC_API_URL . '/coinsnapshotfullbyid/?id=' . $coinID));

            if($response->Response == 'Success')
            {
                $date = $response->Data->General->StartDate;

                $return = array(
                    'Response' => 'Success',
                    'Message' => $response->Message,
                    'Date' => $date,
                );

            }
            else
                $return = array(
                    'Response' => 'Error',
                    'Message' => $response->Message,
                    'Date' => '',
                );
        }
        else
            $return = array(
                'Response' => 'Error',
                'Message' => $response->Message,
                'Date' => '',
            );
    }
    else
        $return = array(
            'Response' => 'Error',
            'Message' => 'Missing coin parameter',
            'Date' => '',
        );

    echo json_encode($return, JSON_UNESCAPED_SLASHES);

    wp_die();
}
add_action( 'wp_ajax_get_start_date', 'get_start_date_callback' );
add_action( 'wp_ajax_nopriv_get_start_date', 'get_start_date_callback' );



