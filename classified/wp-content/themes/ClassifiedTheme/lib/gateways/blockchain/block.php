<?php

add_filter('AuctionTheme_payment_methods_tabs','AuctionTheme_payment_methods_tabs_blkchain');
add_filter('AuctionTheme_payment_methods_content_divs_m','AuctionTheme_payment_methods_content_divs_bckch');
add_filter('AuctionTheme_payment_methods_action', 'AuctionTheme_payment_methods_action_blckk' );

function AuctionTheme_payment_methods_tabs_blkchain()
{
?>
    <li><a href="#tabs78_bk">Blockchain</a></li>
<?php
}

function AuctionTheme_payment_methods_action_blckk()
{

	if(isset($_POST['AuctionTheme_save_blck']))
	{
		update_option('AuctionTheme_block_enable', 		trim($_POST['AuctionTheme_block_enable']));
		update_option('AuctionTheme_blck_api_key', 		trim($_POST['AuctionTheme_blck_api_key']));

		echo '<div class="saved_thing">'.__('Settings saved!','AuctionTheme').'</div>';
	}

}

function AuctionTheme_payment_methods_content_divs_bckch()
{
	$arr = array("yes" => __("Yes",'AuctionTheme'), "no" => __("No",'AuctionTheme'));

?>

<div id="tabs78_bk" >

          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=AT_pay_gate_&active_tab=tabs78_bk">
            <table width="100%" class="sitemile-table">

                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','AuctionTheme'); ?></td>
                    <td><?php echo AuctionTheme_get_option_drop_down($arr, 'AuctionTheme_block_enable'); ?></td>
                    </tr>

                    <tr>
                    <td valign=top width="22"><?php AuctionTheme_theme_bullet(); ?></td>
                    <td ><?php _e('blockchain api key:','AuctionTheme'); ?></td>
                    <td><input type="text" size="45" name="AuctionTheme_blck_api_key" value="<?php echo get_option('AuctionTheme_blck_api_key'); ?>"/>
                      ( <a href="https://api.blockchain.info/v2/apikey/request/">https://api.blockchain.info/v2/apikey/request/</a>)</td>
                    </tr>



                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="AuctionTheme_save_blck" value="<?php _e('Save Options','AuctionTheme'); ?>"/></td>
                    </tr>

            </table>
          	</form>

          </div>

<?php

}
 ?>
