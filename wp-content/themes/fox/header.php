<!DOCTYPE html>
<html   <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
    
	<?php wp_head(); ?>
	
	<link rel="shortcut icon" href="https://www.thecryptoprofit.com/wp-content/uploads/2018/01/favicon.png" type="image/x-ico" />

  <meta name="msvalidate.01" content="4002DBF2AC96CCE8884DD601ACCF364B" />
  <meta name="google-site-verification" content="2A9811NlJfUmguQ1pwiDhKF6ysUIZ68FiMTwLKeW07c" />
  <meta name="yandex-verification" content="bd42a066f6266e89" />
  <meta name="p:domain_verify" content="58b989ac08be4b78b41508a93f9f59aa"/>
<meta name="gb-site-verification" content="e8ca70e09ca5d0022706073c83de5a30b9da9e87">

 <script async src="https://cdn.ampproject.org/v0.js"></script>
 <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
 <link rel='stylesheet' id='font-awesome-css'  href='https://www.thecryptoprofit.com/wp-content/themes/fox/css/font-awesome-4.7.0/css/font-awesome.min.css?ver=4.7' type='text/css' media='all' />


</head>

<style>
    .disable-hand-lines #footer-bottom:before, .disable-hand-lines #footer-widgets, .disable-hand-lines #posts-small-wrapper, .disable-hand-lines #wi-wrapper, .disable-hand-lines .post-nav, .disable-hand-lines blockquote, .disable-hand-lines blockquote:after, .post-list:before {
    background-image: none;
 border-top: 2px solid #fff !important;
}
.disable-hand-lines #wi-wrapper:after, .disable-hand-lines #wi-wrapper:before {
    background: 0 0;
     border-left: 2px solid #fff !important;}
     
     #footer-search, #footer-social {
     margin: 0px 0 0 !important; 
    text-align: center;
}

#footer-bottom{padding: 20px 0 7px !important;}
.nopp{padding: 0 0px 0px !important;}
#subscribe-email{    float: left;}
#subscribe-submit{    float: left;     margin-top: 16px; padding-top: 18px;
  
}
.footer_s{font-size:11px;}
.copyright {
    margin: 0px auto 0 !important;
    padding: 0px 0 !important;
    text-align: center;
    max-width: 600px;
}
#footernav {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 12px;
    text-align: center;
    margin: 0.3em 0 0 !important;
}
p{    margin: 0 0 10px !important;}
.don{line-height:17.5px;}
button, input[type=button], input[type=reset], input[type=submit]{padding: 0 4px !important; letter-spacing: 0px !important;   line-height: 32px !important;}
</style>


<body <?php body_class(); ?>>
<div id="wi-all">

    <div id="wi-wrapper">
        
        <div id="topbar-wrapper">
            <div class="wi-topbar" id="wi-topbar">
                <div class="container">

                    <div class="topbar-inner">

                        <?php if (has_nav_menu('primary')):?>

                        <a class="toggle-menu" id="toggle-menu"><i class="fa fa-align-justify"></i> <span><?php _e('Menu','wi');?></span></a>

                        <nav id="wi-mainnav" class="navigation-ele wi-mainnav" role="navigation">
                            <?php wp_nav_menu(array(
                                'theme_location'	=>	'primary',
                                'depth'				=>	3,
                                'container_class'	=>	'menu',
                                'walker'            =>  new wi_mainnav_walker(),
                            ));?>
                        </nav><!-- #wi-mainnav -->

                        <?php else: ?>

                        <?php echo '<div id="wi-mainnav"><em class="no-menu">'.sprintf(__('Go to <a href="%s">Appearance > Menu</a> to set "Primary Menu"','wi'),get_admin_url('','nav-menus.php')).'</em></div>'; ?>

                        <?php endif; ?>

                        <?php if (!get_theme_mod('wi_disable_header_social')):?>
                        <div id="header-social" class="social-list">
                            <ul>
                                <?php wi_social_list(!get_theme_mod('wi_disable_header_search')); ?>
                            </ul>
                        </div><!-- #header-social -->
                        <?php endif; // footer social ?>

                    </div><!-- .topbar-inner -->

                </div><!-- .container -->

            </div><!-- #wi-topbar -->
        </div><!-- #topbar-wrapper -->
        
        <header id="wi-header" class="wi-header">
            
            <div class="container">
                
                <?php if (!get_theme_mod('wi_disable_header_search')):?>
                <div class="header-search" id="header-search">
                    <form role="search" method="get" action="<?php echo home_url();?>">
                        <input type="text" name="s" class="s" value="<?php echo get_search_query();?>" placeholder="<?php _e('Type & hit enter...','wi');?>" />
                        <button class="submit" role="button" title="<?php _e('Go','wi');?>"><span><?php _e('Go','wi');?></span></button>
                    </form>
                </div><!-- .header-search -->
                <?php endif; ?>
                
                <div id="logo-area">
                    <div id="wi-logo">
                        <h2>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <?php if (!get_theme_mod('wi_logo')):?>

                                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" data-retina="<?php echo get_template_directory_uri(); ?>/images/logo@2x.png" />

                                <?php else: ?>

                                    <img src="<?php echo get_theme_mod('wi_logo');?>" alt="Logo"<?php echo get_theme_mod('wi_logo_retina') ? ' data-retina="'.get_theme_mod('wi_logo_retina').'"' : '';?> />

                                <?php endif; // logo ?>
                            </a>
                        </h2>

                    </div><!-- #wi-logo -->


                    <?php if (!get_theme_mod('wi_disable_header_slogan') ):?>
                    <h3 class="slogan"><?php bloginfo('description');?></h3>
                    <?php endif; ?>
                    
                  
                    
                </div><!-- #logo-area -->
            
                <div class="clearfix"></div>
                
                <?php 
                /**
                 * Header Area
                 *
                 * @since 2.1.4
                 *
                 * Place ad widgets here
                 */
                if ( is_active_sidebar( 'header' ) ) : ?>
                    
                <div id="header-area" class="widget-area" role="complementary">
                    
                    <?php dynamic_sidebar( 'header' ); ?>
                    
                </div><!-- .widget-area -->
                    
                <?php endif; ?>
                
            </div><!-- .container -->
            
        </header><!-- #wi-header -->
    
        <div id="wi-main">