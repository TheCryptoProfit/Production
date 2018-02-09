<?php
/**
 * Provides the markup for a select field
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><th><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'steemit-feed' ); ?>: </label></th><?php

}

if ( empty( $atts['value'] ) ) {
	
	$atts['value'] = '0';
	
}

?><td><select
	aria-label="<?php esc_attr( _e( $atts['aria'], 'steemit-feed' ) ); ?>"
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"><?php

if ( ! empty( $atts['blank'] ) ) {

	?><option value><?php esc_html_e( $atts['blank'], 'steemit-feed' ); ?></option><?php

}

foreach ( $atts['selections'] as $selection ) {

	if ( is_array( $selection ) ) {

		$label = $selection['label'];
		$value = $selection['value'];

	} else {

		$label = strtolower( $selection );
		$value = strtolower( $selection );

	}

	?><option
		value="<?php echo esc_attr( $value ); ?>" <?php
		selected( $atts['value'], $value ); ?>><?php

		esc_html_e( $label, 'steemit-feed' );

	?></option><?php

} // foreach

?></select>
<p class="description"><?php esc_html_e( $atts['description'], 'steemit-feed' ); ?></p>
</td>