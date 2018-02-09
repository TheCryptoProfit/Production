<?php

/**
 * Translate
 * @author Nikita Gusakov <dev@nkt.me>
 * @link   http://api.yandex.com/translate/doc/dg/reference/translate.xml
 */
class WPR_Translator
{
    const BASE_URL = 'https://translate.yandex.net/api/v1.5/tr.json/';
    const MESSAGE_UNKNOWN_ERROR = 'Unknown error';
    const MESSAGE_JSON_ERROR = 'JSON parse error';
    const MESSAGE_INVALID_RESPONSE = 'Invalid response from service';

    /**
     * @var string
     */
    protected $key;

    /**
     * @var resource
     */
    protected $handler;

    /**
     * @link http://api.yandex.com/key/keyslist.xml Get a free API key on this page.
     *
     * @param string $key The API key
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->handler = curl_init();
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Returns a list of translation directions supported by the service.
     * @link http://api.yandex.com/translate/doc/dg/reference/getLangs.xml
     *
     * @param string $culture If set, the service's response will contain a list of language codes
     *
     * @return array
     */
    public function getSupportedLanguages($culture = null)
    {
        return $this->execute('getLangs', array(
            'ui' => $culture
        ));
    }

    /**
     * Detects the language of the specified text.
     * @link http://api.yandex.com/translate/doc/dg/reference/detect.xml
     *
     * @param string $text The text to detect the language for.
     *
     * @return string
     */
    public function detect($text)
    {
        $data = $this->execute('detect', array(
            'text' => $text
        ));

        return $data['lang'];
    }

    /**
     * Translates the text.
     * @link http://api.yandex.com/translate/doc/dg/reference/translate.xml
     *
     * @param string|array $text     The text to be translated.
     * @param string       $language Translation direction (for example, "en-ru" or "ru").
     * @param bool         $html     Text format, if true - html, otherwise plain.
     * @param int          $options  Translation options.
     *
     * @return array
     */
    public function translate($text, $language, $html = false, $options = 0)
    {
        $data = $this->execute('translate', array(
            'text'    => $text,
            'lang'    => $language,
            'format'  => $html ? 'html' : 'plain',
            'options' => $options
        ));

        // @TODO: handle source language detecting
        return new WPR_Translation($text, $data['text'], $data['lang']);
    }

    /**
     * @param string $uri
     * @param array  $parameters
     *
     * @throws Exception
     * @return array
     */
    protected function execute($uri, array $parameters)
    {
        $parameters['key'] = $this->key;
        curl_setopt($this->handler, CURLOPT_URL, static::BASE_URL . $uri);
        curl_setopt($this->handler, CURLOPT_POST, true);
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($parameters));
        
        $remoteResult = curl_exec($this->handler);
        if ($remoteResult === false) {
            throw new Exception(curl_error($this->handler), curl_errno($this->handler));
        }

        $result = json_decode($remoteResult, true);
        if (!$result) {
            $errorMessage = self::MESSAGE_UNKNOWN_ERROR;
            if (version_compare(PHP_VERSION, '5.3', '>=')) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    if (version_compare(PHP_VERSION, '5.5', '>=')) {
                        $errorMessage = json_last_error_msg();
                    } else {
                        $errorMessage = self::MESSAGE_JSON_ERROR;
                    }
                }
            }
            throw new Exception(sprintf('%s: %s', self::MESSAGE_INVALID_RESPONSE, $errorMessage));
        } elseif (isset($result['code']) && $result['code'] > 200) {
            throw new Exception($result['message'], $result['code']);
        }

        return $result;
    }
}

class WPR_Translation
{
    /**
     * @var string|array
     */
    protected $source;
    /**
     * @var string|array
     */
    protected $result;

    /**
     * @var array
     */
    protected $language;

    /**
     * @param string|array $source   The source text
     * @param string|array $result   The translation result
     * @param string       $language Translation language
     */
    public function __construct($source, $result, $language)
    {
        $this->source = $source;
        $this->result = $result;
        $this->language = explode('-', $language);
    }

    /**
     * @return string|array The source text
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return array|string The result text
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string The source language.
     */
    public function getSourceLanguage()
    {
        return $this->language[0];
    }

    /**
     * @return string The translation language.
     */
    public function getResultLanguage()
    {
        return $this->language[1];
    }

    /**
     * @return string The translation text.
     */
    public function __toString()
    {
        if (is_array($this->result)) {
            return join(' ', $this->result);
        }

        return (string) $this->result;
    }
}

function wpr_yandex_trans($apikey, $text, $l1, $l2) {
	
	try {
		$translator = new WPR_Translator($apikey);
		$translation = $translator->translate($text, $l1.'-'.$l2);

		$trans = $translation->getResult(); // Привет мир
		
		if(empty($trans[0])) {
			$return["error"]["module"] = "Translation";
			$return["error"]["reason"] = "cURL Error";
			$return["error"]["message"] = __("Translation Error.","wprobot");
			return $return;			
		}

		return $trans[0];
		
	} catch (Exception $e) {
		$msg = $e->getMessage();
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "cURL Error";
		$return["error"]["message"] = __("Translation Error: ","wprobot").$msg;
		return $return;		
	}
}
/*
function wpr_yandex_trans($apikey, $text, $l1, $l2) {

	//$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key='.$apikey.'&translation direction='.$l1.'-'.$l2.'&text to translate='.$text.'&format=html';
	$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key='.$apikey.'&translation direction='.$l1.'-'.$l2.'&text to translate=hello&format=html';
 echo $url;
	if ( function_exists('curl_init') ) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$buffer = curl_exec ($ch);
		echo $buffer."<br>";		
		if (!$buffer) {
			$return["error"]["module"] = "Translation";
			$return["error"]["reason"] = "cURL Error";
			$return["error"]["message"] = __("cURL Error ","wprobot").curl_errno($ch).": ".curl_error($ch);
			if(isset($proxy)) {$return["error"]["message"] .= " (Proxy $proxy)";}
			return $return;
		}				
		curl_close ($ch);
		
		$pxml = json_decode($buffer, true);
		
		if(!empty($pxml["message"])) {
			$return["error"]["module"] = "Translation";
			$return["error"]["reason"] = "Error";
			$return["error"]["message"] = $pxml["message"];	
			return $return;				
		}
		
	print_r($pxml);	
			

	} else { 				
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "cURL Error";
		$return["error"]["message"] = __("cURL is not installed on this server!","wprobot");	
		return $return;		
	}
}
function wpr_gettr( $url, $post, $referer = "") {

	$options = unserialize(get_option("wpr_options"));	
	$proxy == "";
	if($options["wpr_trans_use_proxies"] == "yes") {
		$proxies = str_replace("\r", "", $options["wpr_trans_proxies"]);
		$proxies = explode("\n", $proxies);  
		$rand = array_rand($proxies);	
		list($proxy,$proxytype,$proxyuser)=explode("|",$proxies[$rand]);
	}
	
    echo $url."<br>";

	$blist[] = "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)";
	$blist[] = "Mozilla/5.0 (compatible; Konqueror/3.92; Microsoft Windows) KHTML/3.92.0 (like Gecko)";
	$blist[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Media Center PC 5.0; .NET CLR 1.1.4322; Windows-Media-Player/10.00.00.3990; InfoPath.2";
	$blist[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; InfoPath.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; Dealio Deskball 3.0)";
	$blist[] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; NeosBrowser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
	$br = $blist[array_rand($blist)];
	if ( function_exists('curl_init') ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $br);
			if($proxy != "") {
				curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1); 
				curl_setopt($ch, CURLOPT_PROXY, $proxy);
				if($proxyuser) {curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyuser);}
				if($proxytype == "socks") {curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);}
			}			
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$buffer = curl_exec ($ch);
		//echo $buffer."<br>";		
		if (!$buffer) {
			// remove dead

			$return["error"]["module"] = "Translation";
			$return["error"]["reason"] = "cURL Error";
			$return["error"]["message"] = __("cURL Error ","wprobot").curl_errno($ch).": ".curl_error($ch);
			if(isset($proxy)) {$return["error"]["message"] .= " (Proxy $proxy)";}
			return $return;
		}				
		curl_close ($ch);
	
		if(strpos($buffer, "Error 403") !== false) {
			$return["error"]["module"] = "Translation";
			$return["error"]["reason"] = "Error";
			$return["error"]["message"] = __("Translation Error 403","wprobot");	
			return $return;				
		}
	
		if(strpos($buffer, "Error 400") !== false) {
			$return["error"]["module"] = "Translation";
			$return["error"]["reason"] = "Error";
			$return["error"]["message"] = __("Translation Error 400","wprobot");	
			return $return;				
		}
		
		return $buffer;
	} else { 				
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "cURL Error";
		$return["error"]["message"] = __("cURL is not installed on this server!","wprobot");	
		return $return;		
	}
}
*/

function wpr_gtrns($text, $from, $to) {

	$options = unserialize(get_option("wpr_options"));	
	$apikey = $options['wpr_trans_api'];
	
	if($from == "zh-CN") {$from = "zh";}
	if($to == "zh-CN") {$to = "zh";}
	
	$string = wpr_yandex_trans($apikey, $text, $from, $to);

	return $string;
	
	/*
	$url = "http://translate.google.com/translate_t";
	$ref = "http://translate.google.com/translate_t";
	
	$text=trim($text);	
	
	$url = "https://translate.google.com/#".$from."/".$to;//"/".$text;
	$ref = "https://translate.google.com/#".$from."/".$to;//."/".$text;	
	
	//$url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl='.$from.'&tl='.$to.'&dt=t&q='.urlencode(strip_tags($text));
	
	$url = 'https://translate.yandex.com/?text='.urlencode(strip_tags($text)).'&lang='.$from.'-'.$to.'';
	
	$text=urlencode($text);	
	if($to=="tw"||$to=="cn") {
		$to="zh-".strtoupper($to);
	}
	if($to=="nor") {$to=="no";}
	$postdata="hl=en&ie=UTF8&text=".$text."&langpair=".$from."%7C".$to;
	$page = wpr_gettr($url, $postdata, $ref);
	if(!empty($page["error"]["reason"])) {
		return $page;
	}
	
	

		$dom = new DOMDocument();
		@$dom->loadHTML($page);
		$xpath = new DOMXPath($dom);

		//$paras = $xpath->query("//span[@id='result_box']"); // additional span? //span[@id='result_box']/span
		$paras = $xpath->query("//div[@id='translation']"); // additional span? //span[@id='result_box']/span
		
		$para = $paras->item(0);
			
		$string = $dom->saveXml($para);	
	echo "LOLLLLL $string";				
		//$string = utf8_decode($string);
	if ($string!="") {
		return stripslashes(strip_tags($string));
	} else {
		return "";
	}*/
}

function wpr_trans_format($transtext) {
		$transtext =str_replace('&lt; / ','</',$transtext);
		$transtext =str_replace('&lt;/ ','</',$transtext);
		$transtext =str_replace('&lt; /','</',$transtext);
		$transtext =str_replace('&lt; ','<',$transtext);
		$transtext =str_replace('&lt;','<',$transtext);
		$transtext =str_replace('&gt;','>',$transtext);
		$transtext =str_replace('num = "','num="',$transtext);
		$transtext =str_replace('kw = "','kw="',$transtext);
		$transtext =str_replace('ebcat = "','ebcat="',$transtext);
		$transtext =str_replace('[Wprebay','[wprebay',$transtext);
		$transtext =str_replace('[/ ','[/',$transtext);
		$transtext =str_replace('Has_rating','has_rating',$transtext);
		//echo $transtext . "<br/>--------------------------------------------<br/>";
		//$transtext = html_entity_decode($transtext);
		//echo $transtext . "<br/>--------------------------------------------<br/>";		
		//$transtext = stripslashes($transtext);

		return $transtext;

}

function wpr_translate($text,$t1="",$t2="",$t3="",$t4="") {

	if(empty($text)) {
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "Translation Failed";
		$return["error"]["message"] = __("Empty text given.","wprobot");	
		return $return;		
	}
	
	if(empty($t2)) {
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "Translation Failed";
		$return["error"]["message"] = __("No target language specified.","wprobot");	
		return $return;		
	}		
	
	if($t1 == $t2) {
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "Translation Failed";
		$return["error"]["message"] = __("Same languages specified.","wprobot");	
		return $return;		
	}		
	
	// SAVE URLS
	//echo "<br/>------------------SAVE-----------------<br/>";
	//preg_match_all('#href\s*=\s*"(.*)"#siU', $text, $matches, PREG_SET_ORDER);
	//print_r($matches);
	// SAVE SRC
	//preg_match_all('#src\s*=\s*"(.*)"#siU', $text, $matches2, PREG_SET_ORDER);

	if ($t1!='no' && $t2!='no') {
		$transtext = wpr_gtrns($text, $t1, $t2);
		if(!empty($transtext["error"]["reason"])) {
			return $transtext;
		}
		
		//$transtext = wpr_trans_format($transtext);
	}
	if ($t1!='no'  && $t2!='no'  && $t3!='no') {
		$transtext = wpr_gtrns($transtext, $t2, $t3);
		if(!empty($transtext["error"]["reason"])) {
			return $transtext;
		}			
		//$transtext = wpr_trans_format($transtext);
	}
	if ($t1!='no'  && $t2!='no'  && $t3!='no'  && $t4!='no') {
		$transtext = wpr_gtrns($transtext, $t3, $t4);
		if(!empty($transtext["error"]["reason"])) {
			return $transtext;
		}			
		//$transtext = wpr_trans_format($transtext);
	}	

	/*$pos = strpos($transtext, "302 Moved");
	$pos2 = strpos($transtext, "301 Moved");	
	$pos3 = strpos($transtext, "404 Not Found");							
	if ($pos === false && $pos2 === false && $pos3 === false) {
		$moved = 2;
	} else {	
		$moved = 1;
	}*/				 

	if ( !empty($transtext) && $transtext != ' ') {
		/*$transtext = html_entity_decode($transtext);	
		// REPLACE URLS
		//echo "<br/>------------------REPLACE-----------------<br/>";\s*=\s*
		//preg_match_all('#href = "(.*)"#siU', $transtext, $rmatches, PREG_SET_ORDER);
		preg_match_all('#href\s*=\s*"(.*)"#siU', $transtext, $rmatches, PREG_SET_ORDER);
		if ($rmatches) {
			$i=0;
			foreach($rmatches as $rmatch) {	// HREF = $match[1]	
				//echo "<br/>ORIGINAL: ".$matches[$i][1];
				//echo "<br/>REPLACEMENT: ".$rmatch[1];
				$transtext = str_replace($rmatch[1], $matches[$i][1], $transtext);
				$i++;
			}
		}		//print_r($rmatches);
		// REPLACE SRC
		//preg_match_all('#src ="(.*)"#siU', $transtext, $rmatches2, PREG_SET_ORDER);
		preg_match_all('#src\s*=\s*"(.*)"#siU', $transtext, $rmatches2, PREG_SET_ORDER);
		if ($rmatches2) {
			$i=0;
			foreach($rmatches2 as $rmatch2) {	// HREF = $match[1]	
				$transtext = str_replace($rmatch2[1], $matches2[$i][1], $transtext);
				$i++;
			}
		}*/

		return $transtext;
	} else {
		$return["error"]["module"] = "Translation";
		$return["error"]["reason"] = "Translation Failed";
		$return["error"]["message"] = __("The post could not be translated.","wprobot");	
		return $return;		
	}
}

function wpr_translate_partial($content) {

	$checkcontent = $content;
	
	preg_match_all('#\[translate(.*)\](.*)\[/translate\]#smiU', $checkcontent, $matches, PREG_SET_ORDER);
	if ($matches) {
		foreach($matches as $match) {
			$match[1] = substr($match[1], 1);
			$langs = explode("|", $match[1]);
			if(!empty($langs)) {

				if(empty($langs[0])) {$langs[0] = "no";}
				if(empty($langs[1])) {$langs[1] = "no";}
				if(empty($langs[2])) {$langs[2] = "no";}
				if(empty($langs[3])) {$langs[3] = "no";}
				$transcontent = wpr_translate($match[2],$langs[0],$langs[1],$langs[2],$langs[3]);

			}
			
			if(!empty($transcontent) && !is_array($transcontent)) {
				$content = str_replace($match[0], $transcontent, $content);	
				return $content;
			} else {
				$content = str_replace($match[0], "", $content);	
				return $content;
			}
		}
	} else {
		return $content;	
	}	
	
	if(!empty($transcontent) && !is_array($transcontent)) {
		return $transcontent;
	} else {
		return $content;
	}

}

function wpr_translation_options_default() {
	$options = array(
		//"wpr_trans_use_proxies" => "no",
		//"wpr_trans_proxies" => "",
		"wpr_trans_api" => "",		
		"wpr_trans_fail" => "post",
		"wpr_trans_delete_proxies" => "yes",
		"wpr_trans_titles" => "yes"
	);
	return $options;
}

function wpr_translation_options($options) {
	?>
	<h3 style="text-transform:uppercase;border-bottom: 1px solid #ccc;"><?php _e("Translation Options","wprobot") ?></h3>			
		<table class="addt" width="100%" cellspacing="2" cellpadding="5" class="editform"> 		
			<tr <?php if($options['wpr_trans_api'] == "") {echo 'style="background:#F8E0E0;"';} ?> valign="top"> 
				<td width="40%" scope="row"><?php _e("Yandex API Key:","wprobot") ?></td> 
				<td><input size="40" name="wpr_trans_api" type="text" id="wpr_trans_api" value="<?php echo $options['wpr_trans_api'] ;?>"/>
				<!--Tooltip--><a class="tooltip" href="https://tech.yandex.com/keys/get/?service=trnsl">?<span><?php _e('This key is required for using translation. Sign up for a free Yandex Translation API key and enter it here. Click the link to go to the signup page.',"wprobot") ?></span></a>
			</td> 
			</tr>		
			<tr valign="top"> 
				<td width="40%" scope="row"><?php _e("If translation fails...","wprobot") ?></td> 
				<td>
				<select name="wpr_trans_fail" id="wpr_trans_fail">
					<option value="skip" <?php if($options['wpr_trans_fail']=="skip"){_e('selected');}?>><?php _e("Skip Post","wprobot") ?></option>
					<option value="post" <?php if($options['wpr_trans_fail']=="post"){_e('selected');}?>><?php _e("Create Untranslated Post","wprobot") ?></option>

				</select>				
				</td> 
			</tr>		
			<tr valign="top"> 
				<td width="40%" scope="row"><?php _e("Translate Titles","wprobot") ?></td> 
				<td>
				<input name="wpr_trans_titles" type="checkbox" id="wpr_trans_titles" value="yes" <?php if ($options['wpr_trans_titles']=='yes') {echo "checked";} ?>/> <?php _e("Yes","wprobot") ?>
				<!--Tooltip--><a class="tooltip" href="#">?<span><?php _e('Choose wether to translate post titles for translated content. If you are translating to a foreign language this has to be enabled or otherwise the titles will stay English. If using the translation feature for rewriting it is recommended to disable this setting in order to reduce requests to Google Translate.',"wprobot") ?></span></a>
				</td> 
			</tr>			
		</table>	
	<?php
}

?>