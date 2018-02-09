<?php $ad_code = trim( get_theme_mod( 'main_stream_ad_code' ) ); if ( $ad_code ) { ?>

<div class="main-stream-ad ad-code">

    <?php echo do_shortcode( $ad_code ); ?>

</div>
<?php } elseif ( $banner = trim( get_theme_mod( 'main_stream_banner' ) ) ) {
    $url = trim( get_theme_mod( 'main_stream_banner_url' ) );
if ( $url ) {
    $open = '<a href="' . esc_url( $url ) . '" target="_blank">';
    $close = '</a>';
} else {
    $open = $close = '';
}
?>
<div class="main-stream-ad ad-code">
    <?php echo $open; ?>
    <img src="<?php echo esc_url( $banner ); ?>" alt="<?php echo esc_html__( 'Banner', 'wi' ); ?>" />
    <?php echo $close; ?>
</div>
<?php } ?>