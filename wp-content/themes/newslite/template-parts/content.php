<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package newslite
 */

global $newslite_customizer_all_values;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
 <div class="wrapper-grid">

	<div class="entry-content <?php if (!has_post_thumbnail( )) { echo 'non-image';}?>">
		<?php
		$newslite_archive_layout = $newslite_customizer_all_values['newslite-archive-layout'];
		$newslite_archive_image_align = $newslite_customizer_all_values['newslite-archive-image-align'];
		if( 'excerpt-only' == $newslite_archive_layout ){
			the_excerpt();
		}
		elseif( 'full-post' == $newslite_archive_layout ){
			the_content( sprintf(
			/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'newslite' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		}
		elseif( 'thumbnail-and-full-post' == $newslite_archive_layout ){
			if (has_post_thumbnail( )) {
				if( 'left' == $newslite_archive_image_align ){
					echo "<div class='image-left post-image'>";
					echo '<a href="'.esc_url(get_permalink()).'">';
					the_post_thumbnail('medium');
				}
				elseif( 'right' == $newslite_archive_image_align ){
					echo "<div class='image-right post-image'>";
					echo '<a href="'.esc_url(get_permalink()).'">';
					the_post_thumbnail('medium');
				}
				else{
					echo "<div class='image-full post-image'>";
					echo '<a href="'.esc_url(get_permalink()).'">';
					the_post_thumbnail('full');
				}
				echo "</a>";
				echo "</div>";/*div end*/ 
			}
				echo "<div class='entry-content-stat'>";
			?> 
			<header class="entry-header">
				<div class="entry-category">
					<?php newslite_entry_footer(); ?>
				</div>
				<?php
				if ( is_single() ) {
				} else {
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				}
				if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta">
						<?php newslite_posted_on(); ?>
					</div><!-- .entry-meta -->
				<?php
				endif;
				 ?>
			</header><!-- .entry-header -->
			<?php the_content( sprintf(
			/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'newslite' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
			echo "</div>";
		}
		else{
			if (has_post_thumbnail( )) {
				if( 'left' == $newslite_archive_image_align ){
					echo "<div class='image-left post-image'>";
					echo '<a href="'.esc_url(get_permalink()).'">';
					the_post_thumbnail('medium');
				}
				elseif( 'right' == $newslite_archive_image_align ){
					echo "<div class='image-right post-image'>";
					echo '<a href="'.esc_url(get_permalink()).'">';
					the_post_thumbnail('medium');
				}
				else{
					echo "<div class='image-full post-image'>";
					echo '<a href="'.esc_url(get_permalink()).'">';
					the_post_thumbnail('full');
				}
				echo "</a>";
				echo "</div>";/*div end*/
			}
				echo "<div class='entry-content-stat'>";
			 ?>
			<header class="entry-header">
				<div class="entry-category">
					<?php newslite_entry_footer(); ?>
				</div>
				<?php
				if ( is_single() ) {
				} else {
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				}
				if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta">
						<?php newslite_posted_on(); ?>
					</div><!-- .entry-meta -->
				<?php
				endif; 
				?>
			</header><!-- .entry-header -->
			<?php the_excerpt();
			echo "</div>";
		}
		?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'newslite' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	</div>
</article><!-- #post-## -->