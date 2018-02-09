<?php
if ( isset( $theme ) && isset( $theme['icon'] ) &&
	isset( $theme['details'] ) && isset( $theme['details']['link'] ) && isset( $theme['details']['text'] ) ) :
?>
	<div class="theme-box">
		<a href="<?php echo esc_attr( $theme['details']['link'] ) ?>" target="_blank">
			<img src="<?php echo esc_attr( $theme['icon'] ) ?>" />
		</a>
		<div class="name">
			<?php echo isset( $theme['id'] ) && 2 == $theme['id'] ? '&#x1F680;' : '';?>
			<?php echo isset( $theme['title'] ) ? esc_attr( $theme['title'] ) : '';?>
		</div>
		<p>
			<a href="<?php echo esc_attr( $theme['details']['link'] ) ?>" class="button" target="_blank">
				<?php echo isset( $theme['details']['text'] ) ? $theme['details']['text'] : '';?>
			</a>
		</p>
	</div>
<?php endif;?>
