<?php

add_action('widgets_init', 'register_best_rated_users_widget');
function register_best_rated_users_widget() {
	register_widget('AuctionTheme_best_rated_users');
}

class AuctionTheme_best_rated_users extends WP_Widget {

	function AuctionTheme_best_rated_users() {
		$widget_ops = array( 'classname' => 'best-rated-users', 'description' => 'Show the best rated users.' );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'best-rated-users' );
		parent::__construct( 'best-rated-users', 'AuctionTheme - Best Rated Users', $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);

		echo $before_widget;

		if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		echo '<div class="my-only-widget-content">';
		$user_limit = $instance['user_limit'];
		//$widget_id = $args['widget_id'];

		if(empty($user_limit)) $user_limit = 5;

		global $wpdb;

		$querystr = "SELECT users.user_email email, users.user_registered dt, users.user_login username, ratings.touser uid,
					AVG(ratings.grade) rate FROM ".$wpdb->prefix."auction_ratings ratings, ".$wpdb->prefix."users users where users.ID=ratings.touser AND ratings.awarded='1'
					group by ratings.touser order by rate DESC LIMIT $user_limit";

				$r = $wpdb->get_results($querystr);

				if(count($r) == 0) echo __('No rated users yet.','AuctionTheme');
				else
				{
					echo '<table width="100%">';
					foreach($r as $row) // = mysql_fetch_object($r))
					{
						$hash = md5( strtolower( trim($row->email)));
						$dt = date("jS \of F, Y",strtotime($row->dt));

						echo '<tr>';

						echo '<td width="20%"><a href="'.get_bloginfo('siteurl').'?a_action=user_profile&post_author='.$row->uid.'">';
						echo '<img alt="avatar" width="50" height="50" border="0" src="'.auctionTheme_get_avatar($row->uid,50,50).'" />';

						echo '</a></td>';
						echo '<td><b><a href="'.AuctionTheme_get_user_profile_link($row->uid).'">'.$row->username.'</a></b><br/>
						Joined on: '.$dt.'
						<br/>'.AuctionTheme_get_auction_stars(round($row->rate/2)).'
						</td>';

						echo '</tr>';
					}
					echo '</table>';
				}


		echo '</div>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {

		return $new_instance;
	}

	function form($instance) { ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','AuctionTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
			value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:95%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('user_limit'); ?>"><?php _e('Users Limit','AuctionTheme'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('user_limit'); ?>" name="<?php echo $this->get_field_name('user_limit'); ?>"
			value="<?php echo esc_attr( $instance['user_limit'] ); ?>" style="width:20%;" />
		</p>

	<?php
	}
}



?>
