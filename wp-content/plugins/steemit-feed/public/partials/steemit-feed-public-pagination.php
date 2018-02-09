<?php
/**
 * This file is used to markup the feed pagination.
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/public/partials
 */
?>

<a id="steemitfeed-load-<?php echo $feed_id; ?>" href="#" class="steemitfeed-page-btn steemitfeed-load-more" data-page="1">
	
	<span class="steemitfeed-more-items">
	
			<span><?php echo __( 'Load more', 'steemit-feed' ); ?></span>
										
	</span>
	
	<span class="steemitfeed-no-more"><?php echo __( 'No more items', 'steemit-feed' ); ?></span>

</a>

<?php if ($feed_options['feed-pagination-reset'][0] == 'yes') { ?>
<a href="#" class="steemitfeed-page-btn steemitfeed-reset-btn">
	
	<span><?php echo __( 'Reset filters', 'steemit-feed' ); ?></span>

</a>
<?php } ?>

<div id="steemitfeed-loader-<?php echo $feed_id; ?>" class="steemitfeed-loader"> </div>
