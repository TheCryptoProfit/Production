<?php

	$pid = $_GET['auction_id'];
	global $error;
	
	//-------------------------------------------


	if(isset($_POST['auction_submit_photos']))
	{
		
		$uploaders = auctiontheme_get_uploaders_tp();
		
		//---------------------------------------
		// pictures
		
		if($uploaders  == "html"):
		
				require_once(ABSPATH . "wp-admin" . '/includes/file.php');
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				
				$default_nr = get_option('AuctionTheme_nr_max_of_images');
				if(empty($default_nr)) $default_nr = 5;
					
				for($j=1;$j<=	$default_nr; $j++)
				{ 
					if(!empty($_FILES['file_' . $j]['name'])):
			  
						$upload_overrides 	= array( 'test_form' => false );
						$uploaded_file 		= wp_handle_upload($_FILES['file_' . $j], $upload_overrides);
						
						$file_name_and_location = $uploaded_file['file'];
						$file_title_for_media_library = $_FILES['file_' . $j]['name'];
								
						$arr_file_type 		= wp_check_filetype(basename($_FILES['file_' . $j]['name']));
						$uploaded_file_type = $arr_file_type['type'];
		
						if($uploaded_file_type == "image/png" or $uploaded_file_type == "image/jpg" or $uploaded_file_type == "image/jpeg" or $uploaded_file_type == "image/gif" )
						{
						
							$attachment = array(
											'post_mime_type' => $uploaded_file_type,
											'post_title' => 'Uploaded image ' . addslashes($file_title_for_media_library),
											'post_content' => '',
											'post_status' => 'inherit',
											'post_parent' =>  $pid,
			
											'post_author' => $current_user->ID,
										);
									 
							$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $pid );
							$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
							wp_update_attachment_metadata($attach_id,  $attach_data);
						
						}
						
					endif;
				}
		
		endif;
		
		//------------	
		
		$arr = $_POST['custom_field_id'];
		for($i=0;$i<count($arr);$i++)
		{
			$ids 	= $arr[$i];
			$value 	= $_POST['custom_field_value_'.$ids];
			
		 
			update_post_meta($pid, "custom_field_ID_".$ids, $value);
			
		}
	
		do_action('AuctionTheme_step2_form_thing_post', $pid);
		
		wp_redirect(AuctionTheme_post_new_with_pid_stuff_thg($pid, 3));
		exit;	
	}


	if(isset($_POST['auction_submit_1']))
	{
		
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'form_step_1' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}
		
		//------------------------------
		
		$auctionOK 				= 1;
		$auction_title 			= trim(strip_tags($_POST['auction_title']));
		$AuctionTheme_enable_html_description = get_option('AuctionTheme_enable_html_description');
		
		if($AuctionTheme_enable_html_description == "yes")
		{
			$auction_description 	= $_POST['auction_description'];	
		}
		else
		{
			$auction_description 	= nl2br(strip_tags($_POST['auction_description']));	
		}
 
		// preg_replace("/[^0-9,.]/", "",  '$4322.1')
		$auction_tags 			= trim(strip_tags($_POST['auction_tags']));
		
		$start_price 			= auctionTheme_clear_sums_of_cash(trim($_POST['start_price']));
		$buy_now 				= auctionTheme_clear_sums_of_cash(trim($_POST['buy_now']));
		$reserve 				= auctionTheme_clear_sums_of_cash(trim($_POST['reserve']));
		$shipping 				= auctionTheme_clear_sums_of_cash(trim($_POST['shipping']));
		
		$price 					= auctionTheme_clear_sums_of_cash(trim($_POST['price']));
		$auction_location_addr 	= strip_tags(trim($_POST['auction_location_addr']));
		
		$auction_category 		= $_POST['auction_cat_cat'];
		$auction_location 		= $_POST['auction_location_cat'];
		
		$do_not_require_shipping = $_POST['do_not_require_shipping'];
		
		if(!empty($do_not_require_shipping)) update_post_meta($pid, 'do_not_require_shipping', "1");
		else update_post_meta($pid, 'do_not_require_shipping', "0");
	
		//-----------------------------------------------------------------------------
		
			do_action('AuctionTheme_step_1_submit');
		
		//-----------------------------------------------------------------------------
		
		$auction_title 			= apply_filters('AuctionTheme_filter_auction_title', 			$auction_title, $pid); 
		$auction_description 	= apply_filters('AuctionTheme_filter_auction_description', 		$auction_description, $pid); 
		$auction_tags 			= apply_filters('AuctionTheme_filter_auction_tags', 			$auction_tags, $pid); 
		$start_price 			= apply_filters('AuctionTheme_filter_auction_start_price', 		$start_price, $pid); 
		$buy_now 				= apply_filters('AuctionTheme_filter_auction_buy_now_price', 	$buy_now, $pid); 
		$reserve 				= apply_filters('AuctionTheme_filter_auction_reserve_price', 	$reserve, $pid); 
		$shipping 				= apply_filters('AuctionTheme_filter_auction_shipping', 		$shipping, $pid); 
		$price 					= apply_filters('AuctionTheme_filter_auction_price', 			$price, $pid); 
		$auction_location_addr	= apply_filters('AuctionTheme_filter_auction_address', 			$auction_location_addr, $pid); 
		
		
		//-----------------------------------------------------------------------------
		
		if(get_option('AuctionTheme_enable_multi_cats') == "yes")
		{
			$slg_arr = array();
			foreach($_POST['auction_cat_cat_multi'] as $ct)
			{
				$term = get_term( $ct, 'auction_cat' );	
				$auction_category = $term->slug;	
				$slg_arr[] = $auction_category;		
			}
			

			wp_set_object_terms($pid, $slg_arr,'auction_cat');		
		}
		else
		{
 
			$term 					= get_term( $auction_category, 'auction_cat' );	
			$auction_category 		= $term->slug;
			$arr_cats 				= array();
			$arr_cats[] 			= $auction_category;

			
			if(!empty($_POST['subcat']))
			{
				$term = get_term( $_POST['subcat'], 'auction_cat' );	
				$jb_category2 = $term->slug;
				$arr_cats[] = $jb_category2;
				 
			}
			
			if(!empty($_POST['subcat2']))
			{
				$term = get_term( $_POST['subcat2'], 'auction_cat' );	
				$jb_category2 = $term->slug;
				$arr_cats[] = $jb_category2;
				 
			}
			
			if(!empty($_POST['subcat3']))
			{
				$term = get_term( $_POST['subcat3'], 'auction_cat' );	
				$jb_category2 = $term->slug;
				$arr_cats[] = $jb_category2;
				 
			}
			
			
			wp_set_object_terms($pid, $arr_cats ,'auction_cat');
			
		}
		
		
				
		//***************************************************
		
		$auction_location 			= trim($_POST['auction_location_cat']);
		$term 						= get_term( $auction_location, 'auction_location' );	
		$auction_location 			= $term->slug;
		$arr_cats 					= array();
		$arr_cats[] 				= $auction_location;
			
		if(!empty($_POST['subloc']))
		{
			$term = get_term( $_POST['subloc'], 'auction_location' );	
			$jb_category2 = $term->slug;
			$arr_cats[] = $jb_category2;
			 
		}
		
		if(!empty($_POST['subloc2']))
		{
			$term = get_term( $_POST['subloc2'], 'auction_location' );	
			$jb_category2 = $term->slug;
			$arr_cats[] = $jb_category2;
			 
		}
		
		wp_set_object_terms($pid, $arr_cats ,'auction_location');	
		
		//***********************************************		
		$AuctionTheme_enable_it_cond = get_option('AuctionTheme_enable_it_cond'); 
		if($AuctionTheme_enable_it_cond == "yes"):
		
		
			
		
			$item_condition 			= trim($_POST['item_condition_cat']);
			$term 						= get_term( $item_condition, 'item_condition' );	
			$item_condition 			= $term->slug;
			$arr_cats 					= array();
			$arr_cats[] 				= $item_condition;
			
			if(empty($item_condition)) 		{ $auctionOK = 0; $error['item_condition'] 		= __('Please select your item condition.','AuctionTheme'); }
				 
			
			wp_set_object_terms($pid, $arr_cats ,'item_condition');	
 		
		endif;
		
		//-----------------------------------------------------------------------------
		
		$quant = trim($_POST['quant']);
		if(empty($quant) || !is_numeric($quant) || $quant < 0) $quant = 1;
		
		if(is_numeric($quant)) $quant = round($quant); 
		
		if(empty($auction_starting_price)) $auction_starting_price = 0;	
		
		//-------------------------------
		
	
		if(empty($auction_title)) 		{ $auctionOK = 0; $error['title'] 			= __('You cannot leave the auction title blank!','AuctionTheme'); }		
		if(empty($auction_category) and count($_POST['auction_cat_cat_multi']) == 0) 		{ $auctionOK = 0; $error['category'] 		= __('You cannot leave the auction category blank!','AuctionTheme'); }
		if(empty($auction_description)) { $auctionOK = 0; $error['description'] 	= __('You cannot leave the auction description blank!','AuctionTheme'); }
		
		if(!empty($start_price) and !is_numeric($start_price)) { $auctionOK = 0; $error['start_price'] 	= __('The start price must be numeric!','AuctionTheme'); }
		if(!empty($reserve) and !is_numeric($reserve)) { $auctionOK = 0; $error['reserve'] 	= __('The reserve price must be numeric!','AuctionTheme'); }
		if(!empty($buy_now) and !is_numeric($buy_now) ) { $auctionOK = 0; $error['buy_now'] 	= __('The buy now price must be numeric!','AuctionTheme'); }
		if( $buy_now < 0 ) { $auctionOK = 0; $error['buy_now'] 	= __('The buy now price must not be negative!','AuctionTheme'); }
		if( $buy_now < 5 ) { $auctionOK = 0; $error['buy_now'] 	= __('The buy now price must be more than 5!','AuctionTheme'); }
		
		
		if(empty($buy_now) and empty($start_price) and empty($_POST['only_buy_now'])) { $auctionOK = 0; $error['start_price'] 	= __('You must set at least a starting price!','AuctionTheme'); }
		
		
		$AuctionTheme_enable_locations = get_option('AuctionTheme_enable_locations');
		if($AuctionTheme_enable_locations != 'no')	
		if(empty($auction_location)) 		{ $auctionOK = 0; $error['location'] 		= __('You cannot leave the auction location blank!','AuctionTheme'); }
		
		

		$coupon = trim($_POST['coupon']);
		if(!empty($coupon))
		{
			$cup = auctionTheme_is_valid_cupoun($coupon);
			$_SESSION['coupon'] = $cup;
			
			if($cup == false) {$false_cup = 0; $ok_ad = 0; }
			
		}
		
		//----------------------------
		
		if(isset($_POST['featured']))
		{ 
			update_post_meta($pid, "featured", "1");
			$end = get_option('auctionTheme_ad_period_featured');
		}
		else update_post_meta($pid, "featured", "0");
		
		
		if(isset($_POST['allow_offers'])) update_post_meta($pid, "allow_offers", "1");
		else update_post_meta($pid, "allow_offers", "0");
		
		
		
		update_post_meta($pid, "closed", "0");
		update_post_meta($pid, "closed_date", "0");

		$my_post = array();
		$my_post['post_title'] 		= substr($auction_title,0,80);
		$my_post['ID'] 				= $pid;
		$my_post['post_content'] 	= $auction_description;	
		$my_post['post_status'] 	= 'draft';	
		wp_update_post( $my_post );
		
		//----- for shipping opt --------------------
		
		
		global $wpdb;
		$is_counts = 0;
		$ok_counts = 0;
						
		$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
		$terms = get_terms( 'auction_shipping', $args );
		foreach($terms as $term):
				
				$location_id 		= $term->term_id;
				$shipping_charge 	= trim($_POST['shipping_value_' . $location_id]);
				
				if(!empty($shipping_charge) and is_numeric($shipping_charge))
				{	
					$ok_counts++;
				}
				
				 
				
				if(is_string($shipping_charge) and !is_numeric($shipping_charge))  $shipping_charge = '';
					
				$sp = "select * from ".$wpdb->prefix."auction_shipping_values where location_id='$location_id' AND pid='$pid'";
				$rp = $wpdb->get_results($sp);
				if(count($rp) > 0)
				{
					$wpdb->query("update ".$wpdb->prefix."auction_shipping_values set shipping_charge='$shipping_charge' where pid='$pid' AND location_id='$location_id'");						
				}
				else
				{
					$wpdb->query("insert into ".$wpdb->prefix."auction_shipping_values (pid, location_id, shipping_charge) values('$pid','$location_id','$shipping_charge')");	
				}
	 
				$is_counts++;
					
		endforeach;			
		
		//--------------------------
		
		if($_POST['shipping_type'] == "variable" and !isset($_POST['do_not_require_shipping']))
		{
			if($is_counts == 0)
			{
				$auctionOK 				= 0;   
				$error['shipping'] 		= __('You need to add shipping options in backend. You have no options defined.','AuctionTheme'); 
			}
			
			if($no_numA == 1)
			{
				$auctionOK 				= 0; 
				$error['shipping'] 		= __('If you fill in shipping charge make sure they are numeric (eg: 1.99).','AuctionTheme');	
			}
		}
			
		//----------------------------------------------------------------
			// set categories
		
		//----------------------------------------------------------------
			
			$reverse = (AuctionTheme_is_reverse_enabled() == true ? 'yes' : 'no' );
			
			wp_set_post_tags($pid, $auction_tags);
			  
			update_post_meta($pid, "Location", $auction_location_addr);
			update_post_meta($pid, "price", $price);
			  
			update_post_meta($pid, "paid", "0");
			update_post_meta($pid, "quant", $quant);			  
			  
			update_post_meta($pid, "shipping", 		empty($shipping) ? 0 : $shipping);
			update_post_meta($pid, "start_price", 	$start_price);
			update_post_meta($pid, "reserve", 		$reserve);
			
			update_post_meta($pid, "buy_now", 		$buy_now); 
			update_post_meta($pid, "reverse", 		$reverse); 
			update_post_meta($pid, "private_bids", 	strip_tags($_POST['private_bids'])); 
			update_post_meta($pid, "views", 		'0');
			update_post_meta($pid, "only_buy_now", 	(isset($_POST['only_buy_now']) ? "1" : "0" ));
			
			$only_b = isset($_POST['only_buy_now']) ? "1" : "0" ;
			 
			$end 	= $_POST['ending']; 
			$nowtm 	= current_time('timestamp',0);
			
			//----- setting ending period ------------
			
			$ending = strtotime($end, $nowtm);
			
			$auctionTheme_auction_period = get_option('AuctionTheme_auction_time_listing');
			if(empty($auctionTheme_auction_period)) $auctionTheme_auction_period = 30;
			
			$auctionTheme_auction_period_featured = get_option('AuctionTheme_auction_featured_time_listing');
			if(empty($auctionTheme_auction_period_featured)) $auctionTheme_auction_period_featured = 30;
			
			$is_auction_featured = get_post_meta($pid, 'featured', true);
			if($is_auction_featured == "1") $time_ending = $nowtm + $auctionTheme_auction_period_featured *3600*24;
			else $time_ending = $nowtm + $auctionTheme_auction_period *3600*24;
			
			if(!empty($end)) 
			{
				$ending = strtotime($end, $nowtm);
				if($ending > $time_ending) $ending = $time_ending;
				
			} else $ending = $time_ending;
			
			//----------------------------------------
			
			update_post_meta($pid, "closed", 		"0");
			update_post_meta($pid, "closed_date", 	"0");
			update_post_meta($pid, "ending", 		$ending);
			update_post_meta($pid, "shipping_type", 		$_POST['shipping_type']);
			
			
			update_post_meta($pid, "auto_renew_item", 		$_POST['auto_renew_item']);
			 update_post_meta($pid, "amount_times", 		$_POST['amount_times']);
			 update_post_meta($pid, "amount_days", 		$_POST['amount_days']);
			 
			 
			 
			  
			  $reverse = get_post_meta($pid, "reverse", true);
			  
			  if($reverse == "yes")
			  {
			  		update_post_meta($pid, "current_bid", $start_price);
					if(empty($start_price))
					{ 
						if(!empty($buy_now))
						update_post_meta($pid, "current_bid", $buy_now);
						else update_post_meta($pid, "current_bid", 0);
			  		}
			  }
			  else
			  {
			  		update_post_meta($pid, "current_bid", $start_price);
					if(empty($start_price))
					{ 
						if(!empty($buy_now))
						update_post_meta($pid, "current_bid", $buy_now);
						else update_post_meta($pid, "current_bid", 0);
			  		}
			  }
			  
			  
			  
			  if(!empty($reserve))
			  {
					if(!empty($buy_now))
					{	
						if($reverse == "yes")
						{
							if($reserve <= $buy_now) { $auctionOK = 0; $error['reserve'] = __('Buy now price must be lower than reserve price.','AuctionTheme'); }
						}
						elseif($reserve >= $buy_now) 
						{ $auctionOK = 0; $error['reserve'] = __('Buy now price must be greater than reserve price.','AuctionTheme'); }	
					}
				  
			  }
			  
			  
			  if(!empty($buy_now))
			  {
					if(!empty($start_price))
					{
						if($reverse == "yes")
						{
							if($buy_now >= $start_price) { $auctionOK = 0; $error['buy_now'] = __('Buy now price must be lower than start price.','AuctionTheme'); }		
						}
						elseif($buy_now <= $start_price) { $auctionOK = 0; $error['buy_now'] = __('Buy now price must be greater than start price.','AuctionTheme'); }	
					}
				  
			  }
			  
			  if(empty($buy_now) and $only_b == "1")
			  {
					  $auctionOK = 0; $error['buy_now'] = __('You must set a buy now price for your item.','AuctionTheme');   
			  }
			  
			  //------------------------------------------------------
			  $zip = get_post_meta($pid, "Location", true);
			  
			  $loc 		= wp_get_post_terms( $pid, 'auction_location');
			  $loc_a 	= '';
			 
			  foreach($loc as $l)
			 	$loc_a .= $l->name.',' ;
				
			  $loc_a .= $zip;
			  
			  $data = AuctionTheme_get_geo_coordinates($loc_a);
			  $long = $data[3];
			  $lat 	= $data[2];			  
			  
			  update_post_meta($pid, 'auction_lat', 	$lat);
			  update_post_meta($pid, 'auction_long', 	$long);
			  
			 // print_r($data);
			  //exit;
			  //------------------------------------------------------
			
			$AuctionTheme_no_time_on_buy_now = get_option('AuctionTheme_no_time_on_buy_now');
					if($AuctionTheme_no_time_on_buy_now == "yes" and isset($_POST['only_buy_now'])):
						update_post_meta($pid, 'ending', 	current_time('timestamp',0) + 3600*24*5*365);	
					endif;
			
		if($auctionOK == 1) //if everything ok, go to next step
		{		
			wp_redirect(AuctionTheme_post_new_with_pid_stuff_thg($pid, 2));
			exit;	
		}
		
	}


?>