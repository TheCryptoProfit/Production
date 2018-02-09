<?php

if( ! function_exists( 'newslite_inline_style' ) ) :

    /**
     * newslite wp_head hook
     *
     * @since  newslite 1.0.0
     */
    function newslite_inline_style(){
      
        global $newslite_customizer_all_values;
        global $newslite_google_fonts;

        $newslite_background_color = get_background_color();
        $newslite_primary_color_option = $newslite_customizer_all_values['newslite-primary-color'];
        $newslite_site_identity_color_option = $newslite_customizer_all_values['newslite-site-identity-color'];

        /*font settings*/
        $newslite_font_family_primary_option = $newslite_google_fonts[$newslite_customizer_all_values['newslite-font-family-Primary']];
        $newslite_font_family_site_identity_option = $newslite_google_fonts[$newslite_customizer_all_values['newslite-font-family-site-identity']];
        $newslite_font_family_title_option = $newslite_google_fonts[$newslite_customizer_all_values['newslite-font-family-title']];
        /*end of about section*/
        ?>
        <style type="text/css">
        /*=====COLOR OPTION=====*/

        /*Color*/
        /*----------------------------------*/
        /*background color*/ 
        <?php 
        if( !empty($newslite_background_color) ){
        ?>
          
          body{
            background-color: #<?php echo esc_html( $newslite_background_color );?>;
          }
        <?php
        } 
        /*Primary*/
        if( !empty($newslite_primary_color_option) ){
        ?>
            section.wrapper-slider .slide-pager .cycle-pager-active,
            section.wrapper-slider .slide-pager .cycle-pager-active:visited,
            section.wrapper-slider .slide-pager .cycle-pager-active:hover,
            section.wrapper-slider .slide-pager .cycle-pager-active:focus,
            section.wrapper-slider .slide-pager .cycle-pager-active:active,
            .title-divider,
            .title-divider:visited,
            .block-overlay-hover,
            .block-overlay-hover:visited,
            #gmaptoggle,
            #gmaptoggle:visited,
            .evision-back-to-top,
            .evision-back-to-top:visited,
            .search-form .search-submit,
            .search-form .search-submit:visited,
            .widget_calendar tbody a,
            .widget_calendar tbody a:visited,
            .wrap-portfolio .button.is-checked,
            .button.button-outline:hover, 
            .button.button-outline:focus, 
            .button.button-outline:active,
            .radius-thumb-holder,
            header.wrapper.top-header .controls,
            .radius-thumb-holder:before,
            .radius-thumb-holder:hover:before, 
            .radius-thumb-holder:focus:before, 
            .radius-thumb-holder:active:before,
            #pbCloseBtn:hover:before,
            .slide-pager .cycle-pager-active, 
            .slick-dots .slick-active button,
            .slide-pager span:hover,
            .featurepost .latestpost-footer .moredetail a,
            .featurepost .latestpost-footer .moredetail a:visited,
            #load-wrap,
            .back-tonav,
            .back-tonav:visited,
            .wrap-service .box-container .box-inner:hover .box-content, 
            .wrap-service .box-container .box-inner:focus .box-content,
            .search-holder .search-bg.search-open form,
            .top-header .timer,
            .nav-buttons,
            .widget .widgettitle:after,
            .widget .widget-title:after,
            .widget input.search-submit,
            .widget .search-form .search-submit,
            .widget .search-form .search-submit:focus,
            .main-navigation.sec-main-navigation ul li.current_page_item:before,
            .comments-area input[type="submit"],
            .slider-controls a i,
            .tabs-menu li.current,
            .read-more-text a,            
            .tagcloud a:hover{
              background-color: <?php echo esc_attr( $newslite_primary_color_option );?>;
            }

            .widget-title,
            .widgettitle,
            .wrapper-slider,
            .flip-container .front,
            .flip-container .back {
              border-color: <?php echo esc_attr( $newslite_primary_color_option );?>; 
            }

            @media screen and (min-width: 768px){
            .main-navigation .current_page_item > a:after,
            .main-navigation .current-menu-item > a:after,
            .main-navigation .current_page_ancestor > a:after,
            .main-navigation li.active > a:after,
            .main-navigation li.active > a:after,
            .main-navigation li.active > a:after,
            .main-navigation li.current_page_parent a:after {
                background-color: <?php echo esc_attr( $newslite_primary_color_option );?>;
              }
            }

            .latestpost-footer .moredetail a,
            .latestpost-footer .moredetail a:visited,
            h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover,
            .posted-on a:hover, .date a:hover, .cat-links a:hover, .tags-links a:hover, .author a:hover, .comments-link a:hover,
            .edit-link a:hover,
            .edit-link a:focus,            
            .widget li a:hover, .widget li a:focus, .widget li a:active, .widget li a:visited:hover, .widget li a:visited:focus, .widget li a:visited:active,
            .main-navigation a:hover, .main-navigation a:focus, .main-navigation a:active, .main-navigation a:visited:hover, .main-navigation a:visited:focus, .main-navigation a:visited:active,
            .site-branding h1 a:hover,
            .site-branding p a:hover,
            .newslite-top-menu ul li a:hover,
            .top-header .noticebar .ticker .slide-item a:hover,
            h2.user-title a span,
            .page-inner-title .entry-header a:hover, .page-inner-title .entry-header a:focus, .page-inner-title .entry-header a:active, .page-inner-title .entry-header a:visited:hover, .page-inner-title .entry-header a:visited:focus, .page-inner-title .entry-header a:visited:active, .page-inner-title .entry-header time:hover, .page-inner-title .entry-header time:focus, .page-inner-title .entry-header time:active, .page-inner-title .entry-header time:visited:hover, .page-inner-title .entry-header time:visited:focus, .page-inner-title .entry-header time:visited:active,
            .wrap-breadcrumb a:hover, .wrap-breadcrumb a:focus, .wrap-breadcrumb a:active,
            body.blog .content-start article:hover h2 a,
            .popular:hover i,
            .comment:hover i,
            .recent:hover i{
              color: <?php echo esc_attr( $newslite_primary_color_option );?> !important;
            }
        <?php
        } 
        if( !empty($newslite_site_identity_color_option) ){
        ?>
            /*Site identity / logo & tagline*/
            .site-header .wrapper-site-identity .site-branding .site-title a,
            .site-header .wrapper-site-identity .site-title a:visited,
            .site-header .wrapper-site-identity .site-branding .site-description,
            .page-inner-title .entry-header time {
              color: <?php echo esc_attr( $newslite_site_identity_color_option );?>;
            }
        <?php
        } 
        if( !empty($newslite_font_family_primary_option) ){
        /*=====FONT FAMILY OPTION=====*/
        ?> 
        /*Primary*/
          html, body, p, button, input, select, textarea, pre, code, kbd, tt, var, samp , .main-navigation a, search-input-holder .search-field,
          .widget{
          font-family: '<?php echo esc_attr( $newslite_font_family_primary_option ); ?>'; /*Lato*/
          }
        <?php
        } 

        if( !empty($newslite_font_family_site_identity_option) ){
        ?> 
          /*Site identity / logo & tagline*/
          .site-header .wrapper-site-identity .site-title a, .site-header .wrapper-site-identity .site-description {
          font-family: '<?php echo esc_attr( $newslite_font_family_site_identity_option ); ?>'; /*Lato*/
          }
        <?php
        } 
        if( !empty($newslite_font_family_title_option) ){
        ?> 
          /*Title*/
          h1, h1 a,
          h2, h2 a,
          h3, h3 a,
          h4, h4 a,
          h5, h5 a,
          h6, h6 a,
          .widget-title h2,
          .widget-title,
          .news-content a{
            font-family: '<?php echo esc_attr( $newslite_font_family_title_option ); ?>'; /*Lato*/
          }
        <?php
        } 
        ?>
        </style>
    <?php
    }
endif;
