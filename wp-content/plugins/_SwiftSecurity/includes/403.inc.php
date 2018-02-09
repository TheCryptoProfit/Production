<?php 
defined('ABSPATH') or die("KEEP CALM AND CARRY ON");
ini_set("display_errors", 0);
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>403 Forbidden</title>
</head><body>
<h1>Forbidden</h1>
<p>You don't have permission to access <?php echo parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);?> 
on this server.</p>
<hr>
<address><?php echo $_SERVER['SERVER_SOFTWARE']?> Server at <?php echo $_SERVER['HTTP_HOST']?> Port <?php echo $_SERVER['SERVER_PORT']?></address>
</body></html>
<?php die;?>