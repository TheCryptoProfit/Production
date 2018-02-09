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

function AuctionTheme_display_blog_page_disp()
{

		global $current_user, $wp_query;
		$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	$paged = $wp_query->query_vars['paged'];

	?>


   <div class="col-xs-12 col-sm-8 col-lg-9">

<div class="my_box3">

            		<div class="box_title"><?php echo __("All Blog Posts", 'AuctionTheme'); ?></div>
            		<div class="box_content">

                    <?php

					$args = array('post_type' => 'post', 'paged' => $paged);
					$my_query = new WP_Query( $args );

					if($my_query->have_posts()):
					while ( $my_query->have_posts() ) : $my_query->the_post();

						AuctionTheme_get_post_blog();

					endwhile;

						if(function_exists('wp_pagenavi')):
							wp_pagenavi( array( 'query' => $my_query ) );
						endif;

					else:
					_e('There are no posts.','AuctionTheme');

					endif;



					?>

  </div></div></div>


      <!-- ################### -->

    <div id="right-sidebar" class="col-xs-12 col-sm-4 col-lg-3">
    	<ul class="xoxo">
        	 <?php dynamic_sidebar( 'other-page-area' ); ?>
        </ul>
    </div>

    <?php

}

?>
