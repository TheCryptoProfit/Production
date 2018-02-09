<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en">
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body leftmargin="0" rightmargin="0" marginwidth="0" topmargin="0" bottommargin="0" marginheight="0" offset="0" bgcolor=“#f8f8f8”>

	<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f8f8f8;font-family: 'Helvetica Neue',Arial,Helvetica,Geneva,sans-serif;">
		<tr style="border-collapse:collapse;">
			<td align="center">
				<table width="640" cellspacing="0" cellpadding="0" border="0" style="margin-top:0;margin-bottom:0;margin-right:10px;margin-left:10px">
					<tr style="border-collapse:collapse"><td width="640" height="25"></td></tr>
					<tr style="border-collapse:collapse">
						<td width="640" height="25">
							<div style="font-size:11px;color:#444444;font-weight:bold">
								<?php echo (isset($timestamp) ? $timestamp : '');?>
							</div>
						</td>					
					</tr>
					<tr style="border-collapse:collapse"><td width="640" height="25"></td></tr>
					<tr style="border-collapse:collapse;">
						<td>
							<img src="cid:swiftsecurity-logo">
						</td>
					</tr>
					<tr style="border-collapse:collapse"><td width="640" height="25"></td></tr>
					<tr style="border-collapse:collapse;">
						<td style="background-color:#3E88B7;color:#ffffff;padding:25px 0;">
							<p style="font-size:28px;margin:0;text-align:center;font-weight:bold;">Bug report</p>
						</td>
					</tr>
					<tr style="border-collapse:collapse;background-color:#ffffff;"><td width="640" height="25"></td></tr>
					<tr style="background-color:#ffffff;border-collapse:collapse;">
						<td style="font-size:13px;line-height:18px;color:#444444;padding-left:20px;padding-right:20px;">
						    <p style="word-break:break-all;"><strong>Description:</strong> <?php echo htmlentities($_POST['bug_description']);?></p>
							<p style="word-break:break-all;"><strong>Theme:</strong> <?php echo htmlentities($BugReport['theme']);?></p>
							<p style="word-break:break-all;"><strong>Child theme:</strong> <?php echo htmlentities($BugReport['child_theme']);?></p>
							<p style="word-break:break-all;"><strong>Plugins:</strong> <?php echo htmlentities($BugReport['plugins']);?></p>
  						    <p style="word-break:break-all;"><strong>Settings:</strong> <?php echo json_encode($BugReport['settings']);?></p>
  						    <p style="word-break:break-all;"><strong>Swift Security Version:</strong> <?php echo SWIFTSECURITY_VERSION_NUM;?></p>
   						    <p style="word-break:break-all;"><strong>SecurityLog:</strong> <?php echo json_encode($BugReport['security_log']);?></p>
							<p style="word-break:break-all;"><strong>User-agent:</strong> <?php echo htmlentities($_SERVER['HTTP_USER_AGENT']);?></p>
							<p style="word-break:break-all;"><strong>PHPinfo:</strong></p>
							<?php echo $phpinfo;?>
							</td>
					</tr>
					<tr style="border-collapse:collapse;background-color:#ffffff;"><td width="640" height="25"></td></tr>
					<tr style="border-collapse:collapse"><td width="640" height="25"></td></tr>
				</table>
			</td>
		</tr>
	</table>
	
</body>
</html>