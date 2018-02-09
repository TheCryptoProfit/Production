<div id="addon" class="chimpmate_box">
	<div class="chimpmate_item addhead simghead">
		<div class="itemhead">
			<h2>Subscribe box in Post Page</h2>
		</div>
		<div class="chimpmate_group">
			<div class="paper-toggle">
				<input type="checkbox" id="addon_en" ng-model="data.addon" ng-true-value="'1'" class="chimpmate_toggle">
				<label for="addon_en">Enable</label>
			</div>
			<span class="chimpmate_hint" data-hint="Enable Add-on"></span>
		</div>
		<div class="chimpmate_group chimpmate_dropc">
			<label>Custom Form</label>
			<div class="chimpmate_drop">
				<div class="chimpmate_drop_head"><div>{{getformbyid(data.addon_form).name || (data.cforms.length?'Select Form':'No Forms')}}</div>
				<div class="bar"></div>
				</div>
				<div class="chimpmate_drop_body">
				<div ng-repeat="form in data.cforms" ng-click="data.addon_form = form.id">{{form.name}}</div>
				</div>
			</div>
			<button class="chimpmate_button orange material-design ng-binding" ng-click="foredit.run(0,data.addon_form)" ng-show="data.addon_form">Edit Form</button>
		</div>
		<div class="chimpmate_group chimpmate_dropc">
			<label>Theme</label>
			<div class="chimpmate_drop">
				<div class="chimpmate_drop_head"><div>{{gettheme(data.addon_theme).name || (data.themes.length?'Select Theme':'No Themes')}}</div>
				<div class="bar"></div>
				</div>
				<div class="chimpmate_drop_body">
				<div ng-repeat="theme in data.themes" ng-click="data.addon_theme = theme.id">{{theme.name}}</div>
				</div>
			</div>
			<button class="chimpmate_button orange material-design ng-binding" ng-click="foredit.run(1,data.addon_theme)" ng-show="data.addon_theme">Edit Theme</button>
		</div>
	</div>
	<div class="chimpmate_item tophead simghead">
		<div class="itemhead">
			<h2>Topbar Subscription Box</h2>
		</div>
		<div class="chimpmate_group">
			<div class="paper-toggle">
				<input type="checkbox" id="topbar_en" ng-model="data.topbar" ng-true-value="'1'" class="chimpmate_toggle">
				<label for="topbar_en">Enable</label>
			</div>
			<span class="chimpmate_hint" data-hint="Enable Topbar"></span>
		</div>
		<div class="chimpmate_group chimpmate_dropc">
			<label>Custom Form</label>
			<div class="chimpmate_drop">
				<div class="chimpmate_drop_head"><div>{{getformbyid(data.topbar_form).name || (data.cforms.length?'Select Form':'No Forms')}}</div>
				<div class="bar"></div>
				</div>
				<div class="chimpmate_drop_body">
				<div ng-repeat="form in data.cforms" ng-click="data.topbar_form = form.id">{{form.name}}</div>
				</div>
			</div>
			<button class="chimpmate_button orange material-design ng-binding" ng-click="foredit.run(0,data.topbar_form)" ng-show="data.topbar_form">Edit Form</button>
		</div>
		<div class="chimpmate_group chimpmate_dropc">
			<label>Theme</label>
			<div class="chimpmate_drop">
				<div class="chimpmate_drop_head"><div>{{gettheme(data.topbar_theme).name || (data.themes.length?'Select Theme':'No Themes')}}</div>
				<div class="bar"></div>
				</div>
				<div class="chimpmate_drop_body">
				<div ng-repeat="theme in data.themes" ng-click="data.topbar_theme = theme.id">{{theme.name}}</div>
				</div>
			</div>
			<button class="chimpmate_button orange material-design ng-binding" ng-click="foredit.run(1,data.topbar_theme)" ng-show="data.topbar_theme">Edit Theme</button>
		</div>
	</div>
	<div class="chimpmate_item flihead simghead">
		<div class="itemhead">
			<h2>Flipbox Subscription Box</h2>
		</div>
		<div class="chimpmate_group">
			<div class="paper-toggle">
				<input type="checkbox" id="flipbox_en" ng-model="data.flipbox" ng-true-value="'1'" class="chimpmate_toggle">
				<label for="flipbox_en">Enable</label>
			</div>
			<span class="chimpmate_hint" data-hint="Enable Flipbox"></span>
		</div>
		<div class="chimpmate_group chimpmate_dropc">
			<label>Custom Form</label>
			<div class="chimpmate_drop">
				<div class="chimpmate_drop_head"><div>{{getformbyid(data.flipbox_form).name || (data.cforms.length?'Select Form':'No Forms')}}</div>
				<div class="bar"></div>
				</div>
				<div class="chimpmate_drop_body">
				<div ng-repeat="form in data.cforms" ng-click="data.flipbox_form = form.id">{{form.name}}</div>
				</div>
			</div>
			<button class="chimpmate_button orange material-design ng-binding" ng-click="foredit.run(0,data.flipbox_form)" ng-show="data.flipbox_form">Edit Form</button>
		</div>
		<div class="chimpmate_group chimpmate_dropc">
			<label>Theme</label>
			<div class="chimpmate_drop">
				<div class="chimpmate_drop_head"><div>{{gettheme(data.flipbox_theme).name || (data.themes.length?'Select Theme':'No Themes')}}</div>
				<div class="bar"></div>
				</div>
				<div class="chimpmate_drop_body">
				<div ng-repeat="theme in data.themes" ng-click="data.flipbox_theme = theme.id">{{theme.name}}</div>
				</div>
			</div>
			<button class="chimpmate_button orange material-design ng-binding" ng-click="foredit.run(1,data.flipbox_theme)" ng-show="data.flipbox_theme">Edit Theme</button>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Filter by Device</h2>
			<span class="chimpmate_hint headhint" data-hint="Show Subscription box(Topbar&Flipbox excluded since only for Desktop) form if the user visits from"></span>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.addon_desktop" ng-true-value="'1'">
			<div class="mcheckbox"></div>Desktop</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.addon_tablet" ng-true-value="'1'">
			<div class="mcheckbox"></div>Tablet</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.addon_mobile" ng-true-value="'1'">
			<div class="mcheckbox"></div>Mobile</label>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Filter by Page type</h2>
			<span class="chimpmate_hint headhint" data-hint="Show Subscription form if the user visits?"></span>
		</div>
		<h3>Subscribe Box</h3>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.addon_page" ng-true-value="'1'">
			<div class="mcheckbox"></div>Pages</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.addon_post" ng-true-value="'1'">
			<div class="mcheckbox"></div>Posts</label>
		</div>
		<h3>Topbar</h3>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.topbar_homepage" ng-true-value="'1'">
			<div class="mcheckbox"></div>Home Page</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.topbar_blog" ng-true-value="'1'">
			<div class="mcheckbox"></div>Blog Page</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.topbar_page" ng-true-value="'1'">
			<div class="mcheckbox"></div>Pages</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.topbar_post" ng-true-value="'1'">
			<div class="mcheckbox"></div>Posts</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.topbar_category" ng-true-value="'1'">
			<div class="mcheckbox"></div>Categories/Archives</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.topbar_search" ng-true-value="'1'">
			<div class="mcheckbox"></div>Search</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.topbar_404error" ng-true-value="'1'">
			<div class="mcheckbox"></div>404 Error</label>
		</div>
		<h3>Flipbox</h3>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.flipbox_homepage" ng-true-value="'1'">
			<div class="mcheckbox"></div>Home Page</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.flipbox_blog" ng-true-value="'1'">
			<div class="mcheckbox"></div>Blog Page</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.flipbox_page" ng-true-value="'1'">
			<div class="mcheckbox"></div>Pages</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.flipbox_post" ng-true-value="'1'">
			<div class="mcheckbox"></div>Posts</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.flipbox_category" ng-true-value="'1'">
			<div class="mcheckbox"></div>Categories/Archives</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.flipbox_search" ng-true-value="'1'">
			<div class="mcheckbox"></div>Search</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.flipbox_404error" ng-true-value="'1'">
			<div class="mcheckbox"></div>404 Error</label>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Behaviour</h2>
			<span class="chimpmate_hint headhint" data-hint="Behaviour of the Subscribe Box"></span>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label class="chimpmate_para">Orientation</label>
			<div class="chimpmate_compac p1">
				<input id="ao1" type="radio" ng-model="data.addon_orient" value="top">
				<label for="ao1">Top <div class="orientvdemo topo"></div></label>
			</div>
			<div class="chimpmate_compac">
				<input id="ao2" type="radio" ng-model="data.addon_orient" value="mid">
				<label for="ao2">Mid <div class="orientvdemo mido"></div></label> 
			</div>
			<div class="chimpmate_compac">
				<input id="ao3" type="radio" ng-model="data.addon_orient" value="bot">
				<label for="ao3">Bottom <div class="orientvdemo boto"></div></label> 
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-true-value="'1'" ng-model="data.addon_scode">
			<div class="mcheckbox"></div>Enable ShortCode</label>
			<span class="chimpmate_hint" data-hint="Enable Short Code"></span>
			<div class="p3">use - [chimpmate]</div>
		</div>
	</div>
</div>