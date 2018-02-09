<?php
// admin
require_once( get_template_directory() . '/inc/admin/admin.php' );

// since 2.4
require_once get_template_directory() . '/woo/hooks.php';
require_once get_template_directory() . '/inc/review.php';

/* -------------------------------------------------------------------- */
/* CONTENT WIDTH 
/* -------------------------------------------------------------------- */
global $content_width;
if ( ! isset( $content_width ) ) {
	$content_width = absint(get_theme_mod('wi_content_width')) ? absint(get_theme_mod('wi_content_width')) : 1020;
}

/* -------------------------------------------------------------------- */
/* LAYOUT ARRAY
/* -------------------------------------------------------------------- */
if ( ! function_exists( 'wi_layout_array' ) ) {
function wi_layout_array() {
    $layout_arr = array(
        'standard'              =>  'Standard',
        'grid-2'                =>  'Grid 2 columns',
        'grid-3'                =>  'Grid 3 columns',
        'grid-4'                =>  'Grid 4 columns',
        'masonry-2'             =>  'Pinterest-like 2 columns',
        'masonry-3'             =>  'Pinterest-like 3 columns',
        'masonry-4'             =>  'Pinterest-like 4 columns',
        'newspaper'             =>  'Newspaper',
        'list'                  =>  'List',
    );
    
    return $layout_arr;
}
}

/* -------------------------------------------------------------------- */
/* BLOCK ARRAY
/* -------------------------------------------------------------------- */
if ( ! function_exists( 'wi_block_array' ) ) {
function wi_block_array() {
    $block_arr = array(
        'slider'                    =>  'Slider',
        'big-post'                  =>  'Big post',
        'grid-2'                    =>  'Grid 2 columns',
        'grid-3'                    =>  'Grid 3 columns',
        'grid-4'                    =>  'Grid 4 columns',
    );
    
    return $block_arr;
}
}

/* -------------------------------------------------------------------- */
/* SIDEBAR ARRAY
/* -------------------------------------------------------------------- */
if ( ! function_exists( 'wi_sidebar_array' ) ) {
function wi_sidebar_array() {
    return array(
        'sidebar-right'     =>  'Sidebar Right',
        'sidebar-left'      =>  'Sidebar Left',
        'no-sidebar'        =>  'No Sidebar',
    );
}
}

/* -------------------------------------------------------------------- */
/* RETURN LAYOUT
/* -------------------------------------------------------------------- */
if (!function_exists('wi_layout')){
function wi_layout(){
    
    if ( is_home() ) {
        $layout = get_theme_mod('wi_home_layout');
    } elseif ( is_category() ) {
        $this_cat = get_category(get_query_var('cat'), false);
        $term_meta = get_option( "taxonomy_$this_cat->term_id" );
        $layout = isset($term_meta['layout']) ? $term_meta['layout'] : '';
        if (!$layout) {
            $layout = get_theme_mod('wi_category_layout');
        }
    } elseif ( is_search() ) {
        $layout = get_theme_mod('wi_search_layout');
    } elseif ( is_day() || is_month() || is_year() ) {
        $layout = get_theme_mod('wi_archive_layout');
    } elseif ( is_tag() ) {
        $tag_id = get_queried_object()->term_id;
        $term_meta = get_option( "taxonomy_$tag_id" );
        $layout = isset($term_meta['layout']) ? $term_meta['layout'] : '';
        if (!$layout) {
            $layout = get_theme_mod('wi_tag_layout');
        }
    } elseif ( is_author() ) {
        $layout = get_theme_mod('wi_author_layout');
    } elseif ( is_404() ) {
        $layout = 'standard';
    } elseif ( is_single() ) {
        $layout = 'standard';
    } elseif ( is_page() && is_page_template('page-featured.php') ) {
        $layout = get_theme_mod('wi_all-featured_layout') ? get_theme_mod('wi_all-featured_layout') : '';
    } else {
        $layout = 'standard';
    }
    
    if (!$layout) $layout = '';
    
    if (!array_key_exists($layout,wi_layout_array())) $layout = 'standard';

    return apply_filters('wi_layout',$layout);
}
}

/* -------------------------------------------------------------------- */
/* SIDEBAR STATE
/* -------------------------------------------------------------------- */
if (!function_exists('wi_sidebar_state')){
function wi_sidebar_state(){
    $sidebar_state = '';
    if (is_page()) {
        if ( 
            is_page_template('page-fullwidth.php') || is_page_template('page-one-column.php')
        ) $sidebar_state = 'no-sidebar';
        else $sidebar_state = get_theme_mod('wi_page_sidebar_state');
    } elseif (is_single()) {
        $sidebar_state = get_theme_mod('wi_single_sidebar_state');
    } elseif (is_home()) {
        $sidebar_state = get_theme_mod('wi_home_sidebar_state');
    } elseif (is_category()) {
        $sidebar_state = get_theme_mod('wi_category_sidebar_state');
    } elseif (is_tag()) {
        $sidebar_state = get_theme_mod('wi_tag_sidebar_state');
    } elseif (is_archive()) {
        $sidebar_state = get_theme_mod('wi_archive_sidebar_state');
    } elseif (is_search()) {
        $sidebar_state = get_theme_mod('wi_search_sidebar_state');
    } elseif (is_author()) {
        $sidebar_state = get_theme_mod('wi_author_sidebar_state');
    }
    if ($sidebar_state!='sidebar-left' && $sidebar_state!='no-sidebar') $sidebar_state = 'sidebar-right';
    return $sidebar_state;
}
}

/* -------------------------------------------------------------------- */
/* BODY CLASSES
/* -------------------------------------------------------------------- */
add_action('body_class','wi_body_class');
if (!function_exists('wi_body_class')){
function wi_body_class($classes){
    
    // one-column template fallback
    if ( is_page_template( 'page-one-column.php' ) ) {
    
        $classes[] = 'disable-2-columns';
        
    } elseif ( is_single() || is_page() ) {
        
        $column_class = get_post_meta( get_the_ID(), '_wi_column_layout', true );
        if ( ! $column_class ) {
            $column_class = get_theme_mod('wi_disable_blog_2_columns') ? 'single-column' : 'two-column';
        }
        $column_class = ( $column_class == 'single-column' ) ? 'disable-2-columns' : 'enable-2-columns';
        
        $classes[] = $column_class;
    
    }
    
    // dropcap
    if (!get_theme_mod('wi_disable_blog_dropcap')) {
        $classes[] = 'enable-dropcap';
    } else {
        $classes[] = 'disable-dropcap';
    }
    
    // Sidebar
    $sidebar_state = wi_sidebar_state();
    if ($sidebar_state=='sidebar-right') {
        $classes[] = 'has-sidebar sidebar-right';
    } elseif ($sidebar_state=='sidebar-left') {
        $classes[] = 'has-sidebar sidebar-left';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    // hand-drawn lines
    if (get_theme_mod('wi_enable_hand_lines')) {
        $classes[] = 'enable-hand-lines';
    } else {
        $classes[] = 'disable-hand-lines';
    }
    
    // menu style
    if (get_theme_mod('wi_submenu_style') == 'dark') {
        $classes[] = 'submenu-dark';
    } else {
        $classes[] = 'submenu-light';
    }
    
	return $classes;
}
}

/* -------------------------------------------------------------------- */
/* SETUP
/* -------------------------------------------------------------------- */
if ( ! function_exists( 'wi_setup' ) ) :
function wi_setup() {
    
    // translation
	load_theme_textdomain( 'wi', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

    // title tag
    add_theme_support( 'title-tag' );

    // post thumbnail
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'thumbnail-big', 1020, 510, true );  // big thumbnail (ratio 2:1)
	add_image_size( 'thumbnail-medium', 480, 384, true );  // medium thumbnail
    add_image_size( 'thumbnail-medium-nocrop', 480, 9999, false );  // medium thumbnail no crop
    add_image_size( 'thumbnail-vertical', 9999, 500, false );  // vertical image used for gallery
    
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'wi' ),
        'footer' => __( 'Footer Menu', 'wi' ),
	) );
    
	// html5
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	// post formats
	add_theme_support( 'post-formats', array(
		'video', 'gallery', 'audio', 'link',
	) );
    
    // since 2.4
    add_theme_support( 'woocommerce' );
    
    // includes
    require_once( get_template_directory() . '/inc/menu-fields.php' );
    require_once( get_template_directory() . '/inc/google-fonts.php' );
    require_once( get_template_directory() . '/inc/fonts.php' );
    require_once( get_template_directory() . '/inc/featured-post.php' );
    require_once( get_template_directory() . '/inc/css.php' );
    require_once( get_template_directory() . '/inc/automatic-featured-images-from-videos.php' );
    require_once( get_template_directory() . '/inc/post-views/bawpv.php' );
    
    // depricated functions
    require_once( get_template_directory() . '/inc/depricated.php' );
    
    // customizer
    require_once( get_template_directory() . '/inc/customizer.php');
    
    // widgets
    require_once( get_template_directory() . '/widgets/about.php' );
    require_once( get_template_directory() . '/widgets/latest-posts.php' );
    require_once( get_template_directory() . '/widgets/best-rated.php' );
    require_once( get_template_directory() . '/widgets/social.php' );
    require_once( get_template_directory() . '/widgets/media.php' );
    require_once( get_template_directory() . '/widgets/facebook.php' );
    require_once( get_template_directory() . '/widgets/ads.php' );

}
endif; // wi_setup
add_action( 'after_setup_theme', 'wi_setup' );

/* -------------------------------------------------------------------- */
/* WIDGETS
/* -------------------------------------------------------------------- */
if (!function_exists('wi_widgets_init')) {
function wi_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'wi' ),
		'id'            => 'sidebar',
		'description'   => __('Add widgets here to appear in your sidebar.', 'wi' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Page Sidebar', 'wi' ),
		'id'            => 'page-sidebar',
		'description'   => __('Add widgets here to appear in your page\'s sidebar.', 'wi' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Header Area', 'wi' ),
		'id'            => 'header',
		'description'   => __('Add widgets here to appear at the header of your site.', 'wi' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
    
    for ($i=1; $i<=4; $i++) {
    register_sidebar( array(
		'name'          => sprintf(__( 'Footer %s', 'wi' ), $i),
		'id'            => 'footer-'.$i,
		'description'   => __('Add widgets here to appear in your footer sidebar.', 'wi' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
    }
    
}
}
add_action( 'widgets_init', 'wi_widgets_init' );

/* -------------------------------------------------------------------- */
/* FONTs
 *
 * @since 1.0
 *
 * @modified in 2.3
 *
/* -------------------------------------------------------------------- */
if ( ! function_exists( 'wi_fonts' ) ) :
function wi_fonts() {
    
    $types = array('body','heading','nav');
    $previous_fonts = array();
    
    $default_fonts = array(
        'body'          =>  'Merriweather',
        'heading'       =>  'Oswald',
        'nav'           =>  'Oswald',
    );
    
    $fonts_url = '';
    $fonts_data = wi_google_fonts_data();
    $subsets = array();
    
    $faces = array();
    $fonts = array();
    
    foreach ($types as $type) {
        
        // custom font
        if (trim(get_theme_mod('wi_'.$type.'_custom_font'))!='') continue;
        
        // get current font
        $current_font = get_theme_mod('wi_'.$type.'_font');
        if ( ! $current_font ) $current_font = $default_fonts[$type];
        
        // not a Google font
        if ( ! isset( $fonts_data[ $current_font ] ) )
            continue;
        
        $faces[] = $current_font;
        
    }
    
    // font data
    foreach ( $faces as $face ) {
    
        $fontData = $fonts_data[ $face ];
        $face = str_replace( ' ', '+', $face );
        $styles = $fontData[ 'styles' ];
        $styles = join( ',', $styles );
        $fonts[] = "{$face}:{$styles}";
        $subsets += $fontData[ 'subsets' ];
        
    }
    
    // remove duplicated elements
    $fonts = array_unique( $fonts );
    $subsets = array_unique( $subsets );

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => implode( '|', $fonts ),
			'subset' => join( ',', $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}
    
    return $fonts_url;
    
}
endif;

/* -------------------------------------------------------------------- */
/* ENQUEUE SCRIPTS
/* -------------------------------------------------------------------- */
function wi_scripts() {
    
    // loads google fonts
    wp_enqueue_style( 'wi-fonts', wi_fonts() );

	// awesome font
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome-4.7.0/css/font-awesome.min.css', array(), '4.7' );

    // Load our main stylesheet.
    if ( is_child_theme() ) {
	   wp_enqueue_style( 'style', get_stylesheet_uri() );
    } else {
        wp_enqueue_style( 'style', get_template_directory_uri() . '/style.min.css' );
    }
    
    if ( withemes_woocommerce_installed() ) {
        wp_enqueue_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
    }
    
    // Responsive
	wp_enqueue_style( 'wi-responsive', get_template_directory_uri() . '/css/responsive.css');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
    
    // facebook
    wp_register_script( 'wi-facebook', 'https://connect.facebook.net/en_US/all.js#xfbml=1', false, '1.0', true );
    
    // main
	wp_enqueue_script( 'wi-main', get_template_directory_uri() . '/js/theme.min.js', array( 'jquery' ), '2.5.2', true );
    
    // Create a filter to add global JS data to <head />
    // @since Fox 2.2
    $jsdata = array( 
        'l10n' => array( 
            'prev' => esc_html__( 'Previous', 'wi' ), 
            'next' => esc_html__( 'Next', 'wi' ),
        ),
        'enable_sticky_sidebar'=> get_theme_mod( 'wi_sticky_sidebar' ),
    );
    $jsdata = apply_filters( 'jsdata', $jsdata );
	wp_localize_script( 'wi-main', 'WITHEMES', $jsdata );
    
}
add_action( 'wp_enqueue_scripts', 'wi_scripts' );


/* -------------------------------------------------------------------- */
/* REQUIRED PLUGINS
/* -------------------------------------------------------------------- */
require(get_template_directory().'/inc/class-tgm-plugin-activation.php');	/* Plugin Required */
add_action( 'tgmpa_register', 'wi_register_required_plugins' ); // thanks https://themes.trac.wordpress.org/browser/firmasite/1.1.13/functions/class-tgm-plugin-activation.php

if ( !function_exists('wi_register_required_plugins') ) {
    function wi_register_required_plugins(){
        $plugins = array(

            /**
             * @since 2.3, we don't need to install this plugin
             *
            array(
                'name'     				=> 'Vafpress Post Formats UI', // The plugin name
                'slug'     				=> 'vafpress-post-formats-ui-develop', // The plugin slug (typically the folder name)
                'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
                'source'   				=> get_stylesheet_directory() . '/inc/vafpress-post-formats-ui-develop.zip', // The plugin source
                'force_activation' 		=> true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            ),
            */

            array(
                'name'     				=> 'Contact Form 7', // The plugin name
                'slug'     				=> 'contact-form-7', // The plugin slug (typically the folder name)
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
            ),

            array(
                'name'     				=> 'Instagram Widget', // The plugin name
                'slug'     				=> 'wp-instagram-widget', // The plugin slug (typically the folder name)
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
            ),
            
            array(
                'name'     				=> 'Display Tweets', // The plugin name
                'slug'     				=> 'display-tweets-php', // The plugin slug (typically the folder name)
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
            ),
            
            array(
                'name'     				=> 'Mailchimp for WP', // The plugin name
                'slug'     				=> 'mailchimp-for-wp', // The plugin slug (typically the folder name)
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
            ),
           
        );

        // Change this to your theme text domain, used for internationalising strings
        $theme_text_domain = 'wi';

        $config = array(
            'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'parent_slug'  => 'themes.php',            // Parent menu slug.
            'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
        );

        tgmpa( $plugins, $config );
    }
}

/* -------------------------------------------------------------------- */
/* MENU ICONS
/* -------------------------------------------------------------------- */
if (!function_exists('get_submenu_items')) {
function get_submenu_items($menu = false, $item_ID = false){

	if (!$menu) $menu = 'primary';

	$locations = get_nav_menu_locations();
	if ( !isset($locations[$menu]) || !$locations[$menu] ) return array();
	$menu_object = wp_get_nav_menu_object( $locations[$menu] );
	$menu_items = wp_get_nav_menu_items($menu_object->term_id);
	
	$return = array();
	foreach ( $menu_items as $item ):
		if ($item->menu_item_parent == $item_ID) $return[] = $item;
	endforeach;
	
	return $return;

} // get submenu items
}

/* ------------------- */

if ( !class_exists('wi_mainnav_walker') ) {
class wi_mainnav_walker extends Walker_Nav_Menu {

	function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
		global $wp_query;
        
        $menu_icon = trim(get_post_meta($item->ID,'menu-item-menu-icon',true));
		
		$class_names = '';		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';
        
        if ( in_array( 'mega',$classes ) ) {
			$subitems = get_submenu_items('primary',$item->ID);
			$classes[] = 'mega-'.count($subitems);
		}

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';	// code indent
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';		
		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $class_names .'>';		
		
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
        if ($menu_icon) {
            $icon_html = '<i class="' . esc_attr('fa fa-'.$menu_icon).'"></i>';
        } else {
            $icon_html = '';
        }
		$item_output .= $icon_html . $args->link_before . '<span>' . apply_filters( 'the_title', do_shortcode($item->title), $item->ID ) . '</span>';
        
		$item_output .= $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
}	// wi_mainnav_walker class
}	// class exists

/* -------------------------------------------------------------------- */
/* PAGINATION
/* -------------------------------------------------------------------- */
if ( !function_exists('wi_pagination') ) {
function wi_pagination( $custom_query = false ){
	global $wp_query;
	
	if ( !$custom_query ) $custom_query = $wp_query;

	$big = 999999999; // need an unlikely integer
	$pagination = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $custom_query->max_num_pages,
		'type'			=> 'plain',
		'before_page_number'	=>	'<span>',
		'after_page_number'	=>	'</span>',
		'prev_text'    => '<span>' . __('Previous','wi') . '</span>',
		'next_text'    => '<span>' . __('Next','wi') . '</span>',
	) );
	
	if ( $pagination ) {
		echo '<div class="wi-pagination"><div class="pagination-inner">';	
		echo $pagination;
		echo '<div class="clearfix"></div></div></div>';
	}
}
}
/* -------------------------------------------------------------------- */
/* CONTACT METHODs
/* -------------------------------------------------------------------- */
if (!function_exists('wi_contactmethods')){
function wi_contactmethods( $contactmethods ) {

	$contactmethods['twitter']   = __('Twitter URL','wi');
	$contactmethods['facebook-square']  = __('Facebook URL','wi');
	$contactmethods['google-plus']    = __('Google+ URL','wi');
	$contactmethods['tumblr']    = __('Tumblr URL','wi');
	$contactmethods['instagram'] = __('Instagram URL','wi');
	$contactmethods['pinterest-p'] = __('Pinterest URL','wi');
    $contactmethods['linkedin'] = __('LinkedIn URL','wi');
    $contactmethods['youtube'] = __('YouTube URL','wi');
    $contactmethods['vimeo-square'] = __('Vimeo URL','wi');
    $contactmethods['soundcloud'] = __('Soundcloud URL','wi');
    $contactmethods['flickr'] = __('Flickr URL','wi');

	return $contactmethods;
}
}
add_filter('user_contactmethods','wi_contactmethods');

/* -------------------------------------------------------------------- */
/* SOCIAL ARRAY
/* -------------------------------------------------------------------- */
if (!function_exists('wi_social_array')){
function wi_social_array() {
    return array(
		'facebook-square'      =>	__('Facebook','wi'),
		'twitter'              =>	__('Twitter','wi'),
		'google-plus'          =>	__('Google+','wi'),
		'linkedin'             =>	__('LinkedIn','wi'),
		'tumblr'               =>	__('Tumblr','wi'),
		'pinterest'            =>	__('Pinterest','wi'),
		'youtube'              =>	__('YouTube','wi'),
		'skype'                       =>	__('Skype','wi'),
		'instagram'                   =>	__('Instagram','wi'),
		'delicious'                   =>	__('Delicious','wi'),
		'digg'                        =>	__('Digg','wi'),
		'reddit'               =>	__('Reddit','wi'),
		'stumbleupon'          =>	__('StumbleUpon','wi'),
        'medium'                      =>	__('Medium','wi'),
		'vimeo-square'                =>	__('Vimeo','wi'),
		'yahoo'                       =>	__('Yahoo!','wi'),
		'flickr'                      =>	__('Flickr','wi'),
		'deviantart'                  =>	__('DeviantArt','wi'),
		'github'               =>	__('GitHub','wi'),
		'stack-overflow'              =>	__('StackOverFlow','wi'),
        'stack-exchange'              =>	__('Stack Exchange','wi'),
        'bitbucket'            =>	__('Bitbucket','wi'),
		'xing'                 =>	__('Xing','wi'),
		'foursquare'                  =>	__('Foursquare','wi'),
		'paypal'                      =>	__('Paypal','wi'),
		'yelp'                        =>	__('Yelp','wi'),
		'soundcloud'                  =>	__('SoundCloud','wi'),
		'lastfm'               =>	__('Last.fm','wi'),
        'spotify'                     =>	__('Spotify','wi'),
        'slideshare'                  =>	__('Slideshare','wi'),
		'dribbble'                    =>	__('Dribbble','wi'),
		'steam'                =>	__('Steam','wi'),
		'behance'              =>	__('Behance','wi'),
		'weibo'                       =>	__('Weibo','wi'),
		'trello'                      =>	__('Trello','wi'),
		'vk'                          =>	__('VKontakte','wi'),
		'home'                        =>	__('Homepage','wi'),
		'envelope'             =>	__('Email','wi'),
		'rss'                  =>	__('Feed','wi'),
	);
}
}

if (!function_exists('wi_social_list')){
    function wi_social_list($search = false){
        $social_array = wi_social_array();
        foreach ( $social_array as $k => $v){
            if ( get_theme_mod('wi_social_'.$k) ){?>
                <li class="li-<?php echo str_replace('','',$k);?>"><a href="<?php echo esc_url(get_theme_mod('wi_social_'.$k));?>" target="_blank" rel="alternate" title="<?php echo esc_attr($v);?>"><i class="fa fa-<?php echo esc_attr($k);?>"></i> <span><?php echo esc_html($v);?></span></a></li>
            <?php }
        }?>
        <?php if ($search){ ?>
        <li class="li-search"><a><i class="fa fa-search"></i> <span>Search</span></a></li>
        <?php }
    }
}

/* -------------------------------------------------------------------- */
/* FORMAT ICON
/* -------------------------------------------------------------------- */
if (!function_exists('wi_format_icon')) {
    function wi_format_icon($format = '') {
        if (!$format) $format = get_post_format();
        if ($format=='quote') return 'quote-left';
        elseif ($format=='gallery') return 'camera';
        elseif ($format=='audio') return 'music';
        elseif ($format=='video') return 'play';
        else return 'file-text-o';
    }
}

/* -------------------------------------------------------------------- */
/* FEATURED CLASS
/* -------------------------------------------------------------------- */
add_filter('post_class','wi_post_featured_class');
if (!function_exists('wi_post_featured_class')){
function wi_post_featured_class( $classes ) {
	if (get_post_meta(get_the_ID(),'_is_featured',true) == 'yes'):
        $classes[] = 'post-featured';
    endif;
    return $classes;
}
}
add_filter( 'the_content_more_link', 'wi_remove_more_link_scroll' );

/* -------------------------------------------------------------------- */
/* PREVENT PAGE MORE LINK SCROLL
/* -------------------------------------------------------------------- */
if (!function_exists('wi_remove_more_link_scroll')){
function wi_remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}
}
add_filter( 'the_content_more_link', 'wi_remove_more_link_scroll' );

/* -------------------------------------------------------------------- */
/* MEDIA RESULT
/* -------------------------------------------------------------------- */
if (!function_exists('wi_get_media_result')) {
function wi_get_media_result($size = 'full') {
    
	// get data
	$type = get_post_format();	
	if ($type=='audio') $media_code = trim( get_post_meta( get_the_ID(), '_format_audio_embed' , true ) );
	elseif ($type=='video') $media_code = trim( get_post_meta( get_the_ID(), '_format_video_embed' , true ) );
	else $media_code = '';
	
	// return none
	if (!$media_code) return;
	
	// iframe
	if ( stripos($media_code,'<iframe') > -1) return $media_code;

	// case url	
	// detect if self-hosted
	$url = $media_code;
	$parse = parse_url(home_url());
	$host = preg_replace('#^www\.(.+\.)#i', '$1', $parse['host']);
	$media_result = '';
	
	// not self-hosted
	if (strpos($url,$host)===false) {
		global $wp_embed;
		return $wp_embed->run_shortcode('[embed]' . $media_code . '[/embed]');
	
	// self-hosted	
	} else {
		if ($type=='video') {
			$args = array('src' => esc_url($url), 'width' => '643' );
			if ( has_post_thumbnail() ) {
				$full_src = wp_get_attachment_image_src( get_post_thumbnail_id() , $size );
				$args['poster'] = $full_src[0];
			}
			$media_result = '<div class="wi-self-hosted-sc">'.wp_video_shortcode($args).'</div>';
		} elseif ($type=='audio') {
            
            if ( has_post_thumbnail() ) {
				$full_src = wp_get_attachment_image_src( get_post_thumbnail_id() , $size );
			}
            
			$media_result = '<figure class="wi-self-hosted-audio-poster"><img src="'.esc_url($full_src[0]).'" width="'.$full_src[1].'" height="'.$full_src[2].'" alt="'.esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) .'" /></figure>' . wp_audio_shortcode(array('src' => esc_url($url)));
		}
	}
	
	return $media_result;
	
}
}

/* -------------------------------------------------------------------- */
/* FORMAT GALLERY OPTIONS
/* -------------------------------------------------------------------- */
// https://gist.github.com/ayublin/8818074
add_action( 'vp_pfui_after_gallery_meta', 'wi_add_gallery_effect_field' );
 
// handle the saving of our new field
add_action( 'admin_init' , 'wi_add_gallery_effect_field_init' );
 
function wi_add_gallery_effect_field_init() {
	$post_formats = get_theme_support('post-formats');
	if (!empty($post_formats[0]) && is_array($post_formats[0])) {
		if (in_array('gallery', $post_formats[0])) {
			add_action('save_post', 'wi_format_gallery_save_post');
		}
	}
}

if (!function_exists('wi_add_gallery_effect_field')){
function wi_add_gallery_effect_field() {
	global $post;
	$effect = get_post_meta($post->ID, '_format_gallery_effect', true);
    if ($effect!='fade' && $effect!='carousel') $effect = 'slide';
	?>
	<div class="vp-pfui-elm-block" style="padding-left:2px; margin-bottom:10px;">
        
		<label for="vp-pfui-format-gallery-type" style="padding-left:0; margin-bottom:10px;"><?php _e('Gallery Style', 'wi'); ?></label>
        
        <input type="radio" name="_format_gallery_effect" value="slide" id="slide" <?php checked( $effect, "slide" ); ?>>
		<label style="display:inline-block;padding-right: 20px;margin-bottom: 4px;padding-left:4px;" for="slide"><?php _e('Slide Slider','wi');?></label>
        
		<input type="radio" name="_format_gallery_effect" value="fade" id="fade" <?php checked( $effect, "fade" ); ?>>
		<label style="display:inline-block;padding-right:20px;margin-bottom: 4px;padding-left:4px;" for="fade"><?php _e('Fade Slider','wi');?></label>
        
        <input type="radio" name="_format_gallery_effect" value="carousel" id="carousel" <?php checked( $effect, "carousel" ); ?>>
		<label style="display:inline-block;padding-right: 20px;margin-bottom: 4px;padding-left:4px;" for="carousel"><?php _e('Carousel','wi');?></label>
        
    </div>
	<?php
}
}
if (!function_exists('wi_format_gallery_save_post')){
function wi_format_gallery_save_post($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (!defined('XMLRPC_REQUEST') && isset($_POST['_format_gallery_effect'])) {
		update_post_meta($post_id, '_format_gallery_effect', $_POST['_format_gallery_effect']);
	}
}
}

/* -------------------------------------------------------------------- */
/* EXCLUDE PAGES FROM SEARCH
/* -------------------------------------------------------------------- */
if (!function_exists('wi_search_filter')) {
function wi_search_filter($query) {
    if (get_theme_mod('wi_exclude_pages_from_search')){
        if ($query->is_search) {
            $query->set('post_type', 'post');
        }
    }
    return $query;
    }
}
add_filter('pre_get_posts','wi_search_filter');

/* -------------------------------------------------------------------- */
/* IGNORE STICKY POSTS
/* -------------------------------------------------------------------- */
if (!function_exists('wi_ignore_sticky')) {
function wi_ignore_sticky($query) {
    if (is_home() && $query->is_main_query())  {
        $query->set('ignore_sticky_posts', true);  
        $query->set('post__not_in', get_option('sticky_posts'));
    }
    
    return $query;
}
}
add_filter('pre_get_posts','wi_ignore_sticky');

/* -------------------------------------------------------------------- */
/* Add a thumbnail column in edit.php
/* Source: http://wordpress.org/support/topic/adding-custum-post-type-thumbnail-to-the-edit-screen
/* -------------------------------------------------------------------- */
add_action( 'manage_posts_custom_column', 'wi_add_thumbnail_value_editscreen', 10, 2 );
add_filter( 'manage_edit-post_columns', 'wi_columns_filter', 10, 1 );

if ( !function_exists('wi_columns_filter') ) {
function wi_columns_filter( $columns ) {
 	$column_thumbnail = array( 'thumbnail' => __('Thumbnail','wi') );
	$columns = array_slice( $columns, 0, 1, true ) + $column_thumbnail + array_slice( $columns, 1, NULL, true );
	return $columns;
}
}
if ( !function_exists('wi_add_thumbnail_value_editscreen') ) {
function wi_add_thumbnail_value_editscreen($column_name, $post_id) {

	$width = (int) 50;
	$height = (int) 50;

	if ( 'thumbnail' == $column_name ) {
		// thumbnail of WP 2.9
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		// image from gallery
		$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
		if ($thumbnail_id)
			$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
		elseif ($attachments) {
			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
			}
		}
		if ( isset($thumb) && $thumb ) {
			echo $thumb;
		} else {
			echo '<em>' . __('None','wi') . '</em>';
		}
	}
}
}


/* -------------------------------------------------------------------- */
/* IMAGE QUALITY
/* -------------------------------------------------------------------- */
add_filter('jpeg_quality', 'wi_image_full_quality');
add_filter('wp_editor_set_quality', 'wi_image_full_quality');
if (!function_exists('wi_image_full_quality')) {
function wi_image_full_quality($quality) {
    return 100;
}
}

/* -------------------------------------------------------------------- */
/* BACK TO TOP
/* -------------------------------------------------------------------- */
add_action('wp_footer','wi_backtotop');
if ( !function_exists('wi_backtotop') ) {
function wi_backtotop() {
    if (!get_theme_mod('wi_disable_backtotop')){
    ?>
    <div id="backtotop" class="backtotop">
        <span class="go"><?php _e('Go to','wi');?></span>
        <span class="top"><?php _e('Top','wi');?></span>
    </div><!-- #backtotop -->
<?php 
    } // endif
}   
}

/* -------------------------------------------------------------------- */
/* EXCERPT
/* -------------------------------------------------------------------- */
/* Remove the ugly bracket [...] in the excerpt */
add_filter('excerpt_more','wi_remove_bracket_in_excerpt');
if ( !function_exists('wi_remove_bracket_in_excerpt') ) {
function wi_remove_bracket_in_excerpt($excerpt){
	return '&hellip;';
}
}
	/* More length */
if ( !function_exists('wi_custom_excerpt_length') ) {
function wi_custom_excerpt_length( $length ) {
	$excerpt_length = absint(get_theme_mod('wi_excerpt_length')) ? absint(get_theme_mod('wi_excerpt_length')) : 55;
    return $excerpt_length;
}
}
add_filter( 'excerpt_length', 'wi_custom_excerpt_length', 999 );

/* -------------------------------------------------------------------- */
/* ICONS
/* -------------------------------------------------------------------- */
add_action('wp_head','wi_icons');
if (!function_exists('wi_icons')) {
    function wi_icons(){
        $sizes = array(57, 72, 76, 114, 144, 152, 180);
        
        if( get_theme_mod('wi_favicon') ) { ?>
            <link rel="shortcut icon" href="<?php echo get_theme_mod('wi_favicon');?>">
        <?php }
        foreach ( $sizes as $size ){ 
            if( get_theme_mod("wi_apple_$size") ) { ?>
                <link href="<?php echo get_theme_mod("wi_apple_$size"); ?>" sizes="<?php echo esc_attr(printf('%sx%s',$size,$size)); ?>" rel="apple-touch-icon-precomposed">
            <?php }
        } // foreach
        
    }
}

/* -------------------------------------------------------------------- */
/* HEADER FOOTER CODE
/* -------------------------------------------------------------------- */
/* Header Code */
add_action('wp_head','wi_add_head_code');
if ( !function_exists('wi_add_head_code') ) {
function wi_add_head_code(){
	echo get_theme_mod('wi_header_code');
}
}

/* -------------------------------------------------------------------- */
/* CATEGORY FIELD
/* -------------------------------------------------------------------- */
// Add term page
if (!function_exists('wi_taxonomy_add_new_meta_field')) {
function wi_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="term_meta[layout]"><?php _e( 'Example meta field', 'wi' ); ?></label>
		<input type="text" name="term_meta[layout]" id="term_meta[layout]" value="">
		<p class="description"><?php _e( 'Enter a value for this field','wi' ); ?></p>
	</div>
<?php
}
}
add_action( 'category_add_form_fields', 'wi_taxonomy_add_new_meta_field', 10, 2 );
add_action( 'post_tag_add_form_fields', 'wi_taxonomy_add_new_meta_field', 10, 2 );

// Edit term page
if (!function_exists('wi_taxonomy_edit_meta_field')) {
function wi_taxonomy_edit_meta_field($term) {
    
    $layout_arr = wi_layout_array();
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" );
    $current_layout = isset($term_meta['layout']) ? $term_meta['layout'] : '';?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[layout]"><?php _e( 'Select layout', 'wi' ); ?></label></th>
		<td>
            <select name="term_meta[layout]" id="term_meta[layout]">
                <?php foreach ($layout_arr as $lay => $out): ?>
                <option value="<?php echo esc_attr($lay);?>" <?php selected( $lay, $current_layout); ?>><?php echo esc_html($out);?></option>
                <?php endforeach; ?>
            </select>
			<p class="description"><?php _e( 'Select layout for displaying posts on this category','wi' ); ?></p>
		</td>
	</tr>
<?php
}
}
add_action( 'category_edit_form_fields', 'wi_taxonomy_edit_meta_field', 10, 2 );
add_action( 'post_tag_edit_form_fields', 'wi_taxonomy_edit_meta_field', 10, 2 );


// Save extra taxonomy fields callback function.
if (!function_exists('save_taxonomy_custom_meta')) {
function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
}
add_action( 'edited_category', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_category', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'edited_post_tag', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_post_tag', 'save_taxonomy_custom_meta', 10, 2 );


/* -------------------------------------------------------------------- */
/* SUBWORD
/* -------------------------------------------------------------------- */
if ( !function_exists('wi_subword') ) {
function wi_subword($str = '',$int = 0, $length = NULL){
	if (!$str) return;
	$words = explode(" ",$str); if (!is_array($words)) return;
	$return = array_slice($words,$int,$length); if (!is_array($return)) return;
	return implode(" ",$return);
}
}


/* -------------------------------------------------------------------- */
/* GET THUMBNAIL WHEN HAS NO THUMBNAIL
 * thumbnail
 * class (grid, masonry...)
 * link (link to single post
 * placeholder image when there's no image
/* -------------------------------------------------------------------- */
if ( !function_exists('wi_display_thumbnail') ) {
function wi_display_thumbnail($thumbnail = 'thumbnail', $class = '', $link = true, $placeholder = false){
    if (has_post_thumbnail()) {?>
        <figure class="<?php echo esc_attr($class);?>">
            <?php if ($link) echo '<a href="'.get_permalink().'">';?>
                <?php the_post_thumbnail($thumbnail); ?>
            
                <?php echo get_post_format() ? '<span class="format-sign sign-' . get_post_format() . '"><i class="fa fa-'.wi_format_icon().'"></i></span>' : ''; ?>
            
            <?php if ($link) echo '</a>';?>
        </figure>
    <?php
                              } 
    elseif ( $attachments = get_posts( array(
    'post_type' => 'attachment',
    'posts_per_page' => 1,
    'post_parent' => get_the_ID(),
    ) ) ) {
        $image = wp_get_attachment_image_src($attachments[0]->ID, $thumbnail);?>

        <figure class="<?php echo esc_attr($class . ' thumbnail-type-secondary');?>">
            <?php if ($link) echo '<a href="'.get_permalink().'">';?>

                <img src="<?php echo esc_url($image[0]);?>" width="<?php echo esc_attr($image[1]);?>" height="<?php echo esc_attr($image[2]);?>" alt="<?php echo esc_attr(get_post_meta($attachments[0]->ID, '_wp_attachment_image_alt', true));?>" />
            
                <?php echo get_post_format() ? '<span class="format-sign sign-'.get_post_format().'"><i class="fa fa-'.wi_format_icon().'"></i></span>' : ''; ?>
            
            <?php if ($link) echo '</a>';?>
        </figure>
    <?php
    } elseif ($placeholder) {
        ?>
        <figure class="<?php echo esc_attr($class . ' thumbnail-pseudo');?>">
            <?php if ($link) echo '<a href="'.get_permalink().'">';?>
        
                <img src="<?php echo get_template_directory_uri();?>/images/thumbnail-medium.png" width="400" height="320" alt="Placeholder" />
                <span class="format-indicator"><i class="fa fa-<?php echo wi_format_icon(get_post_format());?>"></i></span>
            
            <?php if ($link) echo '</a>';?>
        </figure>
    <?php
    }
}
}

/* -------------------------------------------------------------------- */
/* SHARE BUTTONS
/* -------------------------------------------------------------------- */
if ( !function_exists('wi_share') ) {
function wi_share($comment = false){
    global $wp_query;
	if (in_the_loop() || is_single() || is_page()) {$url = get_permalink();}
    elseif (is_category() || is_tag()) {
        $url = get_term_link(get_queried_object());
    } else {
        return;
    }
    ?>
    <ul>
        <?php if ($comment && !get_theme_mod('wi_disable_blog_comment')):?>
        <li class="li-comment">
            <?php
        comments_popup_link( 
            '<i class="fa fa-comment"></i><span>' . __('No comments','wi') . '</span>', 
            '<i class="fa fa-comment"></i><span>' . __('1 comment','wi') . '</span>', 
            '<i class="fa fa-comment"></i><span>' . __('% comments','wi') . '</span>', 
            '',
            '<i class="fa fa-comment"></i><span>' . __('Off','wi') . '</span>'
        ); ?>
        </li>
        <?php endif; ?>
        <li class="li-facebook"><a data-href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url);?>&p[images][0]=<?php echo wp_get_attachment_url(get_post_thumbnail_id());?>" title="<?php _e('Facebook','wi');?>" class="share"><i class="fa fa-facebook"></i><span><?php _e('Facebook','wi');?></span></a></li>
        <li class="li-twitter"><a data-href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url);?>&amp;text=<?php echo urlencode( html_entity_decode( get_the_title() ) );?>" title="<?php _e('Twitter','wi');?>" class="share"><i class="fa fa-twitter"></i><span><?php _e('Twitter','wi');?></span></a></li>
        <li class="li-google-plus"><a data-href="https://plus.google.com/share?url=<?php echo urlencode($url);?>" title="<?php _e('Google+','wi');?>" class="share"><i class="fa fa-google-plus"></i><span><?php _e('Google','wi');?></span></a></li>
        <li class="li-pinterest"><a href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());" title="<?php _e('Pinterest','wi');?>"><i class="fa fa-pinterest"></i><span><?php _e('Pinterest','wi');?></span></a></li>
    </ul>
<?php  
}
}

/* -------------------------------------------------------------------- */
/* QUICK TRANSLATION
/* -------------------------------------------------------------------- */
add_filter('gettext','wi_quick_translate',20,3);
if (!function_exists('wi_quick_translate')){
function wi_quick_translate($string,$text,$domain) {
    
    $options = array(
            'more_link'             =>  'Keep Reading',
            'previous'               =>  'Previous',
            'next'                  =>  'Next',
            'next_story'            =>  'Next Story',
            'previous_story'        =>  'Previous Story',
            'search'                =>  'Search...',
            'category'              =>  'in %s',
            'author'                =>  'by %s',
            'date'                  =>  'Published on %s',
            'latest_posts'          =>  'Latest posts',
            'viewall'                   =>  'View all',
            'related'               =>  'You might be interested in',
            'latest'                   =>  'Latest from %s',
            'go'                    =>  'Go to',
            'top'                   =>  'Top',
        );
    
    foreach ($options as $k => $v) {
        if ($string==$v && get_theme_mod('wi_translate_'.$k)!='') $string = get_theme_mod('wi_translate_'.$k);
    }
    
    return $string;
    
}
}

/* -------------------------------------------------------------------- */
/* FONT SIZE MECHANISM
/* -------------------------------------------------------------------- */
if (!function_exists('wi_font_size_array')) {
function wi_font_size_array() {
    $size_arr = array();
    
    $size_arr['body'] = array(
        'prop'      =>  'body',
        'std'       =>  16,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .875,
        'iphone2'   =>  .875,
    );
    
    $size_arr['nav'] = array(
        'prop'      =>  '#wi-mainnav .menu > ul > li > a',
        'std'       =>  26,
        'ipad1'     =>  1,
        'ipad2'     =>  .75,
        'iphone1'   =>  .75,
        'iphone2'   =>  .75,
    );
    
    $size_arr['nav-sub'] = array(
        'prop'      =>  '#wi-mainnav .menu > ul > li > ul > li > a',
        'std'       =>  16,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  1,
    );
    
    $size_arr['section-heading'] = array(
        'prop'      =>  '.section-heading',
        'std'       =>  80,
        'ipad1'     =>  1,
        'ipad2'     =>  .7,
        'iphone1'   =>  .5,
        'iphone2'   =>  .325,
    );
    
    $size_arr['slider-title'] = array(
        'prop'      =>  '.slider-title',
        'std'       =>  60,
        'ipad1'     =>  1,
        'ipad2'     =>  .8,
        'iphone1'   =>  .6,
        'iphone2'   =>  .5,
    );
    
    $size_arr['big-title'] = array(
        'prop'      =>  '.big-title',
        'std'       =>  16,
        'ipad1'     =>  1,
        'ipad2'     =>  .8,
        'iphone1'   =>  .5,
        'iphone2'   =>  .4,
    );
    
    $size_arr['post-title'] = array(
        'prop'      =>  '.post-title',
        'std'       =>  52,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .6,
        'iphone2'   =>  .46,
    );
    
    $size_arr['grid-title'] = array(
        'prop'      =>  '.grid-title',
        'std'       =>  26,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  .92,
    );
    
    $size_arr['masonry-title'] = array(
        'prop'      =>  '.masonry-title',
        'std'       =>  32,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  .75,
    );
    
    $size_arr['newspaper-title'] = array(
        'prop'      =>  '.newspaper-title',
        'std'       =>  36,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  .666,
    );
    
    $size_arr['list-title'] = array(
        'prop'      =>  '.list-title',
        'std'       =>  36,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .611,
        'iphone2'   =>  .611,
    );
    
    $size_arr['page-title'] = array(
        'prop'      =>  '.page-title',
        'std'       =>  70,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .6,
        'iphone2'   =>  .6,
    );
    
    $size_arr['archive-title'] = array(
        'prop'      =>  '.archive-title',
        'std'       =>  80,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .6,
        'iphone2'   =>  .4,
    );
    
    $size_arr['widget-title'] = array(
        'prop'      =>  '.widget-title',
        'std'       =>  12,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  1,
    );
    
    $size_arr['h1'] = array(
        'prop'      =>  'h1',
        'std'       =>  40,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h2'] = array(
        'prop'      =>  'h2',
        'std'       =>  32,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h3'] = array(
        'prop'      =>  'h3',
        'std'       =>  26,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h4'] = array(
        'prop'      =>  'h4',
        'std'       =>  22,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h5'] = array(
        'prop'      =>  'h5',
        'std'       =>  18,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h6'] = array(
        'prop'      =>  'h6',
        'std'       =>  14,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    return $size_arr;
    
}
}
?>
<?php
add_action('wp_head','wi_facebook_share_picture');
if (!function_exists('wi_facebook_share_picture')) {
function wi_facebook_share_picture(){
    if (is_singular()) {
        global $post;
        if (has_post_thumbnail()) {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
?>
<meta property="og:image" content="<?php echo esc_url($thumbnail[0]);?>"/>
<meta property="og:image:secure_url" content="<?php echo esc_url($thumbnail[0]);?>" />
<?php }
    }
}
}

/* -------------------------------------------------------------------- */
/* CUSTOM NUMBER OF POSTS TO DISPLAY ON HOMEPAGE (NOT ARCHIVE)
/* -------------------------------------------------------------------- */
if ( !function_exists( 'wi_limit_posts_per_page' ) ) :
function wi_limit_posts_per_page(&$query) {
    
    $number = trim( get_theme_mod( 'wi_home_number' ) );
    
    if ( ! empty( $number) && !is_admin() && $query->is_main_query() && is_home() ) {
        $query->set( 'posts_per_page', $number );
    }
}
endif;
add_action('pre_get_posts', 'wi_limit_posts_per_page');


if ( ! function_exists( 'wi_pre_get_posts' ) ) :
/**
 * Advanced Query Options
 *
 * Offset and Exclude Categories
 * 
 * @since 2.3
 */
function wi_pre_get_posts( $query ) {
    
    if ( ! is_admin() && $query->is_home() && $query->is_main_query() ) {
        
        // Exclude Categories
        $exclude_categories = get_theme_mod( 'wi_exclude_categories' );
        if ( is_string( $exclude_categories ) ) $exclude_categories = explode( ',', trim($exclude_categories) );
        if ( ! empty($exclude_categories) ) {
            $query->set( 'category__not_in', $exclude_categories );
        }
        
        // Offset should be available when infinite scroll module not enabled
        $offset = absint( get_theme_mod( 'wi_offset' ) );
        if ( $offset > 0 ) {

            $home_ppp = trim( get_theme_mod( 'wi_home_number' ) );
    
            if ( ! empty( $home_ppp) ) $ppp = $home_ppp;
            else $ppp = get_option( 'posts_per_page' );

            // Detect and handle pagination...
            if ( $query->is_paged ) {

                //Manually determine page query offset (offset + current page (minus one) x posts per page)
                $page_offset = $offset + ( ($query->query_vars['paged']-1) * $ppp );

                //Apply adjust page offset
                $query->set('offset', $page_offset );

            }
            else {

                //This is the first page. Just use the offset...
                $query->set('offset',$offset);

            }

        }
        
	}
    
}
endif;

add_action( 'pre_get_posts', 'wi_pre_get_posts', 300 );

if ( ! function_exists( 'wi_adjust_offset_pagination' ) ) :
/**
 * Adjusts offset pagination
 *
 * @since 2.3
 */
function wi_adjust_offset_pagination($found_posts, $query) {
    
    // Offset should be available when infinite scroll module not enabled
    $offset = absint( get_theme_mod( 'wi_offset' ) );
    if ( $offset > 0 && $query->is_home() && $query->is_main_query() && ! is_admin() ) {

        //Reduce WordPress's found_posts count by the offset... 
        return $found_posts - $offset;

    }
    
    return $found_posts;
    
}
endif;

add_filter( 'found_posts', 'wi_adjust_offset_pagination', 1, 2 );

// Reduce amount of meta value saved to database
// @since 2.4
add_filter( 'baw_count_views_timings', 'wi_baw_count_views_timings' );
function wi_baw_count_views_timings( $timings ) {
    return array( 'all'=>'', 'month'=>'Ym', 'year'=>'Y', 'week' => 'YW' );
}

// Delete all day/week meta keys to save db sizes
// Since 2.4
// Please remove below comment if you wish to delete all day & week meta views
//
// add_action( 'admin_init', 'wi_delete_date_view_keys' );
function wi_delete_date_view_keys() {
    
    if ( get_option( 'wi_delete_date_view_keys' ) ) return;

    $all_posts = new WP_Query( array(
        'posts_per_page' => -1,
        'post_type' => 'any',
    ) );
    
    $done = false;
    
    global $post;
    
    if ( $all_posts->have_posts() ) : while ( $all_posts->have_posts() ) : $all_posts->the_post();
    
        global $wpdb, $timings;
        $wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE post_id = ' . (int)$post->ID . ' AND meta_key LIKE "_count-views_day%"' );
        $wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE post_id = ' . (int)$post->ID . ' AND meta_key LIKE "_count-views_week%"' );
    
    endwhile;
    endif;
    
    wp_reset_query();
    
    if ( ! update_option( 'wi_delete_date_view_keys', true ) ) add_option( 'wi_delete_date_view_keys', true );

}

/**
 * Post Format Link
 *
 * @since 2.4
 */
add_filter( 'post_link', 'wi_post_format_link', 10, 3 );
function wi_post_format_link( $url, $post, $leavename=false ) {
    
    if ( get_post_format( $post ) == 'link' ) {
        $source = trim( get_post_meta( $post->ID, '_format_link_url', true ) );
        if ( $source ) return esc_url( $source );
	}
	return $url;

}

if ( ! function_exists( 'wi_single_ad' ) ) :
/**
 * Single Ad
 *
 * @since 2.5
 */
function wi_single_ad( $pos = 'before' ) {

    if ( 'after' != $pos ) $pos = 'before';
    $code = trim( get_theme_mod( 'wi_single_' . $pos . '_code' ) );
    if ( $code ) { ?>
    <div class="single-ad ad-code ad-<?php echo esc_attr( $pos ); ?>">
        <?php echo do_shortcode( $code ); ?>
    </div><!-- .single-ad -->
<?php } elseif ( $banner = get_theme_mod( 'wi_single_' . $pos . '_banner' ) ) {
        $url = trim( get_theme_mod( 'wi_single_' . $pos . '_banner_url' ) );
    if ( $url ) {
        $open = '<a href="' . esc_url( $url ) . '" target="_blank">';
        $close = '</a>';
    } else {
        $open = $close = '';
    }
?>
    
    <div class="single-ad ad-code ad-<?php echo esc_attr( $pos ); ?>">
        <?php echo $open; ?>
        <img src="<?php echo esc_url( $banner ); ?>" alt="<?php echo esc_html__( 'Banner', 'wi' ); ?>" />
        <?php echo $close; ?>
    </div>
<?php
    }
}
endif;

add_action( 'wp_footer', 'wi_single_sidedock' );
/**
 * Single Side Dock Post
 *
 * @since 2.5
 */
function wi_single_sidedock() {

    if ( get_theme_mod( 'wi_disable_side_dock' ) || ! is_single() ) return;
    
    global $post;
    $current_ID = $post->ID;
    $tags = wp_get_post_tags( $current_ID, array( 'fields' => 'ids' ) );
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 2,

        'ignore_sticky_posts'   =>  true,
        'no_found_rows' => true,
        'cache_results' => false,
        'post__not_in' => array( $current_ID ),
    );
    if ( empty( $tags ) ) return;
    
    $args[ 'tag__in' ] = $tags;

    $related_posts = get_posts( $args );
    if ( $related_posts ): $count = 0; ?>

<aside id="content-dock">
    
    <h3 class="dock-title"><?php _e('You might be interested in','wi');?></h3>
    
    <div class="dock-posts">
        
        <?php foreach ( $related_posts as $post ): setup_postdata($post); $count++;?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('post-dock'); ?>>
            
            <div class="post-inner">

                <?php wi_display_thumbnail('thumbnail','post-dock-thumbnail',true,true);?>

                <section class="post-dock-body">

                    <div class="post-dock-content">

                        <header class="post-dock-header">

                            <h3 class="post-dock-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>

                        </header><!-- .post-dock-header -->
                        
                        <div class="post-dock-excerpt">
                            <p><?php echo wi_subword(get_the_excerpt(),0,10); ?></p>
                        </div><!-- .post-dock-excerpt -->

                        <div class="clearfix"></div>

                    </div><!-- .post-dock-content -->

                </section><!-- .post-dock-body -->

                <div class="clearfix"></div>

            </div><!-- .post -->
            
        </article><!-- .post-dock -->
    
    <?php endforeach; ?>
        
    </div><!-- .dock-posts -->

    <button class="close">
        <i class="fa fa-close"></i>
    </button>

</aside><!-- #content-dock -->
    
<?php
        
    endif; // single related
}