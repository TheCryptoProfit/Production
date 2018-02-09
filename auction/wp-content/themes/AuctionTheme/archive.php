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



	get_header();


?>
<?php 

		if(function_exists('bcn_display'))
		{
		    echo '<div class="my_box3 breadcrumb-wrap"><div class="padd10">';	
		    bcn_display();
			echo '</div></div> ';
		}


	

?>	

 

<div id="content">	
			<div class="my_box3">
            
            	<div class="box_title">
				
                <?php if ( is_day() ) : ?>
							<?php printf( __( 'Daily Blog Archives: %s', 'AuctionTheme' ), '<span>' . get_the_date() . '</span>' ); ?>
						<?php elseif ( is_month() ) : ?>
							<?php printf( __( 'Monthly Blog Archives: %s', 'AuctionTheme' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'AuctionTheme' ) ) . '</span>' ); ?>
						<?php elseif ( is_year() ) : ?>
							<?php printf( __( 'Yearly Blog Archives: %s', 'AuctionTheme' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'AuctionTheme' ) ) . '</span>' ); ?>
						<?php else : ?>
							<?php _e( 'Blog Archives', 'AuctionTheme' ); ?>
						<?php endif; ?>
                
                </div>
                <div class="box_content post-content"> 
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<?php AuctionTheme_get_post_blog(); ?>	
		
<?php endwhile; // end of the loop. ?>

    </div>
			
			</div>
            </div>
        



<div id="right-sidebar">
    <ul class="xoxo">
        <?php dynamic_sidebar( 'other-page-area' ); ?>
    </ul>
</div>

<?php get_footer(); ?>