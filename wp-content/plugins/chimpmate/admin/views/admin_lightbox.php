<div id="lightbox" class="chimpmate_box">
	<div class="chimpmate_item litehead simghead">
		<div class="itemhead">
			<h2>Subscribe box in Lightbox</h2>
		</div>
		<div class="chimpmate_group">
			<div class="paper-toggle">
				<input type="checkbox" id="litebox_en" ng-model="data.litebox" ng-true-value="'1'" class="chimpmate_toggle"/>
				<label for="litebox_en">Enable</label>
			</div>
			<span class="chimpmate_hint" data-hint="Enable Lightbox"></span>
		</div>
		<div class="chimpmate_group chimpmate_dropc">
			<label>Custom Form</label>
			<div class="chimpmate_drop">
				<div class="chimpmate_drop_head"><div>{{getformbyid(data.lite_form).name || (data.cforms.length?'Select Form':'No Forms')}}</div>
				<div class="bar"></div>
				</div>
				<div class="chimpmate_drop_body">
				<div ng-repeat="form in data.cforms" ng-click="data.lite_form = form.id">{{form.name}}</div>
				</div>
			</div>
			<button class="chimpmate_button orange material-design ng-binding" ng-click="foredit.run(0,data.lite_form)" ng-show="data.lite_form">Edit Form</button>
		</div>
		<div class="chimpmate_group chimpmate_dropc">
			<label>Theme</label>
			<div class="chimpmate_drop">
				<div class="chimpmate_drop_head"><div>{{gettheme(data.lite_theme).name || (data.themes.length?'Select Theme':'No Themes')}}</div>
				<div class="bar"></div>
				</div>
				<div class="chimpmate_drop_body">
				<div ng-repeat="theme in data.themes" ng-click="data.lite_theme = theme.id">{{theme.name}}</div>
				</div>
			</div>
			<button class="chimpmate_button orange material-design ng-binding" ng-click="foredit.run(1,data.lite_theme)" ng-show="data.lite_theme">Edit Theme</button>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Filter by Device</h2>
			<span class="chimpmate_hint headhint" data-hint="Show Subscription form if the user visits from?"></span>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_desktop" ng-true-value="'1'">  
			<div class="mcheckbox"></div>Desktop</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_tablet" ng-true-value="'1'">  
			<div class="mcheckbox"></div>Tablet</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_mobile" ng-true-value="'1'">  
			<div class="mcheckbox"></div>Mobile</label>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Filter by Page type</h2>
			<span class="chimpmate_hint headhint" data-hint="Show Subscription form if the user visits?"></span>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_homepage" ng-true-value="'1'">
			<div class="mcheckbox"></div>Home Page</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_blog" ng-true-value="'1'">
			<div class="mcheckbox"></div>Blog Page</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_page" ng-true-value="'1'">
			<div class="mcheckbox"></div>Pages</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_post" ng-true-value="'1'">
			<div class="mcheckbox"></div>Posts</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_category" ng-true-value="'1'">
			<div class="mcheckbox"></div>Categories/Archives</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_search" ng-true-value="'1'">
			<div class="mcheckbox"></div>Search</label>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_404error" ng-true-value="'1'">
			<div class="mcheckbox"></div>404 Error</label>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Behaviour</h2>
			<span class="chimpmate_hint headhint" data-hint="Adjust the behaviour"></span>
		</div>
		<div class="chimpmate_group chimpmate_txts chimpmate_cb"> 
			<label>Appear after</label>
			<input type="text" class="chimpmate_texts" ng-model="data.lite_behave_time">
			<span>seconds</span>
			<label><input type="checkbox" style="margin-left: 10px;" ng-model="data.lite_behave_time_inac" ng-true-value="'1'">
			<div class="mcheckbox"></div>of Inactivity</label>
		</div>
		<div class="chimpmate_group chimpmate_txts chimpmate_cb"> 
			<label><input type="checkbox" ng-model="data.lite_behave_scroll" ng-true-value="'1'">
			<div class="mcheckbox"></div>Appear after</label>
			<input type="text" class="chimpmate_texts" disabled="disabled" value="50">
			<span>% of the page scrolled</span>
		</div>
		<div class="chimpmate_group chimpmate_txts chimpmate_cb"> 
			<label><input type="checkbox" ng-model="data.lite_behave_cookie" ng-true-value="'1'">
			<div class="mcheckbox"></div>Reappear after</label>
			<input type="text" class="chimpmate_texts" disabled="disabled" value="1">
			<span>day using Cookie</span>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Close action</h2>
			<span class="chimpmate_hint headhint" data-hint="When to close the lightbox"></span>
		</div>
		<div class="chimpmate_group chimpmate_txts"> 
			<label>Disappear after</label>
			<input type="text" class="chimpmate_texts" ng-model="data.lite_close_time">
			<span>seconds of inactivity</span>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label><input type="checkbox" ng-model="data.lite_close_bck" ng-true-value="'1'">
			<div class="mcheckbox"></div>Close when Lightbox background is clicked</label>
			<span class="chimpmate_hint" data-hint="If not selected, visitors need to click close button to exit the lightbox"></span>
		</div>
	</div>
</div>