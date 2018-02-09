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

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>



<div class="col-xs-12 col-sm-8 col-lg-9">	
			<div class="my_box3">
            
            	<div class="box_title"><?php  the_title(); ?></div>
                <div class="box_content post-content"> 


<?php the_content(); ?>			
<?php comments_template( '', true ); ?>

    </div>
			</div>
			</div>

        

<?php endwhile; // end of the loop. ?>

<div id="right-sidebar" class="col-xs-12 col-sm-4 col-lg-3">
    <ul class="xoxo">
        <?php dynamic_sidebar( 'single-widget-area' ); ?>
    </ul>
</div>

<?php get_footer(); ?>