<?php
 $AttemptType = array(
 	'SQLi' => 'SQL injection',
 	'XSS' => 'Cross site scripting (XSS)',
 	'Path' => 'File path manipulation',
 	'Upload' => 'File upload',
 	'FailedLogin' => 'Failed login',
 	'Login' => 'Login'
 );
 defined('ABSPATH') or die("KEEP CALM AND CARRY ON");
?>
<div>
	<div class="pull-right">
		<?php if(isset($settings['GlobalSettings']['hide-menu']) && $settings['GlobalSettings']['hide-menu'] == 'enabled'):?>
			<a href="<?php echo admin_url( 'options-general.php?page=SwiftSecurity');?>" class="sft-btn btn-sm"><?php _e('Dashboard', 'SwiftSecurity');?></a>
		<?php endif;?>
	</div>	
	<h2>Swift Security - <?php _e('Firewall logs', 'SwiftSecurity')?></h2>
</div>
<div id="log-container">
	<table id="swift-security-log">
	<?php $i = 0; ?>
	 <thead>
	  <tr>
	     <th class="log-timestamp">Timestamp</th>
	     <th class="log-attempt">Action</th>
	     <th class="log-channel">Details</th>
	     <th>Request</th>
	     <th class="log-ip">IP</th>
	     <th>User Agent</th>
	     <th class="log-user">User</th>
	  </tr>
	 </thead>
	 <tbody>
	 <?php (array)$logs = get_option('swiftsecurity_log');?>
	 <?php if (!empty($logs)) :?>
	 <?php for ($i=0;$i<count($logs);$i++):?>
	 <tr class="main-record <?php if ($i % 2 == 0 ) { echo"even-cell"; } ?>">
		 <td class="log-timestamp"><?php echo date("d-m-Y H:i:s", $logs[$i]['timestamp'])?></td>
		 <td class="log-attempt"><?php echo $AttemptType[$logs[$i]['attempt']];?></td>
		 <td class="log-channel"><?php echo htmlentities($logs[$i]['channel']);?></td>
		 <td><?php echo site_url().htmlentities($logs[$i]['requestURI']);?></td>
		 <td class="log-ip" title="<?php echo htmlentities($logs[$i]['host']);?>"><?php echo htmlentities($logs[$i]['ip']);?></td>
		 <td><?php echo htmlentities($logs[$i]['ua']);?></td>
		 <td class="log-user"><?php echo htmlentities($logs[$i]['user']);?></td>
	 </tr>
	 <tr class="hidden-row <?php if ($i % 2 == 0 ) { echo"even-cell"; } ?>">
		 <td colspan="7"><?php echo htmlentities($logs[$i]['cookies']);?></td>
	 </tr>
	 <?php endfor;?>
	 <?php else:?>
	 <tr class="main-record">
		 <td class="log-timestamp" colspan="7">
		  <?php _e('There are no attempts','SwiftSecurity');?>
		 </td>
	 </tr>
	 <?php endif;?>
	 </tbody>
	</table>
</div>