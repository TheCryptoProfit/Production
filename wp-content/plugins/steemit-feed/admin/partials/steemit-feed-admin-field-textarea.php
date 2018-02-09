<?php
/**
 * Provides the markup for any textarea field
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><th><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'steemit-feed' ); ?>: </label></th><?php

}

?><td><textarea
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	cols="<?php echo esc_attr( $atts['cols'] ); ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"
	rows="<?php echo esc_attr( $atts['rows'] ); ?>"><?php

	echo esc_textarea( $atts['value'] );

?></textarea>
<p class="description"><?php esc_html_e( $atts['description'], 'steemit-feed' ); ?></p>
</td>