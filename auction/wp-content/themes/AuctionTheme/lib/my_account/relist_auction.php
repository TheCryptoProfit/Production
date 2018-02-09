<?php


	global   $wp_query;
  $current_user = wp_get_current_user();
	$pid 	=  $wp_query->query_vars['pid'];

	function AuctionTheme_filter_ttl($title){return __("Repost Auction",'AuctionTheme')." - ";}
	add_filter( 'wp_title', 'AuctionTheme_filter_ttl', 10, 3 );

	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }


	get_currentuserinfo;

	$post_au = get_post($pid);

	$uid 	= $current_user->ID;
	$title 	= $post->post_title;
	$cid 	= $current_user->ID;

	if($uid != $post_au->post_author) { echo 'Not your post. Sorry!'; exit; }

//-------------------------------------


		$cid = $uid;

		if(isset($_POST['submit2']))
		{
			wp_redirect(get_permalink(get_option('AuctionTheme_my_account_page_id')));
			exit;
		}

		//---autodrafting

		if(isset($_POST['submit'])):

			$new_pid = AuctionTheme_get_auto_draft($uid);
			$itwas_reposted = get_post_meta($new_pid, 'itwas_reposted_', true);

			if(empty($itwas_reposted))
			{

				update_post_meta($new_pid, 'itwas_reposted_', "done");


				$args = array(
				'order'          => 'ASC',
				'orderby'        => 'post_date',
				'post_type'      => 'attachment',
				'post_parent'    => $pid,

				'post_status'    => null,
				'numberposts'    => -1,
				);
				$attachments = get_posts($args);
				$uploads = wp_upload_dir();

				foreach($attachments as $att)
				{
						$img_url = wp_get_attachment_url($att->ID);
						$basedir = $uploads['basedir'].'/';
						$exp = explode('/',$img_url);


						$nr = count($exp);
						$pic = $exp[$nr-1];
						$year = $exp[$nr-3];
						$month = $exp[$nr-2];

						if($uploads['basedir'] == $uploads['path'])
						{
							$img_url = $basedir.'/'.$pic;
							$ba = $basedir.'/';
							$iii = $uploads['url'];
						}
						else
						{
							$img_url = $basedir.$year.'/'.$month.'/'.$pic;
							$ba = $basedir.$year.'/'.$month.'/';
							$iii = $uploads['baseurl']."/".$year."/".$month;
						}

						$oldPic_name = $img_url;

						$newpicname = 'copy_'.rand(0,999).'_'.$pic;
						$newPic_name = $uploads['path'].'/'.$newpicname;

						//echo $oldPic_name.'<br/>';
						//echo $newPic_name.'<br/>';

						copy($oldPic_name, $newPic_name);
						AuctionTheme_insert_pic_media_lib($cid, $new_pid, $uploads['url'].'/'.$newpicname, $newPic_name, $newpicname);
						//echo $newPic_name.'<br/>';



				}

				//-----------------------
			}

			// lets submit it

			//-----------------------------------------

					$my_post = array();
					$my_post['post_title'] 		= $post_au->post_title;
					$my_post['ID'] 				= $new_pid;
					$my_post['post_content'] 	= $post_au->post_content;
					$my_post['post_status'] 	= 'draft';
					wp_update_post( $my_post );

					//-------------------

					update_post_meta($new_pid, 'closed','0');
					update_post_meta($new_pid, "views", 		'0');
					update_post_meta($new_pid, "paid", "0");

					$featured 			= get_post_meta($pid, 'featured', true);
					$private_bids 		= get_post_meta($pid, 'private_bids', true);
					$buy_now 			= get_post_meta($pid, 'buy_now', true);
					$reserve 			= get_post_meta($pid, 'reserve', true);
					$start_price 		= get_post_meta($pid, 'start_price', true);
					$only_buy_now 		= get_post_meta($pid, 'only_buy_now', true);
					$shipping 			= get_post_meta($pid, 'shipping', true);
					$quant 				= get_post_meta($pid, 'quant', true);
					$Location 			= get_post_meta($pid, 'Location', true);
					$do_not_require_shipping = get_post_meta($pid, 'do_not_require_shipping', true);
					//-----------------------
					if(empty($start_price)) $start_price = 0;

					update_post_meta($new_pid, "featured", $featured);
					update_post_meta($new_pid, "private_bids", $private_bids);
					update_post_meta($new_pid, "buy_now", $buy_now);
					update_post_meta($new_pid, "reserve", $reserve);
					update_post_meta($new_pid, "start_price", $start_price);
					update_post_meta($new_pid, "only_buy_now", $only_buy_now);
					update_post_meta($new_pid, "shipping", $shipping);
					update_post_meta($new_pid, "quant", $quant);
					update_post_meta($new_pid, "Location", $Location);
					update_post_meta($new_pid, "closed_date", "0");
					update_post_meta($new_pid, "do_not_require_shipping", $do_not_require_shipping);
					//----------

					update_post_meta($new_pid, "current_bid", $start_price);
					if(empty($start_price))
					{
						if(!empty($buy_now))
						update_post_meta($new_pid, "current_bid", $buy_now);
						else update_post_meta($new_pid, "current_bid", 0);
			  		}

			//-----------------------------------------

			$old_cats = wp_get_object_terms( $pid, 'auction_cat' );
			$arrs = array();

			foreach($old_cats as $cat)
				array_push($arrs, $cat->slug);


			wp_set_object_terms( $new_pid, $arrs, 'auction_cat' );

			//-----------------------------------------

			$old_cats = wp_get_object_terms( $pid, 'auction_location' );
			$arrs1 = array();

			foreach($old_cats as $cat)
				array_push($arrs1, $cat->slug);


			wp_set_object_terms( $new_pid, $arrs1, 'auction_location' );

			//-----------------------------------------

			$auction_tags = '';

			$t = wp_get_post_tags($pid);
			foreach($t as $tg)
			{
				$auction_tags .= $tg->name . ', ';
			}

			wp_set_post_tags($new_pid, $auction_tags);

			//-----------------------------------------
			$cat 		  	= wp_get_object_terms($pid, 'auction_cat');
			$catidarr 		= array();

			foreach($cat as $catids)
			{
				$catidarr[] = $catids->term_id;
			}

			get_auction_category_fields_for_relisting($catidarr, $pid, $new_pid);

			wp_redirect(AuctionTheme_post_new_with_pid_stuff_thg($new_pid,1));
			exit;

		endif;

	get_header();



?>


	<div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">

            <div class="my_box3">

            	<div class="box_title"><?php printf(__("Repost Auction - %s", "AuctionTheme"), $post_au->post_title); ?></div>
                <div class="box_content">


    <?php _e('Are you sure you want to repost this item ?',	'AuctionTheme'); ?>

    <br/>  <br/>

    <form method="post">

  		<input type="submit" value="<?php _e('Yes','AuctionTheme'); ?>" name="submit" />
        <input type="submit" value="<?php _e('No','AuctionTheme'); ?>" name="submit2" />

    </form>


                </div>
                </div>
                </div>

	<?php AuctionTheme_get_users_links(); ?>

<?php get_footer(); ?>
