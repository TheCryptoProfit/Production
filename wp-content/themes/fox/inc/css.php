<?php
add_action('wp_head','wi_css');
if (!function_exists('wi_css')) {
function wi_css(){
?>
<style type="text/css">
    
    /* LOGO MARGIN */
    <?php if (get_theme_mod('wi_logo_margin_top')!=''):?>
    #logo-area {
        padding-top:<?php echo get_theme_mod('wi_logo_margin_top');?>px;
    }
    <?php endif; ?>
    
    <?php if (get_theme_mod('wi_logo_margin_bottom')!=''):?>
    #logo-area {
        padding-bottom:<?php echo get_theme_mod('wi_logo_margin_bottom');?>px;
    }
    <?php endif; ?>
    
    /* Logo width */
    <?php if (get_theme_mod('wi_logo_width')!='') {?>
    #wi-logo img {
        width: <?php echo absint(get_theme_mod('wi_logo_width')); ?>px;
    }
    <?php
    }?>
    
    /* footer logo width */
    <?php if (get_theme_mod('wi_footer_logo_width')!='') {?>
    #footer-logo img {
        width: <?php echo absint(get_theme_mod('wi_footer_logo_width')); ?>px;
    }
    <?php
    }?>
    
    /* content width */
    <?php global $content_width;?>
    @media (min-width: 1200px) {
    .container {width:<?php echo $content_width;?>px;}#wi-wrapper {max-width:<?php echo $content_width + 60;?>px;}
    }
    
    /* sidebar width */
    <?php if ($sidebar_w = get_theme_mod('wi_sidebar_width')): global $content_width; ?>
    @media (min-width: 783px) {
    .has-sidebar #secondary {
        width: <?php echo 100*absint($sidebar_w)/$content_width;?>%;
    }
    .has-sidebar #primary {
        width: <?php echo 100*(1 - absint($sidebar_w)/$content_width);?>%;
    }
    }
    <?php endif; ?>
    
    /* ================== FONT FAMILY ==================== */
    <?php
                  $types = array('body','heading','nav');
                  $default_fonts = array(
                        'body'          =>  'Merriweather',
                        'heading'       =>  'Oswald',
                        'nav'           =>  'Oswald',
                    );
                  $elements = array();
                  $elements['body'] = 'body';
                  $elements['heading'] = 'h1,h2,h3,h4,h5,h6, #cboxCurrent,#toggle-menu span,#wi-mainnav,.no-menu,.slide .slide-caption,.title-label span, .gallery-caption,.wp-caption-text, .big-meta,.blog-slider .flex-direction-nav a,.grid-meta,.list-meta,.masonry-meta,.more-link span.post-more,.pagination-inner,.post-big .more-link,.readmore,.slider-more, .post-share, .single-cats,.single-date, .page-links-container, .single-tags, .authorbox-nav,.post-navigation .meta-nav,.same-author-posts .viewall, .post-navigation .post-title, .review-criterion,.review-score, .comment .reply a,.comment-metadata a, .commentlist .fn, .comment-notes,.logged-in-as, #respond p .required,#respond p label, #respond #submit, .widget_archive ul,.widget_categories ul,.widget_meta ul,.widget_nav_menu ul,.widget_pages ul,.widget_recent_entries ul, a.rsswidget, .widget_rss>ul>li>cite, .widget_recent_comments ul, .tagcloud a, .null-instagram-feed .clear a, #backtotop span,#footernav,.view-count,.wpcf7 .wpcf7-submit,.wpcf7 p,div.wpcf7-response-output, button,input[type=button],input[type=reset],input[type=submit], .woocommerce #reviews #comments ol.commentlist li .comment-text p.meta, .woocommerce span.onsale, .woocommerce ul.products li.product .onsale, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce a.added_to_cart, .woocommerce nav.woocommerce-pagination ul, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .woocommerce table.shop_table th, .woocommerce table.shop_table td.product-name a';
                  $elements['nav'] = '#toggle-menu span,.no-menu, #wi-mainnav';
                  
                  
    foreach ($types as $type):
                  $main_font = trim(get_theme_mod('wi_'.$type.'_custom_font'));
                  if (!$main_font) {
                    $main_font = trim(get_theme_mod('wi_'.$type.'_font')) ? trim(get_theme_mod('wi_'.$type.'_font')) : $default_fonts[$type];
                  }
                  if (
                      (strpos($main_font,'-')!==false || strpos($main_font,' ')!==false) && 
                      (strpos($main_font,'"')===false) && 
                      (strpos($main_font,"'")===false) &&
                      (strpos($main_font,",")===false)
                  ) $main_font = '"' . $main_font . '"';
                  
                  $fallback_font = trim(get_theme_mod('wi_'.$type.'_fallback_font')) ? trim(get_theme_mod('wi_'.$type.'_fallback_font')) : 'sans-serif';
                  
                  if ($fallback_font) {$main_font .= ',';}
                  
         echo $elements[$type] . '{font-family:' . $main_font . $fallback_font . ';}';
    
    endforeach; ?>
    
    /* ================== FONT SIZE ==================== */
    <?php
    foreach (wi_font_size_array() as $ele => $arr) {

        $size = absint(get_theme_mod('wi_'.$ele.'_size'));
        if (!$size) continue;
        echo $arr['prop'] . '{font-size:'.$size.'px;}';
        ?>
        /* ipad portrait */
        @media (max-width: 979px) {
            <?php echo $arr['prop'] . '{font-size:'.($size * $arr['ipad2']).'px;}'; ?>
        }
        
        /* iphone landscape */
        @media (max-width: 767px) {
            <?php echo $arr['prop'] . '{font-size:'.($size * $arr['iphone1']).'px;}'; ?>
        }
        
        /* iphone portrait */
        @media (max-width: 479px) {
            <?php echo $arr['prop'] . '{font-size:'.($size * $arr['iphone2']).'px;}'; ?>
        }

    <?php }?>

    /* ================== SLOGAN LETTER SPACING ==================== */
    <?php if ($slogan_spacing = get_theme_mod('wi_slogan_spacing')) {?>
    .slogan {letter-spacing:<?php echo absint($slogan_spacing);?>px;}
    @media (max-width: 1138px) {.slogan {letter-spacing:<?php echo .9*absint($slogan_spacing);?>px;}}
    @media (max-width: 979px) {.slogan {letter-spacing:<?php echo .5*absint($slogan_spacing);?>px;}}
    <?php } ?>
    
    
    /* ================== COLORS ==================== */
    /* selection color */
    <?php if ($selection = get_theme_mod('wi_selection_color')):?>
    ::-moz-selection { /* Code for Firefox */
        background: <?php echo $selection;?>;
        color: <?php echo get_theme_mod('wi_selection_text_color') ? get_theme_mod('wi_selection_text_color') : '#fff'; ?>;
    }
    ::selection {
        background: <?php echo $selection;?>;
        color: <?php echo get_theme_mod('wi_selection_text_color') ? get_theme_mod('wi_selection_text_color') : '#fff'; ?>;
    }
    <?php endif; ?>
    
    /* body text color */
    <?php if ($text_color = get_theme_mod('wi_text_color')):?>
    body {
        color: <?php echo $text_color;?>
    }
    <?php endif; ?>
    
    /* primary color */
    <?php if ($primary_color = get_theme_mod('wi_primary_color')):?>
    
    a, #header-social ul li a:hover, #wi-mainnav .menu>ul>li>ul li.current-menu-ancestor>a,#wi-mainnav .menu>ul>li>ul li.current-menu-item>a,#wi-mainnav .menu>ul>li>ul li>a:hover, .submenu-dark #wi-mainnav .menu>ul>li>ul li.current-menu-ancestor>a,.submenu-dark #wi-mainnav .menu>ul>li>ul li.current-menu-item>a,.submenu-dark #wi-mainnav .menu>ul>li>ul li>a:hover, .blog-slider .counter, .related-title a:hover, .grid-title a:hover, .wi-pagination a.page-numbers:hover, .page-links>a:hover, .single-tags a:hover, .author-social ul li a:hover, .small-title a:hover, .widget_archive ul li a:hover,.widget_categories ul li a:hover,.widget_meta ul li a:hover,.widget_nav_menu ul li a:hover,.widget_pages ul li a:hover,.widget_recent_entries ul li a:hover, .widget_recent_comments ul li>a:last-child:hover, .tagcloud a:hover, .latest-title a:hover, .widget a.readmore:hover, .header-cart a:hover, .woocommerce .star-rating span:before, 
.null-instagram-feed .clear a:hover {
        color: <?php echo $primary_color;?>;
}
            @media (max-width: 979px) {
            #wi-mainnav .menu > ul > li.current-menu-item > a,
            #wi-mainnav .menu > ul > li.current-menu-ancestor > a {
                color: <?php echo $primary_color;?>;
            }
            }
    
.mejs-controls .mejs-time-rail .mejs-time-current {
        background-color: <?php echo $primary_color;?> !important;
    }
    
    

.blog-slider .flex-direction-nav a:hover, .more-link span.post-more:hover, .masonry-thumbnail, .post-newspaper .related-thumbnail, .carousel-thumbnail:hover .format-sign.sign-video,.grid-thumbnail:hover .format-sign.sign-video,.list-thumbnail:hover .format-sign.sign-video,.masonry-thumbnail:hover .format-sign.sign-video,.small-thumbnail:hover .format-sign.sign-video, .related-list .grid-thumbnail, #respond #submit:active,#respond #submit:focus,#respond #submit:hover, .small-thumbnail, .widget-social ul li a:hover, .wpcf7 .wpcf7-submit:hover, #footer-search .submit:hover,#footer-social ul li a:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce span.onsale, .woocommerce ul.products li.product .onsale, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce a.add_to_cart_button:hover, .woocommerce #review_form #respond .form-submit input:hover, 

.review-item.overrall .review-score {
        background-color: <?php echo $primary_color;?>;
}
    .carousel-thumbnail:hover .format-sign:before,.grid-thumbnail:hover .format-sign:before,.list-thumbnail:hover .format-sign:before,.masonry-thumbnail:hover .format-sign:before,.small-thumbnail:hover .format-sign:before  {
    border-right-color: <?php echo $primary_color;?>;
}
    
.null-instagram-feed .clear a:hover, .review-item.overrall .review-score {
        border-color: <?php echo $primary_color;?>;
    }
    <?php endif; // primary color ?>
    
    /* widget title bg color */
    <?php if ($widget_title_bg_color = get_theme_mod('wi_widget_title_bg_color')):?>
    .widget-title {
        background-color: <?php echo $widget_title_bg_color;?>;
    }
    <?php endif; ?>
    
    /* link color */
    <?php if ($link_color = get_theme_mod('wi_link_color')):?>
    a {
        color: <?php echo $link_color;?>;
    }
    <?php endif; ?>
    
    /* link hover color */
    <?php if ($link_hover_color = get_theme_mod('wi_link_hover_color')):?>
    a:hover {
        color: <?php echo $link_hover_color;?>;
    }
    <?php endif; ?>
    
    /* active menu item */
    <?php if ($active_nav_color = get_theme_mod('wi_active_nav_color')):?>
    @media (min-width: 980px) {
    #wi-mainnav .menu > ul > li.current-menu-item > a,
    #wi-mainnav .menu > ul > li.current-menu-ancestor > a {
        color: <?php echo $active_nav_color;?>;
    }
    }
    <?php endif; ?>
    
    body {
        /* body background color */
        <?php if ( $bg = get_theme_mod('wi_body_background_color') ): ?>
        background-color: <?php echo $bg;?>;
        <?php endif; ?>
        
        /* body background */
        <?php if ( $bg = get_theme_mod('wi_body_background') ): ?>
        background-image: url(<?php echo esc_url($bg);?>);
        <?php endif; ?>
        
        /* position */
        <?php if ( $pos = get_theme_mod('wi_body_background_position') ):?>
        background-position: <?php echo esc_html($pos);?>;
        <?php endif; ?>
        
        /* repeat */
        <?php if ( $repeat = get_theme_mod('wi_body_background_repeat') ):?>
        background-repeat: <?php echo esc_html($repeat);?>;
        <?php endif; ?>
        
        /* size */
        <?php if ( $size = get_theme_mod('wi_body_background_size') ):?>
        -webkit-background-size: <?php echo esc_html($size);?>;
        -moz-background-size: <?php echo esc_html($size);?>;
        background-size: <?php echo esc_html($size);?>;
        <?php endif; ?>
        
        /* attachment */
        <?php if ( $attachment = get_theme_mod('wi_body_background_attachment') ):?>
        background-attachment: <?php echo esc_html($attachment);?>;
        <?php endif; ?>
    }
    
     /* content bg opacity */
    <?php $opacity = trim(get_theme_mod('wi_content_background_opacity'));
    if ( $opacity!='' ):?>
    #wi-wrapper {
        background-color: rgba(255,255,255,<?php echo absint($opacity)/100;?>);
    }
    <?php endif; ?>
    
    /* CUSTOM CSS */
    <?php echo get_theme_mod('wi_custom_css'); ?>
    
    <?php do_action('wi_css'); ?>
    
</style>
<?php
}
}
?>