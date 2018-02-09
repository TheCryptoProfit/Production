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


				echo '<h3 class="widget-title">'.__('Latest Posted Auctions','AuctionTheme').'</h3>';
			 
				
				$AuctionTheme_listings_home_pg_th = get_option('AuctionTheme_listings_home_pg_th');
				if(empty($AuctionTheme_listings_home_pg_th) or !is_numeric($AuctionTheme_listings_home_pg_th))	$limit = 12;
				else 	$limit = $AuctionTheme_listings_home_pg_th;
				
				//---------------------------------------------------
				
				 global $wpdb;	
				 $querystr = "
					SELECT distinct wposts.* 
					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
					WHERE wposts.ID = wpostmeta.post_id
					AND wpostmeta.meta_key = 'closed' 
					AND wpostmeta.meta_value = '0' AND 
					wposts.post_status = 'publish' 
					AND wposts.post_type = 'auction' 
					ORDER BY wposts.post_date DESC LIMIT ".$limit;
				
				 $pageposts = $wpdb->get_results($querystr, OBJECT);
				 
				 ?>
					
					 <?php $i = 0; if ($pageposts): ?>
					 <?php global $post; ?>
                     <?php foreach ($pageposts as $post): ?>
                     <?php setup_postdata($post); ?>
                     
                     
                     <?php auctionTheme_get_post(); ?>
                     
                     
                     <?php endforeach; ?>
                     <?php else : ?> <?php $no_p = 1; ?>
                       <div class="padd10"><p class="center"><?php _e("Sorry, there are no posted auctions yet","AuctionTheme"); ?>.</p></div>
                        
                     <?php endif; ?>
				 