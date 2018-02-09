<?php
$featured_image =  has_post_thumbnail();

if( !$featured_image ) {
	return;
}

?>
<amp-img layout="responsive" class="vw vh" width="375" height="667" src="<?php the_post_thumbnail_url( 'full' ); ?>"></amp-img>
