<?php get_header();
    // Start the loop.
    if ( have_posts() ) :
?>

<div class="container">
    
    <div class="content">
    
        <div class="single-body">

            <?php
                woocommerce_content();
            ?>
            <div class="clearfix"></div>

        </div><!-- .single-body -->
        
        <div class="clearfix"></div>
    </div><!-- .content -->
</div><!-- .container -->
    
<?php
// End the loop.
endif;

get_footer();