<?php
if ( ! function_exists( 'wi_review' ) ) :
/**
 * Single Post Review
 *
 * @since 2.4
 */
function wi_review() {
    
    $review = get_post_meta( get_the_ID(), '_wi_review', true ); if ( ! $review || ! is_array( $review ) ) return;
    $items = '';
    ob_start();
    
    foreach ( $review as $item ) : if ( ! isset( $item[ 'criterion' ] ) || ! isset( $item[ 'score' ] ) || ! $item[ 'criterion' ] || ! $item[ 'score' ] ) continue; ?>

<div class="review-item">

    <div class="review-criterion"><?php echo $item[ 'criterion' ]; ?></div>
    <div class="review-score"><?php echo $item[ 'score' ]; ?><span class="unit">/10</span></div>

</div>

<?php endforeach; ?>

<?php $average = get_post_meta( get_the_ID(), '_wi_review_average', true ); ?>

<?php if ( $average && is_numeric( $average ) ) : ?>

<div class="review-item overrall">

    <div class="review-criterion"><?php echo esc_html__( 'Overrall', 'wi' ); ?></div>
    <div class="review-score"><?php echo number_format((float)$average, 1, '.', ''); ?><span class="unit">/10</span></div>

</div>

<?php endif; ?>

<?php
    
    $items = trim ( ob_get_clean() );
    if ( ! $items ) return;
    
?>

<div id="review-wrapper">
    
    <h2 id="review-heading"><?php echo esc_html__( 'Review', 'wi' ); ?></h2>
    
    <div id="review">
        
        <?php echo $items ; ?>
        
        
    </div>
    
</div>

<?php
}
endif;