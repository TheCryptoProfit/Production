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
*	since v4.5.8
*
***************************************************************************/

function AuctionTheme_already_closed_auctions_area_function()
{
	
	?>
    
    	
         <div id="content">
    		<div class="my_box3">
    	            	<div class="box_title"><?php
						  echo __("Closed Auctions",'AuctionTheme');
						 
						
						?>
                       
                
            		<?php
					
						$view = auctiontheme_get_view_grd();
						
						if($view == "normal")
						{
							$list_view = __('List View','AuctionTheme');
							$grid_view = '<a href="'.get_bloginfo('siteurl').'/?switch_to_view=grid&ret_u='.urlencode(auctionTheme_curPageURL()).'">'.__('Grid View','AuctionTheme') . '</a>';	
						}
						else
						{
							$list_view = '<a href="'.get_bloginfo('siteurl').'/?switch_to_view=list&ret_u='.urlencode(auctionTheme_curPageURL()).'">'.__('List View','AuctionTheme') . '</a>';
							$grid_view = __('Grid View','AuctionTheme');	
						}
					
					
					?>
            		<p class="pk_lst_grd"><?php echo $list_view; ?> | <?php echo $grid_view; ?></p>
            		
            	</div> 
                
                              
           
                
                
				<div class="box_content"> 

<?php
	
	$closed = array(
			'key' => 'closed',
			'value' => "1",
			//'type' => 'numeric',
			'compare' => '='
		);
		
	$meta_querya = array();
	array_push($meta_querya,$closed);
	
	$args = array('posts_per_page' => 12, 'paged' => $pj, 'post_type' => 'auction', 
	'order' => "desc" ,'meta_key' => 'ending', 
	'orderby'=> 'meta_value_num' ,'meta_query' => $meta_querya );
	
	
	$the_query = new WP_Query( $args );

?>


<?php if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

<?php 

	
	
	

if($view == "normal")
AuctionTheme_get_post();
else AuctionTheme_get_post_grid() ?>

<?php  
 		endwhile; 
		
		if(function_exists('wp_pagenavi')):
		wp_pagenavi( array( 'query' => $the_query ) ); endif;
		                             
     	else:
		
		echo __('No closed items posted.',"AuctionTheme");
		
		endif;
		// Reset Post Data
		wp_reset_postdata();
		 
		?>

       
             
                    
  
                </div></div>
                </div> 
           
                    
    
    
        <!-- ################ -->
    
    <div id="right-sidebar">
    <ul class="xoxo">
        <?php dynamic_sidebar( 'other-page-area' ); ?>
    </ul>
    </div>
    
   </div> 
    <?php
		
	
	
	
}


?>