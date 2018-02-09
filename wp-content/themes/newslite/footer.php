<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 *
 * @package eVision themes
 * @subpackage newslite
 * @since newslite 1.0.0
 */


/**
 * newslite_action_after_content hook
 * @since newslite 1.0.0
 *
 * @hooked null
 */
do_action( 'newslite_action_after_content' );

/**
 * newslite_action_before_footer hook
 * @since newslite 1.0.0
 *
 * @hooked newslite_before_footer - 10
 */
do_action( 'newslite_action_before_footer' );

/**
 * newslite_action_widget_before_footer hook
 * @since newslite 1.0.0
 *
 * @hooked newslite_widget_before_footer - 10
 */
do_action( 'newslite_action_widget_before_footer' );

/**
 * newslite_action_footer hook
 * @since newslite 1.0.0
 *
 * @hooked newslite_footer - 10
 */
do_action( 'newslite_action_footer' );

/**
 * newslite_action_after_footer hook
 * @since newslite 1.0.0
 *
 * @hooked null
 */
do_action( 'newslite_action_after_footer' );

/**
 * newslite_action_after hook
 * @since newslite 1.0.0
 *
 * @hooked newslite_page_end - 10
 */
do_action( 'newslite_action_after' );
?>
<?php wp_footer(); ?>
</body>
</html>