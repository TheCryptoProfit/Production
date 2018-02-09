<?php
/**
 * Provides the markup for any text field
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><th><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'steemit-feed' ); ?>: </label></th><?php

}

?><td><input
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"
	placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>"
	type="<?php echo esc_attr( $atts['type'] ); ?>"
	value="<?php echo esc_attr( $atts['value'] ); ?>" /><?php

if ( ! empty( $atts['description'] ) ) {

	?><p class="description">
		<?php
		if (array_key_exists('desc_link', $atts)) {
		?><a href="<?php echo esc_url( $atts['desc_link'] ); ?>" target="_blank">
		<?php }
		esc_html_e( $atts['description'], 'steemit-feed' );
		if (array_key_exists('desc_link', $atts)) {
		?></a>
		<?php } ?>
	</p><?php

} 
?></td>