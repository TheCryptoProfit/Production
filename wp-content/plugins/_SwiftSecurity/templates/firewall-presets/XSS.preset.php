<div>
	<div class="onoffswitch-sm">
	    <input type="checkbox" name="settings[Firewall][XSS][settings][GET]" class="onoffswitch-checkbox-sm" id="onoff-sm-xss-get" value="enabled" <?php echo (isset($settings['settings']['GET']) && $settings['settings']['GET'] == 'enabled' ? 'checked="checked"' : '')?>>
	    <label class="onoffswitch-label-sm" for="onoff-sm-xss-get">
		    <span class="onoffswitch-inner-sm"></span>
		    <span class="onoffswitch-switch-sm"></span>
	    </label>
    </div>
	<label><?php _e('GET requests', 'SwiftSecurity');?></label>
	<div class="input-group">
		<label><?php _e('Rules', 'SwiftSecurity');?></label>
		<span class="tooltip-icon" data-tooltip="<?php _e('Block GET requests which matches the defined regular expression. For example if a user try to inject script to page content','SwiftSecurity');?>" data-tooltip-position="right">?</span>
		<?php if(isset($settings['GET'])) :?>	
		<?php foreach ((array)$settings['GET'] as $regExp) :?>
		<div>
			<input type="text" name="settings[Firewall][XSS][GET][]" value="<?php swiftsecurity_safe_echo($regExp);?>">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][XSS][GET][]">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<label><?php _e('Exceptions', 'SwiftSecurity');?></label>
		<span class="tooltip-icon" data-tooltip="<?php _e('Files, where file-path filters are ignored. (eg: wp-comments-post.php)','SwiftSecurity');?>" data-tooltip-position="right">?</span>
		<?php if(isset($settings['exceptions']['GET'])) :?>		
		<?php foreach ((array)$settings['exceptions']['GET'] as $exception) :?>
		<div>
			<input type="text" name="settings[Firewall][XSS][exceptions][GET][]" value="<?php swiftsecurity_safe_echo($exception)?>">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][XSS][exceptions][GET][]">
			<a href="#" class="remove-input close-icon"></a>
		</div>
	</div>
</div>
<div>
	<div class="onoffswitch-sm">
	    <input type="checkbox" name="settings[Firewall][XSS][settings][POST]" class="onoffswitch-checkbox-sm" id="onoff-sm-xss-post" value="enabled" <?php echo (isset($settings['settings']['POST']) && $settings['settings']['POST'] == 'enabled' ? 'checked="checked"' : '')?>>
	    <label class="onoffswitch-label-sm" for="onoff-sm-xss-post">
		    <span class="onoffswitch-inner-sm"></span>
		    <span class="onoffswitch-switch-sm"></span>
	    </label>
    </div>
	<label><?php _e('POST requests', 'SwiftSecurity');?></label>
	<div class="input-group">
		<label><?php _e('Rules', 'SwiftSecurity');?></label>
		<span class="tooltip-icon" data-tooltip="<?php _e('Block GET requests which matches the defined regular expression. For example if a user try to inject script to page content','SwiftSecurity');?>" data-tooltip-position="right">?</span>
		<?php if(isset($settings['POST'])) :?>		
		<?php foreach ((array)$settings['POST'] as $regExp) :?>
		<div>
			<input type="text" name="settings[Firewall][XSS][POST][]" value="<?php swiftsecurity_safe_echo($regExp);?>">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][XSS][POST][]">
			<a href="#" class="remove-input close-icon"></a>	 				
		</div>
		<label><?php _e('Exceptions', 'SwiftSecurity');?></label>
		<span class="tooltip-icon" data-tooltip="<?php _e('Files, where file-path filters are ignored. (eg: wp-comments-post.php)','SwiftSecurity');?>" data-tooltip-position="right">?</span>
		<?php if(isset($settings['exceptions']['POST'])) :?>		
		<?php foreach ((array)$settings['exceptions']['POST'] as $exception) :?>
		<div>
			<input type="text" name="settings[Firewall][XSS][exceptions][POST][]" value="<?php swiftsecurity_safe_echo($exception)?>">
			<a href="#" class="remove-input close-icon"></a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
		<div class="sample-container">
			<input type="text" class="input-sample" data-name="settings[Firewall][XSS][exceptions][POST][]">
			<a href="#" class="remove-input close-icon"></a>	 				
		</div>
	</div>
</div>