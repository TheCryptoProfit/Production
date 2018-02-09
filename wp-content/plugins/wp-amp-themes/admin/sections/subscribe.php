<?php

$wp_amp_themes_options = new \WP_AMP_Themes\Includes\Options();
$joined_subscriber_list = $wp_amp_themes_options->get_setting( 'joined_subscriber_list' );

?>

<div class="container wp_amp_themes_subscribe_container">
	<h2>News & Updates</h2>
	<hr class="separator" />
	<p>Join our subscribers list and you'll be notified when new themes &amp; features are on their way.</p>
	<div class="spacer-10"></div>
	<?php if ( false == $joined_subscriber_list ) : ?>
		<form id="wp_amp_themes_subscribe_form" name="wp_amp_themes_subscribe_form" method="post">
			<input name="wp_amp_themes_subscribe_email" id="wp_amp_themes_subscribe_email" type="text" placeholder="Your e-mail address" class="small" value="<?php echo get_option( 'admin_email' );?>" />
			<div class="spacer-0"></div>
			<p class="field-message error" id="error_email_container"></p>
			<div class="spacer-0"></div>
			<a class="button button-primary button-large" href="javascript:void(0)" id="wp_amp_themes_subscribe_send_btn">Subscribe</a>
		</form>
	<?php endif;?>
	<div id="wp_amp_themes_subscribe_added" class="added" style="display: <?php echo $joined_subscriber_list ? 'block' : 'none'?>;">
		<div class="switcher blue">
			<div class="msg">SUBSCRIBED</div>
			<div class="check"><span class="dashicons dashicons-yes"></span></div>
		</div>
		<div class="spacer-15"></div>
	</div>
</div>
<div class="spacer-15"></div>

<?php if ( false == $joined_subscriber_list ) : ?>
<script type="text/javascript">
	if (window.WATJSInterface && window.WATJSInterface != null){
		jQuery(document).ready(function(){
			window.WATJSInterface.add("UI_subscribe",
			"WP_AMP_THEMES_SUBSCRIBE",
				{
					'DOMDoc':       window.document,
					'container' :   window.document.getElementById('wp_amp_themes_subscribe_container'),
					'submitURL' :   '<?php echo WP_AMP_THEMES_SUBSCRIBE_PATH;?>'
				},
				window
			);
		});
	}
</script>
<?php endif;?>
