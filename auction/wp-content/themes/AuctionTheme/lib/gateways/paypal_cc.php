<?php
if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }
//-----------

	add_filter('sitemile_before_footer', 'auctionTheme_my_account_before_footer');
	function auctionTheme_my_account_before_footer()
	{
		echo '<div class="clear10"></div>';
	}

	//----------

	global $wpdb,$wp_rewrite,$wp_query;
	$pid = $wp_query->query_vars['pid'];



	global $current_user;
		$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$post_auction = get_post($pid);


	if(isset($_POST['yes']))
	{

		wp_redirect(get_bloginfo('siteurl')."/?a_action=buy_now&pid=".$pid);
		exit;
	}
	if(isset($_POST['no']))
	{
		wp_redirect(get_permalink($pid));
		exit;
	}

//==========================

get_header();

?>
<div class="clear10"></div>


			<div class="my_box3 breadcrumb-wrap">

            	<div class="box_title"><?php echo sprintf(__("Pay by credit card for listing auction: %s",'AuctionTheme'), $post_auction->post_title); ?></div>
                <div class="box_content">
                <div class="padd10">


                    <table width="100%">

                      <tr>
                        <td><?php _e('First Name: ','AuctionTheme'); ?></td>
                          <td><input type="text" value="" class="do_input" required name="firstname" size="40" /></td>
                        </tr>


                        <tr>
                          <td><?php _e('Last Name: ','AuctionTheme'); ?></td>
                            <td><input type="text" value="" class="do_input" required name="lastname" size="40" /></td>
                          </tr>


                          <tr>
                            <td><?php _e('Street Address: ','AuctionTheme'); ?></td>
                              <td><input type="text" value="" class="do_input" required name="address" size="10" /></td>
                            </tr>

                            <tr>
                              <td><?php _e('City: ','AuctionTheme'); ?></td>
                                <td><input type="text" value="" class="do_input" required name="city" size="10" /></td>
                              </tr>


                              <tr>
                                <td><?php _e('ZIP Code: ','AuctionTheme'); ?></td>
                                  <td><input type="text" value="" class="do_input" required name="zip" size="10" /></td>
                                </tr>


                          <tr>
                            <td><?php _e('Credit Card Number: ','AuctionTheme'); ?></td>
                              <td><input type="text" value="" class="do_input" required name="ccnr" size="60" /></td>
                            </tr>


                            <tr>
                              <td><?php _e('CVV Number: ','AuctionTheme'); ?></td>
                                <td><input type="text" value="" class="do_input" required name="cvv" size="10" /></td>
                              </tr>


                              <tr>
                                <td><?php _e('Expiry Date: ','AuctionTheme'); ?></td>
                                  <td>
                                    <select class="do_input" required name="month"> <option value="">Select Month</option>
                                      <option value="01">01</option>
                                      <option value="02">02</option>
                                      <option value="03">03</option>
                                      <option value="04">04</option>
                                      <option value="05">05</option>
                                      <option value="06">06</option>
                                      <option value="07">07</option>
                                      <option value="08">08</option>
                                      <option value="09">09</option>
                                      <option value="10">10</option>
                                      <option value="11">11</option>
                                      <option value="12">12</option>
                                    </select>

                                    <select class="do_input" required name="year"> <option value="">Select Year</option>
                                      <option value="2017">2017</option>
                                      <option value="2018">2018</option>
                                      <option value="2019">2019</option>
                                      <option value="2020">2020</option>
                                      <option value="2021">2021</option>
                                      <option value="2022">2022</option>
                                      <option value="2023">2023</option>
                                      <option value="2024">2024</option>
                                      <option value="2025">2025</option>
                                    </select>
                                  </td>
                                </tr>
                    </table>


    </div> </div>
			</div>



        <div class="clear100"></div>


<?php

get_footer();

?>
