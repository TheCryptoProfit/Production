<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - AuctionTheme
*	More Info: http://sitemile.com/p/auctionTheme
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/

	include 'sett.php';
	include 'login.php';
	include 'register.php';
	

	add_action('init', 'AuctionTheme_do_login_register_init', 99);
	
	//=======================================================
	
		function AuctionTheme_do_login_register_init()
		{
		  global $pagenow;
		
			if(isset($_GET['action']) && $_GET['action'] == "register" && !isset($_GET['_wpnonce']))
			{
				AuctionTheme_do_register_scr();	
			}
		
		  switch ($pagenow)
		  {
			case "wp-login.php":
			
				
			  AuctionTheme_do_login_scr();
			break;
			case "wp-register.php":
				
	
				
			  AuctionTheme_do_register_scr();
			break;
		  }
		}
		
	//=========================================================	



?>