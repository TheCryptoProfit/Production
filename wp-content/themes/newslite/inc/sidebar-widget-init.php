<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function newslite_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'newslite' ),
        'id'            => 'sidebar-1',
        'description'   => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    $newslite_get_all_options = newslite_get_all_options(1);
    $newslite_footer_widgets_number = $newslite_get_all_options['newslite-footer-sidebar-number'];

    if( $newslite_footer_widgets_number > 0 ){
        register_sidebar(array(
            'name' => __('Footer Column One', 'newslite'),
            'id' => 'footer-col-one',
            'description' => __('Displays items on footer section.','newslite'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title'  => '<h1 class="widget-title">',
            'after_title'   => '</h1>',
        ));
        if( $newslite_footer_widgets_number > 1 ){
            register_sidebar(array(
                'name' => __('Footer Column Two', 'newslite'),
                'id' => 'footer-col-two',
                'description' => __('Displays items on footer section.','newslite'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget' => '</aside>',
                'before_title'  => '<h1 class="widget-title">',
                'after_title'   => '</h1>',
            ));
        }
        if( $newslite_footer_widgets_number > 2 ){
            register_sidebar(array(
                'name' => __('Footer Column Three', 'newslite'),
                'id' => 'footer-col-three',
                'description' => __('Displays items on footer section.','newslite'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget' => '</aside>',
                'before_title'  => '<h1 class="widget-title">',
                'after_title'   => '</h1>',
            ));
        }
        if( $newslite_footer_widgets_number > 3 ){
            register_sidebar(array(
                'name' => __('Footer Column Four', 'newslite'),
                'id' => 'footer-col-four',
                'description' => __('Displays items on footer section.','newslite'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget' => '</aside>',
                'before_title'  => '<h1 class="widget-title">',
                'after_title'   => '</h1>',
            ));
        }
    }
}
add_action( 'widgets_init', 'newslite_widgets_init' );

require get_template_directory() . '/inc/widgets/sidebar-style1.php';
require get_template_directory() . '/inc/widgets/sidebar-tab.php';
require get_template_directory() . '/inc/widgets/author-widget.php';