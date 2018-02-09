<?php 
global $SwiftSecurity;
?>
<?php defined('ABSPATH') or die("KEEP CALM AND CARRY ON");?>
<h2>Swift Security - <?php _e('Nginx rules','SwiftSecurity');?></h2>
<form>
	<!-- All rules -->
	<section class="swift-security-row opened">
		<h4><?php _e('All rules', 'SwiftSecurity');?></h4>
		<div>
			<textarea class="nginx-rule" readonly>###BEGIN SWIFTSECURITY###<?php echo PHP_EOL . $SwiftSecurity->GetNginxRules('Firewall') . $SwiftSecurity->GetNginxRules('HideWP') . $SwiftSecurity->GetNginxRules('WPScanner') . PHP_EOL;?>###END SWIFTSECURITY###</textarea>
		</div>
	</section>
	<!-- Firewall -->
	<section class="swift-security-row">
		<h4><?php _e('Firewall rules', 'SwiftSecurity');?></h4>
		<div>
			<textarea class="nginx-rule" readonly><?php echo $SwiftSecurity->GetNginxRules('Firewall');?></textarea>
		</div>
	</section>
	<!-- Hide WordPress -->
	<section class="swift-security-row">
		<h4><?php _e('Hide WordPress rules', 'SwiftSecurity');?></h4>
		<div>
			<textarea class="nginx-rule" readonly><?php echo $SwiftSecurity->GetNginxRules('HideWP');?></textarea>
		</div>
	</section>
	<!-- Code Scanner -->
	<section class="swift-security-row">
		<h4><?php _e('Code Scanner rules', 'SwiftSecurity');?></h4>
		<div>
			<textarea class="nginx-rule" readonly><?php echo $SwiftSecurity->GetNginxRules('WPScanner');?></textarea>
		</div>
	</section>
</form>