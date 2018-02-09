<?php 	 

			$menu_name = 'primary-classified-big-menu';

			if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
						
			$menu_items = wp_get_nav_menu_items($menu->term_id);
					
			$m = 0;			
			foreach ( (array) $menu_items as $key => $menu_item ) {
								$title = $menu_item->title;
								$url = $menu_item->url;
								if(!empty($title))
								$m++;
			}}
							
							
						
			
		?>
         
      	<div id="cssmenu">
        
        <?php
		
			if($m == 0):
		
		?>
        <ul>
            
            <li><a class="first_me" href="<?php bloginfo('siteurl'); ?>"><?php _e('Home','ClassifiedTheme'); ?></a></li>
            <li><a href="<?php echo get_post_type_archive_link('ad'); ?>"><?php _e('All Listings','ClassifiedTheme'); ?></a></li> 
            <li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_adv_search_page_id')); ?>"><?php _e('Advanced Search','ClassifiedTheme'); ?></a></li> 
            <li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_all_categories_page_id')); ?>"><?php _e('Show All Categories','ClassifiedTheme'); ?></a></li> 
            <li><a href="<?php echo get_permalink(get_option('ClassifiedTheme_all_locations_page_id')); ?>"><?php _e('Show All Locations','ClassifiedTheme'); ?></a></li>      
                       
            </ul>
        	<?php else: 
			
			$event = 'hover';
			$effect = 'fade';
			$fullWidth = ',fullWidth: true';
			$speed = 0;
			$submenu_width = 200;
			$menuwidth = 100;
		
		?>
        
  
       
       
        	
		<?php
			
			$menu_name = 'primary-classified-big-menu';

			if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) 
			$nav_menu = wp_get_nav_menu_object( $locations[ $menu_name ] );					
							 
			
			wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu, 'container' => false ) );
		
		?>		
		  
      
        
            <?php endif; ?>
        
        </div>   








 