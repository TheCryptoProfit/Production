<div>
	<div class="onoffswitch-sm">
	    <input type="checkbox" name="settings[Firewall][File][settings][POST]" class="onoffswitch-checkbox-sm" id="onoff-sm-file-post" value="enabled" <?php echo (isset($settings['settings']['POST']) && $settings['settings']['POST'] == 'enabled' ? 'checked="checked"' : '')?>>
	    <label class="onoffswitch-label-sm" for="onoff-sm-file-post">
		    <span class="onoffswitch-inner-sm"></span>
		    <span class="onoffswitch-switch-sm"></span>
	    </label>
    </div>
	<label><?php _e('POST requests', 'SwiftSecurity');?></label>
		<div class="input-group">
			<label><?php _e('Content based', 'SwiftSecurity');?></label>
			<span class="tooltip-icon" data-tooltip="<?php _e('Regular expression filters for uploaded file\'s content. For example if uploaded file contains <?php firewall will block the upload','SwiftSecurity');?>" data-tooltip-position="right">?</span>	
 			<?php if (isset($settings['POST']['content'])) : ?>
 			<?php foreach ((array)$settings['POST']['content'] as $regExp) :?>
 			<div>
 				<input type="text" name="settings[Firewall][File][POST][content][]" value="<?php swiftsecurity_safe_echo($regExp);?>">
 				<a href="#" class="remove-input close-icon"></a>
 			</div>
 			<?php endforeach;?>
 			<?php endif;?>
 			<div class="sample-container">
 				<input type="text" class="input-sample" data-name="settings[Firewall][File][POST][content][]">
 				<a href="#" class="remove-input close-icon"></a>
 			</div>
			<label><?php _e('Extension based', 'SwiftSecurity');?></label>
			<span class="tooltip-icon" data-tooltip="<?php _e('Filter for uploaded file\'s extensions. For example if you set .php users can\'t upload .php files','SwiftSecurity');?>" data-tooltip-position="right">?</span>
			 <?php if (isset($settings['POST']['extension'])) : ?>
 			<?php foreach ((array)$settings['POST']['extension'] as $regExp) :?>
 			<div>
 				<input type="text" name="settings[Firewall][File][POST][extension][]" value="<?php swiftsecurity_safe_echo($regExp);?>">
 				<a href="#" class="remove-input close-icon"></a>
 			</div>
 			<?php endforeach;?>
 			<?php endif;?>
 			<div class="sample-container">
 				<input type="text" class="input-sample" data-name="settings[Firewall][File][POST][extension][]">
 				<a href="#" class="remove-input close-icon"></a>	 				
 			</div>
 			<label><?php _e('Exceptions', 'SwiftSecurity');?></label>
 			<?php if (isset($settings['exceptions']['POST'])) : ?>
 			<?php foreach ((array)$settings['exceptions']['POST'] as $exception) :?>
 			<div>
 				<input type="text" name="settings[Firewall][File][exceptions][POST][]" value="<?php swiftsecurity_safe_echo($exception)?>">
 				<a href="#" class="remove-input close-icon"></a>
 			</div>
 			<?php endforeach;?>
 			<?php endif;?>
 			<div class="sample-container">
 				<input type="text" class="input-sample" data-name="settings[Firewall][File][exceptions][POST][]">
 				<a href="#" class="remove-input close-icon"></a>
 			</div>
		</div>
	</div>