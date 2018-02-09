<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
		<?php do_action( 'amp_post_template_head', $this ); ?>
		<style amp-custom>
			<?php $this->load_parts( array( 'style' ) ); ?>
			<?php do_action( 'amp_post_template_css', $this ); ?>
		</style>
	</head>


	<body>
		<!-- Start Navbar -->
		<header class="ampstart-headerbar <?php echo ( has_post_thumbnail() ) ? 'has-image' : '' ?> fixed flex justify-start items-center top-0 left-0 right-0 pl2 pr4">
			<div role="button" aria-label="open sidebar" on="tap:header-sidebar.toggle" tabindex="0" class="ampstart-navbar-trigger  pr2">&#9776;</div>
		</header>

		<?php $this->load_parts( ['side-menu'] ); ?>
		<!-- End Navbar -->

		<figure class="ampstart-image-fullpage-hero m0 relative">

			<?php $this->load_parts( ['featured-image'] ); ?>

			<figcaption <?php echo ( has_post_thumbnail() ) ? 'class="absolute bottom-0 right-0 left-0"' : 'class="has-mt-2"' ?>>
				<header class="ampstart-header">
					<div class="ampstart-header-obliq"></div>
					<div class="ampstart-header-inner p3">

					<?php $this->load_parts( ['meta-categories'] ); ?>

					<h3 class="mb1"><?php echo wp_kses_data( $this->get( 'post_title' ) ); ?></h3>

					<?php $this->load_parts( ['meta-author-date'] ); ?>
					</div>
				</header>
			</figcaption>
		</figure>

		<!-- End Fullpage Hero -->
		<main id="content" role="main" class="px3">

			<article class="photo-article ampstart-article-content">
				<?php echo $this->get( 'post_amp_content' ); // amphtml content; no kses ?>
			</article>
		</main>

		<?php do_action( 'amp_post_template_footer', $this ); ?>
   </body>
</html>
