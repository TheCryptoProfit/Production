<?php
/*
	Plugin Name: RankWyzWP
	Version: 1.1
*/

defined('ABSPATH') or die("No script kiddies please!");

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'RankWyzWP_plugin_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'RankWyzWP_plugin_remove' );

function RankWyzWP_plugin_install() {
    delete_option("RankWyzWP_settings");
}

function RankWyzWP_plugin_remove() {
	delete_option("RankWyzWP_settings");
	wp_clear_scheduled_hook( 'RankWyzWP_cron_event_hook' );
}
global $defaults;

$defaults = array(
	'apiKey' => array("value" => ""),
	'backlinkSettings' => array(
		'counter' => array("value" => "3"),
		'newPosts' => false,
		'randomPosts' => false,
		'randomPostCounter' => array("value" => "1"),
		'randomPostDateFormat' => array("value" => "daily")
	),
	'networkSettings' => array(
		'sharedNetworksEnabled' => false,
		'sharedNetworksList' => array(
			"selected" => "",
			"list" => array()
		),
		'anchors' => array(),
	),
	'myNetworks' => array(
		'submission1' => array(
			"enabled" => false,
			"value" => ""
		),
		'submission2' => array(
			"enabled" => false,
			"value" => ""
		),
		'submission3' => array(
			"enabled" => false,
			"value" => ""
		)
	),
	'trigger_history' => array()
);

global $RankWyzWP_settings;

$RankWyzWP_settings = parse_args_recursively( get_option("RankWyzWP_settings"), $defaults );

$RankWyzWP_settings["backlinkSettings"] = wp_parse_args( $RankWyzWP_settings["backlinkSettings"], $defaults['backlinkSettings'] );
$RankWyzWP_settings["networkSettings"] =  wp_parse_args( $RankWyzWP_settings["networkSettings"], $defaults['networkSettings'] );
$RankWyzWP_settings["backlinkSettings"] = wp_parse_args( $RankWyzWP_settings['backlinkSettings'], $defaults['backlinkSettings'] );

add_action( 'publish_post', 'RankWyzWP_post_published' );
add_filter( 'cron_schedules', 'RankWyzWP_cron' );


add_action( 'RankWyzWP_cron_event_hook', 'RankWyzWP_cron_event_hook');
add_action( 'wp_ajax_updateRankwyzSettings', 'rankWyzSettings_callback' );


function parse_args_recursively( $array, $defaults)
{
	foreach ($defaults as $defaultKey => $defaultValue) {
		if (!isset($array[$defaultKey]) || gettype($array[$defaultKey]) != gettype($defaultValue))
		{
			$array[$defaultKey] = $defaultValue;
			continue;
		}
		
		
	    if (is_array($defaultValue))
	    {
	    	parse_args_recursively($array[$defaultKey], $defaultValue);
	    }	
	}		
	
	
	return $array;
}

function rankWyzSettings_callback() {
	
	global $RankWyzWP_settings, $defaults;

	$model = parse_args_recursively($_POST["model"], $defaults );

/*	if (!$model["apiKey"]["value"])
	{
		$model["apiKey"]["error"] = "Please, add API Key";
	}*/
	
	$jsonResult = rankWyzSettings_getSharedNetworks($model["apiKey"]["value"]);
	
	$result = json_decode($jsonResult);
	if ($result->status && $result->status == 403)
	{
		$model["networkSettings"]["sharedNetworksList"]["list"] = array();
			
	/*	$model["apiKey"]["error"] = "Incorrect API key";
		$model["apiKey"]["value"] = "";
		
		echo json_encode($model);die();*/
	}
	else {
		$model["networkSettings"]["sharedNetworksList"]["list"] = $result;
	}
		
	$isReschedulingRequired = false;
	if ($model["backlinkSettings"]["randomPosts"] && $RankWyzWP_settings["backlinkSettings"]["randomPostDateFormat"]["value"] !== $model["backlinkSettings"]["randomPostDateFormat"]["value"]
		|| $RankWyzWP_settings["backlinkSettings"]["randomPostCounter"]["value"] !== $model["backlinkSettings"]["randomPostCounter"]["value"])
	{
		$isReschedulingRequired = true;
	}
		
		
	$RankWyzWP_settings = $model;
	update_option("RankWyzWP_settings", $model);


	if ($isReschedulingRequired)
		rankWyzSettings_recheduleCron($model);
		
	echo json_encode($model);die();
	wp_die(); // this is required to terminate immediately and return a proper response
}

function rankWyzSettings_recheduleCron($model)
{
	wp_clear_scheduled_hook( 'RankWyzWP_cron_event_hook' );
	wp_schedule_event( time() + RankWyzWP_cron_getInterval($model), 'rankwyz_cron', 'RankWyzWP_cron_event_hook' );
}

function rankWyzSettings_getSharedNetworks($apiKey) {
	$url = "http://app.rankwyz.com/rwbo/api/1.0/sharedsubmissions?apikey=" . $apiKey;

	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL,$url); // set url to post to  
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable  
	curl_setopt($ch, CURLOPT_TIMEOUT, 15); // times out after 4s  
	$result = curl_exec($ch); // run the whole process  
	curl_close($ch);  
	
	return $result;
}

function RankWyzWP_cron_getInterval($model)
{
 	$customTime = DAY_IN_SECONDS; //daily
 	
 	if ($model["backlinkSettings"]["randomPostDateFormat"]["value"] == 'hourly')
 		$customTime = HOUR_IN_SECONDS;
 		
 	$occurance = $model["backlinkSettings"]["randomPostCounter"]["value"];
 	
 	if (!is_numeric($occurance))
 		$occurance = 0;
 		
 	$totalTime = $customTime * $occurance;
 	
 	return $totalTime;
}

 
function RankWyzWP_cron( $schedules ) {
	global $RankWyzWP_settings;
	
 	$schedules['rankwyz_cron'] = array(
 		'interval' => RankWyzWP_cron_getInterval($RankWyzWP_settings),
 		'display' => __( 'RankWyz custom cron' )
 	);
 	
 	return $schedules;
}

function RankWyzWP_post_published( $post_id ) {
	global $RankWyzWP_settings;

	$post = get_post($post_id);

	if ($RankWyzWP_settings["backlinkSettings"]['newPosts'] != "true")
		return;

	if ( $post->status == "auto-draft" ) return $post_id;
	if ( $post->post_date != $post->post_modified) return $post_id;
	if ( !$RankWyzWP_settings['apiKey']["value"]) return $post_id;

	RankWyzWP_trigger_event($post);
}

function RankWyzWP_findSlug($selectedID)
{
	global $RankWyzWP_settings;
	foreach ($RankWyzWP_settings["networkSettings"]["sharedNetworksList"]["list"] as $items)
	{
		foreach ($items as $item)
		{
			if ($item->id == $selectedID)
				return $item->slug;
		}
	}
}


function RankWyzWP_trigger_event($post)
{
	global $RankWyzWP_settings;

	$post_url = get_permalink($post->ID);
	
	for ($i = 1; $i <=3 ; $i++)
	{
		$submission = $RankWyzWP_settings["myNetworks"]["submission" . $i];

		if ($submission["enabled"] == "true" && $submission["value"])
		{
			$url = "http://app.rankwyz.com/rwbo/api/1.0/links/" . $submission["value"];

			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_URL,$url); // set url to post to  
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable  
			curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s  
			curl_setopt($ch, CURLOPT_POST, 1); // set POST method  
			curl_setopt($ch, CURLOPT_POSTFIELDS, "urls=".$post_url."&apikey=".$RankWyzWP_settings['apiKey']["value"]); // add POST fields 
			$result = curl_exec($ch); // run the whole process  
			curl_close($ch);  
		}
	}
		
	if ($RankWyzWP_settings["networkSettings"]["sharedNetworksEnabled"])
	{
		$url = "http://app.rankwyz.com/rwbo/api/1.0/links/backlinking";

		$randomAnchor = $RankWyzWP_settings["networkSettings"]["anchors"][array_rand($RankWyzWP_settings["networkSettings"]["anchors"])];
		$number = $RankWyzWP_settings["backlinkSettings"]["counter"]["value"];
		$slug = RankWyzWP_findSlug($RankWyzWP_settings["networkSettings"]["sharedNetworksList"]["selected"]);
		
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL,$url); // set url to post to  
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-wyz: '.$RankWyzWP_settings['apiKey']["value"]));
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable  
		curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s  
		curl_setopt($ch, CURLOPT_POST, 1); // set POST method  
		curl_setopt($ch, CURLOPT_POSTFIELDS, "url=".$post_url."&niche=".$slug."&title=".$post->post_title."&anchor=".$randomAnchor."&number=".$number); // add POST fields 
		$result = curl_exec($ch); // run the whole process  
		curl_close($ch);  
		
	}
}

add_action('admin_menu', 'RankWyzWP_plugin_menu');
add_action('admin_notices', 'RankWyzWP_admin_notices');

function RankWyzWP_admin_notices() {
	global $RankWyzWP_settings;

//  	if (!$RankWyzWP_settings['api_key'] || !count(array_filter($RankWyzWP_settings['submission']))) {
//    	echo "<div class='updated'><p>RankWyzWP: <a href='options-general.php?page=RankWyzWP_options'>Please add api_key and submission</a></p></div>";
//    }
}

function RankWyzWP_plugin_menu() {
	wp_enqueue_style('admin_css_bootstrap_wrapper', plugins_url('/RankWyzWP/libs/bootstrap/css/bootstrap-wrapper.css'), false, '1.0.0', 'all');
	wp_enqueue_script('admin_css_bootstrap_js', plugins_url('/RankWyzWP/libs/bootstrap/js/bootstrap.min.js'), array( 'jquery' ) );
	wp_enqueue_style('admin_rankwyzwp', plugins_url('/RankWyzWP/views/css/rankwyzwp.css'), false, '1.0.0', 'all');
	add_options_page( "RankWyzWP", "RankWyzWP", 'manage_options', 'RankWyzWP_options', "RankWyzWP_settings_page");
}

function RankWyzWP_cron_event_hook() {
	global $RankWyzWP_settings;

	if ($RankWyzWP_settings["backlinkSettings"]['randomPosts'] != "true")
		return;
		
	if (!$RankWyzWP_settings['apiKey']["value"])
		return;
		
	$args = array( 'posts_per_page' => 1, 'orderby' => 'rand' );
	$posts_array = get_posts( $args );

	if (count($posts_array))
	{
		$post = $posts_array[0];
		RankWyzWP_trigger_event($post);
	}
}

function RankWyzWP_settings_page() {
	global $RankWyzWP_settings;
	
	wp_enqueue_style('admin_css_bootstrap_wrapper', plugins_url('/RankWyzWP/libs/bootstrap/css/bootstrap-wrapper.css'), false, '1.0.0', 'all');
	//wp_enqueue_style('admin_css_bootstrap', plugins_url('/RankWyzWP/libs/bootstrap/css/bootstrap.min.css'), false, '1.0.0', 'all');
	include_once(__DIR__ . "/views/settings.php");
	$view = new SettingsView();
	$view->draw($RankWyzWP_settings);
	die();
}

function RankWyzWP_settings_page2() {

	global $RankWyzWP_settings;

	if (isset($_POST["update_settings"])) {

		wp_clear_scheduled_hook( 'RankWyzWP_cron_event_hook' );

		$error = false;
		$error_text = "";

	    $RankWyzWP_settings['api_key'] = $_POST["api_key"];
	    $RankWyzWP_settings['submission'] = $_POST["submission"];

	    $RankWyzWP_settings['new_posts'] = isset($_POST["require_new_posts"]);

	    $history = array("enabled" => false);
	    if (isset($_POST["require_history_posts"]))
	    {
	    	if (!isset($_POST["history_posts_period"]) || !is_numeric($_POST["history_posts_period"]) || intval($_POST["history_posts_period"]) != $_POST["history_posts_period"] || $_POST["history_posts_period"] < 1)
	    	{
	    		$error = true;
	    		$error_text = "Incorrect post period";
	    	}

	    	if (!isset($_POST["history_posts_period_type"]) || !is_numeric($_POST["history_posts_period_type"]) || $_POST["history_posts_period_type"] < 0 || $_POST["history_posts_period_type"] > 2)
	    	{
	    		$error = true; 
	    		$error_text = "Incorrect post type";
	    	}

	    	$history["enabled"] = true;
	    	$history["period"] = $_POST["history_posts_period"];
	    	$history["period_type"] = $_POST["history_posts_period_type"];

	    	$wp_period = 'hourly';
	    	switch ($history["period_type"]) {
	    		case 0:
	    			$wp_period = 'hourly';
	    		break;
	    		case 1:
	    			$wp_period = 'daily';
	    		break;
	    		case 2:
	    			$wp_period = 'weekly';
	    		break;
	    		default:
	    			$wp_period = 'hourly';
	    		break;
	    	}

	    	wp_schedule_event( time(), $wp_period, 'RankWyzWP_cron_event_hook' );
	    }

	    $RankWyzWP_settings['historical_posts'] = $history;

		if (!$error)
		{
		    update_option("RankWyzWP_settings", $RankWyzWP_settings);
		    ?>
			    <div id="message" class="updated">Settings saved</div>
			<?php
		}
		else
		{
			?>
			<div id="message" class="error"><?php echo $error_text;?></div>
			<?php
		}

	}

	$new_posts_checkbox = $RankWyzWP_settings['new_posts'] ? "checked=\"checked\"" : "";
	$historical_posts_checkbox = $RankWyzWP_settings['historical_posts']["enabled"] ? "checked=\"checked\"" : "";
	$historical_period_type = $RankWyzWP_settings['historical_posts']["period_type"];

	?>

		<script type="text/javascript">
			(function ($) {
			    $( document ).ready(function() {
			        if($("#require_history_posts").is(':checked'))
					    $(".period_settings").prop('disabled', false);  // checked
					else
					    $(".period_settings").prop('disabled', true);  // unchecked

					$('#require_history_posts').click(function () {
					    $(".period_settings").prop('disabled',!this.checked);
					});
			    });
			})(jQuery);

    	</script>

		<div class="wrap">
		<h2>RankWyzWP</h2>

		<form method="post" action="">



		<table id="rankopt_table" class="form-table">

			<tr valign="top">
			<th scope="row">API Key</th>
			<td><input type="text" name="api_key" value="<?php echo $RankWyzWP_settings['api_key']; ?>" /></td>
			</tr>
			 
			<tr valign="top">
			<th scope="row">Submission #1</th>
			<td><input type="text" name="submission[0]" value="<?php echo $RankWyzWP_settings['submission'][0]; ?>" />
			</td>
			</tr>

			<tr valign="top">
			<th scope="row">Submission #2</th>
			<td><input type="text" name="submission[1]" value="<?php echo $RankWyzWP_settings['submission'][1]; ?>" />
			</td>
			</tr>

			<tr valign="top">
			<th scope="row">Submission #3</th>
			<td><input type="text" name="submission[2]" value="<?php echo $RankWyzWP_settings['submission'][2]; ?>" />
			</td>
			</tr>

			<tr valign="top">
			<th scope="row">Settings</th>
				<td>
					<fieldset>
						<label for="require_new_posts"><input type="checkbox" name="require_new_posts" id="require_new_posts" value="1" <?php echo $new_posts_checkbox; ?>>Process new posts</label>
						<br/>
						<label for="require_history_posts"><input type="checkbox" name="require_history_posts" id="require_history_posts" value="1" <?php echo $historical_posts_checkbox; ?>>Process random posts with period: </label>
						<input type="number" name="history_posts_period" id="history_posts_period" value="<?php echo $RankWyzWP_settings['historical_posts']["period"]; ?>" class="period_settings" style="width:50px;">
						<select name="history_posts_period_type" class="period_settings">
							<option value="0" <?php echo $historical_period_type == 0 ? "selected" : ""?>>Hours</option>
							<option value="1" <?php echo $historical_period_type == 1 ? "selected" : ""?>>Days</option>
							<option value="2" <?php echo $historical_period_type == 2 ? "selected" : ""?>>Weeks</option>
						</select>
					</fieldset>
				</td>
			</tr>

		</table>

		<input type="hidden" name="update_settings" value="update" />

		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>

		</form>
		</div>

	<?php
}