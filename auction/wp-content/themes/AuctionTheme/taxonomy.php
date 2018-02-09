<?php
/***************************************************************************
*
*	AuctionTheme - copyright (c) - sitemile.com
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/p/auctionTheme
*	since v4.4
*
***************************************************************************/

global $query_string;
	
$closed = array(
		'key' => 'closed',
		'value' => "0",
		//'type' => 'numeric',
		'compare' => '='
);

//meta_key=keyname&orderby=meta_value&order=ASC
	
$prs_string_qu = wp_parse_args($query_string);
$prs_string_qu['meta_query'] = array($closed);
$prs_string_qu['meta_key'] = 'featured';
$prs_string_qu['orderby'] = 'meta_value';
$prs_string_qu['order'] = 'DESC';




if(isset($_SESSION['price_max']) || isset($_SESSION['price_max'])) {
		
		if(!empty($_SESSION['price_max'])) $max =  $_SESSION['price_max']; else $max = 99999999;
		if(!empty($_SESSION['price_min'])) $min =  $_SESSION['price_min']; else $min = 0;
		
		$price_q = array(
			'key' => 'current_bid',
			'value' => array($min, $max),
			'type' => 'numeric',
			'compare' => 'BETWEEN'
		); 
		
	
		
	}
	
if(!empty($_SESSION['meta_key']) && $_SESSION['meta_key'] == 'start_price')
	{
		$start_price_custom_meta = array(
			'key' => 'start_price',
			'value' => array(1, 999999999999),
			'type' => 'numeric',
			'compare' => 'BETWEEN'
		);
			
	}
	
	
	$meta_querya = array();

			$arr = $_SESSION['custom_field_id'];
			
			for($i=0;$i<count($arr);$i++)
			{
				$ids 	= $arr[$i];
				$value 	= $_SESSION['custom_field_value_'.$ids];
				
				if(!empty($value)) {
				
				
				if(is_array($value))
				{
					$val = array();
					
					for($j=0;$j<count($value);$j++)						
						$val[] = $value[$j];
					
				}
				elseif(!empty($value)) {
				
					$val = $value;
				
				}
				
				$stuff = array(
					'key' => "custom_field_ID_".$ids,
					'value' => $val,
					'compare' => 'LIKE'
				);
				
				array_push($meta_querya,$stuff);
				
				}
			}
	

		array_push($meta_querya,$price_q);
		array_push($meta_querya,$buy_now_custom_meta);
		array_push($meta_querya,$closed);
		array_push($meta_querya,$featured);
		array_push($meta_querya,$start_price_custom_meta);


$prs_string_qu['meta_query'] = $meta_querya;		
query_posts($prs_string_qu);

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$term_title = $term->name;
			
//======================================================

	get_header();
	
	$AuctionTheme_adv_code_cat_page_above_content = stripslashes(get_option('AuctionTheme_adv_code_cat_page_above_content'));
		if(!empty($AuctionTheme_adv_code_cat_page_above_content)):
		
			echo '<div class="full_width_a_div">';
			echo $AuctionTheme_adv_code_cat_page_above_content;
			echo '</div>';
		
		endif;
	

?>

<?php 

		if(function_exists('bcn_display'))
		{
		    echo '<div class="my_box3 breadcrumb-wrap"><div class="padd10">';	
		    bcn_display();
			echo '</div></div>';
		}

?>	

<div class="  col-xs-12 col-sm-8 col-lg-9">

<div class="my_box3">
      
            	<div class="box_title"><?php
						if(empty($term_title)) echo __("All Posted Items",'AuctionTheme');
						else { echo sprintf( __("Latest Posted Items in %s",'AuctionTheme'), $term_title);
						
						?>
                        
                        <a href="<?php bloginfo('siteurl'); ?>/?feed=rss2&<?php echo get_query_var( 'taxonomy' ); ?>=<?php echo get_query_var( 'term' ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/rss_icon.png" 
                    border="0" width="19" height="19" alt="rss icon" /></a>
                        
                        <?php
						
						}
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
                
                              
                <?php $description = term_description(); 
				
				if(!empty($description))
				echo '<div class="box_content">  '.$description . "</div>"; ?>
                
                
				<div class="box_content"> 

<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

<?php 

	
	
	

if($view == "normal")
AuctionTheme_get_post();
else AuctionTheme_get_post_grid() ?>

<?php  
 		endwhile; 
		
		if(function_exists('wp_pagenavi')):
		wp_pagenavi(); endif;
		                             
     	else:
		
		echo __('No items posted.',"AuctionTheme");
		
		endif;
		// Reset Post Data
		wp_reset_postdata();
		 
		?>


</div></div></div> 


<div id="right-sidebar" class="col-xs-12 col-sm-4 col-lg-3  ">
    <ul class="xoxo">
    
    <li id="text-6" class="widget-container widget_text">
        <h3 class="widget-title"><?php _e('Refine Search','AuctionTheme'); ?></h3>	
        <div class="textwidget" style="overflow:hidden">
        
                <div style="float:left;width:100%">
              <table class="table">
                
                
                <form method="POST" action="<?php echo get_bloginfo('siteurl'); ?>/?sess_search=1">
                <input name="curl" value="<?php echo auctionTheme_curPageURL() ?>" type="hidden" />
                 
                 <tr><td><?php _e('Auction ID#',"AuctionTheme"); ?>: </td><td>
                   <input class="form-control" size="10" value="<?php echo $_SESSION['auction_ID']; ?>" name="auction_ID" />
                   </td></tr>	
                
                   <tr><td><?php _e('Keyword',"AuctionTheme"); ?>: </td><td>
                   <input class="form-control" size="10" value="<?php echo $_SESSION['term']; ?>" name="term" />
                   </td></tr>
                   
                   <tr><td><?php _e('Min Price',"AuctionTheme"); ?>:</td><td>
                    <input class="form-control" size="10" value="<?php echo $_SESSION['price_min']; ?>" placeholder="<?php echo auctiontheme_get_currency() ?>" name="price_min" /></td></tr> 
                    
                   <tr><td><?php _e('Max Price',"AuctionTheme"); ?>:</td><td> 
                   <input class="form-control" size="10" value="<?php echo $_SESSION['price_max']; ?>" placeholder="<?php echo auctiontheme_get_currency() ?>" name="price_max" /></td></tr>
          			 
                   
        

                        <?php
		
		$get_catID = AuctionTheme_get_CATID($term->slug);
		
		if(empty($get_catID)) $get_catID = 0;
		
		$get_catID = array($get_catID);
		$arr = AuctionTheme_get_auction_category_fields_without_vals_ses($get_catID, 'no');
		
		for($i=0;$i<count($arr);$i++)
		{
			
			        echo '<tr>';
					echo '<td>'.$arr[$i]['field_name'].$arr[$i]['id'].':</td>';
					echo '<td>'.$arr[$i]['value'].'</td>';
					echo '</tr>';
			
			
		}	
		
		
		?>  
                
                </div>

                   <tr><td></td><td>
                   <input type="submit" class="submit_bottom2" value="<?php _e("Refine Search","AuctionTheme"); ?>" name="ref-search1" class="big-search-submit2" /></td></tr>
                   </form>
</table> </div>
</div>
</li>

    
        <?php dynamic_sidebar( 'other-page-area' ); ?>
    </ul>
</div>


<?php

	get_footer();

?>