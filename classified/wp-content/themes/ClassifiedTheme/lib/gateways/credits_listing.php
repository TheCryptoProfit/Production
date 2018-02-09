<?php
/***************************************************************************
*
*	AuctionTheme - copyright (c) - sitemile.com
*	The only auction theme for wordpress on the world wide web.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/p/auctionTheme
*	since v4.5
*
***************************************************************************/
 
 	 
	global $current_user, $wp_query;
	$pid 	= $wp_query->query_vars['pid'];
	$uid	= $current_user->ID;
	$tms	= $_GET['tms'];
	
	//-----------------------------------
	
	function AuctionTheme_filter_ttl($title){return __("Pay by Virtual Currency",'AuctionTheme')." - ";}
	add_filter( 'wp_title', 'AuctionTheme_filter_ttl', 10, 3 );	
	
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }   
	
	$cid 	= $uid;
	$post_pr 	= get_post($pid);
	

	//-------------------------------------------------------------------------------
	// sealed bidding fee calculation
	
	$auctionTheme_sealed_bidding_fee = get_option('AuctionTheme_new_auction_sealed_bidding_fee');
	if(!empty($auctionTheme_sealed_bidding_fee))
	{
		$opt = get_post_meta($pid,'private_bids',true);
		if($opt == "0") { $auctionTheme_sealed_bidding_fee = 0; }
		
		 
	} else $auctionTheme_sealed_bidding_fee = 0;

	
	//-------
	
	$featured	 = get_post_meta($pid, 'featured', true);
	$feat_charge = get_option('AuctionTheme_new_auction_feat_listing_fee');
	
	if($featured != "1" ) $feat_charge = 0;

//---------------------------------------------	
	
	//-----------------------
	
	$AuctionTheme_new_auction_listing_fee = get_option('AuctionTheme_new_auction_listing_fee');
	if(empty($AuctionTheme_new_auction_listing_fee)) $AuctionTheme_new_auction_listing_fee = 0;
	
	$AuctionTheme_new_auction_feat_listing_fee = get_option('AuctionTheme_new_auction_feat_listing_fee');
	if(empty($AuctionTheme_new_auction_feat_listing_fee)) $AuctionTheme_new_auction_feat_listing_fee = 0;
	
	$AuctionTheme_new_auction_sealed_bidding_fee = get_option('AuctionTheme_new_auction_sealed_bidding_fee');
	if(empty($AuctionTheme_new_auction_sealed_bidding_fee)) $AuctionTheme_new_auction_sealed_bidding_fee = 0;
	
	$AuctionTheme_get_images_cost_extra = AuctionTheme_get_images_cost_extra($pid); 
	$catid 								= AuctionTheme_get_item_primary_cat($pid);
	
	//---------------------------------
	
	$custom_set = get_option('auctionTheme_enable_custom_posting');
	if($custom_set == 'yes')
	{
		$posting_fee = get_option('auctionTheme_theme_custom_cat_'.$catid);
		if(empty($posting_fee)) $posting_fee = 0;		
	}
	else
	{
		$posting_fee = $AuctionTheme_new_auction_listing_fee;
	}
	

	//----------------------------------------------
	
		$payment_arr = array();
		
		$base_fee_paid = get_post_meta($pid,'base_fee_paid',true);
		
		if($base_fee_paid != "1"):
				
			$my_small_arr = array();
			$my_small_arr['fee_code'] 		= 'base_fee';
			$my_small_arr['show_me'] 		= true;
			$my_small_arr['amount'] 		= $posting_fee;
			$my_small_arr['description'] 	= __('Base Fee','AuctionTheme');
			array_push($payment_arr, $my_small_arr);
			//-----------------------
			
		endif;
		
		$my_small_arr = array();
		$my_small_arr['fee_code'] 		= 'extra_img';
		$my_small_arr['show_me'] 		= true;
		$my_small_arr['amount'] 		= $AuctionTheme_get_images_cost_extra;
		$my_small_arr['description'] 	= __('Extra Images Fee','AuctionTheme');
		array_push($payment_arr, $my_small_arr);
		//------------------------
		
		$featured 			= get_post_meta($pid, 'featured', true);
		$featured_paid		= get_post_meta($pid, 'featured_paid',  true);
		
		if($featured == "1" and $featured_paid != '1'):
			$my_small_arr = array();
			$my_small_arr['fee_code'] 		= 'feat_fee';
			$my_small_arr['show_me'] 		= true;
			$my_small_arr['amount'] 		= $feat_charge;
			$my_small_arr['description'] 	= __('Featured Fee','AuctionTheme');
			array_push($payment_arr, $my_small_arr);
		endif;
		
		//------------------------
		
		
		$private_bids 			= get_post_meta($pid, 'private_bids', true);
		$private_bids_paid		= get_post_meta($pid, 'private_bids_paid',  true);
		
		if(($private_bids == "yes" or $private_bids == "1") and $private_bids_paid != "1" ):
		
			$my_small_arr = array();
			$my_small_arr['fee_code'] 		= 'sealed_project';
			$my_small_arr['show_me'] 		= true;
			$my_small_arr['amount'] 		= $auctionTheme_sealed_bidding_fee;
			$my_small_arr['description'] 	= __('Sealed Bidding Fee','AuctionTheme');
			array_push($payment_arr, $my_small_arr);

		endif;
		
		$payment_arr = apply_filters('AuctionTheme_filter_payment_array', $payment_arr, $pid);
		$new_total 		= 0;
		
		foreach($payment_arr as $payment_item):			
			if($payment_item['amount'] > 0):				
				$new_total += $payment_item['amount'];			
			endif;			
		endforeach;
		
		
		$total = apply_filters('AuctionTheme_filter_payment_total', $new_total, $pid);
			
	//----------------------------------------------
	
	$post_pr 			= get_post($pid);
	$admin_email 	= get_bloginfo('admin_email');


	
	//----------------

	get_header();
	
?>

	<div id="content" style="width:100%">       	
            <div class="my_box3">
            	          
            		<div class="box_title"><?php _e("Pay Listing by Virtual Currency", "AuctionTheme"); ?></div>
                		<div class="box_content margin_bottom_class"> 



           <div class="post no_border_btm" id="post-<?php the_ID(); ?>">
                
                <div class="image_holder">
                <a href="<?php echo get_permalink($pid); ?>"><?php echo AuctionTheme_get_first_post_image($pid,45,45); ?></a>
                </div>
                <div  class="title_holder" > 
                     <h2><a href="<?php echo get_permalink($post_pr->ID) ?>" rel="bookmark" title="Permanent Link to <?php echo $post_pr->post_title; ?>">
                        <?php  echo $post_pr->post_title; ?></a></h2>
      			</div>
                <?php
				
					if(isset($_GET['pay'])):
						echo '<div class="details_holder sk_sk_class">';
						
							$post_pr 	= get_post($pid);
							$cr 		= auctionTheme_get_credits($uid);
							$amount 	= $total;
							
							if($cr < $amount) { echo '<div class="error2">'; echo __('You do not have enough credits to pay for listing this project.','AuctionTheme');
							echo '</div><div class="clear10 flt_lft"></div>';
							?>
                            
							<div class="tripp">
								<a class="post_bid_btn" href="<?php echo AuctionTheme_get_payments_page_url('deposit'); ?>"><?php echo __('Add More Credits','AuctionTheme'); ?></a>
							</div>
                    
							<?php
                            }
							else
							{
								
								$paid 				= get_post_meta($pid, 'paid', true);
								$private_bids_paid 	= get_post_meta($pid, 'private_bids_paid', true);
								$featured_paid		= get_post_meta($pid, 'featured_paid',  true);
								$base_fee_paid		= get_post_meta($pid, 'base_fee_paid', true);
								$tmstms				= get_option('tmstms_pid_credits_' . $tms.$pid);
								 
								if($amount > 0 and  empty($tmstms)): //$paid != "1" or $private_bids_paid or $featured_paid or $base_fee_paid):
								// echo $pid;
										//auctionTheme_send_email_to_project_payer($pid, $uid, $post_pr->post_author, $amount, '1');	
										
										update_option('tmstms_pid_credits_'. $tms.$pid, "1");
										
										$perm = get_permalink($pid);
										auctionTheme_update_credits($uid, $cr - $amount);
										$reason = sprintf(__('Listing payment for item <a href="%s">%s</a>','AuctionTheme'), $perm, $post_pr->post_title);										
										$reason = apply_filters('AuctionTheme_reason_listing_auction', $reason, $pid);
										
										auctionTheme_add_history_log('0', $reason, $amount, $uid );
										
										$AuctionTheme_get_post_nr_of_images = AuctionTheme_get_post_nr_of_images($pid);
										update_post_meta($pid, 'paid_already_images', $AuctionTheme_get_post_nr_of_images );
										//---------------------
										
										update_post_meta($pid, "paid", 				"1");
										update_post_meta($pid, "paid_listing_date", current_time('timestamp',0));
										update_post_meta($pid, "closed", 			"0");
										
										//--------------------------------------------
										
										update_post_meta($pid, 'base_fee_paid', '1');
										
										$featured = get_post_meta($pid,'featured',true);	
										if($featured == "1") update_post_meta($pid, 'featured_paid', '1');
										else update_post_meta($pid, 'featured_paid', '0');
										
										$private_bids = get_post_meta($pid,'private_bids',true);	
										if($private_bids == "yes" or $private_bids == "1") update_post_meta($pid, 'private_bids_paid', '1');
										else update_post_meta($pid, 'private_bids_paid', '0');
		
										
										//--------------------------------------------
										
										$auctionTheme_admin_approves_each_auction = get_option('AuctionTheme_admin_approve_auction');
										
										if($auctionTheme_admin_approves_each_auction == "no")
										{
											 
											
											wp_publish_post( $pid );	
											$ct = time(); //current_time('timestamp',0);
											$post_new_date = date('Y-m-d H:i:s', $ct); 
											$post_date_gmt = gmdate($post_new_date);  
											
											$post_info = array(  "ID" 	=> $pid,
											  "post_date" 				=> $post_new_date,
											  "post_date_gmt" 			=> $post_date_gmt,
											  "post_status" 			=> "publish"	);
											
											wp_update_post($post_info);
											wp_publish_post( $pid );	
									
											 
											AuctionTheme_send_email_posted_item_approved($pid);
											AuctionTheme_send_email_posted_item_not_approved_admin($pid);
										
										}
										else
										{
											$ct = time(); //current_time('timestamp',0);
											$post_new_date = date('Y-m-d H:i:s', $ct); 
											$post_date_gmt = gmdate($post_new_date);  
											
											$post_pr_info = array(  "ID" 	=> $pid,
											  "post_date" 				=> $post_new_date,
											  "post_date_gmt" 			=> $post_date_gmt,
											  "post_status" 			=> "draft"	);
											
											wp_update_post($post_pr_info);
											
											AuctionTheme_send_email_posted_item_not_approved($pid);
											AuctionTheme_send_email_posted_item_approved_admin($pid);
												
											//AuctionTheme_send_email_subscription($pid);	
											
										}
								
								
								endif;
								
								//---------------------						
								
								echo sprintf(__('Your payment has been sent. Return to <a href="%s">your account</a>.','AuctionTheme'), get_permalink(get_option('AuctionTheme_my_account_page_id')) );	
							}
							echo '</div>';
				?>
           
                
                <?php else: ?>
                <div class="details_holder sk_sk_class">  
           
                
                <?php
				
				echo '<table style="margin-top:25px">';

	
		foreach($payment_arr as $payment_item):
			
			if($payment_item['amount'] > 0):
			
				echo '<tr>';
				echo '<td>'.$payment_item['description'].'&nbsp; &nbsp;</td>';
				echo '<td>'.AuctionTheme_get_show_price($payment_item['amount'],2).'</td>';
				echo '</tr>';

			endif;
			
		endforeach;	
	
	
	echo '<tr>';
	echo '<td>&nbsp;</td>';
	echo '<td></td>';
	echo '<tr>';
	
	
	echo '<tr>';
	echo '<td><strong>'.__('Total to Pay','AuctionTheme').'</strong></td>';
	echo '<td><strong>'.AuctionTheme_get_show_price($total,2).'</strong></td>';
	echo '<tr>';
	
	echo '</table>';
	
	?>
                
                
                
               <?php _e("Your credits amount",'AuctionTheme'); ?>: <?php echo AuctionTheme_get_show_price(auctionTheme_get_credits($uid)); ?>   <br/><br/>
               <a class="post_bid_btn" href="<?php echo get_bloginfo('siteurl'); ?>/?tms=<?php echo $_GET['tms'] ?>&a_action=credits_listing&pid=<?php echo $pid; ?>&pay=yes"><?php echo __('Pay Now','AuctionTheme'); ?></a> 
               
               <a class="post_bid_btn" href="<?php echo AuctionTheme_get_payments_page_url('deposit'); ?>"><?php echo __('Add More Credits','AuctionTheme'); ?></a>
                </div>
				
  
				<?php endif; ?>
                
                
				</div>


                	 
                	</div>
                </div>
            </div>
                
<div class="clear100"></div>
<div class="clear100"></div>

<?php get_footer(); ?>