<?php

do_action('AuctionTheme_pay_for_item_page','AuctionTheme_pay_for_item_page_payfast');

function AuctionTheme_pay_for_item_page_payfast($bid_id)
{
	?>
    
    <a href="<?php bloginfo('siteurl'); ?>/?pay_for_item_payfast=1&bid_id=<?php echo $bid_id; ?>"><?php echo __('Pay by Payfast','AuctionTheme'); ?></a></a>
    
    <?php	
}


?>