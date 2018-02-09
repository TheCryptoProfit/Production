<?php

function AuctionTheme_my_account_buyer_offers_area_function()
{
$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	global $wpdb,$wp_rewrite,$wp_query;

	?>


   <div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">

            <div class="my_box3">

            	<div class="box_title"><?php _e("Submitted Offers","AuctionTheme"); ?></div>
            	<div class="box_content">

                  <?php

 
					$uid = $current_user->ID;

					global $wp_query;
					$query_vars = $wp_query->query_vars;
					$post_per_page = 5;
					$nrpostsPage = $post_per_page;

					$page = $_GET['pj'];
					if(empty($page)) $page = 1;

					//---------------------------------


				 global $wpdb;
				 $querystr2 = "
					SELECT distinct wposts.ID, offers.*
					FROM $wpdb->posts wposts, ".$wpdb->prefix."auction_offers offers
					WHERE

					wposts.ID=offers.pid AND
					offers.uid='$uid' ";

				$pageposts2 = $wpdb->get_results($querystr2, OBJECT);
				$total_count = count($pageposts2);
				$my_page = $page;
				$pages_curent = $page;
			//-----------------------------------------------------------------------

				$totalPages = ($total_count > 0 ? ceil($total_count / $nrpostsPage) : 0);
				$pagess = $totalPages;


				$querystr = "
					SELECT distinct wposts.*, offers.*
					FROM $wpdb->posts wposts, ".$wpdb->prefix."auction_offers offers
					WHERE

					wposts.ID=offers.pid AND
					offers.uid='$uid'

					ORDER BY offers.dateanswered asc LIMIT ".($nrpostsPage * ($page - 1) ).",". $nrpostsPage ;


				 $pageposts = $wpdb->get_results($querystr, OBJECT);
				 $posts_per = 7;
				 ?>



					 <?php $i = 0; if ($pageposts): ?>
					 <?php global $post; ?>
                     <?php foreach ($pageposts as $post): ?>
                     <?php setup_postdata($post); ?>


                     <?php

                      			auctionTheme_get_post_sent_offer();

                     ?>

                     <?php endforeach; ?>

                      <div class="nav">
                     <?php

		$batch = 10; //ceil($page / $nrpostsPage );
		$end = $batch * $nrpostsPage;


		if ($end > $pagess) {
			$end = $pagess;
		}
		$start = $end - $nrpostsPage + 1;

		if($start < 1) $start = 1;

		$links = '';


		$raport = ceil($my_page/$batch) - 1; if ($raport < 0) $raport = 0;

		$start 		= $raport * $batch + 1;
		$end		= $start + $batch - 1;
		$end_me 	= $end + 1;
		$start_me 	= $start - 1;

		if($end > $totalPages) $end = $totalPages;
		if($end_me > $totalPages) $end_me = $totalPages;

		if($start_me <= 0) $start_me = 1;

		$previous_pg = $page - 1;
		if($previous_pg <= 0) $previous_pg = 1;

		$next_pg = $pages_curent + 1;
		if($next_pg > $totalPages) $next_pg = 1;



		//PricerrTheme_get_browse_jobs_link($job_tax, $job_category, 'new', $page)

		if($my_page > 1)
		echo '<a href="'.AuctionTheme_get_browse__special_pg_link('AuctionTheme_my_account_offers_buyer_id', $previous_pg).'"><< '.__('Previous','AuctionTheme').'</a>';
		echo '<a href="'.AuctionTheme_get_browse__special_pg_link('AuctionTheme_my_account_offers_buyer_id', $start_me).'"><<</a>';

		//------------------------
		//echo $start." ".$end;
		for($i = $start; $i <= $end; $i ++) {
			if ($i == $pages_curent) {
				echo '<a class="activee" href="#">'.$i.'</a>';
			} else {

				echo '<a href="'.AuctionTheme_get_browse__special_pg_link('AuctionTheme_my_account_offers_buyer_id', $i).'">'.$i.'</a>';

			}
		}

		//----------------------

		if($totalPages > $my_page)
		echo '<a href="'.AuctionTheme_get_browse__special_pg_link('AuctionTheme_my_account_offers_buyer_id', $end_me).'">>></a>';
		echo '<a href="'.AuctionTheme_get_browse__special_pg_link('AuctionTheme_my_account_offers_buyer_id', $next_pg).'">'.__('Next','AuctionTheme').' >></a>';

					 ?>
                     </div>



                     <?php else: ?>
                     <div class="padd10">
                     <?php _e('There are no offers yet','AuctionTheme'); ?>
                     </div>

                     <?php endif; ?>



					<?php

					wp_reset_query();

					?>


                </div>
           </div>


    <!-- ############################################# -->
    </div>

    <?php

	auctionTheme_get_users_links();


}

?>
