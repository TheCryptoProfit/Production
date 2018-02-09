<?php
/***************************************************************************
*
*	AuctionTheme - copyright (c) - sitemile.com
*	The most popular auction theme for wordress on the internet. Launch your
*	auction site in minutes from purchasing. Turn-key solution.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/p/auctionTheme
*	since v4.4.7.1
*
***************************************************************************/

	global $wp_query, $wp_rewrite, $post;
	$paagee 	=  $wp_query->query_vars['my_custom_page_type'];
	$a_action 	=  $wp_query->query_vars['a_action'];



if(is_home() && empty($a_action)):
		
		$auctionTheme_show_front_slider = get_option('auctionTheme_show_front_slider');
		if($auctionTheme_show_front_slider != "no"):
		
		?>
			<div class="content_super_div">
        		<div id="auction-home-page-main-inner" class="main_wrapper_slider">
				<div class="clear20"></div>
                
                <div id="slider2">
         	
			
			<?php
					
				$ord = "wposts.post_date";
				$AuctionTheme_randomize_slider_front = get_option('AuctionTheme_randomize_slider_front');
				if($AuctionTheme_randomize_slider_front == "yes") $ord = "rand()";	
					
				 global $wpdb;	
				 $querystr = "
					SELECT distinct wposts.* 
					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta, $wpdb->postmeta wpostmeta2
					WHERE wposts.ID = wpostmeta.post_id AND
					wpostmeta.meta_key='closed' AND wpostmeta.meta_value='0'
					AND 
					
					wposts.ID = wpostmeta2.post_id AND
					wpostmeta2.meta_key='featured' AND wpostmeta2.meta_value='1'
					AND 
					
					wposts.post_status = 'publish' 
					AND wposts.post_type = 'auction' 
					ORDER BY $ord DESC LIMIT 28 ";
				
				 $pageposts = $wpdb->get_results($querystr, OBJECT);
				 $posts_per = 7;
				 ?>
					
					 <?php $i = 0; if ($pageposts): ?>
					 <?php global $post; ?>
                     <?php foreach ($pageposts as $post): ?>
                     <?php setup_postdata($post); ?>
                     
                     
                     <?php 
                     
					 echo '<div class="nk_slider_child">';
                      			auctionTheme_slider_post();
					 echo '</div>';
  
                     
                     ?>

                     <?php endforeach; ?>
                     <?php endif; ?>

		
             
      
       </div>
       		<div class="clear20"></div>

       
        </div>
        </div>
        
        <?php 
			else:
			
			//echo '<div class="clear10"></div>';
		
		
			endif;
			endif; ?>