<?php
/**
 * newslite functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package newslite
 */

/**
 * require newslite int.
 */
require get_template_directory() . '/inc/init.php';

if ( ! function_exists( 'newslite_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function newslite_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on newslite, use a find and replace
	 * to change 'newslite' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'newslite', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	add_image_size( 'newslite-recent-sidebar', 165, 165, true );


	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'newslite' ),
		'secondary' => esc_html__( 'Secondary', 'newslite' ),
		'social' => esc_html__( 'Social Menu', 'newslite' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'newslite_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	/*implimenting new feature from 4.5*/
	add_theme_support( 'custom-logo', array(
	   'header-text' => array( 'site-title', 'site-description' ),
	) );
	
	/*woocommerce support*/
	add_theme_support( 'woocommerce' );

}
endif;
add_action( 'after_setup_theme', 'newslite_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function newslite_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'newslite_content_width', 640 );
}
add_action( 'after_setup_theme', 'newslite_content_width', 0 );

/*Google Fonts*/
function newslite_google_fonts() {
    global $newslite_customizer_all_values;
	$fonts_url = '';
	$fonts     = array();


	$newslite_font_family_body = $newslite_customizer_all_values['newslite-font-family-Primary'];
	$newslite_font_family_title = $newslite_customizer_all_values['newslite-font-family-title'];
	$newslite_font_family_site_identity = $newslite_customizer_all_values['newslite-font-family-site-identity'];
    
	$newslite_fonts = array();
	$newslite_fonts[]=$newslite_font_family_body;
	$newslite_fonts[]=$newslite_font_family_title;
	$newslite_fonts[]=$newslite_font_family_site_identity;

	  $newslite_fonts_stylesheet = '//fonts.googleapis.com/css?family=';

	  $i  = 0;
	  for ($i=0; $i < count( $newslite_fonts ); $i++) { 

	    if ( 'off' !== sprintf( _x( 'on', '%s font: on or off', 'newslite' ), $newslite_fonts[$i] ) ) {
			$fonts[] = $newslite_fonts[$i];
		}

	  }

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
/**
 * Enqueue scripts and styles.
 */
function newslite_scripts() {
    global $newslite_customizer_all_values;
		// *** STYLE ***//
		//slick main
	    wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/assets/frameworks/slick/slick.css', array(), '3.4.0' );/*added*/

		
		// Main stylesheet
		wp_enqueue_style( 'newslite-style', get_stylesheet_uri() );

		// google font
		wp_enqueue_style( 'newslite-google-fonts', newslite_google_fonts() );

		//inline style
		wp_add_inline_style( 'newslite-style', newslite_inline_style() );

		// *** SCRIPT ***//

		// modernizr
		wp_enqueue_script( 'jquery-modernizr', get_template_directory_uri() . '/assets/js/modernizr.min.js', array('jquery'), '2.8.3', true );
		
		// easing
		wp_enqueue_script('jquery-easing', get_template_directory_uri() . '/assets/frameworks/jquery.easing/jquery.easing.js', array('jquery'), '0.3.6', 1);	   
	    // waypoints
	    wp_enqueue_script('jquery-waypoints', get_template_directory_uri() . '/assets/frameworks/waypoints/jquery.waypoints.min.js', array('jquery'), '4.0.0', 1);
	     // slick
	    wp_enqueue_script('jquery-slick', get_template_directory_uri() . '/assets/frameworks/slick/slick.js', array('jquery'), '', true);
		// custom
		wp_enqueue_script('newslite-custom-js', get_template_directory_uri() . '/assets/js/evision-custom.js', array('jquery'), '', true);

		// skip-link-focus-fix
		wp_enqueue_script( 'newslite-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'newslite_scripts' );

/*added admin css for meta*/
function newslite_wp_admin_style($hook) {
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
        wp_register_style( 'newslite-admin-css', get_template_directory_uri() . '/assets/css/admin-meta.css',array(), ''  );
        wp_enqueue_style( 'newslite-admin-css' );
    }
	if ( 'widgets.php' == $hook ) {
		wp_enqueue_media();
		wp_enqueue_script( 'newslite-widgets-script', get_template_directory_uri() . '/assets/js/widgets.js', array( 'jquery' ), '1.0.0' );
	}
}
add_action( 'admin_enqueue_scripts', 'newslite_wp_admin_style' );
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


/*breadcrum function*/

if ( ! function_exists( 'newslite_simple_breadcrumb' ) ) :

	/**
	 * Simple breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function newslite_simple_breadcrumb() {

		if ( ! function_exists( 'breadcrumb_trail' ) ) {
			require_once get_template_directory() . '/assets/frameworks/breadcrumbs/breadcrumbs.php';
		}

		$breadcrumb_args = array(
			'container'   => 'div',
			'show_browse' => false,
		);
		breadcrumb_trail( $breadcrumb_args );

	}

endif;

/*update to pro added*/
require_once( trailingslashit( get_template_directory() ) . 'trt-customize-pro/newslite/class-customize.php' );