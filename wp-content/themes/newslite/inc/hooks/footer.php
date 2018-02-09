<?php
if ( ! function_exists( 'newslite_before_footer' ) ) :
    /**
     * Footer content
     *
     * @since newslite 1.0.0
     *
     * @param null
     * @return false | void
     *
     */
    function newslite_before_footer() {
    ?>
        </div><!-- #content -->
    </div>
    </section>
        <!-- *****************************************
             Footer section starts
    ****************************************** -->
    <footer class=" container wrapper wrap-footer">
    <?php
    }
endif;
add_action( 'newslite_action_before_footer', 'newslite_before_footer', 10 );

if ( ! function_exists( 'newslite_widget_before_footer' ) ) :
    /**
     * Footer content
     *
     * @since newslite 1.0.0
     *
     * @param null
     * @return false | void
     *
     */
    function newslite_widget_before_footer() {
        global $newslite_customizer_all_values;
        $newslite_footer_widgets_number = $newslite_customizer_all_values['newslite-footer-sidebar-number'];
        if( !is_active_sidebar( 'footer-col-one' ) && !is_active_sidebar( 'footer-col-two' ) && !is_active_sidebar( 'footer-col-three' ) && !is_active_sidebar( 'footer-col-four' )){
            return false;
        }
        if( 1 == $newslite_footer_widgets_number ){
                $col = 'col-md-12';
            }
        elseif( 2 == $newslite_footer_widgets_number ){
            $col = 'col-md-6 col-sm-6 col-xs-12';
        }
        elseif( 3 == $newslite_footer_widgets_number ){
            $col = 'col-md-4 col-sm-4 col-xs-12';
        }
        else{
            $col = 'col-md-3 col-sm-3 col-xs-12';
        }
        ?>
        <!-- footer widget -->
        <section class="wrapper footer-widget">
            <div class="container">
                <div class="row">
                     <?php if( is_active_sidebar( 'footer-col-one' ) && $newslite_footer_widgets_number > 0 ) : ?>
                        <div class="contact-list <?php echo esc_attr( $col );?>">
                            <?php dynamic_sidebar( 'footer-col-one' ); ?>
                        </div>
                    <?php endif; ?>
                    <?php if( is_active_sidebar( 'footer-col-two' ) && $newslite_footer_widgets_number > 1 ) : ?>
                        <div class="contact-list <?php echo esc_attr( $col );?>">
                            <?php dynamic_sidebar( 'footer-col-two' ); ?>
                        </div>
                    <?php endif; ?>
                    <?php if( is_active_sidebar( 'footer-col-three' ) && $newslite_footer_widgets_number > 2 ) : ?>
                        <div class="contact-list <?php echo esc_attr( $col );?>">
                            <?php dynamic_sidebar( 'footer-col-three' ); ?>
                        </div>
                    <?php endif; ?>
                    <?php if( is_active_sidebar( 'footer-col-four' ) && $newslite_footer_widgets_number > 3 ) : ?>
                        <div class="contact-list <?php echo esc_attr( $col );?>">
                            <?php dynamic_sidebar( 'footer-col-four' ); ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </section>
    <?php
    }
endif;
add_action( 'newslite_action_widget_before_footer', 'newslite_widget_before_footer', 10 );

if ( ! function_exists( 'newslite_footer' ) ) :
    /**
     * Footer content
     *
     * @since newslite 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function newslite_footer() {
        global $newslite_customizer_all_values;
        ?> 
        <!-- footer site info -->
        <section id="colophon" class="wrapper site-footer" role="contentinfo">
            <div class="container">
                <div class="row">
                    <div class="xs-12 col-sm-12 col-md-12">
                        <div class="site-info">
                            <?php
                            if(isset($newslite_customizer_all_values['newslite-copyright-text'])){
                                echo wp_kses_post( $newslite_customizer_all_values['newslite-copyright-text'] );
                            }
                            ?>
                            <?php
                             if( 1 == $newslite_customizer_all_values['newslite-enable-theme-name']){
                                ?>
                                <span class="sep"> | </span>
                                <?php printf( esc_html__( 'Theme: %1$s by %2$s', 'newslite' ), 'Newslite', '<a href="http://evisionthemes.com/" target = "_blank" rel="designer">eVisionThemes </a>' ); ?>
                                <?php
                            }
                            ?>
                        </div><!-- .site-info -->
                    </div>                   
                </div>
            </div>
        </section><!-- #colophon -->     

    </footer><!-- #colophon -->
    <!-- *****************************************
             Footer section ends
    ****************************************** -->
    <?php
    }
endif;
add_action( 'newslite_action_footer', 'newslite_footer', 10 );

if ( ! function_exists( 'newslite_page_end' ) ) :
    /**
     * Page end
     *
     * @since newslite 1.0.0
     *
     * @param null
     * @return null
     *
     */
    function newslite_page_end() {
    global $newslite_customizer_all_values;
        ?>
    <?php
     if( 1 == $newslite_customizer_all_values['newslite-enable-back-to-top']) {
        ?>
            <a id="gotop" class="evision-back-to-top" href="#page"><i class="fa fa-angle-up"></i></a>
        <?php
        } ?>
    </div><!-- #page -->
    <?php }
endif;
add_action( 'newslite_action_after', 'newslite_page_end', 10 );