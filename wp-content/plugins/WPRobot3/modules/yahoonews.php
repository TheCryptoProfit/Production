<?php

function wpr_yahoonewsrequest($keyword,$num,$start) {	
	libxml_use_internal_errors(true);
	$options = unserialize(get_option("wpr_options"));	
	$apikey = $options['wpr_yan_appkey'];
	if(empty($start)) {$start = 0;}
	$region = $options['wpr_yan_lang'];
	$country = $options['wpr_yan_country2'];
	if(empty($country)) {$country = "en-us";}
	$keyword = str_replace( '"',"",$keyword );	
	$keyword = urlencode($keyword);

	if (!$apikey) {
		$return["error"]["module"] = "Yahoo News";
		$return["error"]["reason"] = "cURL Error";
		$return["error"]["message"] = __("You need to enter your API key on the options page to use the news module.","wprobot");	
		return $return;		
	}	
	
    //$request = "http://search.yahooapis.com/NewsSearchService/V1/newsSearch?appid=".$appid."&query=".$keyword."&language=".$region."&start=".$start."&results=".$num;
	//$request = "http://query.yahooapis.com/v1/public/yql?appid=".$appid."&q=select%20*%20from%20search.news(".$start."%2C".$num.")%20where%20query%3D%22".$keyword."%22%20AND%20lang%3D%22".$region."%22%20AND%20region%3D%22".$country."%22%20AND%20appid%3D%22".$appid."%22&diagnostics=true";
	//$request = "http://query.yahooapis.com/v1/public/yql?appid=".$appid."&q=select%20*%20from%20google.news%20where%20q%3D%22".$keyword."%22%20AND%20lang%3D%22".$region."%22%20AND%20region%3D%22".$country."%22%20AND%20appid%3D%22".$appid."%22&diagnostics=true";
	//$request = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20google.news(".$start."%2C".$num.")%20where%20q%20%3D%20%22".$keyword."%22%20AND%20ned%3D%22".$country."%22&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";

	$request = "https://api.cognitive.microsoft.com/bing/v5.0/news/search?q=".$keyword."&count=".$num."&offset=".$start."&mkt=".$country."&safeSearch=Moderate";

	$headers = array(
		'Ocp-Apim-Subscription-Key: '. $apikey,
	);	
	
	//echo $request."<br>";
	if ( function_exists('curl_init') ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $request);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);		
		$response = curl_exec($ch);
		if (!$response) {
			$return["error"]["module"] = "Yahoo News";
			$return["error"]["reason"] = "cURL Error";
			$return["error"]["message"] = __("cURL Error Number ","wprobot").curl_errno($ch).": ".curl_error($ch);	
			return $return;
		}		
		curl_close($ch);
	} else { 				
		$response = @file_get_contents($request);
		if (!$response) {
			$return["error"]["module"] = "Yahoo News";
			$return["error"]["reason"] = "cURL Error";
			$return["error"]["message"] = __("cURL is not installed on this server!","wprobot");	
			return $return;		
		}
	}
 
	$pxml = json_decode($response);
	
	//echo "<pre>";print_r($pxml);echo "</pre>";
	
	if(!empty($pxml->statusCode) && !empty($pxml->message)) {
		$return["error"]["module"] = "Yahoo News";
		$return["error"]["reason"] = "XML Error";
		$return["error"]["message"] = $pxml->statusCode . " - " . $pxml->message;	
		return $return;			
	}
	
	if ($pxml === False) {
		$pxml = simplexml_load_file($request); 
		if ($pxml === False) {	
			$emessage = __("Failed loading XML, errors returned: ","wprobot");
			foreach(libxml_get_errors() as $error) {
				$emessage .= $error->message . ", ";
			}	
			libxml_clear_errors();
			$return["error"]["module"] = "Yahoo News";
			$return["error"]["reason"] = "XML Error";
			$return["error"]["message"] = $emessage;	
			return $return;		
		} else {
			return $pxml;
		}			
	} else {
		return $pxml;
	}
}

function wpr_yahoonewspost($keyword,$num,$start,$optional="",$comments="") {
	global $wpdb,$wpr_table_templates;

	if($keyword == "") {
		$return["error"]["module"] = "Yahoo News";
		$return["error"]["reason"] = "No keyword";
		$return["error"]["message"] = __("No keyword specified.","wprobot");
		return $return;	
	}
	
	$template = $wpdb->get_var("SELECT content FROM " . $wpr_table_templates . " WHERE type = 'yahoonews'");
	if($template == false || empty($template)) {
		$return["error"]["module"] = "Yahoo News";
		$return["error"]["reason"] = "No template";
		$return["error"]["message"] = __("Module Template does not exist or could not be loaded.","wprobot");
		return $return;	
	}		
	$x = 0;
	$newscontent = array();
	$pxml = wpr_yahoonewsrequest($keyword,$num,$start);
	if(is_array($pxml) && !empty($pxml["error"])) {return $pxml;}
	if ($pxml === False) {
		$newscontent["error"]["module"] = "Yahoonews";
		$newscontent["error"]["reason"] = "Request fail";
		$newscontent["error"]["message"] = __("API request could not be sent.","wprobot");	
		return $newscontent;	
	} else {
		if (isset($pxml->value)) {
			foreach($pxml->value as $news) {		
	// abstract, title, date, clickurl, source, language, ...
				$title = $news->name;					
				$summary = $news->description;				
				$url = $news->url;				
				$source = $news->provider[0]->name;
				$sourceurl = $news->url;
				$language = $news->language;
				$date = $news->datePublished;
				$thumb = $news->image->thumbnail->contentUrl;

				$source = "Read more on <a rel=\"nofollow\" href=\"$url\">$source</a><br/><br/>";
				if($thumb != "") {$thumbnail = '<a href="'.$url.'" rel="nofollow"><img style="width:100px;float:left;margin: 0 20px 10px 0;" src="'.$thumb.'" /></a>';} else {$thumbnail = '';}
				$content = $template;
				$content = wpr_random_tags($content);
				$content = str_replace("{thumbnail}", $thumbnail, $content);
				$content = str_replace("{title}", $title, $content);
				$summary = strip_tags($summary); 
				$summary = str_replace("$", "$ ", $summary); 
				$content = str_replace("{summary}", $summary, $content);
				$content = str_replace("{source}", $source, $content);
				$content = str_replace("{url}", $url, $content);	
				$content = str_replace("{date}", $date, $content);		
				$content = str_replace("{sourceurl}", $sourceurl, $content);	
				$content = str_replace("{language}", $language, $content);		
				$noqkeyword = str_replace('"', '', $keyword);
				$content = str_replace("{keyword}", $noqkeyword, $content);
				$content = str_replace("{Keyword}", ucwords($noqkeyword), $content);									
					if(function_exists("wpr_translate_partial")) {
						$content = wpr_translate_partial($content);
					}
					if(function_exists("wpr_rewrite_partial")) {
						$content = wpr_rewrite_partial($content,$options);
					}	
					
				$newscontent[$x]["unique"] = $url;
				$newscontent[$x]["title"] = $title;
				$newscontent[$x]["content"] = $content;	
				$x++;
			}
			
			if (isset($pxml->description)) {
				$message = __('There was a problem with your API request. This is the error Yahoo returned:',"wprobot").' <b>'.$pxml->description.'</b>';	
				$newscontent["error"]["module"] = "Yahoonews";
				$newscontent["error"]["reason"] = "API fail";
				$newscontent["error"]["message"] = $message;	
				return $newscontent;			
			} elseif(empty($newscontent)) {
				$newscontent["error"]["module"] = "Yahoonews";
				$newscontent["error"]["reason"] = "No content";
				$newscontent["error"]["message"] = __("No (more) Yahoo news items found.","wprobot");	
				return $newscontent;		
			} else {
				return $newscontent;	
			}			
		} else {
			if (isset($pxml->description)) {
				$message = __('There was a problem with your API request. This is the error Yahoo returned:',"wprobot").' <b>'.$pxml->description.'</b>';	
				$newscontent["error"]["module"] = "Yahoonews";
				$newscontent["error"]["reason"] = "API fail";
				$newscontent["error"]["message"] = $message;	
				return $newscontent;			
			} else {
				$newscontent["error"]["module"] = "Yahoonews";
				$newscontent["error"]["reason"] = "No content";
				$newscontent["error"]["message"] = __("No (more) Yahoo news items found.","wprobot");	
				return $newscontent;				
			}			
		}
	}	
}

function wpr_yahoonews_options_default() {
	$options = array(
		"wpr_yan_lang" => "en",
		"wpr_yan_appkey" => "",
		"wpr_yan_country2" => "en-us"
	);
	return $options;
}

function wpr_yahoonews_options($options) {
	if(empty($options['wpr_yan_appkey']) && !empty($options['wpr_yap_appkey'])) {$options['wpr_yan_appkey'] = $options['wpr_yap_appkey'];}
	?>
	<h3 style="text-transform:uppercase;border-bottom: 1px solid #ccc;"><?php _e("Yahoo News Options","wprobot") ?></h3>

	<p><i><strong>Important</strong>: This module does now use the Bing News API because both Google and Yahoo News have suspended their free news APIs. To use the news module you need to <a href="https://www.microsoft.com/cognitive-services/">sign up for a free Bing News API key here</a> and enter it below. </i></p>
	
		<table class="addt" width="100%" cellspacing="2" cellpadding="5" class="editform"> 
			<!--<tr <?php if($options['wpr_yan_appkey'] == "") {echo 'style="background:#F8E0E0;"';} ?> valign="top"> 
				<td width="40%" scope="row"><?php _e("Yahoo Application ID:","wprobot") ?></td> 
				<td><input size="40" name="wpr_yan_appkey" type="text" id="wpr_yan_appkey" value="<?php echo $options['wpr_yan_appkey'] ;?>"/>
				Tooltip<a target="_blank" class="tooltip" href="http://developer.yahoo.com/answers/">?<span><?php _e('This setting is required for the Yahoo Answers module to work!<br/><br/><b>Click to go to the Yahoo API sign up page!</b>',"wprobot") ?></span></a>
			</td> 
			</tr>-->	
			<!--<tr valign="top"> 
				<td width="40%" scope="row"><?php _e("Language:","wprobot") ?></td> 
				<td>
				<select name="wpr_yan_lang" id="wpr_yan_lang">
					<option value="en" <?php if($options['wpr_yan_lang']=="en"){_e('selected');}?>><?php _e("English","wprobot") ?></option>
					<option value="de" <?php if($options['wpr_yan_lang']=="de"){_e('selected');}?>><?php _e("German","wprobot") ?></option>
					<option value="fr" <?php if($options['wpr_yan_lang']=="fr"){_e('selected');}?>><?php _e("French","wprobot") ?></option>
					<option value="it" <?php if($options['wpr_yan_lang']=="it"){_e('selected');}?>><?php _e("Italian","wprobot") ?></option>
					<option value="es" <?php if($options['wpr_yan_lang']=="es"){_e('selected');}?>><?php _e("Spanish","wprobot") ?></option>
					<option value="nl" <?php if($options['wpr_yan_lang']=="nl"){_e('selected');}?>><?php _e("Dutch","wprobot") ?></option>
					<option value="cn" <?php if($options['wpr_yan_lang']=="cn"){_e('selected');}?>><?php _e("Chinese","wprobot") ?></option>
					<option value="tzh" <?php if($options['wpr_yan_lang']=="tzh"){_e('selected');}?>><?php _e("Taiwanese","wprobot") ?></option>	
					<option value="ru" <?php if($options['wpr_yan_lang']=="ru"){_e('selected');}?>><?php _e("Russian","wprobot") ?></option>					
				</select>
			</td> 
			</tr>	-->	
			<tr <?php if($options['wpr_yan_appkey'] == "") {echo 'style="background:#F8E0E0;"';} ?> valign="top"> 
				<td width="40%" scope="row"><?php _e("Bing News API Key:","wprobot") ?></td> 
				<td><input size="40" name="wpr_yan_appkey" type="text" id="wpr_yan_appkey" value="<?php echo $options['wpr_yan_appkey'] ;?>"/>
				<!--Tooltip--><a target="_blank" class="tooltip" href="#">?<span><?php _e('This setting is required for the news module to work!',"wprobot") ?></span></a>
			</td> 
			</tr>			
			<tr valign="top"> 
				<td width="40%" scope="row"><?php _e("Country:","wprobot") ?></td> 
				<td>
				<select name="wpr_yan_country2" id="wpr_yan_country2">
					<option value="en-us" <?php if($options['wpr_yan_country2']=="en-us"){_e('selected');}?>><?php _e("United States","wprobot") ?></option>	
					<option value="de-AT" <?php if($options['wpr_yan_country2']=="de-AT"){_e('selected');}?>><?php _e("Austria","wprobot") ?></option>
					<option value="pt-BR" <?php if($options['wpr_yan_country2']=="pt-BR"){_e('selected');}?>><?php _e("Brazil","wprobot") ?></option>	
					<option value="en-CA" <?php if($options['wpr_yan_country2']=="en-CA"){_e('selected');}?>><?php _e("Canada","wprobot") ?></option>	
					<option value="es-CL" <?php if($options['wpr_yan_country2']=="es-CL"){_e('selected');}?>><?php _e("Chile","wprobot") ?></option>	
					<option value="zh-CN" <?php if($options['wpr_yan_country2']=="zh-CN"){_e('selected');}?>><?php _e("China","wprobot") ?></option>					
					<option value="fr-FR" <?php if($options['wpr_yan_country2']=="fr-FR"){_e('selected');}?>><?php _e("France","wprobot") ?></option>					
					<option value="de-DE" <?php if($options['wpr_yan_country2']=="de-DE"){_e('selected');}?>><?php _e("Germany","wprobot") ?></option>
					<option value="zh-HK" <?php if($options['wpr_yan_country2']=="zh-HK"){_e('selected');}?>><?php _e("Hong Kong","wprobot") ?></option>
					<option value="en-IN" <?php if($options['wpr_yan_country2']=="en-IN"){_e('selected');}?>><?php _e("India","wprobot") ?></option>	
					<option value="en-ID" <?php if($options['wpr_yan_country2']=="en-ID"){_e('selected');}?>><?php _e("Indonesia","wprobot") ?></option>	
					<option value="it-IT" <?php if($options['wpr_yan_country2']=="it-IT"){_e('selected');}?>><?php _e("Italy","wprobot") ?></option>
					<option value="es-MX" <?php if($options['wpr_yan_country2']=="es-MX"){_e('selected');}?>><?php _e("Mexico","wprobot") ?></option>
					<option value="nl-NL" <?php if($options['wpr_yan_country2']=="nl-NL"){_e('selected');}?>><?php _e("Netherlands","wprobot") ?></option>	
					<option value="en-NZ" <?php if($options['wpr_yan_country2']=="en-NZ"){_e('selected');}?>><?php _e("New Zealand","wprobot") ?></option>
					<option value="ru-RU" <?php if($options['wpr_yan_country2']=="ru-RU"){_e('selected');}?>><?php _e("Russia","wprobot") ?></option>
					<option value="es-ES" <?php if($options['wpr_yan_country2']=="es-ES"){_e('selected');}?>><?php _e("Spain","wprobot") ?></option>	
					<option value="en-GB" <?php if($options['wpr_yan_country2']=="en-GB"){_e('selected');}?>><?php _e("United Kingdom","wprobot") ?></option>
				</select>			
			</td> 
			</tr>	
		</table>		
	<?php
}

?>