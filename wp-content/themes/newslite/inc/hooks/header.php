<?php
if ( ! function_exists( 'newslite_set_global' ) ) :
/**
 * Setting global values for all saved customizer values
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
function newslite_set_global() {
    /*Getting saved values start*/
    $GLOBALS['newslite_customizer_all_values'] = newslite_get_all_options(1);
}
endif;
add_action( 'newslite_action_before_head', 'newslite_set_global', 0 );

if ( ! function_exists( 'newslite_doctype' ) ) :
/**
 * Doctype Declaration
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
function newslite_doctype() {
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
<?php
}
endif;
add_action( 'newslite_action_before_head', 'newslite_doctype', 10 );

if ( ! function_exists( 'newslite_before_wp_head' ) ) :
/**
 * Before wp head codes
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
function newslite_before_wp_head() {
    ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'/>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php
}
endif;
add_action( 'newslite_action_before_wp_head', 'newslite_before_wp_head', 10 );

if( ! function_exists( 'newslite_default_layout' ) ) :
    /**
     * newslite default layout function
     *
     * @since  newslite 1.0.0
     *
     * @param int
     * @return string
     */
    function newslite_default_layout( $post_id = null ){

        global $newslite_customizer_all_values,$post;
        $newslite_default_layout = $newslite_customizer_all_values['newslite-default-layout'];
        if( is_home()){
            $post_id = get_option( 'page_for_posts' );
        }
        if( null == $post_id && isset ( $post->ID ) ){
            $post_id = $post->ID;
        }
        $newslite_default_layout_meta = get_post_meta( $post_id, 'newslite-default-layout', true );

        if( false != $newslite_default_layout_meta ) {
            $newslite_default_layout = $newslite_default_layout_meta;
        }
        return $newslite_default_layout;
    }
endif;

if ( ! function_exists( 'newslite_body_class' ) ) :
/**
 * add body class
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
function newslite_body_class( $newslite_body_classes ) {
  global $newslite_customizer_all_values;
  $newslite_alternate_layout = '';
    if ($newslite_customizer_all_values['newslite-alternate-layout'] == 1) {
      $newslite_alternate_layout = " alternate";
    } else {
      $newslite_alternate_layout = " non-alternate";
    }

    if(!is_front_page() || ( is_front_page())){
        $newslite_default_layout = newslite_default_layout();
        if( !empty( $newslite_default_layout ) ){
            if( 'left-sidebar' == $newslite_default_layout ){
                $newslite_body_classes[] = 'evision-left-sidebar'. $newslite_alternate_layout;
            }
            elseif( 'right-sidebar' == $newslite_default_layout ){
                $newslite_body_classes[] = 'evision-right-sidebar'. $newslite_alternate_layout;
            }
            elseif( 'both-sidebar' == $newslite_default_layout ){
                $newslite_body_classes[] = 'evision-both-sidebar' . $newslite_alternate_layout;
            }
            elseif( 'no-sidebar' == $newslite_default_layout ){
                $newslite_body_classes[] = 'evision-no-sidebar'. $newslite_alternate_layout;
            }
            else{
                $newslite_body_classes[] = 'evision-right-sidebar'. $newslite_alternate_layout;
            }
        }
        else{
            $newslite_body_classes[] = 'evision-right-sidebar'. $newslite_alternate_layout;
        }
    }
    return $newslite_body_classes;

}
endif;
add_filter( 'body_class', 'newslite_body_class', 10, 1);

if ( ! function_exists( 'newslite_before_page_start' ) ) :
/**
 * intro loader
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
function newslite_before_page_start() {
    global $newslite_customizer_all_values;
    /*intro loader*/
}
endif;
add_action( 'newslite_action_before', 'newslite_before_page_start', 10 );

if ( ! function_exists( 'newslite_page_start' ) ) :
/**
 * page start
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
function newslite_page_start() {
?>
    <div id="page" class="site clearfix container">
<?php
}
endif;
add_action( 'newslite_action_before', 'newslite_page_start', 15 );

if ( ! function_exists( 'newslite_skip_to_content' ) ) :
/**
 * Skip to content
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
function newslite_skip_to_content() {
    ?>
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'newslite' ); ?></a>
<?php
}
endif;
add_action( 'newslite_action_before_header', 'newslite_skip_to_content', 10 );

if ( ! function_exists( 'newslite_header' ) ) :
/**
 * Main header
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
function newslite_header() {
    global $newslite_customizer_all_values;
    ?>   
        <div id="preloader">
          <div id="status">&nbsp;</div>
        </div>
        <div class="date-social container "> <!-- newslite top bar -->     
            <?php if (1 == $newslite_customizer_all_values['newslite-header-enable-date']) { ?>
                <div class="timer col-md-2 col-xs-12 col-sm-3 pad0r">
                    <?php echo date_i18n( get_option( 'date_format' ), strtotime( 'l, M j, Y' ) ); ?>
                </div>
            <?php } ?>  <!-- news lite current date -->

        <div class="col-md-5 col-sm-4 newsticker">
           <?php if (1 == $newslite_customizer_all_values['newslite-header-enable-tinker']) { ?>
           <header class="wrapper top-header">
              <div class="container">
                 <div class="wrap-inner">
                    <div class="row">
                       <div class="top-header-left">
                          <div class="noticebar">
                             <?php if (!empty($newslite_customizer_all_values['newslite-header-tinker-title'])) { ?>
                             <span class="notice-title"><?php echo esc_html($newslite_customizer_all_values['newslite-header-tinker-title']); ?></span>
                             <?php } ?>
                             <div class="latest-news">
                                <?php $newslite_tinker_args = array(
                                  'post_type' => 'post',
                                  'posts_per_page' => 6,
                                  'ignore_sticky_posts' => 1,
                                  );
                                  $newslite_home_tinker_post_query = new WP_Query($newslite_tinker_args);
                                  if ($newslite_home_tinker_post_query->have_posts()) :
                                  while ($newslite_home_tinker_post_query->have_posts()) : $newslite_home_tinker_post_query->the_post(); ?>
                                    <div  class="slide-item">
                                      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>     
                                <?php endwhile; 
                                   wp_reset_postdata();
                                   endif; ?>                       
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </header>
           <?php } ?>  
        </div> <!-- news lite news ticker -->

        <div class="col-md-5 col-xs-12 col-sm-5 newslite-top-menu pad0r">
        <i class="fa fa-bars top-nav-mobile"></i>
          <?php
             wp_nav_menu( array(
                 'theme_location' => 'secondary',
                 'menu_id'        => 'secondary-menu',
                 'menu_class'     => 'secondary-menu',
                 'fallback_cb'    => false,
                 'depth'          => 1,
             ) );
             ?>
        </div>
        <!-- newslite top menu -->        
    </div>
<header id="masthead" class="wrapper wrap-head site-header">
   <div class="wrapper wrapper-site-identity">
      <div class="container">
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 pad0r">
               <div class="site-branding">
                  <?php
                     if ( is_front_page() && is_home() ) : ?>
                  <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                  <?php else : ?>
                  <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                  <?php
                     endif;
                     
                     $description = get_bloginfo( 'description', 'display' );
                     if ( $description || is_customize_preview() ) : ?>
                  <p class="site-description"><?php echo esc_html($description); /* WPCS: xss ok. */ ?></p>
                  <?php
                     endif; ?>
                  <?php newslite_the_custom_logo(); ?>
               </div>
               <!-- .site-branding -->
            </div>
            <?php if (!empty($newslite_customizer_all_values['newslite-header-add'])) { ?>
            <div class="col-xs-12 col-sm-12 col-md-8 pad0r">
               <div class="ads-section header-right">
                  <a href= "<?php echo esc_url($newslite_customizer_all_values['newslite-home-header-add-link'] ); ?>">
                  <img src="<?php echo esc_url($newslite_customizer_all_values['newslite-header-add']);?>">
                  </a>
               </div>
            </div>
            <?php } ?>
         </div>
      </div>
   </div>
</header>
<!-- #masthead -->
<?php 
   }
   endif;
   add_action( 'newslite_action_header', 'newslite_header', 10 );
   
   
   if ( ! function_exists( 'newslite_navigation_page_start' ) ) :
   /**
    * Skip to content
    *
    * @since newslite 1.0.0
    *
    * @param null
    * @return null
    *
    */
   function newslite_navigation_page_start() {
       global $newslite_customizer_all_values; 
       ?>
<nav class="wrapper wrap-nav">
   <div class="container">
      <div class="wrap-inner">
         <div class="sec-menu">
            <nav id="sec-site-navigation" class="main-navigation sec-main-navigation" role="navigation" aria-label="secondary-menu">
               <?php
                  wp_nav_menu( array(
                      'theme_location' => 'primary',
                      'menu_id'        => 'primary-menu',
                      'menu_class'     => 'primary-menu',
                  ) );
                  ?>
            </nav>
            <!-- #site-navigation -->
            <div class="nav-holder">
               <button id="sec-menu-toggle" class="menu-toggle" aria-controls="secondary-menu" aria-expanded="false"><span class="fa fa-bars"></span></button>
               <div id="sec-site-header-menu" class="site-header-menu">
                  <div class="container">
                     <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                           <button id="mobile-menu-toggle-close" class="menu-toggle" aria-controls="secondary-menu"><span class="fa fa-close"></span></button>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 newslite-main-nav">
                           <nav id="sec-site-navigation-mobile" class="main-navigation sec-main-navigation" role="navigation" aria-label="secondary-menu">
                              <?php
                                 wp_nav_menu( array(
                                     'theme_location' => 'primary',
                                     'menu_id'        => 'primary-menu-mobile',
                                     'menu_class'     => 'primary-menu',
                                 ) );
                                 ?>
                           </nav>
                           <!-- #site-navigation -->
                        </div>
                     </div>
                  </div>
               </div>
               <!-- site-header-menu -->
            </div>
         </div>
         <?php if ((1 == $newslite_customizer_all_values['newslite-header-enable-home-link']) || (1 == $newslite_customizer_all_values['newslite-header-enable-search'])) { ?>
         <div class="nav-buttons col-md-1">
            <?php if (1 == $newslite_customizer_all_values['newslite-header-enable-search']) { ?>
            <div class="button-list">
               <div class="search-holder">
                  <a class="button-search button-outline" href="#">
                  <i class="fa fa-search"></i>
                  </a>                                
               </div>
            </div>
            <?php } ?>
         </div>
         <?php } ?>                              
      </div>
      <div class="search-form-nav" id="top-search">
         <?php get_search_form();?>
      </div>
   </div>
</nav>
<section class="wrapper">
<div id="content" class="site-content">
<?php
}
endif;
add_action( 'newslite_action_nav_page_start', 'newslite_navigation_page_start', 10 );


if( ! function_exists( 'newslite_main_slider_setion' ) ) :
/**
 * Main slider
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
    function newslite_main_slider_setion(){
        if (  is_front_page() && !is_home() ) {
            do_action('newslite_action_main_slider');
        } else {
            /**
             * newslite_action_after_title hook
             * @since newslite 1.0.0
             *
             * @hooked null
             */
            do_action( 'newslite_action_after_title' );
        }
    }
endif;
add_action( 'newslite_action_on_header', 'newslite_main_slider_setion', 10 );


if( ! function_exists( 'newslite_add_breadcrumb' ) ) :

/**
 * Breadcrumb
 *
 * @since newslite 1.0.0
 *
 * @param null
 * @return null
 *
 */
    function newslite_add_breadcrumb(){
        global $newslite_customizer_all_values;
        // Bail if Breadcrumb disabled
        $breadcrumb_enable_breadcrumb = $newslite_customizer_all_values['newslite-enable-breadcrumb' ];
        if ( 1 != $breadcrumb_enable_breadcrumb ) {
            return;
        }
        // Bail if Home Page
        if ( is_front_page() || is_home() ) {
            return;
        }
        echo '<div id="breadcrumb" class="wrapper wrap-breadcrumb"><div class="container">';
         newslite_simple_breadcrumb();
        echo '</div><!-- .container --></div><!-- #breadcrumb -->';
        return;
    }
endif;
add_action( 'newslite_action_after_title', 'newslite_add_breadcrumb', 10 );


