<?php
/**
 * SERP check Class
 * http://www.aa-team.com
 * ======================
 *
 * @package			pspSERPCheck
 * @author			AA-Team
 */
class pspSERPCheck
{
    const VERSION = '1.0';

	static protected $_instance;
	public $the_plugin = null;
	private $module_folder = '';
	private $settings = array();


	// google custom search api url
	private static $google_custom_search_url = "https://www.googleapis.com/customsearch/v1?cx={cx}&key={key}&q={q}&gl={gl}&num={num}&start={start}";

	// cache folder & files
	private static $alias;
	private static $paths;
	private static $CACHE_CONFIG_LIFE = 720; // cache lifetime in minutes /half a day
	private static $CACHE_FOLDER = null;
	
	// debug only, the html file have result of search "test" on google.com
	private static $__isdebug = false;
	private static $__debug_url = '';

	private $config = array(); // config info related to google custom search api request
	
	private static $saveLog = false; // log with info about last request to api

	// google returns only 8 items per request (changed in 2015)
	// 2017-sept, changed to 10 items per request - seems to work (but not with anything > 10)
	private static $request_max_allowed_items = 10;


    /*
     * Required __construct() function
     */
    public function __construct()
    {
    	global $psp;

    	$this->the_plugin = $psp;
    	
    	$this->settings = $this->the_plugin->getAllSettings( 'array', 'serp' );
		
		// cache folder & files
		//self::$CACHE_FOLDER = $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/serp/cache/';
		self::$alias = $this->the_plugin->alias . '-serprank-';
		if ( !$this->build_cache_folder() ) {
			// todo: Error - could not create cache folder!
			return;
		}

		self::$__debug_url = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/serp/test-google.com.json';
		
		$this->config = array(
			// maximum number of requests ( to make daily ) to google custom search api
			'max_nb_requests' 	=> isset($this->settings['nbreq_max_limit']) ? (int) $this->settings['nbreq_max_limit'] : 100,

			// developer key
			'key' 				=> isset($this->settings['developer_key']) ? $this->settings['developer_key'] : '',

			// custom search engine id
			'cx' 				=> isset($this->settings['custom_search_id']) ? $this->settings['custom_search_id'] : '',

			// google engine location / country | ex. us | ro | bg
			'gl' 				=> '', //$this->settings['google_country']
		);

		$is_valid_serp_config = $this->api_set_config( $this->config );
		if ( 'invalid' == $is_valid_serp_config['status'] ) {
			//var_dump('<pre>', $is_valid_serp_config , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		}
    }

	/**
     * Singleton pattern
     *
     * @return pspSERPCheck Singleton instance
     */
    static public function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

	public function saveLog( $val=false ) {
		self::$saveLog = $val;
	}



	/**
	 *
	 * MAKE REQUEST & PARSE RESPONSE - FOR GOOGLE CUSTOM SEARCH API
	 */
	public function api_set_config( $pms=array() ) {
		$cfg = array();
		$cfg = array_replace_recursive($cfg, $pms);

		// gl param
		if ( isset($cfg['gl']) ) {
			$cfg['gl'] = $this->api_clean_gl_param( $cfg['gl'] );
		}
		if ( isset($cfg['key']) ) {
			$cfg['key'] = $this->api_clean_devkey_param( $cfg['key'] );
		}

		$this->config = array_replace_recursive($this->config, $cfg);
		//var_dump('<pre>', $this->config , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

		$ret = $this->api_verify_params();
		return $ret;
	}

	public function api_find_domains( $pms=array() ) {
		$pmsDefault = array(
			'top_type' 		=> 100,
			'engine'		=> '',
			'keyword'		=> '',
			'domains' 		=> array(),
		);
		$pms = array_replace_recursive($pmsDefault, $pms);
		extract($pms);

		$ret = array(
			'status'	=> 'invalid',
			'msg'		=> 'api_find_domains: unknown'
		);

		$pms_find = array_replace_recursive($pms, array());
		unset( $pms_find['domains'] );
		$ret_find = $this->api_find_keyword( $pms_find );

		if ( 'invalid' == $ret_find['status'] ) {
			$ret = array_replace_recursive($ret, $ret_find);
			return $ret;
		}

		$pms_parse = array_replace_recursive($pms, array(
			//'startPos' 		=> 0, // where to start (index position in content)
			'content' 		=> $ret_find['content'], // a json which will be converted to an array
		));
		unset( $pms_find['domains'] );
		$ret_parse = $this->api_parse_response( $pms_parse );

		$ret = array_replace_recursive($ret, $ret_parse);
		return $ret;
	}

	public function api_find_keyword( $pms=array() ) {
		//rand(30,55), //in seconds: serp sleep between consecutive requests!
		$pmsDefault = array(
			'top_type' 		=> 100, // ex.: top 100, top 50, top 10...
			'engine'		=> '', // search engine, ex.: google.com | google.ro | google.com.mx
			'keyword'		=> '', // single keyword, ex.: wordpress plugin premium seo pack
		);
		$pms = array_replace_recursive($pmsDefault, $pms);
		foreach ($pms as $key => $val) {
			if ( ! is_array($val) ) {
				$pms["$key"] = strip_tags( $val );
			}
		}
		extract($pms);

		$ret = array(
			'status'	=> 'invalid',
			'msg'		=> 'api_find_keyword: unknown'
		);

		//mandatory params missing!
		if ( !isset($engine) || empty($engine)
			 || !isset($keyword) || empty($keyword)
		) {
			$ret = array_replace_recursive($ret, array(
				'msg'	=> 'api_find_keyword: invalid mandatory params!',
			));
			return $ret;
		}
			
		if ( false === preg_match("/^google/i", $engine) ) {
			$ret = array_replace_recursive($ret, array(
				'msg'	=> 'api_find_keyword: invalid engine param!',
			));
			return $ret;
		}

		// some init
		$dataToSave = array(); // top100 array

		$cachename = $engine . '||' . $keyword . '||' . $top_type;
		//$filename = self::$CACHE_FOLDER . md5($cachename) . '.json';
		$filename = self::$paths['cache_path'] . md5($cachename) . '.json';

		//:: CACHE - try to read from cache!
		//$cache_is_valid = false;
		// no need for new cache!
		if ( $this->needNewCache($filename) !== true ) {

			$body = $this->getCacheFile($filename);

			// error
			if (is_null($body) || !$body || trim($body)=='') {
				$msg = __('api_find_keyword: cache file is empty!', $this->the_plugin->localizationName);
				//$this->api_save_log( 'error', $msg, 'line: '.__LINE__ );

				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				//return $ret;
			}
			// success
			else {
				//$cache_is_valid = true;
				$dataToSave = $body;

				$msg = __('api_find_keyword: successfull from cache!', $this->the_plugin->localizationName);
				//$this->api_save_log( 'success', $msg, 'line: '.__LINE__ );

				$ret = array_replace_recursive($ret, array(
					'status' 	=> 'valid',
					'msg' 		=> $msg,
					'from' 		=> 'cache',
					'content' 	=> $dataToSave,
				));
				return $ret;
			}

		}

		//:: API Request
		//if ( ! $cache_is_valid ) {
		if (1) {

			// new day OR min 1 hour between requests => try to reset available number of requests to the api
			//   (because we don't know the time when day start on serp api)
			// strtotime('1 hour') = current time minus 1 hour
			$this->api_nbreq_init();

			$nbreq_per_top = $this->api_nbreq_per_top( $top_type );
			$nbreq_current = 0;
			$contor = 1;

			// main loop
			do {

				extract( $this->api_nbreq_get() );
				$devkey = $currentDevkey;

				// can we do a request today?
				$can_make_request = $this->api_can_make_request();
				if ( 'invalid' == $can_make_request['status'] ) {
					$ret = array_replace_recursive($ret, $can_make_request);
					return $ret;
				}

				// try to make the request
				if ( $contor > 1 ) {
					usleep(200000); // wait for 0.2 seconds - in microseconds, a microsecond is one millionth of a second
				}

				$nbreq_current++; // increase the number of current requests made in this loop

				$doReqStat = $this->api_do_request( array(
					'startPos' 		=> $contor,
					'keyword'		=> $keyword,
					'devkey' 		=> $devkey,
				));
				//var_dump('<pre>',$contor, $doReqStat ,'</pre>');
				if ( 'invalid' == $doReqStat['status'] ) {
					return $doReqStat;
				}
				$body = $doReqStat['body'];
				$body_decode = json_decode( $body, true );

				// increase today number of requests
				if ( $currentData == date('Y-m-d') ) {
					$this->api_nbreq_set(  $devkey, 'inc', 'today' ); // increase today number of requests
				}
				// reset today number of requests
				else {
					$this->api_nbreq_set( $devkey, 0, 'today' ); // reset today number of requests
				}

 				// parse json response
				// we don't search for links or domains, just build the top100 array
				$ret_parse = $this->api_parse_response( array(
					'startPos' 		=> $contor-1, // where to start (index position in content)
					'content' 		=> $body, // a json which will be converted to an array
				));
				//var_dump('<pre>',$contor, $ret_parse ,'</pre>');

				if ( 'valid' == $ret_parse['status'] ) {
					$dataToSave = array_merge( $dataToSave, $ret_parse['top100'] );
				}
				else {
					$msg = $ret_parse['msg'];
					$this->api_save_log( 'error', $msg, 'line: '.__LINE__ );
					$ret = array_replace_recursive($ret, array(
						'msg'	=> $msg,
					));
					return $ret;
				}

				$contor += self::$request_max_allowed_items;

				// condition
				$condition_res = true;
				if ( isset($body_decode['searchInformation'], $body_decode['searchInformation']['totalResults']) ) {
					$totalres = (int) $body_decode['searchInformation']['totalResults'];
					//var_dump('<pre>',$contor, $totalres ,'</pre>');

					$condition_res = $contor <= $totalres;
				}

				$condition = $nbreq_current < $nbreq_per_top;
				$condition = $condition && $condition_res;

			}
			while ( $condition );
			// end main loop

			// write cache!
			if ( !empty($dataToSave) ) {
				$dataToSave = array(
					'request' 	=> $pms,
					'items' 	=> $dataToSave
				);
				$dataToSave = json_encode( $dataToSave );

				// write new local cached file! - append new data
				$this->writeCacheFile( $filename, $dataToSave );
			}

			// success
			$msg = __('api_find_keyword: successfull from API request!', $this->the_plugin->localizationName);
			//$this->api_save_log( 'success', $msg, 'line: '.__LINE__ );

			$ret = array_replace_recursive($ret, array(
				'status' 	=> 'valid',
				'msg' 		=> $msg,
				'from' 		=> 'api',
				'content' 	=> $dataToSave,
			));
			return $ret;
		}
		// end API Request
	}
    
	public function api_parse_response( $pms=array() ) {
		$pmsDefault = array(
			'content' 		=> '', // a json which will be converted to an array
			'startPos' 		=> 0, // where to start (index position in content)
			'domains' 		=> array(), // find all pages (for this domains) in content
			'links'			=> array(), // find these pages in content

			'filter_top_by_domain' 	=> true, // filter links by their domains
			'filter_multi_domains' 	=> true, // retrieve domains with multiple links/pages
		);
		$pms = array_replace_recursive($pmsDefault, $pms);
		extract($pms);

		$ret = array(
			'status'	=> 'invalid',
			'msg'		=> 'api_parse_response: unknown'
		);

		// REMOVED VALIDATION - WE WILL BUILD THE TOP100 ARRAY IF NO (LINKS OR DOMAINS) ARE PROVIDED
		// validate links | domains, to search for in the content
		//if ( ( empty($links) || ! is_array( $links ) )
		//	&& ( empty($domains) || ! is_array( $domains ) )
		//) {
		//	$ret = array_replace_recursive($ret, array(
		//		'msg'	=> 'invalid links or domains (input param)!',
		//	));
		//	return $ret;
		//}

		// validate content
		$content = trim($content);

		if ( '' == $content ) {
			$ret = array_replace_recursive($ret, array(
				'msg'	=> 'api_parse_response: invalid content (is empty)!',
			));
			return $ret;
		}

		$content = json_decode( $content, true );

		$request = isset($content['request']) ? (array) $content['request'] : array();
		$content = isset($content['items']) ? (array) $content['items'] : array();

		if ( empty($content) || ! is_array( $content ) ) {
			$ret = array_replace_recursive($ret, array(
				'msg'	=> 'api_parse_response: invalid content (json issue)!',
			));
			return $ret;
		}

		// some init
		$top100 = array();
		$pos = (int) $startPos;

		// find these pages in content
		$linksToFind = array();
		foreach ($links as $thelink) {
			$linksToFind["$thelink"] = 999; //not in top 100
		}

		// find all pages (for this domains) in content
		$domainsToFind = array();
		foreach ($domains as $thedomain) {
			$domainsToFind["$thedomain"] = array();
		}

		// main foreach - loop through content and try to find matches
		foreach ($content as $tag) {

			$title = isset($tag['title']) ? (string) $tag['title'] : '';
			$url = isset($tag['link']) ? (string) $tag['link'] : '';
			//$url = trim( $url );

			$isURL = false;
			if ( preg_match("#^https?://#im", $url) ) {
				$isURL = true;
			}
			else if ( preg_match("#^www\.#im", $url) ) {
				$isURL = true;
			}

			if ( ! $isURL ) {
				//continue 1;
			}

			$pos++;

			$top100[ $pos ] = array(
				'link'		=> $url,
				'title'		=> $title,
			);

			if ( empty($linksToFind) && empty($domainsToFind) ) {
				continue 1;
			}

			$cleanUrl = $this->clean_website_url( $url );

			// find these pages in content
			foreach ($linksToFind as $key => $val) {
				$cleanLinkToFind = $this->clean_website_url( $key );

				if ( $cleanLinkToFind == $cleanUrl ) {
					$linksToFind["$key"] = $pos;
				}
			} // end foreach

			// find all pages (for this domains) in content
			foreach ($domainsToFind as $key => $val) {
				$cleanLinkToFind = $this->clean_website_url( $key );

				$found = preg_match("#^" . preg_quote($cleanLinkToFind, '/') . "#im", $cleanUrl, $matches);

				if ( $found ) {
					$domainsToFind["$key"]["$url"] = $pos;
				}
			} // end foreach

		} // end main foreach

		// is found - is true if every element from links | domains is found at least once
		$is_found = true;
		if ( ! empty($links) && in_array(999, $links) ) {
			$is_found = false;
		}
		if ( ! empty($domains) ) {
			foreach ($domainsToFind as $key => $val) {
				if ( empty($val) ) {
					$is_found = false;
					break 1;
				}
			} // end foreach
		}

		// filter links by their domains
		$top_by_domain = array();
		if ( $filter_top_by_domain ) {
			$top_by_domain = $this->filter_top_by_domain( $top100 );
		}

		// retrieve domains with multiple links/pages
		$multi_domains = array();
		if ( $filter_multi_domains ) {
			$multi_domains = $this->filter_multi_domains( $top_by_domain );
		}

		$ret = array_replace_recursive($ret, array(
			'status' 	=> 'valid',
			'msg'		=> 'api_parse_response: ok!',

			'is_found' 	=> $is_found, //is true if every element from links | domains is found at least once
			'request' 	=> $request, // request used for this top (engine, keyword, top type)
			'domains'	=> $domainsToFind, // find all pages (for this domains) in content
			'links' 	=> $linksToFind, // find these pages in content
			'multi_domains'=> $multi_domains, // retrieve domains with multiple links/pages
			'top_by_domain'=> $top_by_domain, // filter links by their domains
			'top100' 	=> $top100, // top100 orderd by position
		));
		return $ret;
	}

    public function api_can_make_request() {
		$ret = array(
			'status'	=> 'invalid',
			'msg'		=> 'api_can_make_request: unknown'
		);

		extract( $this->api_nbreq_get() );

		if ( $currentNbReq >= $this->config['max_nb_requests'] ) {

			$msg = __('You\'ve reached the maximum allowed number of requests for this day.', $this->the_plugin->localizationName);
			$this->api_save_log( 'error', $msg, 'line: '.__LINE__ );

			$ret = array_replace_recursive($ret, array(
				'msg'	=> $msg,
			));
			return $ret;
		}

		$ret = array_replace_recursive($ret, array(
			'status' 	=> 'valid',
			'msg'		=> 'api_can_make_request: ok!',
		));
		return $ret;
    }

	public function api_do_request( $pms=array() ) {
		$pmsDefault = array(
			'startPos' 		=> 0,
			'keyword'		=> '',
			'devkey' 		=> '',
		);
		$pms = array_replace_recursive($pmsDefault, $pms);
		extract($pms);

		$ret = array(
			'status'	=> 'invalid',
			'msg'		=> 'api_do_request: unknown'
		);

		$apiURL = $this->api_build_request_url( $startPos, $keyword, $devkey );
				//var_dump('<pre>', $apiURL , '</pre>');

		// get json response from API
		$resp = $this->the_plugin->remote_get( $apiURL );

		// validate response!
		if ( is_array($resp) && isset($resp['status']) && $resp['status'] == 'valid' ) {
			$body = $resp['body'];
		}
		else {
			$body = false;
		}

		$msg = $resp;
		if (is_null($body) || !$body || trim($body)=='') {
			$this->api_save_log( 'error', $msg, 'line: '.__LINE__ );
			$ret = array_replace_recursive($ret, array(
				'msg'	=> $msg,
			));
			return $ret;
		}
		else {
			$body_decode = json_decode( $body, true );

			if ( isset( $body_decode['error'], $body_decode['error']['code'] ) ) {

				if ( isset($body_decode['error']['errors']) && is_array($body_decode['error']['errors']) ) {
					foreach ( $body_decode['error']['errors'] as $key => $val ) {
						if ( isset($val['reason']) && ($val['reason'] == 'dailyLimitExceeded') ) {
							// reached max limit for today number of requests
							$this->api_nbreq_set( $devkey, 'max', 'today' );
						}
					} // end foreach
				}

				$body_decode['__api_request_url'] = $apiURL;
				$msg = json_encode( $body_decode );
				$this->api_save_log( 'error', $msg, 'line: '.__LINE__ );
				$ret = array_replace_recursive($ret, array(
					'msg'	=> $msg,
				));
				return $ret;
			}
		}

		// success
		$msg = __('api_do_request: successfull from API request!', $this->the_plugin->localizationName);

		$msg2 = array();
		$msg2['status'] = 'valid';
		$msg2['msg'] = $msg;
		$msg2['__api_request_url'] = $apiURL;
		$msg2 = json_encode( $msg2 );
		$this->api_save_log( 'success', $msg2, 'line: '.__LINE__ );

		$ret = array_replace_recursive($ret, array(
			'status' 	=> 'valid',
			'msg'		=> $msg,
			'body' 		=> $body,
		));
		return $ret;
	}

	public function api_nbreq_get() {
		$currentReqInfo = get_option('psp_serp_nbrequests', array());

		// reset if old version - single key
		if ( isset($currentReqInfo['nbreq']) ) {
			$currentReqInfo = array();
		}

		// init => default is invalid (no request can be made)
		$currentDevkey = '';
		$currentNbReq = (int) $this->config['max_nb_requests'];
		$currentData = date('Y-m-d');
		$currentTimestamp = time();

		// try to find the first valid key (with which you can still make requests)
		foreach ($currentReqInfo as $key => $val) {

			if ( $val['nbreq'] >= $this->config['max_nb_requests'] ) {
				continue 1;
			}

			$currentDevkey = $key;
			$currentNbReq = isset($val['nbreq']) ? (int) $val['nbreq'] : 0;
			$currentData = isset($val['data']) ? (string) $val['data'] : '';
			$currentTimestamp = isset($val['timestamp']) ? (int) $val['timestamp'] : 0;
			break;
		} // end foreach

		return compact('currentDevkey', 'currentNbReq', 'currentData', 'currentTimestamp');
	}

	public function api_nbreq_set( $devkey, $nbreq=0, $data='today', $pms=array() ) {
		$pms = array_replace_recursive(array(
			'do_update' 		=> true,
			'currentReqInfo'	=> false,
		), $pms);
		extract($pms);

		if ( false === $currentReqInfo ) {
			$currentReqInfo = get_option('psp_serp_nbrequests', array());
		}

		// reset if old version - single key
		if ( isset($currentReqInfo['nbreq']) ) {
			$currentReqInfo = array();
		}

		if ( 'inc' === $nbreq ) {
			$nbreq = isset($currentReqInfo["$devkey"], $currentReqInfo["$devkey"]['nbreq'])
				? (int) ($currentReqInfo["$devkey"]['nbreq'] + 1) : 1;
		}
		else if ( 'max' === $nbreq ) {
			$nbreq = (int) $this->config['max_nb_requests'];
		}
		$nbreq = (int) $nbreq;

		$currentReqInfo["$devkey"] = array(
			'nbreq' 		=> $nbreq,
			'data' 			=> 'today' === $data ? date('Y-m-d') : $data,
			'timestamp' 	=> 'today' === $data ? time() : strtotime( $data ),
		);

		if ( $do_update ) {
			update_option( 'psp_serp_nbrequests', $currentReqInfo );
		}
		return $currentReqInfo;
	}

	public function api_nbreq_init() {
		$currentReqInfo = get_option('psp_serp_nbrequests', array());

		// reset if old version - single key
		if ( isset($currentReqInfo['nbreq']) ) {
			$currentReqInfo = array();
		}

		$new = array();
		$cfg = $this->config['key'];

		foreach ($cfg as $key) {

			$key_db = array();
			if ( isset($currentReqInfo["$key"]) ) {
				$key_db = $currentReqInfo["$key"];
				$new["$key"] = $key_db;
			}

			if ( empty($key_db) 
				|| ( $key_db['data'] != date('Y-m-d') )
				|| ( $key_db['timestamp'] <= strtotime('-1 hour') )
			) {
				$__ = $this->api_nbreq_set( $key, 0, 'today', array(
					'do_update' 		=> false,
					'currentReqInfo'	=> $currentReqInfo,
				));
				$new["$key"] = $__["$key"];
			}
		} // end foreach

		// reset today number of requests
		update_option( 'psp_serp_nbrequests', $new );
	}


	private function api_clean_gl_param( $str='' ) {
		$hasdot = strrpos($str, '.');
		if ( false !== $hasdot ) {
			$str = substr($str, $hasdot+1);
		}

		if ( 'com' == $str ) {
			$str = 'us';
		}
		return $str;
	}

	private function api_clean_devkey_param( $str='' ) {
		$new = $str;
		if ( ! is_array($new) ) {
			$new = explode("\n", $new);
		}
		$new = array_filter($new);
		$new = array_unique($new);
		$new = array_map('trim', $new); // remove \r and other chars
		//$new = array_flip($new);
		//$new = array_fill_keys($new, array());
		return $new;
	}

	private function api_verify_params() {
		$ret = array(
			'status' => 'invalid',
			'msg' 	=> 'unknown',
		);

		$cfg = $this->config;

		// developer key
		if ( empty($cfg['key']) ) {
			$ret = array_replace_recursive($ret, array(
				'msg'	=> 'google developer key is empty!',
			));
			return $ret;
		}

		if ( '' == $cfg['cx'] ) {
			$ret = array_replace_recursive($ret, array(
				'msg'	=> 'google custom search engine id is empty!',
			));
			return $ret;
		}

		if ( strlen($cfg['gl']) != 2 ) {
			$ret = array_replace_recursive($ret, array(
				'msg'	=> 'google engine location is an invalid country code (must have 2 chars)!',
			));
			return $ret;
		}

		$ret = array_replace_recursive($ret, array(
			'status' => 'valid',
			'msg'	=> 'ok!',
		));
		return $ret;
	}

    private function api_build_request_url( $start, $keyword, $devkey ) {
		if (self::$__isdebug) {
			$url = self::$__debug_url;
			return $url;
		}
    	$url = self::$google_custom_search_url;

    	$api_req_max_items = self::$request_max_allowed_items;

    	$url = str_replace('{key}', $devkey, $url);
    	$url = str_replace('{cx}', urlencode($this->config['cx']), $url);
    	$url = str_replace('{gl}', $this->config['gl'], $url);

		$url = str_replace('{num}', $api_req_max_items, $url);
		$url = str_replace('{start}', $start, $url);
		$url = str_replace('{q}', urlencode(htmlspecialchars_decode($keyword, ENT_QUOTES)), $url);
		//var_dump('<pre>', $url , '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

		return $url;
    }

    private function api_save_log( $status, $msg, $step='' ) {
    	if ( ! self::$saveLog ) {
    		return false;
    	}

		$last_status = array('last_status' => array(
			'status' 	=> $status,
			'step' 		=> $step,
			'data' 		=> date("Y-m-d H:i:s"),
			'msg' 		=> $msg,
		));
		$this->the_plugin->save_theoption( $this->the_plugin->alias . '_serp_last_status', $last_status );
		$this->the_plugin->save_theoption( $this->the_plugin->alias . '_serp', array_merge( (array) $this->settings, $last_status ) );
		return true;
    }

    private function api_nbreq_per_top( $top_type=100 ) {
		$api_req_max_items = self::$request_max_allowed_items;
		return (int) ceil( $top_type / $api_req_max_items );
    }

    private function filter_top_by_domain( $top=array() ) {
    	$ret = array();
    	foreach ($top as $key => $val) {
    		$pos = $key;
    		$url = $val['link'];
			$__url = parse_url($url);

			if ( false !== $__url ) {
				if ( isset($__url['host']) && ('' != $__url['host']) ) {
					
					$__host = $__url['host'];
					$__host = $this->clean_website_url( $__host );

					if ( ! isset($ret["$__host"]) ) {
						$ret["$__host"] = array();
					}
					$ret["$__host"]["$url"] = $pos;
				}
			}
		}
		return $ret;
    }

    private function filter_multi_domains( $top=array() ) {
    	$ret = array();
    	foreach ($top as $key => $val) {
    		if ( count($val) > 1 ) {
    			$ret["$key"] = $val;
    		}
    	}
    	return $ret;
    }



	/**
	 *
	 * UTILS
	 */
	//clean url for comparation!
	private function clean_website_url( $url ) {
		return $this->the_plugin->clean_website_url( $url );
	}

	//use cache to limits search accesses!
	public function needNewCache($filename) {
	
		// cache file needs refresh!
		if (($statCache = $this->isCacheRefresh($filename))===true || $statCache===0) {
			return true;
		}
		return false;
	}
	
	// verify cache refresh is necessary!
	private function isCacheRefresh($filename) {
		$cache_life = self::$CACHE_CONFIG_LIFE;

		// cache folder!
		//$this->makedir(self::$CACHE_FOLDER);
		$this->makedir(self::$paths['cache_path']);

		// cache file exists!
		if ($this->verifyFileExists($filename)) {
			$verify_time = time();
			$file_time = filemtime($filename);
			$mins_diff = ($verify_time - $file_time) / 60;
			if($mins_diff > $cache_life){
				// new cache is necessary!
				return true;
			}
			// cache is empty! => new cache is necessary!
			if (filesize($filename)<=0) return 0;

			// NO new cache!
			return false;
		}
		// cache file NOT exists! => new cache is necessary!
		return 0;
	}

	// write content to local cached file
	public function writeCacheFile($filename, $content) {
		return file_put_contents($filename, $content);
	}

	// cache file
	public function getCacheFile($filename) {
		if ($this->verifyFileExists($filename)) {
			$content = file_get_contents($filename);
			return $content;
		}
		return false;
	}
	
	// delete cache
	public function deleteCache($cache_file) {
		//$filename = self::$CACHE_FOLDER . $cache_file;
		$filename = self::$paths['cache_path'] . $cache_file;

		if ($this->verifyFileExists($filename)) {
			return unlink($filename);
		}
		return false;
	}

	// verify if file exists!
	private function verifyFileExists($file, $type='file') {
		clearstatcache();
		if ($type=='file') {
			if (!file_exists($file) || !is_file($file) || !is_readable($file)) {
				return false;
			}
			return true;
		} else if ($type=='folder') {
			if (!is_dir($file) || !is_readable($file)) {
				return false;
			}
			return true;
		}
		// invalid type
		return 0;
	}

	// make a folder!
	private function makedir($path, $folder='') {
		$fullpath = $path . $folder;

		clearstatcache();
		if(file_exists($fullpath) && is_dir($fullpath) && is_readable($fullpath)) {
			return true;
		}else{
			$stat1 = @mkdir($fullpath);
			$stat2 = @chmod($fullpath, 0777);
			if ($stat1===true && $stat2===true)
				return true;
		}
		return false;
	}
	
	// get file name/ dot indicate if a .dot will be put in front of image extension, default is not
	private function fileName($fullname) {
		$return = substr($fullname, 0, strrpos($fullname, "."));
		return $return;
	}

	// get file extension
	private function fileExtension($fullname, $dot=false) {
		$return = "";;
		if( $dot == true ) $return .= ".";
		$return .= substr(strrchr($fullname, "."), 1);
		return $return;
	}
	
	private function build_cache_folder() {
		self::$CACHE_FOLDER = substr(self::$alias, 0, strlen(self::$alias) - 1);

		// make sure upload dirs exist and set file path and uri
		$upload_dir = wp_upload_dir();
		if ( !$this->verifyFileExists($upload_dir['basedir'], 'folder') ) {
			wp_mkdir_p( $upload_dir['basedir'] );   
		}

		self::$paths = array(
			'cache_path'         => $upload_dir['basedir'] . '/' . self::$CACHE_FOLDER . '/',
			'cache_url'          => $upload_dir['baseurl'] . '/' . self::$CACHE_FOLDER . '/',
		);

		if ( !$this->verifyFileExists(self::$paths['cache_path'], 'folder') ) {
			wp_mkdir_p( self::$paths['cache_path'] );
		}
		if ( $this->verifyFileExists(self::$paths['cache_path'], 'folder')
			&& is_writable(self::$paths['cache_path']) ) {
			return true;
		}
		return false;
	}
}
//new pspSERPCheck();