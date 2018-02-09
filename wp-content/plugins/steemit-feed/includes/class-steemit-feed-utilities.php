<?php

/**
 * Utilities class
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/includes
 */

class Steemit_Feed_Utilities {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Nothing to see here...

	} // __construct()
		
	/**
	 * Formats the date.
	 *
	 * @since    1.1.0
	 */
	public function sf_time_since($date) 
	{
		$date = strtotime($date);
		$now = current_time( 'mysql' );
		$now = strtotime($now);
		$since = $now - $date;
		
		$chunks = array(
			array(60 * 60 * 24 * 365 , __('year ago', 'steemit-feed'), __('years ago', 'steemit-feed')),
			array(60 * 60 * 24 * 30 , __('month ago', 'steemit-feed'), __('months ago', 'steemit-feed')),
			array(60 * 60 * 24 * 7, __('week ago', 'steemit-feed'), __('weeks ago', 'steemit-feed')),
			array(60 * 60 * 24 , __('day ago', 'steemit-feed'), __('days ago', 'steemit-feed')),
			array(60 * 60 , __('hour ago', 'steemit-feed'), __('hours ago', 'steemit-feed')),
			array(60 , __('minute ago', 'steemit-feed'), __('minutes ago', 'steemit-feed')),
			array(1 , __('second ago', 'steemit-feed'), __('seconds ago', 'steemit-feed'))
		);
	
		for ($i = 0, $j = count($chunks); $i < $j; $i++) {
			$seconds = $chunks[$i][0];
			$name_1 = $chunks[$i][1];
			$name_n = $chunks[$i][2];
			if (($count = floor($since / $seconds)) != 0) {
				break;
			}
		}
	
		$print = ($count == 1) ? '1 '.$name_1 : "$count {$name_n}";
		return $print;
	}
	
	/**
	 * Calculates the author reputation.
	 *
	 * @since    1.1.0
	 */
	public function sf_format_reputation($reputation)
	{
		if ($reputation == null) return $reputation;
	
		$is_neg = $reputation < 0 ? true : false;
		$rep = $is_neg ? abs($reputation) : $reputation;
		$str = $rep;
		$leadingDigits = (int)substr($str, 0, 4);
		$log = log($leadingDigits) / log(10);
		$n = strlen((string)$str) - 1;
		$out = $n + ($log - (int)$log);
		if (!($out)) $out = 0;
		$out = max($out - 9, 0);
		$out = ($is_neg ? -1 : 1) * $out;
		$out = $out * 9 + 25;
		$out = (int)$out;
		
		return $out;
	}
	
	/**
	 * Gets replies count.
	 *
	 * @since    1.1.0
	 */
	public function sf_replies_count($author, $permlink)
	{
		$replies = file_get_contents('https://api.steemjs.com/getContentReplies?parent='.$author.'&parentPermlink='.$permlink);
		$isjson = $this->sf_is_json($replies);
		
		if ($isjson)
		{
			$replies = json_decode($replies, false);
			$childrenCount = 0;
			
			// Get children replies
			foreach ($replies as $reply)
			{
				$childrenCount += $reply->children;
			}
			
			$repliesCount = count($replies) + $childrenCount;
			
			return $repliesCount;	
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Checks for json.
	 *
	 * @since    1.1.0
	 */
	public function sf_is_json($string) 
	{
		json_decode($string);
		
		return (json_last_error() == JSON_ERROR_NONE);
	}
		 	
} // class