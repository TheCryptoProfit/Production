<?php
	$post_author = $this->get( 'post_author' );

	if ( ! $post_author ) {
		return;
	}

	$date = date_i18n( 'F j, Y', $this->get( 'post_publish_timestamp' ) );
	$url = get_author_posts_url( $post_author->ID );
?>
<span class="ampstart-header-meta">
	By <a href="<?php echo esc_url( $url ) ?>" role="author" class="text-decoration-none"><?php echo esc_html( $post_author->display_name ); ?></a>, <?php echo esc_html( $date ); ?>
</span>
