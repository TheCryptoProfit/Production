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

function AuctionTheme_my_account_reviews_area_function()
{
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	global $wpdb,$wp_rewrite,$wp_query;

	?>


     <div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">


                <div class="my_box3">

            	<div class="box_title"><?php _e("Reviews I need to award",'AuctionTheme'); ?></div>
                <div class="box_content">

              	<?php

					global $wpdb;
					$query = "select * from ".$wpdb->prefix."auction_ratings where fromuser='$uid' AND awarded='0' order by id desc";
					$r = $wpdb->get_results($query);

					if(count($r) > 0)
					{
						echo '<div class="table-responsive">';
						echo '<table class="table">';
							echo '<tr>';
								echo '<th>&nbsp;</th>';
								echo '<th><b>'.__('Auction Title','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('To User','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('Aquired on','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('Price','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('Options','AuctionTheme').'</b></th>';

							echo '</tr>';


						foreach($r as $row)
						{
							$post_aux 	= $row->pid;
							$post_aux 	= get_post($post_aux);
							$bid 	= auctionTheme_get_winner_bid($row->bid_id);
							$user 	= get_userdata($row->touser);


							echo '<tr>';

								echo '<th>'.AuctionTheme_get_first_post_image($row->pid, 42, 42).'</th>';
								echo '<th><a href="'.get_permalink($row->pid).'">'.$post_aux->post_title.'</a></th>';
								echo '<th><a href="'.AuctionTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';
								echo '<th>'.date('d-M-Y H:i:s', $row->datemade).'</th>';
								echo '<th>BTC '.get_rate_USD_to_btc($bid->bid).'</th>';
								echo '<th><a href="'.get_bloginfo('siteurl').'/?a_action=rate_user&rid='.$row->id.'">'.__('Rate User','AuctionTheme').'</a></th>';

							echo '</tr>';

						}

						echo '</table></div>';
					}
					else
					{
						echo '<div class="padd10">';
						_e("There are no reviews to be awarded.","AuctionTheme");
						echo '</div>';
					}
				?>



           </div>
           </div>

           <!-- ##### -->
           <div class="clear10"></div>

           <div class="my_box3">


            	<div class="box_title"><?php _e("Reviews I am waiting ",'AuctionTheme'); ?></div>
                <div class="box_content">

              	<?php

					global $wpdb;
					$query = "select * from ".$wpdb->prefix."auction_ratings where touser='$uid' AND awarded='0' order by id desc";
					$r = $wpdb->get_results($query);

					if(count($r) > 0)
					{
						echo '<div class="table-responsive">';
						echo '<table class="table">';
							echo '<tr>';
								echo '<th>&nbsp;</th>';
								echo '<th><b>'.__('Auction Title','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('From User','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('Aquired on','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('Price','AuctionTheme').'</b></th>';
								//echo '<th><b>'.__('Options','AuctionTheme').'</b></th>';

							echo '</tr>';


						foreach($r as $row)
						{
							$post_aux = $row->pid;
							$post_aux = get_post($post_aux);
							$bid = auctionTheme_get_winner_bid($row->bid_id);
							$user = get_userdata($row->fromuser);
							echo '<tr>';

								echo '<th>'.AuctionTheme_get_first_post_image($row->pid, 42, 42).'</th>';
								echo '<th><a href="'.get_permalink($row->pid).'">'.$post_aux->post_title.'</a></th>';
								echo '<th><a href="'.AuctionTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';
								echo '<th>'.date('d-M-Y H:i:s',$bid->date_choosen).'</th>';
								echo '<th>BTC '.get_rate_USD_to_btc($bid->bid).'</th>';
								//echo '<th><a href="#">Rate User</a></th>';

							echo '</tr>';

						}

						echo '</table></div>';
					}
					else
					{
						echo '<div class="padd10">';
						_e("There are no reviews to be awarded.","AuctionTheme");
						echo '</div>';
					}
				?>


           </div>
           </div>

           <div class="clear10"></div>

           <div class="my_box3">


            	<div class="box_title"><?php _e("Reviews I was awarded ",'AuctionTheme'); ?></div>
                <div class="box_content">

              	<?php

					global $wpdb;
					$query = "select * from ".$wpdb->prefix."auction_ratings where touser='$uid' AND awarded='1' order by id desc";
					$r = $wpdb->get_results($query);

					if(count($r) > 0)
					{
						echo '<div class="table-responsive">';
						echo '<table class="table">';
							echo '<tr>';
								echo '<th>&nbsp;</th>';
								echo '<th><b>'.__('Auction Title','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('From User','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('Aquired on','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('Price','AuctionTheme').'</b></th>';
								echo '<th><b>'.__('Rating','AuctionTheme').'</b></th>';


							echo '</tr>';


						foreach($r as $row)
						{
							$post_aux = $row->pid;
							$post_aux = get_post($post_aux);
							$bid = auctionTheme_get_winner_bid($row->bid_id);
							$user = get_userdata($row->fromuser);
							echo '<tr>';

								echo '<th>'.AuctionTheme_get_first_post_image($row->pid, 42, 42).'</th>';
								echo '<th><a href="'.get_permalink($row->pid).'">'.$post_aux->post_title.'</a></th>';
								echo '<th><a href="'.AuctionTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';
								echo '<th>'.date('d-M-Y H:i:s',$bid->date_choosen).'</th>';
								echo '<th>BTC '.get_rate_USD_to_btc($bid->bid).'</th>';
								echo '<th>'.round($row->grade/2).'</th>';


							echo '</tr>';
							echo '<tr>';
							echo '<th></th>';
							echo '<th colspan="5"><b>'.__('Comment','AuctionTheme').':</b> '.$row->comment.'</th>'	;
							echo '</tr>';

							echo '<tr><th colspan="6"><hr color="#eee" /></th></tr>';

						}

						echo '</table></div>';
					}
					else
					{
						echo '<div class="padd10">';
						_e("There are no reviews to be awarded.","AuctionTheme");
						echo '</div>';
					}
				?>



           </div>
           </div>




    <!-- ############################################# -->
    </div>

    <?php

	auctionTheme_get_users_links();
}

?>
