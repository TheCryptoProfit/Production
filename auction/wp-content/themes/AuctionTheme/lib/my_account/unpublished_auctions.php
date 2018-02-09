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


function AuctionTheme_my_account_unpub_auctions_area_function()
{
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	global $wpdb,$wp_rewrite,$wp_query;

	?>

<div id="content" class="account-content-area">
            <div class="my_box3">

            	<div class="box_title"><?php _e("Unpublished Auctions","AuctionTheme"); ?></div>
            	<div class="box_content">



					<?php

					global $wp_query;
					$query_vars = $wp_query->query_vars;
					$post_per_page = 8;

					query_posts( "post_status=draft&post_type=auction&order=DESC&orderby=id&author=".$uid."&posts_per_page=" . $post_per_page . "&paged=" . $query_vars['paged'] );

					if(have_posts()) :
					while ( have_posts() ) : the_post();
						auctionTheme_get_post();
					endwhile;

					if(function_exists('wp_pagenavi')):
					wp_pagenavi(); endif;

					else:

					_e("There are no closed auctions yet.",'AuctionTheme');

					endif;

					wp_reset_query();

					?>




                </div>
                </div>



    <!-- ############################################# -->
    </div>

    <?php

	auctionTheme_get_users_links();

}

?>
