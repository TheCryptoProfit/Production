<?php
/***************************************************************************
*
*	AuctionTheme - copyright (c) - sitemile.com
*	The most popular auction theme for wordress on the internet. Launch your
*	auction site in minutes from purchasing. Turn-key solution.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/p/auctionTheme
*	since v4.4.7.1
*
***************************************************************************/


		global $wpdb;
		
 		$id = $wp_query->query_vars['rid'];
		$s = "select * from ".$wpdb->prefix."auction_ratings where id='$id'";
		$result = $wpdb->get_results($s);
		
		$row = $result[0]; 
		$pid = $row->pid;
		$user = get_userdata($row->touser);
		$post_au = get_post($row->pid);
		
 		$my_uid = $row->touser;
		$my_uid2 = $row->fromuser;
 
 	get_header();
 ?> 
 
 
<div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">
        	
            <div class="my_box3">
            
            	<div class="box_title"><?php printf(__("Review User %s for item %s",'AuctionTheme'), $user->user_login, $post_au->post_title ) ; ?></div>
                <div class="box_content"> 
            	
                <?php
			$nok = 1;
			
			if(isset($_POST['rateme']))
			{
			
				$rating = $_POST['rating'];
				$comment = nl2br(strip_tags($_POST['commenta']));
				
				if(empty($comment)):
					
					$nok = 1;
					
					echo '<div class="error">';
					echo __('Please leave a comment with your review.','AuctionTheme');
					echo '</div>';
					
				else:
					
					$tm = current_time('timestamp',0);
						
					$s = "update ".$wpdb->prefix."auction_ratings set grade='$rating', datemade='$tm', comment='$comment', awarded='1' where id='$id'";
					$wpdb->query($s);					
						
					$link = get_permalink(get_option('AuctionTheme_my_account_page_id'));	
					printf(__("Your rating has been posted. <a href='%s'>Return to your account area</a>","AuctionTheme"),$link);
					
					$nok = 0;
					

					AuctionTheme_send_email_when_review_has_been_awarded($pid, $my_uid, $my_uid2);
					//---------------------------
					
					$cool_user_rating = get_user_meta($my_uid, 'cool_user_rating', true);
					if(empty($cool_user_rating)) update_user_meta($my_uid, 'cool_user_rating', 0);
					
					//---------------------------
					
					$cool_user_rating = get_user_meta($my_uid, 'cool_user_rating', true);
					
					global $wpdb;
					$s = "select grade from ".$wpdb->prefix."auction_ratings where touser='$my_uid' AND awarded='1'";
					$r = $wpdb->get_results($s);
					$i = 0; $s = 0;
						
					if(count($r) > 0)
					{
						foreach($r as $row) // = mysql_fetch_object($r))
						{
							$i++;
							$s = $s + $row->grade;
								
						}
	
						$rating2 = round(($s/$i)/2, 2);
						update_user_meta($my_uid, 'cool_user_rating', $rating2);
					
					}
					
					
					//---------------------------
					
				endif;
			}
			
			if($nok == 1)
			{
		
		?>
        <form method="post">	
             	   <table class="table">
            <tr>
        	<td><?php echo __('Your Rating','AuctionTheme'); ?>:</td>
        	<td><select class="do_input" name="rating"><?php for($i=5;$i>0;$i--) echo '<option value="'.($i*2).'">'.$i.'</option>'; ?></select></td>
        </tr>
        
        <tr>
        	<td><?php echo __('Your Comment','AuctionTheme'); ?>:</td>
        	<td><textarea name="commenta" class="do_input" rows="5" cols="40" ></textarea></td>
        </tr>
        
        
        
           <tr>
        	<td>&nbsp;</td>
        	<td><input type="submit" name="rateme" class="submit_bottom" value="<?php _e("Submit Rating","AuctionTheme"); ?>"  /></td>
        </tr>
        
        
        
        </table>
         </form> <?php } ?>      
                
                
                </div>
                </div>
                </div>
                
	<?php AuctionTheme_get_users_links(); ?>

<?php get_footer(); ?>