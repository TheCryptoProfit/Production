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

function AuctionTheme_watch_list_area_function()
{


	global $current_user;
		$current_user = wp_get_current_user();
	$uid = $current_user->ID;


	?>
    <div id="content" class="col-xs-12 col-sm-8 col-lg-9">

    		<div class="my_box3" >

                <div class="box_title"><?php echo __("Watch List", 'AuctionTheme'); ?></div>
                <div class="box_content">


                                <?php


				global $wpdb, $wp_query;
				$s = "select * from ".$wpdb->prefix."auction_watchlist where uid='$uid' order by id asc";
				$r = $wpdb->get_results($s);

				$my_arr = array();

				if(count($r) > 0)
				foreach($r as $item)
				{
					$my_arr[] = $item->pid;
				}

				if(count($my_arr) == 0) $my_arr[0] = 0;

				$args = array('post__in' => $my_arr,
				'post_type' 	=> 'auction',
				'paged'			=> $wp_query->query_vars['paged']);

				$the_query = new WP_Query( $args );

				if($the_query->have_posts()):
				// The Loop
				while ( $the_query->have_posts() ) : $the_query->the_post();

					auctionTheme_get_post();

				endwhile;

				if(function_exists('wp_pagenavi')):

					echo '<div class="navi-wrap">';
					wp_pagenavi( array( 'query' => $the_query ) );
					echo '</div>';

				endif;

				else:
				echo '<div class="padd10">';
					_e('There are no auctions in your watch list.','AuctionTheme');
					echo '</div>';
				endif;

				// Reset Post Data
				wp_reset_postdata();

				?>



                </div>
            </div>


    </div>


    <!-- ################ -->

    <div id="right-sidebar" class="col-xs-12 col-sm-4 col-lg-3  ">
    <ul class="xoxo">
        <?php dynamic_sidebar( 'other-page-area' ); ?>
    </ul>
    </div>

    <?php

}

?>
