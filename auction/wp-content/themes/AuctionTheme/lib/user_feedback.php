<?php

	global $wpdb,$wp_rewrite,$wp_query;
	$username = $wp_query->query_vars['post_author'];
	$uid = $username;
	$paged = $wp_query->query_vars['paged'];

	$user = get_userdata($uid);
	$username = $user->user_login;

	function sitemile_filter_ttl($title){return __("User Feedback",'AuctionTheme')." - ";}
	add_filter( 'wp_title', 'sitemile_filter_ttl', 10, 3 );	
	
	get_header();
?>


	<div id="content" class="col-xs-12 col-sm-8 col-lg-9">
    		<div class="my_box3">
            
            	<div class="box_title"><?php _e("User Feedback",'AuctionTheme'); ?> - <?php echo $username; ?></div>
            	<div class="box_content">	
               <!-- ####### -->
                
                
                <?php
					
					global $wpdb;
					$query = "select * from ".$wpdb->prefix."auction_ratings where touser='$uid' AND awarded='1' order by id desc";
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
							$post_aux = $row->pid;
							$post_aux = get_post($post_aux);
							$bid = auctionTheme_get_winner_bid($row->bid_id);
							$user = get_userdata($row->fromuser);
							echo '<tr>';
								
								echo '<th> '.AuctionTheme_get_first_post_image($row->pid, 42, 42).' </th>';	
								echo '<th><a href="'.get_permalink($row->pid).'">'.$post_aux->post_title.'</a></th>';
								echo '<th><a href="'.AuctionTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';								
								echo '<th>'.date('d-M-Y H:i:s',$row->datemade).'</th>';								
								echo '<th>'.AuctionTheme_get_show_price($bid->bid).'</th>';
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
						_e("There are no reviews for this user yet.","AuctionTheme");	
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

	//sitemile_after_content(); 

	get_footer();
	
?>
