<?php

	global $wpdb,$wp_rewrite,$wp_query;
	$username = $wp_query->query_vars['post_author'];
	$uid = $username;
	$paged = $wp_query->query_vars['paged'];
	
	global $username;
	
	$user = get_userdata($uid);
	$username = $user->user_login;
	
	
	
	function sitemile_filter_ttl($title){ global $username; return __("User Profile",'AuctionTheme')." - " . $username;}
	add_filter( 'wp_title', 'sitemile_filter_ttl', 10, 3 );	
	
	get_header();
?>

<div id="content" class="col-xs-12 col-sm-8 col-lg-9">
	
    		<div class="my_box3">
            
            	<div class="box_title"><?php _e("User Profile",'AuctionTheme'); ?> - <?php echo html_entity_decode($username); ?> </div>
            	<div class="padd10">	
                    	
                      
                    
                        <div class="user-profile-description">
                        <?php
                        
                        $info = get_user_meta($uid, 'personal_info', true);
                        if(empty($info)) _e("No personal info defined.",'AuctionTheme');
                        else echo $info;
                        
                        
                        ?>                        
                        </div>
                        
                          <div class="user-profile-avatar"><img class="imgImg" width="100" height="100" src="<?php echo AuctionTheme_get_avatar($uid,100,100); ?>" /><br/><br/>
                          
                          <a class="post_bid_btn" href="<?php
                
				
				$using_perm = AuctionTheme_using_permalinks();
	
			if($using_perm)	$privurl_m = get_permalink(get_option('AuctionTheme_my_account_priv_mess_page_id')). "?";
			else $privurl_m = get_bloginfo('siteurl'). "/?page_id=". get_option('AuctionTheme_my_account_priv_mess_page_id'). "&";	
			
			echo $privurl_m."priv_act=send&";
			echo 'uid='.$user->ID;
				
				?>"><?php _e('Contact User','AuctionTheme'); ?></a>
                          
                          
                   	 	</div>
                
            </div>
            </div>
                
                
                <div class="clear10"></div>
    
    

			<div class="my_box3">
            
            	<div class="box_title"><?php _e("User Latest Posted Auctions",'AuctionTheme'); ?></div>
            	<div class="box_content">	

        
<?php

	
	$closed = array(
							'key' => 'closed',
							'value' => '0',
							'compare' => '='
						);	
	
	$nrpostsPage = 8;
	$args = array( 'author' => $uid , 'meta_query' => array($closed) ,'posts_per_page' => $nrpostsPage, 'paged' => $paged, 'post_type' => 'auction', 'order' => "DESC" , 'orderby'=>"date");
	$the_query = new WP_Query( $args );
		
		// The Loop
		
		if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			
			auctionTheme_get_post();
	
			
		endwhile;
	
	if(function_exists('wp_pagenavi'))
	wp_pagenavi( array( 'query' => $the_query ) );
	
          ?>
          
          <?php                                
     	else:
		
		echo '<div class="padd10">';
		echo __('No auctions posted.','AuctionTheme');
		echo '</div>';
		
		endif;
		// Reset Post Data
		wp_reset_postdata();

            
					 
		?>
	


</div>
</div>


<div class="clear10"></div>


			<div class="my_box3">
            
            	<div class="box_title"><?php _e("User Latest Won Auctions",'AuctionTheme'); ?></div>
            	<div class="box_content">	

        
<?php

	
	$nrpostsPage = 8;
	$args = array( 'meta_key' => 'winner', 'meta_value' => $uid ,'posts_per_page' => $nrpostsPage, 'paged' => $paged, 'post_type' => 'auction', 'order' => "DESC" , 'orderby'=>"date");
	$the_query = new WP_Query( $args );
		
		// The Loop
		
		if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			
			auctionTheme_get_post();
	
			
		endwhile;
	
	if(function_exists('wp_pagenavi'))
	wp_pagenavi( array( 'query' => $the_query ) );
	
          ?>
          
          <?php                                
     	else:
		
		echo '<div class="padd10">';
		echo __('No auctions posted.','AuctionTheme');
		echo '</div>';
		
		endif;
		// Reset Post Data
		wp_reset_postdata();

            
					 
		?>
	

	

</div>
</div>

<div class="clear10"></div>

<div class="my_box3">
          
            
            	<div class="box_title"><?php _e("User Latest Feedback",'AuctionTheme'); ?> 
               <span class="sml_ltrs"> [<a href="<?php bloginfo('siteurl'); ?>?a_action=user_feedback&post_author=<?php echo $uid; ?>"><?php _e('See All Feedback','AuctionTheme'); ?></a>]</span>
               </div>
            	<div class="box_content">	
               <!-- ####### -->
                
                
                <?php
					
					global $wpdb;
					$query = "select * from ".$wpdb->prefix."auction_ratings where touser='$uid' AND awarded='1' order by id desc limit 5";
					$r = $wpdb->get_results($query);
					
					if(count($r) > 0)
					{
						echo '<table width="100%">';
							echo '<tr>';
								echo '<th>&nbsp;</th>';	
								echo '<th><b>'.__('Item Title','AuctionTheme').'</b></th>';								
								echo '<th><b>'.__('From User','AuctionTheme').'</b></th>';	
								echo '<th><b>'.__('Aquired on','AuctionTheme').'</b></th>';								
								echo '<th><b>'.__('Price','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('Rating','AuctionTheme').'</b></th>';
								
							
							echo '</tr>';	
						
						
						foreach($r as $row)
						{
							$post = $row->pid;
							$post = get_post($post);
							$bid = auctionTheme_get_winner_bid($row->pid);
							$user = get_userdata($row->fromuser);
							echo '<tr>';
								
								echo '<th>'.AuctionTheme_get_first_post_image($row->pid, 42, 42).'</th>';	
								echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
								echo '<th><a href="'.AuctionTheme_get_user_profile_link($user->user_login).'">'.$user->user_login.'</a></th>';								
								echo '<th>'.date('d-M-Y H:i:s',$row->datemade).'</th>';								
								echo '<th>'.auctionTheme_get_show_price($bid->bid).'</th>';
								echo '<th>'.AuctionTheme_get_auction_stars(floor($row->grade/2)).' ('.floor($row->grade/2).'/5)</th>';
								
							
							echo '</tr>';
							echo '<tr>';
							echo '<th></th>';
							echo '<th colspan="5"><b>'.__('Comment','AuctionTheme').':</b> '.$row->comment.'</th>'	;						
							echo '</tr>';
							
							echo '<tr><th colspan="6"><hr color="#eee" /></th></tr>';
							
						}
						
						echo '</table>';
					}
					else
					{
						echo '<div class="padd10">';
						_e("There are no reviews to be awareded.","AuctionTheme");	
						echo '</div>';
					}
				?>
                
                
				<!-- ####### -->
                </div>
                
            </div>
          


</div>


<div id="right-sidebar" class="col-xs-12 col-sm-4 col-lg-3">
	<ul class="xoxo">
	<?php dynamic_sidebar( 'other-page-area' ); ?>
	</ul>
</div>


<?php

	get_footer();
	
?>
