<?php

/**
 * Data source class
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/includes
 */

class Steemit_Feed_Data {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Nothing to see here...

	} // __construct()
	
	/**
	 * Query posts.
	 *
	 * @since    1.1.0
	 */
	public function feed_query_items($feed_options, $ajax = false, $permlink, $page) 
	{
		// Options - Data source
		$mn_sf_author = trim($feed_options['feed-author'][0]);
		$mn_sf_datenow = current_time( 'Y-m-d\TH:i:s' );
		$last_post_permlink = '';
										
		// Options - Pagination
		$feed_initial_items = (int)$feed_options['feed-initial-items'][0];
		$feed_items_per_page = (int)$feed_options['feed-items-per-page'][0];
		$feed_additional_pages = (int)$feed_options['feed-additional-pages'][0];
			
		// Page load
		if (!$ajax)
		{ 
			$posts_per_page = $feed_initial_items;
		}
		// Ajax
		else
		{
			$posts_per_page = $feed_initial_items;	
			
			if ($permlink)
			{
				$posts_per_page = $feed_items_per_page;	
				$last_post_permlink = $permlink;
				
				// Check for allowed additional pages
				if ($page && $page > $feed_additional_pages)
				{
					return false;	
				}
			}
		}
			
		// Included tags
		$included_tags = array();
		if (isset($feed_options['feed-tags-include'][0]) && $feed_options['feed-tags-include'][0])
		{
			$included_tags = array_map( 'trim', explode( ',', $feed_options['feed-tags-include'][0] ) );
		}

		// Excluded tags
		$excluded_tags = array();
		if (isset($feed_options['feed-tags-exclude'][0]) && $feed_options['feed-tags-exclude'][0])
		{
			$excluded_tags = array_map( 'trim', explode( ',', $feed_options['feed-tags-exclude'][0] ) );
		}
		
		// Excluded posts
		$excluded_posts = array();
		if (isset($feed_options['feed-posts-exclude'][0]) && $feed_options['feed-posts-exclude'][0])
		{
			$excluded_posts = array_map( 'trim', explode( PHP_EOL, $feed_options['feed-posts-exclude'][0] ) );
		}

		// Define posts array and permlinks
		$posts = array();
		$previous_batch_permlink = '';
			
		// Starting posts count = 0
		$c = 0;
		while ($c < $posts_per_page)
		{
			// API call
			$raw_url = 'https://api.steemjs.com/get_discussions_by_author_before_date?author='.$mn_sf_author.'&startPermlink='.$last_post_permlink.'&beforeDate='.$mn_sf_datenow.'&limit='.$posts_per_page;
			$temp = file_get_contents($raw_url);
			$isjson = $this->sf_is_json($temp);

			if ($isjson)
			{
				$batch = json_decode($temp, false);

				// Track last permlink of this batch
				$batch_count = count($batch);
				$last_post = $batch[$batch_count - 1];
				$last_post_permlink = $last_post->permlink;
				
				// Break if last permlink of this batch is the same as last permlink of previous batch
				if ($last_post_permlink === $previous_batch_permlink) break;
				// Update previous batch
				$previous_batch_permlink = $last_post->permlink;
				
				foreach ($batch as $item)
				{
					// Exclude last permlink of previous page
					if ($permlink && $permlink === $item->permlink)
					{
						continue;
					}
					
					// Exclude posts
					if (in_array($item->permlink, $excluded_posts))
					{
						continue;
					}
					
					if (count($posts) >= $posts_per_page)
					{
						break;	
					}
				
					// Metadata
					$metadata = json_decode($item->json_metadata, false);
				
					// Add item to posts
					if (!in_array($item, $posts))
					{
						if (empty($included_tags) && empty($excluded_tags))
						{
							$posts[] = $item;
						}
						else
						{
							if (empty($included_tags) && !empty($excluded_tags))
							{
								if (empty(array_intersect($metadata->tags, $excluded_tags)))
								{
									$posts[] = $item;
								}
							}
							else if (!empty($included_tags) && empty($excluded_tags))
							{
								if (!empty(array_intersect($metadata->tags, $included_tags)))
								{
									$posts[] = $item;
								}
							}
							else if (!empty($included_tags) && !empty($excluded_tags))
							{
								if (!empty(array_intersect($metadata->tags, $included_tags)) && empty(array_intersect($metadata->tags, $excluded_tags)))
								{
									$posts[] = $item;
								}
							}
						}
					}
				}
			}
			
			// Update posts count
			$c = count($posts);
			
			// Change limit if limit == 1 and posts is empty
			if ($c === 0 && $posts_per_page === 1)
			{
				$posts_per_page = 2;
			}
		}

		return $posts;
	}
	
	/**
	 * Check for json.
	 *
	 * @since    1.1.0
	 */
	public function sf_is_json($string) 
	{
		json_decode($string);
		
		return (json_last_error() == JSON_ERROR_NONE);
	}
				
} // class