<?php
/**
 * Provides the markup for a radio field
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

?>
<td>

<fieldset>
<?php
foreach ( $atts['selections'] as $selection ) {

	if ( is_array( $selection ) ) {

		$label = $selection['label'];
		$value = $selection['value'];
		$image = $selection['image'];

	} else {

		$label = strtolower( $selection );
		$value = strtolower( $selection );
		$image = strtolower( $selection );
		
	}

	?><div class="grid-radio">
		
		<label>
		
			<p><?php echo $label; ?></p>
			
			<div class="grid-radio-demo-cont">
				<div class="grid-radio-demo">
					<img alt="<?php echo esc_html_e( $label, 'steemit-feed' ); ?>" src="<?php echo esc_url( plugin_dir_url(dirname(__FILE__)).'images/'.$image ); ?>">
				</div>
			</div>
					
			<input
				type="radio"
				name="<?php echo esc_attr( $atts['name'] ); ?>"
				value="<?php echo esc_attr( $value ); ?>" 
				class="grid-radio-input" <?php
				checked( $atts['value'], $value ); ?>>
	
		</label>	
		
	</div>

<?php
} // foreach

?>
</fieldset>
</td>
