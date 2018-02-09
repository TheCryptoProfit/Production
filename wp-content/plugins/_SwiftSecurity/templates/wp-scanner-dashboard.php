<?php defined('ABSPATH') or die("Cannot access pages directly.");?>
<div>
	<div class="pull-right">
		<?php if(isset($settings['GlobalSettings']['hide-menu']) && $settings['GlobalSettings']['hide-menu'] == 'enabled'):?>
			<a href="<?php echo admin_url( 'options-general.php?page=SwiftSecurity');?>" class="sft-btn btn-sm"><?php _e('Dashboard', 'SwiftSecurity');?></a>
		<?php endif;?>
	</div>	
	<h2>Swift Security - <?php _e('Code Scanner','SwiftSecurity');?></h2>
</div>
<div id="codescanner-container">
	<div id="swift-overlay"></div>
<button id="swiftsecurity-scan-now" value="scan" class="sft-btn btn-green"><?php _e('Scan my Wordpress!','SwiftSecurity');?></button>
<?php 
	try{
		SwiftSecurity::CompatibilityCheck('CodeScanner');
		?><a href="<?php menu_page_url( 'SwiftSecurityCodeScanner', true);?>&option=Settings" class="sft-btn"><?php _e('Settings','SwiftSecurity');?></a><?php
	}
	catch (SwiftSecuritySettingsException $e){}
?> 
<button class="swiftsecurity-list-filtered-results sft-btn" data-filter="whitelisted"><?php _e('List whitelisted files','SwiftSecurity');?></button>
<button class="swiftsecurity-list-filtered-results sft-btn" data-filter="quarantined"><?php _e('List quarantined files','SwiftSecurity');?></button>
	
	<div id="wpscan-progress-container">
		<div class="swiftsecurity-wpscan-progress"><span></span></div>
	</div>
	<div class="swiftsecurity-wpscan-results">
		<div class="scan-title"></div>
		<div class="scan-results"></div>
	</div>
	<div id="result-sample-container" class="hidden">
	    <div id="php-results" class="scan-result-container"><h4><?php _e('PHP Settings', 'SwiftSecurity'); ?></h4></div>
		<div class="php-result">
			<div class="label"></div>
			<div class="score"></div>
			<div class="text"></div>
		</div>
		<div id="mysql-results"><h4><?php _e('MySQL Settings', 'SwiftSecurity'); ?></h4></div>
		<div class="mysql-result" class="scan-result-container">
			<div class="label"></div>
			<div class="score"></div>
			<div class="text"></div>
		</div>
		<div id="filescan-results" class="scan-result-container"><h4><?php _e('Possible Vulnerabilities', 'SwiftSecurity'); ?></h4></div>
		<div class="filescan-result">
			<div class="sft-loader-container">
				<div class="sft-loader">
					<div class="rect1"></div>
					<div class="rect2"></div>
					<div class="rect3"></div>
					<div class="rect4"></div>
					<div class="rect5"></div>
				</div>
			</div>
			
			<div class="filename"></div>
			<div class="score"></div>
			<div class="button-container">
				<button class="whitelist sft-btn btn-green btn-sm"><?php _e('Add to whitelist','SwiftSecurity');?></button>
				<button class="quarantine sft-btn btn-red btn-sm"><?php _e('Quarantine','SwiftSecurity');?></button>
			</div>
		</div>
		<div class="filescan-subresults">
			<div class="label"></div>
		 	<div class="text"></div>
		 	<div class="match"></div>
		</div>
	</div>
</div>