<?php
	$categories = get_categories();
	$pages = get_pages();
	$logo = $this->get_customizer_setting( 'logo' );
?>


<!-- Start Sidebar -->
<amp-sidebar id="header-sidebar" class="ampstart-sidebar  " layout="nodisplay">
	<div class="ampstart-sidebar-inner relative">
		<div class="flex justify-start items-center ampstart-sidebar-header">
			<div role="button" aria-label="close sidebar" on="tap:header-sidebar.toggle" tabindex="0" class="ampstart-navbar-trigger items-start px3 ampstart-navbar-close">✕</div>
		</div>
		<nav class="ampstart-sidebar-nav ampstart-nav">
			<ul class="list-reset m0 p0 ampstart-label">
				<li class="ampstart-nav-item ampstart-nav-dropdown relative">
					<!-- Start Dropdown-inline -->
					<amp-accordion layout="container" disable-session-states="" class="ampstart-dropdown">
						<section>
							<header class="px3 py3">Categories</header>
							<ul class="ampstart-dropdown-items list-reset m0 p0">
							<?php if ( $categories ) : ?>
								<?php foreach ( $categories as $category ) : ?>
									<li class="ampstart-dropdown-item">
										<a href="<?php echo esc_url( get_category_link( $category->cat_ID ) ); ?>" class="text-decoration-none">
											<h6 class="px3"><?php echo esc_html( $category->cat_name ); ?></h6>
										</a>
									</li>
								<?php endforeach; ?>
							<?php endif; ?>
							</ul>
						</section>
					</amp-accordion>
					<!-- End Dropdown-inline -->
				</li>
				<li class="ampstart-nav-item ampstart-nav-dropdown relative">
					<!-- Start Dropdown-inline -->
					<amp-accordion layout="container" disable-session-states="" class="ampstart-dropdown">
						<section>
							<header class="px3 py3">Pages</header>
							<ul class="ampstart-dropdown-items list-reset m0 p0">
								<?php if ( $pages ) : ?>
									<?php foreach ( $pages as $page ) : ?>
										<li class="ampstart-dropdown-item">
											<a href="<?php echo esc_url( get_permalink( $page->ID ) ); ?>" class="text-decoration-none">
												<h6 class="px3"><?php echo esc_html( $page->post_title ); ?></h6>
											</a>
										</li>
									<?php endforeach; ?>
								<?php endif; ?>
							</ul>
						</section>
					</amp-accordion>
					<!-- End Dropdown-inline -->
				</li>
			</ul>
			<?php if ( '' != $logo ): ?>
				<div class="ampstart-logo-wrap">
					<amp-img layout="responsive" class="" width="30" height="30" src="<?php echo esc_url( $logo ); ?>"></amp-img>
				</div>
			<?php endif; ?>
		</nav>
	</div>
</amp-sidebar>
<!-- End Sidebar -->
