<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package newslite
 */

/**
 * newslite_action_before_head hook
 * @since newslite 1.0.0
 *
 * @hooked newslite_set_global -  0
 * @hooked newslite_doctype -  10
 */
do_action( 'newslite_action_before_head' );?>



<head>

	<?php
	/**
	 * newslite_action_before_wp_head hook
	 * @since newslite 1.0.0
	 *
	 * @hooked newslite_before_wp_head -  10
	 */
	do_action( 'newslite_action_before_wp_head' );

	wp_head();

	/**
	 * newslite_action_after_wp_head hook
	 * @since newslite 1.0.0
	 *
	 * @hooked null
	 */
	do_action( 'newslite_action_after_wp_head' );
	?>

</head>

<body <?php body_class(); ?>>

<?php
/**
 * newslite_action_before hook
 * @since newslite 1.0.0
 *
 * @hooked newslite_page_start - 15
 */
do_action( 'newslite_action_before' );

/**
 * newslite_action_before_header hook
 * @since newslite 1.0.0
 *
 * @hooked newslite_skip_to_content - 10
 */
do_action( 'newslite_action_before_header' );

/**
 * newslite_action_header hook
 * @since newslite 1.0.0
 *
 * @hooked newslite_after_header - 10
 */
do_action( 'newslite_action_header' );

/**
 * newslite_action_nav_page_start hook
 * @since newslite 1.0.0
 *
 * @hooked page start and navigations - 10
 */
do_action( 'newslite_action_nav_page_start' );

/**
 * newslite_action_on_header hook
 * @since newslite 1.0.0
 *
 * @hooked page start and navigations - 10
 */
do_action( 'newslite_action_on_header' );

/**
 * newslite_action_before_content hook
 * @since newslite 1.0.0
 *
 * @hooked null
 */
do_action( 'newslite_action_before_content' );
?>

