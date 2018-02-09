<?php
/**
 * This file is used to markup the list1 layout.
 *
 * @since      1.1.0
 * @package    Steemit-Feed
 * @subpackage Steemit-Feed/public/partials
 */

foreach($items as $key => $item) 
{ ?>
	<article class="sf-li" data-permlink="<?php echo $item->permlink; ?>">
		
		<?php // Image
		if ($feed_show_images === 'yes' && isset($item->image) && $item->image)
		{ ?>
			<a href="https://steemit.com<?php echo $item->url.''.$referral_code; ?>" class="sf-image" target="_blank">
				<img src="<?php echo $item->image; ?>" alt="<?php echo $item->title; ?>" />
			</a>
		<?php } ?>
		
		<div class="sf-li-content">
		
			<?php // Title
			if ($this->detailBoxTitle === 'yes')
			{ ?>
				<a class="sf-li-title" href="https://steemit.com<?php echo $item->url.''.$referral_code; ?>" target="_blank"><?php echo $item->short_title; ?></a>
			<?php }
			
			// Body
			if ($this->detailBoxIntrotext === 'yes')
			{ ?>
				<div class="sf-li-body"><?php echo $item->short_body; ?></div>
			<?php }
			
			// Tags
			if ($this->detailBoxTags === 'yes')
			{ ?>
				<div class="sf-li-tags">
					<?php foreach ($item->tags as $tag)
					{ ?>
						<a href="https://steemit.com/trending/<?php echo $tag.''.$referral_code; ?>" class="sf-li-tag" target="_blank">&#35;<?php echo $tag; ?></a>
					<?php } ?>
				</div>
			<?php }
				
			// Post footer
			if ($this->detailBoxDate === 'yes'
			|| $this->detailBoxCategory === 'yes'
			|| $this->detailBoxAuthor === 'yes'
			|| $this->detailBoxAuthorRep === 'yes'
			|| $this->detailBoxReward === 'yes'
			|| $this->detailBoxVotes === 'yes'
			|| $this->detailBoxComments === 'yes')
			{ ?>
				
				<div class="sf-li-footer">
					
					<?php // Reward
					if ($this->detailBoxReward === 'yes')
					{ ?>
						<span class="sf-li-reward">
							<span class="sf-li-dollar-sign">&#36;</span><?php echo $item->total_reward; ?>
						</span>
					<?php }
					
					// Votes
					if ($this->detailBoxVotes === 'yes')
					{ ?>
						<span class="sf-li-votes">
							<i class="fa fa-chevron-up"></i>&nbsp;
							<?php echo $item->net_votes; ?>
						</span>
					<?php }
					
					// Replies
					if ($this->detailBoxComments === 'yes')
					{ ?>
						<span class="sf-li-replies">
							<a href="https://steemit.com<?php echo $item->url.''.$referral_code; ?>#comments" target="_blank">
								<i class="fa fa-comments"></i>&nbsp;
								<span><?php echo $item->replies_count; ?></span>
							</a>
						</span>
					<?php }
					
					// Date - author - category
					if ($this->detailBoxDate === 'yes'
					|| $this->detailBoxAuthor === 'yes'
					|| $this->detailBoxCategory === 'yes')
					{ ?>
						<span class="sf-li-vcard">
						
							<?php // Date
							if ($this->detailBoxDate === 'yes')
							{ ?>
								<span class="sf-li-date"><?php echo $item->formatted_date; ?></span>
							<?php }
							
							// Author
							if ($this->detailBoxAuthor === 'yes')
							{ ?>
								<span class="sf-li-author">
									<?php echo __('by', 'steemit-feed' ); ?> <a href="https://steemit.com/@<?php echo $item->author.''.$referral_code; ?>" target="_blank"><?php echo $item->author; ?></a>
									<?php if ($this->detailBoxAuthorRep === 'yes')
									{ ?>
										<span class="sf-li-rep"><?php echo $item->author_reputation; ?></span>
									<?php } ?>
								</span>
							<?php }
							
							// Category
							if ($this->detailBoxCategory === 'yes')
							{ ?>
								<span class="sf-li-category">
									<?php echo __('in', 'steemit-feed' ); ?> <a href="https://steemit.com/trending/<?php echo $item->category.''.$referral_code; ?>" target="_blank"><?php echo $item->category; ?></a>
								</span>
							<?php } ?>
						
						</span>
					<?php } ?>
					
				</div>
			<?php } ?>
		
		</div>
	
	</article>
<?php }
