<?php
 defined('ABSPATH') or die("KEEP CALM AND CARRY ON");
 global $SwiftSecurity;
?>
<div>
	<div class="pull-right">
		<?php if(isset($settings['GlobalSettings']['hide-menu']) && $settings['GlobalSettings']['hide-menu'] == 'enabled'):?>
			<a href="<?php echo admin_url( 'options-general.php?page=SwiftSecurity');?>" class="sft-btn btn-sm"><?php _e('Dashboard', 'SwiftSecurity');?></a>
		<?php endif;?>
	</div>	
	<h2>Swift Security - <?php _e('Troubleshooting','SwiftSecurity');?></h2>
</div>
<!-- Automated testing-->
<div id="swift-security-items" class="swift-security-troubleshooting">
	<div class="swift-security-db">
		<h3><?php _e('Automated testing','SwiftSecurity');?></h3>
		<p><?php _e('If you have any problems on your page (eg. images are not loading) the plugin can automatically identify and fix them.', 'SwiftSecurtiy');?></p>
		<label><?php _e('URL to check','SwiftSecurity');?>:</label>
		<input type="text" id="swiftsecurity_at_url" value="<?php echo home_url(); ?>">
		<button id="swiftsecurity_at_start" class="sft-btn btn-sm btn-gray"><?php _e('Start','SwiftSecurity');?></button>
		<div id="swiftsecurity_at_container"></div>
	</div>
	<br>
	<!-- Bug report -->
	<div class="swift-security-db">
		<h3><?php _e('Bug report','SwiftSecurity');?></h3>
		<p><?php _e('Problems still persist? Please send a bug report to us.','SwiftSecurity');?></p>
		<form method="post">
			<div class="ss-clearfix">
				<label><?php _e('Your e-mail address','SwiftSecurity');?>:</label>
				<input type="text" name="reporter_email" value="<?php echo bloginfo('admin_email');?>">
			</div>
			<div class="ss-clearfix">
				<label><?php _e('Please describe your problem','SwiftSecurity');?>:</label>
				<textarea name="bug_description"></textarea>
			</div>
			<div class="clearfix">
				<?php _e('Include system data','SwiftSecurity');?>
				<input type="checkbox" name="include_system_data" value='enabled' checked>
				<span class="tooltip-icon" data-tooltip="<?php _e('Include your current theme, active plugins and phpinfo','SwiftSecurity');?>" data-tooltip-position="right">?</span>
			</div>
			<br>
			<input type="hidden" name="sq" value="<?php echo $SwiftSecurity->settings['GlobalSettings']['sq']?>">
			<button name="swiftsecurity_bug_report" value="send" class="sft-btn btn-sm btn-gray"><?php _e('Send','SwiftSecurity');?></button>
		</form>
	</div>
	
	
</div>