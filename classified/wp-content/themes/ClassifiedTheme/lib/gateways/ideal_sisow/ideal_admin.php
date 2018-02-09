<?php

include 'ideal_dep.php';

add_filter('AuctionTheme_payment_methods_content_divs_m',	'AuctionTheme_payment_methods_content_divs_ideal_sisow');
add_filter('AuctionTheme_payment_methods_action',			'AuctionTheme_payment_methods_action_ideal_sisow');
add_filter('AuctionTheme_payment_methods_tabs',				'AuctionTheme_payment_methods_tabs_ideal_sisow');


add_filter('AuctionTheme_add_payment_options_to_post_new_project','AuctionTheme_add_payment_options_to_post_new_project_sisow');
add_filter('AuctionTheme_add_payment_options_to_edit_auction','AuctionTheme_add_payment_options_to_post_new_project_sisow');


function AuctionTheme_add_payment_options_to_post_new_project_sisow($pid)
{
	$AuctionTheme_ideal_sisow_enable = get_option('AuctionTheme_ideal_sisow_enable');
	
	if($AuctionTheme_ideal_sisow_enable == "yes")
	{
		?>
        
         <br/><br/>
                
                <form method="post">
                <input type="hidden" value="<?php echo $pid ?>" name="pid" />
                              Bank: 
                <?php
               
			   include 'ideal.php';
			   
			   $sis = new Sisow_Ideal();
			   $sis->doIssuerRequest();
			   echo '<select name="issuer">';
			   echo $sis->getIssuers(true);
			   echo '</select>';
			   
			   ?>
                
                &nbsp; &nbsp; <input type="submit" name="deposit_ideal_sisow_me2" value="<?php _e('Pay By iDeal','AuctionTheme'); ?>" /></form>
        
        
        <?php	
		
	}
		
}

function AUCTION_THEME_PAY_LISTING_IDEAL($pid)
{ 
	global $wp_query, $wpdb, $current_user;
	$pid = $wp_query->query_vars['pid'];
	get_currentuserinfo();
	$uid = $current_user->ID;
	$post = get_post($pid);
	include 'ideal.php';  
	

	//----------------------------
	
			$features_not_paid = array();		
			$catid = AuctionTheme_get_auction_primary_cat($pid);
			$AuctionTheme_get_images_cost_extra = AuctionTheme_get_images_cost_extra($pid);
			$payment_arr = array();
			
			//-----------------------------------
			
			$base_fee_paid 	= get_post_meta($pid, 'base_fee_paid', true);
			$base_fee 		= get_option('AuctionTheme_new_auction_listing_fee');

			
			$custom_set = get_option('auctionTheme_enable_custom_posting');
			if($custom_set == 'yes')
			{
				$base_fee = get_option('auctionTheme_theme_custom_cat_'.$catid);
				if(empty($base_fee)) $base_fee = 0;		
			}
			
			//----------------------------------------------------------
			
			if($base_fee_paid != "1" && $base_fee > 0)
			{

				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'base_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $base_fee;
				$my_small_arr['description'] 	= __('Base Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
				
			}
			
			//----------------------------------------------------------
			
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'extra_img';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $AuctionTheme_get_images_cost_extra;
				$my_small_arr['description'] 	= __('Extra Images Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
			
			
			//-------- Featured Project Check --------------------------
			
			
			$featured 		= get_post_meta($pid, 'featured', true);
			$featured_paid 	= get_post_meta($pid, 'featured_paid', true);
			$feat_charge 	= get_option('AuctionTheme_new_auction_feat_listing_fee');
			
			if($featured == "1" && $featured_paid != "1" && $feat_charge > 0)
			{
				
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'feat_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $feat_charge;
				$my_small_arr['description'] 	= __('Featured Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
				
			}
			
			//---------- Private Bids Check -----------------------------
			
			$private_bids 		= get_post_meta($pid, 'private_bids', true);
			$private_bids_paid 	= get_post_meta($pid, 'private_bids_paid', true);
			
			$auctionTheme_sealed_bidding_fee = get_option('AuctionTheme_new_auction_sealed_bidding_fee');
			if(!empty($auctionTheme_sealed_bidding_fee))
			{
				$opt = get_post_meta($pid,'private_bids',true);
				if($opt == "no") $auctionTheme_sealed_bidding_fee = 0;
			}
			
			
			if($private_bids == "yes" && $private_bids_paid != "1" && $auctionTheme_sealed_bidding_fee > 0)
			{				
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'sealed_project';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $auctionTheme_sealed_bidding_fee;
				$my_small_arr['description'] 	= __('Sealed Bidding Fee','AuctionTheme');
				array_push($payment_arr, $my_small_arr);
			}

			//---------------------
			
			$payment_arr = apply_filters('AuctionTheme_filter_payment_array', $payment_arr, $pid);
		
						
			$my_total = 0;
			foreach($payment_arr as $payment_item):
				if($payment_item['amount'] > 0):
					$my_total += $payment_item['amount'];
				endif;
			endforeach;			
			
			$my_total = apply_filters('AuctionTheme_filter_payment_total', $my_total, $pid);

//----------------------------------------------
	$additional_paypal = 0;
	$additional_paypal = apply_filters('AuctionTheme_filter_paypal_listing_additional', $additional_paypal, $pid);
	
	//$AuctionTheme_get_show_price = AuctionTheme_get_show_price($pid);
	$total = $my_total + $additional_paypal;
	
	$title_post = $post->post_title;
	$title_post = apply_filters('AuctionTheme_filter_paypal_listing_title', $title_post, $pid);
	  
//---------------------------------------------		

	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?a_action=payfast_listing_response&pid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?a_action=payfast_listing_response';
	$ccnt_url		= get_permalink(get_option('AuctionTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('AuctionTheme_currency');
	
		
	$amount = $my_total;
		$issuer = $_POST['issuer'];
		$sis = new Sisow_Ideal();
		
		$sMerchantId 	= get_option('AuctionTheme_ideal_email');
		$sMerchantKey 	= get_option('AuctionTheme_ideal_secret');
		//$sShopId 		= get_option('AuctionTheme_ideal_shopid');
		
		$tm = current_time('timestamp',0);
	 
		$sCallbackUrl 			= get_bloginfo("siteurl") . "/?callback_ideal2=1";
		$notifyurl				 = get_bloginfo("siteurl") . "/?notify_ideal_deposit2=1&dets_a=" . $pid.'_'.$uid.'_'.$tm; 
		
		$sReturnUrl 			= get_bloginfo('siteurl');
		$sPurchaseDescription 	= "Item payment";
		$fPurchaseAmount 		= $amount;
		$sPurchaseId 			= $uid;
		$sIssuerId 				= $issuer;
		$sEntranceCode 			= "238334";
		
		$sis->setMerchant($sMerchantId, $sMerchantKey);
		$sis->doTransactionRequest($sIssuerId, $sPurchaseId, $fPurchaseAmount, $sPurchaseDescription, $sEntranceCode, $sReturnUrl, $sCallbackUrl, $notifyurl);
		
		 
		
		 $sis->doTransaction();
		
		
		die();
	
	
}

function AuctionTheme_payment_methods_tabs_ideal_sisow()
{
?>
		<li><a href="#tabs_90">iDeal Sisow</a></li>
<?php	
}


function AuctionTheme_payment_methods_action_ideal_sisow()
{
	
	if(isset($_POST['AuctionTheme_save_ideal_sisow']))
	{
		update_option('AuctionTheme_ideal_email', 	trim($_POST['AuctionTheme_ideal_email']));
		update_option('AuctionTheme_ideal_secret', 		trim($_POST['AuctionTheme_ideal_secret']));
		update_option('AuctionTheme_ideal_shopid', 		trim($_POST['AuctionTheme_ideal_shopid']));
		update_option('AuctionTheme_ideal_sisow_enable', 		trim($_POST['AuctionTheme_ideal_sisow_enable']));
		
		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';		
	}
		
}

function AuctionTheme_payment_methods_content_divs_ideal_sisow()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));
	
?>

<div id="tabs_90" >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs_90">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_ideal_sisow_enable'); ?></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('iDeal Merchant ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_ideal_email" value="<?php echo get_option('AuctionTheme_ideal_email'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Merchant Key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_ideal_secret" value="<?php echo get_option('AuctionTheme_ideal_secret'); ?>"/></td>
                    </tr>
                    
                    
                     <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Shop ID:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_ideal_shopid" value="<?php echo get_option('AuctionTheme_ideal_shopid'); ?>"/></td>
                    </tr>
                    

                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_ideal_sisow" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          	
          </div>

<?php	
	
}

?>