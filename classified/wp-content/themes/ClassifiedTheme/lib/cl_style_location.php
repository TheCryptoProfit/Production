<?php

	global $wpdb,$wp_rewrite,$wp_query;
 	$current_taxo 	=  $wp_query->query_vars['current_taxo'];
 	
	$show_me_count = true;
	
	function sitemile_filter_ttl($title){return __("Locations", "ClassifiedTheme")." - ";}
	add_filter( 'wp_title', 'sitemile_filter_ttl', 10, 3 );	
	
	get_header();
?>

<div id="content">
	
    		<div class="my_box3">
            <div class="padd10">
            
            	<div class="box_title"><?php _e("Locations", "ClassifiedTheme"); ?></div>
            	<div class="box_content">	
         		<?php
				
						if(empty($current_taxo)) 	$terms 		= get_terms("ad_location", "parent=0&hide_empty=0");
						else
						{								
							$trm = get_term_by( 'slug', $current_taxo, 'ad_location'); // $output, $filter );
							$terms 		= get_terms("ad_location", "parent=".$trm->term_id."&hide_empty=0");		
						}
				?>
                
                <!-- ############################# -->
              <?php
			    
             global $wpdb;
		$arr = array();
		
		$count = count($terms); $i = 0;
		if ( $count > 0 ){
			
			
		$nr = 4;
		
		
		//=========================================================================

		
		$total_count = 0;
		$arr = array();        
        global $wpdb;
		$contor = 0;


		 
		 $count = count($terms); $i = 0;
		 if ( $count > 0 ){
		     
		     foreach ( $terms as $term ) {
		       

			
			$stuffy = '';
			$cnt	= 1;
			
		       	$stuffy .= "<ul id='location-stuff'><li>";
			    

				$mese = '';
				
					$mese .= '<ul>';
					$link = CT_get_super_site_link_taxe($term->slug);
					$mese .= "<li class='top-mark'> <a href='#' class='parent_taxe active_plus' rel='taxe_project_cat_".$term->term_id."' ><img rel='img_taxe_project_cat_".$term->term_id."'
					 src=\"".get_bloginfo('template_url')."/images/bullet-cat.png\" border='0' width=\"9\" height=\"12\" /></a>
		       		<h3><a href='".$link."'>" . $term->name;
					
				
			   
			   $total_ads = ClassifiedTheme_get_custom_taxonomy_count('ad',$term->slug);
			   
			   
					
					$stuffy .= $mese.($show_me_count == true ? "(".$total_ads.")" : "") ."</a></h3></li>
					";
					$stuffy .= $mese2;
					
					$mese2 = '';
					
					$stuffy .= '</ul></li>
					';
				$stuffy .= '</ul>
				';
				
			   
			   	$i++;
		        $arr[$contor]['content'] 	= $stuffy;
				$arr[$contor]['size'] 		= $cnt;
				$total_count 		= $total_count + $cnt;
				$contor++;
		     }

		 }   
         
        //=======================================

		 $i = 0; $k = 0;
		 $result = array();
		 
		 foreach($arr as $category)
		 {			

			$result[$k] .= $category['content'];
			//echo $k." ";
			$k++;
				
			if($k == $nr) $k=0;
	
		 }
		
		 foreach($result as $res)
		 echo "<div class='stuffa4'>".$res.'</div>
		 
		 '; 
		 
		
		 
		} ?>
                
                
                <!-- ################################ -->
                            
                </div>
                
            </div>
            </div>
                
                
            
</div>



<div id="right-sidebar">
    <ul class="xoxo">
        <?php dynamic_sidebar( 'other-page-area' ); ?>
    </ul>
</div>


<?php


	get_footer();
	
?>
