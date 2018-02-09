<?php

			echo '<h3 class="widget-title">'.__('Latest Listings','ClassifiedTheme');
			
			 
					
						$view = classifiedtheme_get_view_grd();
						
						if($view == "normal")
						{
							$list_view = __('List View','ClassifiedTheme');
							$grid_view = '<a href="'.get_bloginfo('siteurl').'/?switch_to_view=grid&ret_u='.urlencode(classifiedTheme_curPageURL()).'">'.__('Grid View','ClassifiedTheme') . '</a>';	
						}
						else
						{
							$list_view = '<a href="'.get_bloginfo('siteurl').'/?switch_to_view=list&ret_u='.urlencode(classifiedTheme_curPageURL()).'">'.__('List View','ClassifiedTheme') . '</a>';
							$grid_view = __('Grid View','ClassifiedTheme');	
						}
					
					
					?>
            		
                    <p class="pk_lst_grd"><?php echo $list_view; ?> | <?php echo $grid_view; ?></p> 
			
            <?php
			echo '</h3>';

				$limit = 12;

				 global $wpdb;	
				 $querystr = "
					SELECT distinct wposts.* 
					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
					WHERE wposts.ID = wpostmeta.post_id
					AND wpostmeta.meta_key = 'closed' 
					AND wpostmeta.meta_value = '0' AND 
					wposts.post_status = 'publish' 
					AND wposts.post_type = 'ad' 
					ORDER BY wposts.post_date DESC LIMIT ".$limit;
				
				 $pageposts = $wpdb->get_results($querystr, OBJECT);
				 
				 ?>
					
					 <?php $i = 0; if ($pageposts): ?>
					 <?php global $post; ?>
                     <?php foreach ($pageposts as $post): ?>
                     <?php setup_postdata($post); ?>
                     
                     
                     <?php 
					 if($view == "normal")
					 ClassifiedTheme_get_post();
					 else
					  classifiedTheme_get_post_function_grid();
					  ?>
                     
                     
                     <?php endforeach; ?>
                     
                     <?php 
					 
					 	echo '<div class="padd10" style="float:left; width:90%"><a class="see_more_ads" href="'.get_post_type_archive_link('ad').'">'.__('See more ads','ClassifiedTheme').'</a></div>';
					 
					 ?>
                     <?php else : ?> <?php $no_p = 1; ?>
                       <div class="padd100"><p class="center"><?php _e("Sorry, there are no posted listings yet","ClassifiedTheme"); ?>.</p></div>
                        
                     <?php endif; ?>