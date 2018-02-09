<div id="advanced" class="chimpmate_box">
	<div class="chimpmate_item advhead simghead">
		<div class="itemhead">
			<h2>Follow Us to get Instant Updates!<span class="show_love"></span></h2>
		</div>
		<div class="chimpmate_group">
			<div class="chimpmate_social" style="margin-left:120px;">
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
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Typography Live Preview</h2>
		</div>
		<div class="chimpmate_group">
			<style type="text/css">
				#chimpmate_preview p{
					color:{{democolor}};
					font-family:{{demofont.f | livepf}};
					font-size:{{demofont.s}}px;
					font-weight:{{demofont.w}};
					font-style:{{demofont.t}};
				}
			</style>
			<span id="chimpmate_preview">
			<p>THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG</p>
			<p>the quick brown fox jumps over the lazy dog</p>
			</span>
		</div>
		<optfont ng-model="demofont"></optfont>
		<div class="chimpmate_group chimpmate_color">
			<input minicolors type="text" class="chimpmate-color-sel" ng-model="democolor"/>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
				<h2>Plugin Resources</h2>
		</div>
		<div class="chimpmate_group chimpmate_para">
			Want more awesome plugins? Encourage us by donating :)
		</div>
		<div class="chimpmate_group">
				<input type="image" ng-click="wpmchimpa_donate()" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		</div>
		<div class="chimpmate_group chimpmate_para">
			ChimpMate is a MailChimp based email marketing plugin for wordpress. Mailchimp is one of the most powerful email marketing tool with more than 7 million users. Beginners can start using the service with free* account. Mailchimp also let the users to send mail to unlimited number of recipients. It is also ensures greater deliverability. Being inspired by mailchimp service we created this newsletter plugin for wordpress.org customers. It is a fully customizable plugin with professional design. The plugin offers easy installation of lightbox and widget. Hope you will like the plugin. Your feedback is appreciated.
		</div>
		<div class="chimpmate_group chimpmate_para">
		<h2>Credits</h2>
			<a href="http://voltroid.com/">Voltroid</a><br>
			<a href="http://mailchimp.com/">MailChimp</a><br>
			<a href="https://developers.google.com/fonts/docs/webfont_loader">Google Web Font Loader</a><br>
			<a href="https://developers.google.com/chart/">Google Chart</a><br>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Back up and Restore</h2>
		</div>
		<div class="chimpmate_group">
			<div class="chimpmate_para">One click backup and restore 
				<span class="chimpmate_hint" data-hint="You can save your settings and restore it later"></span>
			</div>
		</div>
		<div class="chimpmate_group">
		<button class="chimpmate_button green material-design" ng-click="configure.backup()">Backup</button>
		<button class="chimpmate_button green material-design" ng-click="configure.restore()">Restore</button>
		<input type="file" id="file_sel" accept=".json" style="display:none;"/>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Reset Plugin</h2>
		</div>
		<div class="chimpmate_group">
			<div class="chimpmate_para">One click plugin reset 
				<span class="chimpmate_hint" data-hint="Reset your plugin to default values"></span>
			</div>
		</div>
		<div class="chimpmate_group">
			<button class="chimpmate_button green material-design" ng-click="configure.reset()">Reset</button>
		</div>
	</div>
	<div class="chimpmate_item">
	<div class="itemhead">
		<h2>ChimpMate Pro</h2>
	</div>
	<div class="chimpmate_group feat_list">
		<div class="fl_row">
			<div class="feat"><span>FEATURES</span></div>
			<div class="featbox_h grey"><span>FREE</span></div>
			<div class="featbox_h blue"><span style="color:#fff">PRO</span></div>
		</div>
		<div class="fl_row">
			<div class="feat">Lightbox, Slider, Widget, Add-on, Topbar, Flipbox</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Built-in Editor</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Custom Fields</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">650+ Google fonts</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Automatic List and Group Fetching</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Fully Customizable Typography </div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Typography Live Preview</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Button Customization</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Live Editor</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Search Engine Target</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">User Status Based Filter</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Reappear Delay(Cookie)</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Scroll Toggle Detection </div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Fully Responsible</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Multi-Device Filter</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Filter By Page Type</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Lightbox Open Delay </div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Inactivity based events</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">One Click Bakup and Restore</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Easy to Configuration (No coding required!)</div>
			<div class="featbox avail grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Premium Themes</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Multiple Forms</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Multiple Lists</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">A/B Testing</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Open-on-Click</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Instant Analytics</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Depart Intent Tecnolagy</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Reappear Delay Customization</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Scroll Toggle % Custamization</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Advanced Addon Behaviour Customizations</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Post/Page Level Targeting</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Targeting Social Networking vistors</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Targeting URL Shoretners</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Targeting Specific URLs</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Custom CSS editor</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row">
			<div class="feat">Premium Support 24x7</div>
			<div class="featbox pro grey"></div>
			<div class="featbox avail blue"></div>
		</div>
		<div class="fl_row last">
			<div class="featbox_h feat_buypro" ng-click="feat_buypro()"></div>
		</div>
	</div>
</div>
</div>