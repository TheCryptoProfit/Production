<?php

/* Admin Area
-------------------------------------------------------------------------------------- */

/**
 * Admin Class
 *
 * @since Fox 2.2
 */
if ( !class_exists( 'Wi_Admin' ) ) :

class Wi_Admin
{   
    
    /**
	 *
	 */
	public function __construct() {
	}
    
    /**
	 * The one instance of Wi_Admin
	 *
	 * @since Fox 2.2
	 */
	private static $instance;

	/**
	 * Instantiate or return the one Wi_Admin instance
	 *
	 * @since Fox 2.2
	 *
	 * @return Wi_Admin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
    
    /**
     * Initiate the class
     * contains action & filters
     *
     * @since Fox 2.2
     */
    public function init() {
        
        // require frameworks
        require 'framework/metabox/metabox.php';
        
        // VP Post Formats UI by default
        require_once 'framework/formatui/vp-post-formats-ui.php'; // This plugin has a high compatible ability so we don't need to check if it exists or not
        // correct the post format-ui url
        add_filter( 'vp_pfui_base_url', function() { return get_template_directory_uri() . '/inc/admin/framework/formatui/'; });
        
        // Include media upload to sidebar area
        // This will be used when we need to upload something
        add_action( 'sidebar_admin_setup', function() {
            wp_enqueue_media();
        });
        
        // enqueue scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
        
        // localization
        add_action( 'wiadminjs', array( $this, 'l10n' ) );
        
        // metabox
        add_action( 'wi_metaboxes', array( $this, 'metaboxes' ) );
        
    }
    
    /**
     * Enqueue javascript & style for admin
     *
     * @since Fox 2.2
     */
    function enqueue(){
        
        // We need to upload image/media constantly
        wp_enqueue_media();
        
        // admin css
        wp_enqueue_style( 'wi-admin', get_template_directory_uri() . '/css/admin.css', array( 'wp-color-picker', 'wp-mediaelement' ) );
        
        // admin javascript
        wp_enqueue_script( 'wi-admin', get_template_directory_uri() . '/js/admin.js', array( 'wp-color-picker', 'wp-mediaelement' ), '20160326', true );
        
        // localize javascript
        $jsdata = apply_filters( 'wiadminjs', array() );
        wp_localize_script( 'wi-admin', 'WITHEMES_ADMIN' , $jsdata );
        
    }
    
    /**
     * Localization some text
     *
     * @since Fox 2.2
     */
    function l10n( $jsdata ) {
    
        $jsdata[ 'l10n' ] =  array(
        
            'choose_image' => esc_html__( 'Choose Image', 'wi' ),
            'change_image' => esc_html__( 'Change Image', 'wi' ),
            'upload_image' => esc_html__( 'Upload Image', 'wi' ),
            
            'choose_images' => esc_html__( 'Choose Images', 'wi' ),
            'change_images' => esc_html__( 'Change Images', 'wi' ),
            'upload_images' => esc_html__( 'Upload Images', 'wi' ),
        
        );
        
        return $jsdata;
    
    }
    
    /**
     * Metaboxes
     *
     * @return $metaboxes
     *
     * @modified since 2.4
     * @since Fox 2.2
     */
    function metaboxes( $metaboxes ) {
    
        $metaboxes[] = array (
            
            'id' => 'post-options',
            'screen' => array( 'post', 'page' ),
            'title' => esc_html__( 'Settings', 'wi' ),
            'fields' => array(
                
                array(
                    'id' => 'column_layout',
                    'name' => esc_html__( 'Layout', 'wi' ),
                    'type' => 'select',
                    'options' => array(
                        '' => esc_html__( 'Default', 'wi' ),
                        'single-column' => esc_html__( 'Single-column', 'wi' ),
                        'two-column' => esc_html__( 'Two-column', 'wi' ),
                    ),
                    'std' => '',
                ),
            
            ),
        
        );
        
        $metaboxes[] = array (
            
            'id' => 'reiview-options',
            'screen' => array( 'post' ),
            'title' => esc_html__( 'Review Settings', 'wi' ),
            'fields' => array(
                
                array(
                    'id' => 'review',
                    'name' => esc_html__( 'Review', 'wi' ),
                    'type' => 'review',
                ),
            
            ),
        
        );
        
        return $metaboxes;
    
    }
    
}

Wi_Admin::instance()->init();

endif; // class exists