<?php defined('ABSPATH') or die("KEEP CALM AND CARRY ON");?>
<form id="NotificationForm" method="post">	  
	<div class="pull-right">
		<?php if(isset($settings['GlobalSettings']['hide-menu']) && $settings['GlobalSettings']['hide-menu'] == 'enabled'):?>
			<a href="<?php echo admin_url( 'options-general.php?page=SwiftSecurity');?>" class="sft-btn btn-sm"><?php _e('Dashboard', 'SwiftSecurity');?></a>
		<?php endif;?>
	</div>	
    <?php wp_nonce_field( 'save_settings', 'SwiftSecurityNonce' ); ?>
	<h2>Swift Security - <?php _e('General Settings', 'SwiftSecurity');?></h2>
	
	<h4><?php _e('Admin menu', 'SwiftSecurity');?></h4>
	 <div>
 	 	<label><?php _e('Hide root-level menu', 'SwiftSecurity');?></label>
	 	<div class="onoffswitch-sm">
		    <input type="checkbox" name="settings[GlobalSettings][hide-menu]" class="onoffswitch-checkbox-sm" id="onoff-sm-hide-menu" value="enabled" <?php echo ((isset($settings['GlobalSettings']['hide-menu']) && $settings['GlobalSettings']['hide-menu'] == 'enabled') ? 'checked="checked"' : '')?>>
		    <label class="onoffswitch-label-sm" for="onoff-sm-hide-menu">
			    <span class="onoffswitch-inner-sm"></span>
			    <span class="onoffswitch-switch-sm"></span>
		    </label>
	    </div>
		<span class="tooltip-icon" data-tooltip="<?php _e('Move Swift Security menu under Settings.','SwiftSecurity');?>" data-tooltip-position="right">?</span>	 		
	</div>

	<h4><?php _e('Self-checks', 'SwiftSecurity');?></h4>
	 <div>
 	 	<label><?php _e('Safe activation', 'SwiftSecurity');?></label>
	 	<div class="onoffswitch-sm">
		    <input type="checkbox" name="settings[GlobalSettings][safe-activation]" class="onoffswitch-checkbox-sm" id="onoff-sm-safe-activation" value="enabled" <?php echo ((isset($settings['GlobalSettings']['safe-activation']) && $settings['GlobalSettings']['safe-activation'] == 'enabled') ? 'checked="checked"' : '')?>>
		    <label class="onoffswitch-label-sm" for="onoff-sm-safe-activation">
			    <span class="onoffswitch-inner-sm"></span>
			    <span class="onoffswitch-switch-sm"></span>
		    </label>
	    </div>
		<span class="tooltip-icon" data-tooltip="<?php _e('Check htaccess errors after activation','SwiftSecurity');?>" data-tooltip-position="right">?</span>	 		
	</div>

	<h4><?php _e('Purchase key', 'SwiftSecurity');?></h4>
	<div class="additional-settings">
		<label><?php _e('Envato purchase key', 'SwiftSecurity');?></label>
		<input type="text" name="settings[GlobalSettings][purchase-key]" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" value="<?php echo (isset($settings['GlobalSettings']['purchase-key']) ? $settings['GlobalSettings']['purchase-key'] : '');?>" class="regular-text">
		<span class="small">Set your purchase key for automatic updates</span>
	</div>
	
	
	
 	<h4><?php _e('E-mail notifications', 'SwiftSecurity');?></h4>
	<div class="additional-settings">
		<label><?php _e('Notification E-mail', 'SwiftSecurity');?></label>
		<input type="text" name="settings[GlobalSettings][Notifications][NotificationEmail]" value="<?php echo $settings['GlobalSettings']['Notifications']['NotificationEmail']?>">
	</div>
	
	<div class="additional-settings">
		<label><?php _e('Notification Type', 'SwiftSecurity');?></label>
		<div class="input-group">
			<div class="onoffswitch-sm">
			    <input type="checkbox" name="settings[GlobalSettings][Notifications][EmailNotifications][Login]" class="onoffswitch-checkbox-sm" id="onoff-sm-login-notification-email" value="enabled" <?php echo (isset($settings['GlobalSettings']['Notifications']['EmailNotifications']['Login']) && $settings['GlobalSettings']['Notifications']['EmailNotifications']['Login'] == 'enabled' ? 'checked="checked"' : '')?>>
			    <label class="onoffswitch-label-sm" for="onoff-sm-login-notification-email">
				    <span class="onoffswitch-inner-sm"></span>
				    <span class="onoffswitch-switch-sm"></span>
			    </label>
		    </div>
			<label for="onoff-sm-notification"><?php _e('Login Attempts', 'SwiftSecurity');?></label>
	
		 	<div class="onoffswitch-sm">
			    <input type="checkbox" name="settings[GlobalSettings][Notifications][EmailNotifications][Firewall]" class="onoffswitch-checkbox-sm" id="onoff-sm-firewall-notification-email" value="enabled" <?php echo (isset($settings['GlobalSettings']['Notifications']['EmailNotifications']['Firewall']) && $settings['GlobalSettings']['Notifications']['EmailNotifications']['Firewall'] == 'enabled' ? 'checked="checked"' : '')?>>
			    <label class="onoffswitch-label-sm" for="onoff-sm-firewall-notification-email">
				    <span class="onoffswitch-inner-sm"></span>
				    <span class="onoffswitch-switch-sm"></span>
			    </label>
		    </div>
			<label for="onoff-sm-notification"><?php _e('Firewall', 'SwiftSecurity');?></label>
			<div class="onoffswitch-sm">
			    <input type="checkbox" name="settings[GlobalSettings][Notifications][EmailNotifications][CodeScanner]" class="onoffswitch-checkbox-sm" id="onoff-sm-codescanner-notification-email" value="enabled" <?php echo (isset($settings['GlobalSettings']['Notifications']['EmailNotifications']['CodeScanner']) && $settings['GlobalSettings']['Notifications']['EmailNotifications']['CodeScanner'] == 'enabled' ? 'checked="checked"' : '')?>>
			    <label class="onoffswitch-label-sm" for="onoff-sm-codescanner-notification-email">
				    <span class="onoffswitch-inner-sm"></span>
				    <span class="onoffswitch-switch-sm"></span>
			    </label>
		    </div>
			<label for="onoff-sm-notification"><?php _e('Code Scanner', 'SwiftSecurity');?></label>
		</div>
	</div>
	
 	<h4><?php _e('Pushover notifications', 'SwiftSecurity');?></h4>
	<div class="additional-settings">
		<label><?php _e('Pushover Application Key', 'SwiftSecurity');?></label>
		<input type="text" name="settings[GlobalSettings][Notifications][NotificationPushoverToken]" value="<?php if (isset($settings['GlobalSettings']['Notifications']['NotificationPushoverToken'])){echo $settings['GlobalSettings']['Notifications']['NotificationPushoverToken'];}?>" class="regular-text">
		<span class="small">Create a free Pushover application from <a href="https://pushover.net/apps/clone/Swift%20Security" target="_blank">Pushover.net</a></span>
	</div>
	
	<div class="additional-settings">
		<label><?php _e('Pushover User Key', 'SwiftSecurity');?></label>
		<input type="text" name="settings[GlobalSettings][Notifications][NotificationPushoverUser]" value="<?php if (isset($settings['GlobalSettings']['Notifications']['NotificationPushoverUser'])){echo $settings['GlobalSettings']['Notifications']['NotificationPushoverUser'];}?>" class="regular-text">
		<span class="small">Get your Pushover user key from <a href="https://pushover.net/apps/clone/Swift%20Security" target="_blank">Pushover.net</a></span>
	</div>	
	
	<div class="additional-settings">
		<?php $PushoverSelectedSound[$settings['GlobalSettings']['Notifications']['NotificationPushoverSound']] = true;?>
		<label><?php _e('Notification Sound', 'SwiftSecurity');?></label>
		<select name="settings[GlobalSettings][Notifications][NotificationPushoverSound]">
			<option value="pushover" <?php if (isset($PushoverSelectedSound['pushover'])){echo 'selected="selected"';}?>>Pushover</option>
			<option value="bike" <?php if (isset($PushoverSelectedSound['bike'])){echo 'selected="selected"';}?>>Bike</option>
			<option value="bugle" <?php if (isset($PushoverSelectedSound['bugle'])){echo 'selected="selected"';}?>>Bugle</option>
			<option value="cashregister" <?php if (isset($PushoverSelectedSound['cashregister'])){echo 'selected="selected"';}?>>Cash Register</option>
			<option value="classical" <?php if (isset($PushoverSelectedSound['classical'])){echo 'selected="selected"';}?>>Classical</option>
			<option value="cosmic" <?php if (isset($PushoverSelectedSound['cosmic'])){echo 'selected="selected"';}?>>Cosmic</option>
			<option value="falling" <?php if (isset($PushoverSelectedSound['falling'])){echo 'selected="selected"';}?>>Falling</option>
			<option value="gamelan" <?php if (isset($PushoverSelectedSound['gamelan'])){echo 'selected="selected"';}?>>Gamelan</option>
			<option value="incoming" <?php if (isset($PushoverSelectedSound['incoming'])){echo 'selected="selected"';}?>>Incoming</option>
			<option value="intermission" <?php if (isset($PushoverSelectedSound['intermission'])){echo 'selected="selected"';}?>>Intermission</option>
			<option value="magic" <?php if (isset($PushoverSelectedSound['magic'])){echo 'selected="selected"';}?>>Magic</option>
			<option value="mechanical" <?php if (isset($PushoverSelectedSound['mechanical'])){echo 'selected="selected"';}?>>Mechanical</option>
			<option value="pianobar" <?php if (isset($PushoverSelectedSound['pianobar'])){echo 'selected="selected"';}?>>Piano Bar</option>
			<option value="siren" <?php if (isset($PushoverSelectedSound['siren'])){echo 'selected="selected"';}?>>Siren</option>
			<option value="spacealarm" <?php if (isset($PushoverSelectedSound['spacealarm'])){echo 'selected="selected"';}?>>Space Alarm</option>
			<option value="tugboat" <?php if (isset($PushoverSelectedSound['tugboat'])){echo 'selected="selected"';}?>>Tug Boat</option>
			<option value="alien" <?php if (isset($PushoverSelectedSound['alien'])){echo 'selected="selected"';}?>>Alien Alarm (long)</option>
			<option value="climb" <?php if (isset($PushoverSelectedSound['climb'])){echo 'selected="selected"';}?>>Climb (long)</option>
			<option value="persistent" <?php if (isset($PushoverSelectedSound['persistent'])){echo 'selected="selected"';}?>>Persistent (long)</option>
			<option value="echo" <?php if (isset($PushoverSelectedSound['echo'])){echo 'selected="selected"';}?>>Pushover Echo (long)</option>
			<option value="updown" <?php if (isset($PushoverSelectedSound['updown'])){echo 'selected="selected"';}?>>Up Down (long)</option>
			<option value="none" <?php if (isset($PushoverSelectedSound['none'])){echo 'selected="selected"';}?>>None (silent) 
		</select>
	</div>	
	
	<div class="additional-settings">
		<label><?php _e('Notification Type', 'SwiftSecurity');?></label>
		<div class="input-group">
			<div class="onoffswitch-sm">
			    <input type="checkbox" name="settings[GlobalSettings][Notifications][PushNotifications][Login]" class="onoffswitch-checkbox-sm" id="onoff-sm-login-notification-pushover" value="enabled" <?php echo (isset($settings['GlobalSettings']['Notifications']['PushNotifications']['Login']) && $settings['GlobalSettings']['Notifications']['PushNotifications']['Login'] == 'enabled' ? 'checked="checked"' : '')?>>
			    <label class="onoffswitch-label-sm" for="onoff-sm-login-notification-pushover">
				    <span class="onoffswitch-inner-sm"></span>
				    <span class="onoffswitch-switch-sm"></span>
			    </label>
		    </div>
			<label for="onoff-sm-notification"><?php _e('Login Attempts', 'SwiftSecurity');?></label>
	
		
	
		 	<div class="onoffswitch-sm">
			    <input type="checkbox" name="settings[GlobalSettings][Notifications][PushNotifications][Firewall]" class="onoffswitch-checkbox-sm" id="onoff-sm-firewall-notification-pushover" value="enabled" <?php echo (isset($settings['GlobalSettings']['Notifications']['PushNotifications']['Firewall']) && $settings['GlobalSettings']['Notifications']['PushNotifications']['Firewall'] == 'enabled' ? 'checked="checked"' : '')?>>
			    <label class="onoffswitch-label-sm" for="onoff-sm-firewall-notification-pushover">
				    <span class="onoffswitch-inner-sm"></span>
				    <span class="onoffswitch-switch-sm"></span>
			    </label>
		    </div>
			<label for="onoff-sm-notification"><?php _e('Firewall', 'SwiftSecurity');?></label>
	
		
	
			<div class="onoffswitch-sm">
			    <input type="checkbox" name="settings[GlobalSettings][Notifications][PushNotifications][CodeScanner]" class="onoffswitch-checkbox-sm" id="onoff-sm-codescanner-notification-pushover" value="enabled" <?php echo (isset($settings['GlobalSettings']['Notifications']['PushNotifications']['CodeScanner']) && $settings['GlobalSettings']['Notifications']['PushNotifications']['CodeScanner'] == 'enabled' ? 'checked="checked"' : '')?>>
			    <label class="onoffswitch-label-sm" for="onoff-sm-codescanner-notification-pushover">
				    <span class="onoffswitch-inner-sm"></span>
				    <span class="onoffswitch-switch-sm"></span>
			    </label>
		    </div>
			<label for="onoff-sm-notification"><?php _e('Code Scanner', 'SwiftSecurity');?></label>
		</div>
	</div>
	<div class="additional-settings">
	<label>Get the App</label>
	<div class="input-group">
	<img src="<?php echo SWIFTSECURITY_PLUGIN_URL; ?>/images/pushover.jpg" style="margin:6px 20px 0 0;">
	</div>
	</div>
 	<input type="hidden" name="sq" value="<?php echo $settings['GlobalSettings']['sq']?>">
 	<input type="hidden" name="module" value="Notification">
 	<br>
 	<button name="swift-security-settings-save" class="sft-btn btn-green" value="General"><?php _e('Save settings', 'SwiftSecurity');?></button>
 </form> 