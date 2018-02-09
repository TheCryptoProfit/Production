<?php
/**
 * This file is used to markup the Feed header.
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/public/partials
 */

echo 'Header test';

// Category filters - Dropdown list 
/*if ($feed_options['feed-category-filters'][0] == '1') 
{ 	
	$cat_array = array();	
	foreach($queryItems as $key => $item) 
	{	
		$post_cats = wp_get_post_categories( $item->ID );
		
		foreach($post_cats as $temp_cat) 
		{
			$cat_array[] = $temp_cat;	
		}
	}
	$cat_array = $utilities->unique_multidim_array($cat_array, 'term_id');
	
	$final_cats = array();
	foreach ($cat_array as $catid)
	{
		$final_cats[] = get_category($catid);
	}
	
	// Sort by name
	usort($final_cats, function($a, $b)
	{
		return strcmp($a->name, $b->name);
	});  				
	
	if (count($final_cats))
	{ ?>
		<div class="steemitfeed_dropdown">
			
			<div class="dropdown-label cat-label">
				<span data-label="<?php echo __('Filter by category', 'steemit-feed'); ?>">
					<i class="fa fa-angle-down"></i><?php echo __('Filter by category', 'steemit-feed'); ?>
				</span>
			</div>
			
			<ul class="button-group button-group-category" data-filter-group="category">
			
				<li><a href="#" data-filter="" class="steemitfeed-filter sf_filter_active"><?php echo __('Show all', 'steemit-feed'); ?></a></li>
				
				<?php
				foreach($final_cats as $final_cat) 
				{ ?>
					<li><a href="#" data-filter=".cat-<?php echo $final_cat->slug; ?>" class="steemitfeed-filter"><?php echo $final_cat->name; ?></a></li>
				<?php } ?>
				
			</ul>
		
		</div>
	<?php } 
}
*/