 

jQuery(document).ready(function() {

	jQuery(".cancel_order").click(function (){		
		
		var id = jQuery(this).attr('rel');
		jQuery("#cancel_order_div_id_" + id).toggle('slow');
		return false;
	});

});

//--------------------------------------------------------

jQuery(document).ready(function() {


jQuery(".rem-to-watchlist").on("click", function(){ 
		
		var pid = jQuery(this).attr('rel');
		var cette = jQuery(this);
		
		jQuery.ajax({
						url: SITE_URL + "/wp-admin/admin-ajax.php",
						type:'POST',
						data:'action=remove_from_watchlist&pid=' + pid,
						success: function (text) {  
						
							//text = text.slice(0, -1);
							//jQuery('#my_pkg_cell' + delete_package).animate({ backgroundColor: "red" }, 'slow');
							//jQuery("#my_pkg_cell" + delete_package).remove();
							
							if(text == "NO_LOGGED-1")
							{
								window.location = SITE_URL + "/wp-login.php";
							}
							else
							{
								if(is_on_check_list == 1)
								{
									jQuery("#post-ID-" + pid).hide('slow'); 
								}
								else{
									cette.html(plus_watchlist);
									cette.removeClass("rem-to-watchlist");
									cette.addClass("add-to-watchlist");
								}
							}
							
							return false;
						  }
					 });
			
		return false;
	});

//----------------------------------------------------------------------------
	
	jQuery(".add-to-watchlist").on("click", function(){ 
		
		var pid = jQuery(this).attr('rel');
		var cette = jQuery(this);
		
		jQuery.ajax({
						url: SITE_URL + "/wp-admin/admin-ajax.php",
						type:'POST',
						data:'action=add_to_watchlist&pid=' + pid,
						success: function (text) {  
						
							//text = text.slice(0, -1);
							//jQuery('#my_pkg_cell' + delete_package).animate({ backgroundColor: "red" }, 'slow');
							//jQuery("#my_pkg_cell" + delete_package).remove();
						if(text == "NO_LOGGED-1")
						{
							window.location = SITE_URL + "/wp-login.php";
						}
						else
						{	
							
						
								cette.html(minus_watchlist);
								cette.removeClass("add-to-watchlist");
								cette.addClass("rem-to-watchlist");
							
						}
						
							
							return false;
						  }
					 });
			
		return false;
	});	
	    
});