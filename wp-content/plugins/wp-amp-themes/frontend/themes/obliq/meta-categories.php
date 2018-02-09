<?php
$categories = get_the_category();

if( empty( $categories ) ) {
	return;
}

?>
<?php foreach ( $categories as $category ) : ?>
	<span class="ampstart-header-category mb2"><?php echo esc_html( $category->cat_name ); ?> </span> <br/>
<?php endforeach; ?>
