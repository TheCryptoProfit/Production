        </div><!-- #wi-main -->

<footer id="wi-footer">
    
    <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
    
    <div id="footer-widgets" style="display:none;">
        <div class="container">
            <div class="footer-widgets-inner">
                <?php for ($i=1; $i<=4; $i++): ?>
                    
                <div class="footer-col" >
                    
                    <?php if ( is_active_sidebar( 'footer-' . $i ) ) {
                        dynamic_sidebar( 'footer-' . $i );
                    }?>
                    
                </div><!-- .footer-col -->

                <?php endfor; ?>
                <div class="clearfix"></div>
                <div class="line line1"></div>
                <div class="line line2"></div>
                <div class="line line3"></div>
            </div><!-- .footer-widgets-inner -->
        </div><!-- .container -->
    </div><!-- #footer-widgets -->
    
    <?php endif; ?>
    
    <div id="footer-bottom" role="contentinfo">
        
        <div class="container">
         
            <div class="seciton-list">
        
                
        <div class="blog-container">

            <div class="wi-blog blog-grid column-4 footer_s" >
               
                
                  
                <article class="post-grid nopp" style="width: 39%;">
                <div class="grid-inner">
				 <?php  {
                        dynamic_sidebar( 'footer-2');
                    }?>
               </div>
                </article>
                  
                  <article class="post-grid nopp" style="width: 31%;">
                <div class="grid-inner" style="margin-top: -45px;">
                 <?php $footer_logo = get_theme_mod('wi_footer_logo');?>
            <?php if ($footer_logo):?>
            <div id="footer-logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <img src="<?php echo esc_url($footer_logo);?>"<?php echo get_theme_mod('wi_footer_logo_retina') ? ' data-retina="'.esc_url(get_theme_mod('wi_footer_logo_retina')).'"' : '';?> alt="Footer logo" />
                </a>
                     
            </div>
            <?php endif; // footer logo ?>
				</div>
				  <?php if (!get_theme_mod('wi_disable_footer_social')):?>
            <div id="footer-social" class="social-list">
                <ul>
                    <?php wi_social_list(); ?>
                </ul>
            </div><!-- #footer-social -->
            <?php endif; // footer social ?>
                  </article>
                  
                <article class="post-grid nopp don" style="width: 30%;">
                <div class="grid-inner">
			 <?php  {
                        dynamic_sidebar( 'footer-4');
                    }?>	
              
               </div>
                </article>
                  
                
                   
                       
			</div>
                 
                 </div>
                 
                 </div>
                  
           

          
            
            
            <?php if (!get_theme_mod('wi_disable_footer_search') ):?>
            <div class="footer-search-container">
                
                <div class="footer-search" id="footer-search">
                    <form action="<?php echo site_url(); ?>" method="get">

                        <input type="text" name="s" class="s" value="<?php echo get_search_query();?>" placeholder="<?php _e('Search...','wi');?>" />
                        <button class="submit" type="submit"><i class="fa fa-search"></i></button>

                    </form><!-- .searchform -->
                </div><!-- #footer-search -->
            </div><!-- .footer-search-container -->

            <?php endif; // footer search ?>
            
            <?php if (!get_theme_mod('wi_copyright')):?>
            <p class="copyright"><?php _e( 'All rights reserved. Designed by <a href="https://themeforest.net/user/withemes/portfolio?ref=withemes" target="_blank">Withemes</a>', 'wi' );?></p>
            <?php else: ?>
            <p class="copyright"><?php echo wp_kses(get_theme_mod('wi_copyright'),'');?></p>
            <?php endif; ?>
            
            <?php

            /** Since 2.4 */
            if ( has_nav_menu( 'footer' ) ): ?>

            <nav id="footernav" class="footernav" role="navigation">
                <?php wp_nav_menu(array(
                    'theme_location'	=>	'footer',
                    'depth'				=>	1,
                    'container_class'	=>	'menu',
                ));?>
            </nav><!-- #footernav -->
            
            <?php endif; // primary ?>

        </div><!-- .container -->    
    </div><!-- #footer-bottom --> 
</footer><!-- #wi-footer -->

</div><!-- #wi-wrapper -->

<div class="clearfix"></div>
</div><!-- #wi-all -->

<?php wp_footer(); ?>
<script src="https://www.gstatic.com/firebasejs/4.9.0/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyC1L1D7hsMkzthBCmHTZBth9hEPUqokgEc",
    authDomain: "fir-auth-tcp.firebaseapp.com",
    databaseURL: "https://fir-auth-tcp.firebaseio.com",
    projectId: "fir-auth-tcp",
    storageBucket: "fir-auth-tcp.appspot.com",
    messagingSenderId: "989772423975"
  };
  firebase.initializeApp(config);
</script>

</body>
</html>