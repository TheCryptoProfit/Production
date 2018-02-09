<?php _e('Scheduled Code Scanner complete','SwiftSecurity');?>						
<?php _e('File scan','SwiftSecurity');?>: <?php echo $FileErrors . ' ' . __('suspicious files','SwiftSecurity');?>.
<?php if ($NewQuarantinedFiles > 0):?>
<?php _e('Actions','SwiftSecurity');?>: <?php echo __('Code Scanner put','SwiftSecurity') . ' ' . $NewQuarantinedFiles . ' ' . __('new suspicious files to quarantine','SwiftSecurity');?>
<?php endif;?>
<?php if ($QuarantinedFiles > 0):?>
<?php _e('Quarantine','SwiftSecurity');?>: <?php echo $QuarantinedFiles+$NewQuarantinedFiles . ' ' . __('files are in quarantine','SwiftSecurity');?>
<?php endif;?>
	