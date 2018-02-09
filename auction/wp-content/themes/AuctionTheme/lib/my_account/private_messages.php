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

if(!function_exists('AuctionTheme_my_account_priv_mess_area_function'))
{
function AuctionTheme_my_account_priv_mess_area_function()
{

	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

		global $wpdb,$wp_rewrite,$wp_query;
		$third_page = $_GET['priv_act'];

		if(empty($third_page)) $third_page = 'home';


		$using_perm = AuctionTheme_using_permalinks();

		if($using_perm)	$privurl_m = get_permalink(get_option('AuctionTheme_my_account_priv_mess_page_id')). "?";
		else $privurl_m = get_bloginfo('siteurl'). "/?page_id=". get_option('AuctionTheme_my_account_priv_mess_page_id'). "&";

	?>

    <div id="content" class="account-content-area col-xs-12 col-sm-8 col-lg-9">
    <div class="my_box3">
            	<div class="padd10">
                <a href="<?php echo $privurl_m; ?>" class="green_btn"><?php _e("Messaging Home","AuctionTheme"); ?></a>
                <a href="<?php echo $privurl_m; ?>priv_act=send" class="green_btn"><?php _e("Send New Message","AuctionTheme"); ?></a>
                <a href="<?php echo $privurl_m; ?>priv_act=inbox" class="green_btn"><?php _e("Inbox","AuctionTheme");

				 $current_user = wp_get_current_user();

				$rd = auctionTheme_get_unread_number_messages($current_user->ID);
				if($rd > 0) echo ' ('.$rd.')';

				 ?></a>
                <a href="<?php echo $privurl_m; ?>priv_act=sent-items" class="green_btn"><?php _e("Sent Items","AuctionTheme"); ?></a>

                </div>
        </div>
        <div class="clear10"></div>
        <?php

			if($third_page == 'home') {

		$current_user = wp_get_current_user();
			$myuid = $current_user->ID;

		?>

		<!-- page content here -->



            	<div class="my_box3">


            	<div class="box_title"><?php _e("Latest Received Messages","AuctionTheme"); ?></div>
                <div class="box_content">
                <?php
				global $wpdb;
				$s = "select * from ".$wpdb->prefix."auction_pm where user='$myuid' AND show_to_destination='1' order by id desc limit 4";
				$r = $wpdb->get_results($s);

				if(count($r) > 0)
				{
					echo '<div class="table-responsive">';
					echo '<table class="table">';

					echo '<tr>';
						echo '<td>'.__('From User','AuctionTheme').'</td>';
						echo '<td>'.__('Subject','AuctionTheme').'</td>';
						echo '<td>'.__('Date','AuctionTheme').'</td>';
						echo '<td>'.__('Options','AuctionTheme').'</td>';
						echo '</tr>';



					foreach($r as $row)
					{
						if($row->rd == 0) $cls = 'bold_stuff';
						else $cls = '';

						$user = get_userdata($row->initiator);

						echo '<tr>';
						echo '<td class="'.$cls.'"><a href="'.AuctionTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></td>';
						echo '<td class="'.$cls.'">'.$row->subject.'</td>';
						echo '<td class="'.$cls.'">'.date('d-M-Y H:i:s',$row->datemade).'</td>';
						echo '<td><a href="'.$privurl_m.'priv_act=read-message&id='.$row->id.'">'.__('Read','AuctionTheme').'</a> |
						<a href="'.$privurl_m.'return=home&priv_act=delete-message&id='.$row->id.'">'.__('Delete','AuctionTheme').'</a></td>';
						echo '</tr>';

					}


					echo '</table></div>';
				} else { echo '<div class="padd10">'; _e('No messages here.','AuctionTheme'); echo '</div>'; }

				?>

                </div>
                </div>


            <!--#######-->

            <div class="clear10"></div>

            	<div class="my_box3">


            	<div class="box_title"><?php _e("Latest Sent Items","AuctionTheme"); ?></div>
                <div class="box_content">
                <?php
				global $wpdb;
				$s = "select * from ".$wpdb->prefix."auction_pm where initiator='$myuid' AND show_to_source='1' order by id desc limit 4";
				$r = $wpdb->get_results($s);

				if(count($r) > 0)
				{
					echo '<div class="table-responsive">';
					echo '<table class="table">';

					echo '<tr>';
						echo '<td>'.__('To User','AuctionTheme').'</td>';
						echo '<td>'.__('Subject','AuctionTheme').'</td>';
						echo '<td>'.__('Date','AuctionTheme').'</td>';
						echo '<td>'.__('Options','AuctionTheme').'</td>';
						echo '</tr>';



					foreach($r as $row)
					{
						//if($row->rd == 0) $cls = 'bold_stuff';
						//else
						 $cls = '';

						$user = get_userdata($row->user);

						echo '<tr>';
						echo '<td class="'.$cls.'"><a href="'.AuctionTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></td>';
						echo '<td class="'.$cls.'">'.$row->subject.'</td>';
						echo '<td class="'.$cls.'">'.date('d-M-Y H:i:s',$row->datemade).'</td>';
						echo '<td><a href="'.$privurl_m.'priv_act=read-message&id='.$row->id.'">'.__('Read','AuctionTheme').'</a> |
						<a href="'.$privurl_m.'return=home&priv_act=delete-message&id='.$row->id.'">'.__('Delete','AuctionTheme').'</a></td>';
						echo '</tr>';

					}


					echo '</table></div>';
				}
				else { echo '<div class="padd10">'; _e('No messages here.','AuctionTheme'); echo '</div>'; }
				?>

                </div>
                </div>



		<!-- page content here -->

        <?php }


			elseif($third_page == 'inbox') {

		$current_user = wp_get_current_user();
			$myuid = $current_user->ID;

		?>

		<!-- page content here -->


            	<div class="my_box3">

            	<div class="box_title"><?php _e("Private Messages: Inbox","AuctionTheme"); ?></div>
                <div class="box_content">
                <?php
				global $wpdb;
				$s = "select * from ".$wpdb->prefix."auction_pm where user='$myuid' AND show_to_destination='1' order by id desc";
				$r = $wpdb->get_results($s);

				if(count($r) > 0)
				{
					echo '<table width="100%">';

					echo '<tr>';
						echo '<td>'.__('From User','AuctionTheme').'</td>';
						echo '<td>'.__('Subject','AuctionTheme').'</td>';
						echo '<td>'.__('Date','AuctionTheme').'</td>';
						echo '<td>'.__('Options','AuctionTheme').'</td>';
						echo '</tr>';



					foreach($r as $row)
					{
						if($row->rd == 0) $cls = 'bold_stuff';
						else $cls = '';

						$user = get_userdata($row->initiator);

						echo '<tr>';
						echo '<td class="'.$cls.'"><a href="'.AuctionTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></td>';
						echo '<td class="'.$cls.'">'.$row->subject.'</td>';
						echo '<td class="'.$cls.'">'.date('d-M-Y H:i:s',$row->datemade).'</td>';
						echo '<td><a href="'.$privurl_m.'priv_act=read-message&id='.$row->id.'">'.__('Read','AuctionTheme').'</a> |
						<a href="'.$privurl_m.'return=inbox&priv_act=delete-message&id='.$row->id.'">'.__('Delete','AuctionTheme').'</a>
						</td>';
						echo '</tr>';

					}


					echo '</table>';
				} else { echo '<div class="padd10">'; _e('No messages here.','AuctionTheme'); echo '</div>'; }

				?>

                </div>
                </div>



		<!-- page content here -->

        <?php }

		elseif($third_page == 'sent-items') {

			$current_user = wp_get_current_user();
			$myuid = $current_user->ID;

		?>

		<!-- page content here -->


            	<div class="my_box3">

            	<div class="box_title"><?php _e("Private Messages: Sent Items","AuctionTheme"); ?></div>
                <div class="box_content">
                <?php
				global $wpdb;
				$s = "select * from ".$wpdb->prefix."auction_pm where initiator='$myuid' AND show_to_source='1' order by id desc";
				$r = $wpdb->get_results($s);

				if(count($r) > 0)
				{

					echo '<div class="table-responsive">';
					echo '<table class="table">';

					echo '<tr>';
						echo '<td>'.__('To User','AuctionTheme').'</td>';
						echo '<td>'.__('Subject','AuctionTheme').'</td>';
						echo '<td>'.__('Date','AuctionTheme').'</td>';
						echo '<td>'.__('Options','AuctionTheme').'</td>';
						echo '</tr>';



					foreach($r as $row)
					{
						//if($row->rd == 0) $cls = 'bold_stuff';
						//else
						$cls = '';

						$user = get_userdata($row->user);

						echo '<tr>';
						echo '<td class="'.$cls.'"><a href="'.AuctionTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></td>';
						echo '<td class="'.$cls.'">'.$row->subject.'</td>';
						echo '<td class="'.$cls.'">'.date('d-M-Y H:i:s',$row->datemade).'</td>';
						echo '<td><a href="'.$privurl_m.'priv_act=read-message&id='.$row->id.'">'.__('Read','AuctionTheme').'</a> |
						<a href="'.$privurl_m.'return=outbox&priv_act=delete-message&id='.$row->id.'">'.__('Delete','AuctionTheme').'</a></td>';
						echo '</tr>';

					}


					echo '</table></div>';
				}
				else { echo '<div class="padd10">'; _e('No messages here.','AuctionTheme'); echo '</div>'; }
				?>

                </div>
                </div>



		<!-- page content here -->

        <?php }

		elseif($third_page == 'read-message') {

			global $wpdb;
			$current_user = wp_get_current_user();
			$myuid = $current_user->ID;

			$id = $_GET['id'];
			$s = "select * from ".$wpdb->prefix."auction_pm where id='$id' AND (user='$myuid' OR initiator='$myuid')";
			$r = $wpdb->get_results($s);
			$row = $r[0];

			if($myuid == $row->initiator) $owner = true; else $owner = false;

			if($owner == false)
			{
				//echo "asd";
				$wpdb->query("update ".$wpdb->prefix."auction_pm set rd='1' where id='".$row->id."'");
			}

		?>

		<!-- page content here -->


            	<div class="my_box3">


            	<div class="box_title"><?php _e("Read Message: ","AuctionTheme"); echo " ".$row->subject ?></div>
                <div class="box_content">
                <div class="padd10">
                <?php echo $row->content; ?>
      <br/> <br/>

      <?php if($owner == false): ?>
       <a href="<?php echo $privurl_m; ?>priv_act=send&<?php

			echo 'pid='.$row->pid.'&uid='.$row->initiator;

			?>" class="nice_link"><?php _e("Reply",'AuctionTheme'); ?></a> <?php endif; ?>
                </div>
                </div>
                </div>


		<!-- page content here -->

        <?php }

		elseif($third_page == 'delete-message') {

			global $wpdb;
			$current_user = wp_get_current_user();
			$myuid = $current_user->ID;

			$id = $_GET['id'];
			$s = "select * from ".$wpdb->prefix."auction_pm where id='$id' AND (user='$myuid' OR initiator='$myuid')";
			$r = $wpdb->get_results($s);
			$row = $r[0];

			if($myuid == $row->initiator) $owner = true; else $owner = false;


			$owner = false;
			//if(!$owner)
			//$wpdb->query("update ".$wpdb->prefix."auction_pm set rd='1' where id='{$row->id}'");


		?>

		<!-- page content here -->


            	<div class="my_box3">


            	<div class="box_title"><?php _e("Delete Message: ","AuctionTheme"); echo " ".$row->subject ?></div>
                <div class="box_content">
                <div class="padd10">
                <?php echo $row->content; ?>
      <br/> <br/>

      <?php if($owner == false): ?>
       <a href="<?php echo $privurl_m; ?>priv_act=delete-message&<?php

			echo 'id='.$row->id.'&return='.$_GET['return']."&confirm_message_deletion=yes";

			?>" class="green_btn"><?php _e("Confirm Deletion",'AuctionTheme'); ?></a> <?php endif; ?>

                </div>
                </div>
                </div>


		<!-- page content here -->

        <?php }

		 elseif($third_page == 'send') { ?>
        <?php

			$pid = $_GET['pid'];
			$uid = $_GET['uid'];

			$user = get_userdata($uid);

			if(!empty($pid))
			{
				$post = get_post($pid);
				$subject = "RE: ".$post->post_title;
			}



			if(isset($_POST['send']))
			{
				$subject = strip_tags(trim($_POST['subject']));
				$message = strip_tags(trim($_POST['message']));
				$to = $_POST['to'];

				if(!empty($to))
				{
					$uid = auctionTheme_get_userid_from_username($to);
				}

				if($uid != false && $current_user->ID != $uid):

				$current_user = wp_get_current_user();
				$myuid = $current_user->ID;

				global $wpdb;
				$tm = current_time('timestamp',0);
				if(!empty($_POST['tm'])) $tm = $_POST['tm'];

				$s = "select * from ".$wpdb->prefix."auction_pm where datemade='$tm' AND initiator='$myuid' AND user='$uid'";
				$r = $wpdb->get_results($s);

				if(count($r) == 0)
				{

					$s = "insert into ".$wpdb->prefix."auction_pm (subject, content, datemade, pid, initiator, user) values('$subject','$message','$tm','$pid','$myuid','$uid')";
					$wpdb->query($s);

					$user = get_userdata($uid);
				AuctionTheme_send_email_on_priv_mess_received($myuid, $uid);

				}
			//-----------------------



			//-----------------------
				?>

                <div class="my_box3">
            	<div class="padd10">
                 <?php _e('Your message has been sent.','AuctionTheme'); ?>
                </div>
                </div>

                <?php
				elseif($current_user->ID == $uid):
				?>

                    <div class="error">
                 <?php _e('Cant send messsages to yourself','AuctionTheme'); ?>
                </div>


                <?php
				else:
				?>

                <div class="my_box3">
            	<div class="padd10">
                 <?php _e('The message was not sent. The recipient does not exist.','AuctionTheme'); ?>
                </div>
                </div>


				<?php
				endif;
			}
			else
			{


		?>

        <div class="my_box3">


            	<div class="box_title"><?php echo sprintf(__("Send Private Message to: %s","AuctionTheme"), $user->user_login); ?></div>
                <div class="table-responsive">
                <form method="post" enctype="application/x-www-form-urlencoded">
                <input type="hidden" value="<?php echo current_time('timestamp',0) ?>" name="tm" />
                <table class="table">
                <?php if(empty($uid)): ?>
                <tr>
                <td width="140"><?php _e("Send To", "AuctionTheme"); ?>:</td>
                <td><input size="20" name="to" class="do_input" type="text" value="" /></td>
                </tr>
                <?php endif; ?>

                <tr>
                <td width="140"><?php _e("Subject", "AuctionTheme"); ?>:</td>
                <td><input size="50" name="subject"  class="do_input" type="text" value="<?php echo $subject; ?>" /></td>
                </tr>

                <tr>
                <td valign="top"><?php _e("Message", "AuctionTheme"); ?>:</td>
                <td><textarea name="message" rows="6"  class="do_input" cols="50"></textarea></td>
                </tr>



                 <tr>
                <td width="140">&nbsp;</td>
                <td><input class="submit_bottom" name="send" type="submit" value="<?php _e("Send Message",'AuctionTheme'); ?>" /></td>
                </tr>

                </table>
      			</form>

                </div>
                </div>



        <?php } } ?>

    </div>
    <?php

	auctionTheme_get_users_links();
}
}

?>
