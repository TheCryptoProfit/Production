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
								<?php echo $timestamp;?>
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
							<p style="font-size:28px;margin:0;text-align:center;font-weight:bold;"><?php _e('Firewall Security Event','SwiftSecurity');?></p>
							<p style="font-size:18px;margin:0;text-align:center;"><?php echo $user_login . ' ' . __('Successfully logged in','SwiftSecurity');?></p>						</td>
					</tr>
					<tr style="border-collapse:collapse;background-color:#ffffff;"><td width="640" height="25"></td></tr>
					<tr style="background-color:#ffffff;border-collapse:collapse;">
						<td style="font-size:13px;line-height:18px;color:#444444;padding-left:20px;padding-right:20px;">
							<p style="word-break:break-all;"><strong><?php _e('URL','SwiftSecurity');?>:</strong> <?php echo $uri;?></p>
							
							<p style="word-break:break-all;"><strong><?php _e('COOKIES','SwiftSecurity');?>:</strong> <?php echo $cookies;?></p>
							
							<p style="word-break:break-all;"><strong><?php _e('IP address','SwiftSecurity');?>:</strong> <?php echo $ip;?></p>
							
							<p style="word-break:break-all;"><strong><?php _e('Host','SwiftSecurity');?>:</strong> <?php echo $host;?></p>
							
							<p style="word-break:break-all;"><strong><?php _e('Useragent','SwiftSecurity');?>:</strong> <?php echo $useragent;?></p>
							
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