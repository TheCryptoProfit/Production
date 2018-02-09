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

if(!function_exists('AuctionTheme_my_account_area_function'))
{
function AuctionTheme_my_account_area_function()
{
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

?>
		<div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">
        	<?php

				if(AuctionTheme_is_able_to_post_auctions()):

			?>
        		<div class="my_box3 ">
            	<div class="box_title"><?php _e("My Latest Active Auctions",'AuctionTheme'); ?></div>
                <div class="box_content">


                    <?php

					query_posts( "meta_key=closed&meta_value=0&post_type=auction&order=DESC&orderby=id&author=".$uid."&posts_per_page=3" );

					if(have_posts()) :
					while ( have_posts() ) : the_post();
						auctionTheme_get_post();
					endwhile; else:

					echo '<div class="padd10">';
					_e("There are no auctions yet.",'AuctionTheme');
					echo '</div>';

					endif;

					wp_reset_query();

					?>

                </div>
                </div>


                <div class="clear10"></div>


			<div class="my_box3">
            	<div class="box_title"><?php _e("My Unpaid/Pending Auctions",'AuctionTheme'); ?></div>
                <div class="box_content">


				<?php

				query_posts( "post_status=draft&post_type=auction&order=DESC&orderby=id&author=".$uid."&posts_per_page=3" );

				if(have_posts()) :
				while ( have_posts() ) : the_post();
					auctionTheme_get_post();
				endwhile; else:

				echo '<div class="padd10">';
				_e("There are no auctions yet.",'AuctionTheme');
				echo '</div>';

				endif;

				wp_reset_query();

				?>


			</div>
			</div>


           <div class="clear10"></div>


			<div class="my_box3">
            	<div class="box_title"><?php _e("My Latest Closed Auctions",'AuctionTheme'); ?></div>
                <div class="box_content">


				<?php

				query_posts( "meta_key=closed&meta_value=1&post_type=auction&order=DESC&orderby=id&author=".$uid."&posts_per_page=3" );

				if(have_posts()) :
				while ( have_posts() ) : the_post();
					auctionTheme_get_post();
				endwhile; else:

				echo '<div class="padd10">';
				_e("There are no auctions yet.",'AuctionTheme');
				echo '</div>';

				endif;
				wp_reset_query();

				?>

			</div>
			</div>

                <div class="clear10"></div>
                <?php endif; ?>

                <div class="my_box3">
            	<div class="box_title"><?php _e("My Latest Bids",'AuctionTheme'); ?></div>
                <div class="box_content">


                    <?php

					query_posts( "meta_key=bid&meta_value=".$uid."&post_type=auction&order=DESC&orderby=id&posts_per_page=3" );

					if(have_posts()) :
					while ( have_posts() ) : the_post();
						auctionTheme_get_post();
					endwhile; else:

					echo '<div class="padd10">';
					_e("There are no bids yet.",'AuctionTheme');
					echo '</div>';

					endif;

					wp_reset_query();

					?>

                </div>
                </div>


             <!-- ##################### -->

        </div>


<?php	auctionTheme_get_users_links();
}
}

?>
