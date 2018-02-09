<?php
/** 
 * ChimpMate - WordPress MailChimp Assistant
 *
 * @package   ChimpMate - Advanced Subscription Hub For Wordpress
 * @author    Voltroid<care@voltroid.com>
 * @link      http://voltroid.com/chimpmate
 * @copyright 2017 Voltroid
 */

?>
<div class="wrap chimpmate_home" ng-app="chimpmate" id="chimpmatectlr" ng-controller="chimpmatectlr">
	<div class="chimpmate_header">
		<div class="h_left">
			<div class="h_container l h_left">
				<div class="chimpmate_logo"></div>
			</div>
			<div class="h_container h_right">
				<div class="button_cont">
					<button class="chimpmate_button button_header blue material-design" id="sup_button" ng-click="sup_button()">support</button>
					<button class="chimpmate_button button_header blue material-design" id="faq_button" ng-click="faq_button()">faq</button>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" id="donate_form" style="display:inline-block">
						<input type="hidden" name="cmd" value="_donations">
						<input type="hidden" name="business" value="jpolachan@gmail.com">
						<input type="hidden" name="lc" value="US">
						<input type="hidden" name="item_name" value="Voltroid ChimpMate - WordPress MailChimp Assistant">
						<input type="hidden" name="no_note" value="0">
						<input type="hidden" name="currency_code" value="USD">
						<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
						<button class="chimpmate_button button_header green material-design" ng-click="wpmchimpa_donate()">donate</button>
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" style="display:none">
					</form>
				</div>
			</div>
		</div>
		<div class="h_right">
			<div class="h_left chimpmate_social">
				<div class="chimpmate_soc_cont fb">
					<a href="https://www.facebook.com/Voltroid"><div class="chimpmate_socioicon"></div></a>
				</div>
				<div class="chimpmate_soc_cont tt">
					<a href="https://twitter.com/Voltroid"><div class="chimpmate_socioicon"></div></a>
				</div>
				<div class="chimpmate_soc_cont gp">
					<a href="https://plus.google.com/+VoltroidInc"><div class="chimpmate_socioicon"></div></a>
				</div>
			</div> 
			<div class="header_voltroid h_right">
					<span class="voltroid"></span>
					<span class="apanel"></span>
					<div class="vlogo">
					</div>
			 </div>
		</div>
	</div> 
	<div class="chimpmate_toolbar">
		<div class="chimpmate_tabs">
			<ul>
				<li class="tabitem material-design" ng-class="{active: $route.current.activetab == 'general'}"><a href="#/general" data-title="general">GENERAL</a></li>
				<li class="tabitem material-design" ng-class="{active: $route.current.activetab == 'theme'}"><a href="#/theme" data-title="theme">THEME</a></li>
				<li class="tabitem material-design" ng-class="{active: $route.current.activetab == 'lightbox'}"><a href="#/lightbox" data-title="lightbox">LIGHTBOX</a></li>
				<li class="tabitem material-design" ng-class="{active: $route.current.activetab == 'slider'}"><a href="#/slider" data-title="slider">SLIDER</a></li>
				<li class="tabitem material-design" ng-class="{active: $route.current.activetab == 'widget'}"><a href="#/widget" data-title="widget">WIDGET</a></li>
				<li class="tabitem material-design" ng-class="{active: $route.current.activetab == 'addon'}"><a href="#/addon" data-title="addon">ADD-ON</a></li>
				<li class="tabitem material-design" ng-class="{active: $route.current.activetab == 'advanced'}"><a href="#/advanced" data-title="advanced">ADVANCED</a></li>
			</ul>
		</div>
		<button ng-click="update_options()" class="chimpmate_button red material-design">Update Options</button>
		<div class="chimpmate_loading_container">
			<div class="chimpmate_spinner" ng-if="isLoading == 1">
				<svg class="circular">
					<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/>
				</svg>
			</div>
			<div class="chimpmate_status chimpmate_updated" ng-if="isLoading == 2"></div>                
			<div class="chimpmate_status chimpmate_error" ng-if="isLoading == 3"></div>                
		</div>
	</div>  

	<div class="chimpmate_content"><?php $this->update_notice(1);?>
		<div ng-view></div>
	</div>
	<div id="errcont"></div>
</div>