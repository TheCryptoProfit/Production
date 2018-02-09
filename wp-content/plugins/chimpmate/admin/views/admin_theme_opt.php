
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Edit : {{gettheme(theme_ctrl.esel).name}}</h2>
			<span class="chimpmate_hint headhint" data-hint="Edit your Theme"></span>
		</div>
		<div class="chimpmate_group">
			<button class="chimpmate_button orange material-design" ng-click="prev.switch()">{{prev.st?'Close Editor':'Live Editor'}}</button>
		</div>
	</div>
	<div class="chimpmate_prev" ng-class="{showlive:prev.st}">
		<div class="chimpmate_topbar">
			<div class="chimpmate_round" style="background:#f67a00"></div><div class="chimpmate_round" style="background:#ebc71f"></div><div class="chimpmate_round" style="background:#31bb37"></div><div class="chimpmate_left"></div><div class="chimpmate_right"></div>
			<div class="chimpmate_long">ChimpMate - {{gettpl(theme.tpl).n}} - Live Editor</div>
			<div class="chimpmate_search"></div><div class="chimpmate_opts"></div>
		</div>
		<div class="chimpmate_toptab">
			<div class="chimpmate_tab" ng-click="prev.ty = 0" ng-class="{chimpmate_tabact:prev.ty == 0}">Lightbox</div>
			<div class="chimpmate_tab" ng-click="prev.ty = 1" ng-class="{chimpmate_tabact:prev.ty == 1}">Slider</div>
			<div class="chimpmate_tab" ng-click="prev.ty = 2" ng-class="{chimpmate_tabact:prev.ty == 2}">Widget</div>
			<div class="chimpmate_tab" ng-click="prev.ty = 3" ng-class="{chimpmate_tabact:prev.ty == 3}">Addon</div>
			
		</div>
		<div class="chimpmate_viewportbck">
			<div class="chimpmate_lineimg"></div>
			<div class="chimpmate_divide" style="left:33%"></div>
			<div class="chimpmate_divide" style="left:66%"></div>
			<div ng-repeat="i in fontsiz.slice(0, 2)" class="chimpmate_linecont">
				<div ng-repeat="i in fontsiz.slice(0, 10)" class="chimpmate_line"></div>
			</div>
		</div>
		<div ng-if="prev.st" class="chimpmate_viewport" ng-include="prev.get()"></div>
	</div>
	<div class="chimpmate_item" data-setno="1">
		<div class="itemhead">
			<h2>Custom Message</h2>
		</div>
		<opttext ng-model="theme.heading" optlab="Heading" opthint="Heading"></opttext>
		<optfont ng-model="theme.heading_f"></optfont>
		<optcolor ng-model="theme.heading_fc" optlab="Font Color"></optcolor>
		<div class="chimpmate_group"> 
			<div class="chimpmate_para">Message
				<span class="chimpmate_hint" data-hint="Sub-heading"></span>
			</div>
			<ng-quill-editor ng-model="theme.msg" toolbar="true" link-tooltip="true" image-tooltip="true" toolbar-entries="bold list bullet italic underline strike align color background link image"></ng-quill-editor>
		</div>
		<optfont ng-model="theme.msg_f" optdis="true"></optfont>
	</div>
	<div class="chimpmate_item" data-setno="2">
		<div class="itemhead">
			<h2>Personalize your Text Box</h2>
		</div>
		<optfont ng-model="theme.tbox_f"></optfont>
		<optcolor ng-model="theme.tbox_fc" optlab="Font Color"></optcolor>
		<optcolor ng-model="theme.tbox_bgc" optlab="Background Color"></optcolor>
		<opttxts ng-model="theme.tbox_w" optlab="Width" opttxt="px"></opttxts>
		<opttxts ng-model="theme.tbox_h" optlab="Height" opttxt="px"></opttxts>
		<opttxts ng-model="theme.tbox_bor" optlab="Border Width" opttxt="px"></opttxts>
		<optcolor ng-model="theme.tbox_borc" optlab="Border Color"></optcolor>
	</div>
	<div class="chimpmate_item" data-setno="3">
		<div class="itemhead">
			<h2>Personalize your Checkbox/Radio</h2>
		</div>
		<div class="chimpmate_group chimpmate_cb">
			<label class="chimpmate_para">Checkbox Theme</label>
			<div class="chimpmate_compac p1">
				<input id="cm1" type="radio" value="1" ng-model="theme.check_shade">
				<label for="cm1">Light <div class="checkbdemo litet"></div></label>
			</div>
			<div class="chimpmate_compac">
				<input id="cm2" type="radio" value="2" ng-model="theme.check_shade">
				<label for="cm2">Dark <div class="checkbdemo darkt"></div></label> 
			</div>
			<div style="clear:both"></div>
		</div>
		<optcolor ng-model="theme.check_c" optlab="Theme Color"></optcolor>
		<optcolor ng-model="theme.check_borc" optlab="Border Color"></optcolor>

		<optfont ng-model="theme.check_f"></optfont>
		<optcolor ng-model="theme.check_fc" optlab="Font Color"></optcolor>	
	</div>
	<div class="chimpmate_item" data-setno="4">
		<div class="itemhead">
				<h2>Personalize your Button</h2>
		</div>
		<div ng-show="[5].indexOf(theme.tpl) == -1">
			<opttext ng-model="theme.button" optlab="Button Text"></opttext>
			<optfont ng-model="theme.button_f"></optfont>
			<optcolor ng-model="theme.button_fc" optlab="Font Color"></optcolor>
			<optcolor ng-model="theme.button_fch" optlab="Hover Font Color"></optcolor>
		</div>
		<opticon ng-model="theme.button_i" optlab="Icon"></opticon>
		<div ng-show="[5].indexOf(theme.tpl) == -1">
			<opttxts ng-model="theme.button_w" optlab="Width" opttxt="px"></opttxts>
			<opttxts ng-model="theme.button_h" optlab="Height" opttxt="px"></opttxts>
			<optcolor ng-model="theme.button_bc" optlab="Background Color"></optcolor>
			<optcolor ng-model="theme.button_bch" optlab="Hover Background Color"></optcolor>
			<opttxts ng-model="theme.button_br" optlab="Border Radius" opttxt="px"></opttxts>
			<opttxts ng-model="theme.button_bor" optlab="Border Width" opttxt="px"></opttxts>
			<optcolor ng-model="theme.button_borc" optlab="Border Color"></optcolor>
		</div>
	</div>
	<div class="chimpmate_item" data-setno="5">
		<div class="itemhead">
				<h2>Personalize your Spinner</h2>
		</div>
		<optcolor ng-model="theme.spinner_c" optlab="Theme Color"></optcolor>
	</div>
	<div class="chimpmate_item" data-setno="6">
		<div class="itemhead">
			<h2>Personalize your Status Message</h2>
			<span class="chimpmate_hint headhint" data-hint="Customize your Success or Error Message"></span>
		</div>
		<optfont ng-model="theme.status_f"></optfont>
		<optcolor ng-model="theme.status_fc" optlab="Font Color"></optcolor>
	</div>
	<div class="chimpmate_item" data-setno="7">
		<div class="itemhead">
				<h2>Personalize your Tag</h2>
				<span class="chimpmate_hint headhint" data-hint="Customize your Tag"></span>
		</div>
		<optchk ng-model="theme.tag_en" optlab="Enable"></optchk>
		<opttext ng-model="theme.tag" optlab="Tag Text"></opttext>
		<optfont ng-model="theme.tag_f"></optfont>
		<optcolor ng-model="theme.tag_fc" optlab="Font Color"></optcolor>
	</div>

	<div class="chimpmate_item" data-setno="8">
		<div class="itemhead">
				<h2>Other Theme Options</h2>
		</div>
		<optcolor ng-model="theme.bg_c" optlab="Background Color" ng-show="[1,8,9].indexOf(theme.tpl) != -1"></optcolor>
		<optcolor ng-model="theme.inico_c" optlab="Icon Color"></optcolor>
		<opttext ng-model="theme.soc_head" optlab="Social Buttons Header" ng-show="[1,8].indexOf(theme.tpl) != -1"></opttext>
		<optfont ng-model="theme.soc_f" ng-show="[1,8].indexOf(theme.tpl) != -1"></optfont>
		<optcolor ng-model="theme.soc_fc" optlab="Social Buttons Header Color" ng-show="[1,8].indexOf(theme.tpl) != -1"></optcolor>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
				<h2>Lightbox</h2>
				<span class="chimpmate_hint headhint" data-hint="Lightbox Specific Settings"></span>
		</div>
		<optcolor ng-model="theme.lite_close_col" optlab="Close Button Color" ng-show="[1,8,9].indexOf(theme.tpl) != -1"></optcolor>
		<optrange ng-model="theme.lite_bg_op" optlab="Background Opacity"></optrange>
		<optchk ng-model="theme.lite_dislogo" optlab="Disable Logo Head" ng-show="[1].indexOf(theme.tpl) != -1"></optchk>
		<optcolor ng-model="theme.lite_head_col" optlab="Head Color" ng-show="[1].indexOf(theme.tpl) != -1"></optcolor>
		<optcolor ng-model="theme.lite_hshad_col" optlab="Head Shadow Color" ng-show="[1].indexOf(theme.tpl) != -1"></optcolor>
		<opttext ng-model="theme.lite_img1" optlab="Featured Image URL" optmedup="true" optpx="idim.l[theme.tpl]" opthint="Upload Image or Enter base64 of image with dimension {{optpx}}(px)" ng-show="[1].indexOf(theme.tpl) != -1"></opttext>
		<optchk ng-model="theme.lite_dissoc" optlab="Disable Social Buttons" ng-show="[1,8].indexOf(theme.tpl) != -1"></optchk>

	</div>

	<div class="chimpmate_item">
		<div class="itemhead">
				<h2>Slider</h2>
				<span class="chimpmate_hint headhint" data-hint="Slider Specific Settings"></span>
		</div>
		<optcolor ng-model="theme.slider_canvas_c" optlab="Canvas Color" ng-show="[1,8,9].indexOf(theme.tpl) != -1"></optcolor>
		<optchk ng-model="theme.slider_dissoc" optlab="Disable Social Buttons" ng-show="[1,8].indexOf(theme.tpl) != -1"></optchk>

		<div class="itemhead" data-setno="9">
				<h2>Slider Trigger Options</h2>
				<span class="chimpmate_hint headhint" data-hint="Personalize your Trigger"></span>
		</div>
		<opticon ng-model="theme.slider_trigger_i" optlab="Icon"></opticon>
		<optcolor ng-model="theme.slider_trigger_c" optlab="Icon Color"></optcolor>
		<optcolor ng-model="theme.slider_trigger_bg" optlab="Background Color"></optcolor>
		<optrange ng-model="theme.slider_trigger_top" optlab="Position from top(%)"></optrange>
		<optchk ng-model="theme.slider_trigger_hider" optlab="Distraction-free Mode" opthint="A small button to hide trigger"></optchk>
		<optcolor ng-model="theme.slider_trigger_hc" optlab="Hide-icon Color"></optcolor>
	</div>

	<div class="chimpmate_item">
		<div class="itemhead">
				<h2>Widget</h2>
				<span class="chimpmate_hint headhint" data-hint="Widget Specific Settings"></span>
		</div>
		<optchk ng-model="theme.widget_dissoc" optlab="Disable Social Buttons" ng-show="[1,8].indexOf(theme.tpl) != -1"></optchk>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
				<h2>Addon</h2>
				<span class="chimpmate_hint headhint" data-hint="Addon Specific Settings"></span>
		</div>
		<optcolor ng-model="theme.addon_bor_c" optlab="Border Color" ng-show="[1,8,9].indexOf(theme.tpl) != -1"></optcolor>
		<opttxts ng-model="theme.addon_bor_w" optlab="Border Width" opttxt="px" ng-show="[1,8,9].indexOf(theme.tpl) != -1"></opttxts>
		<optchk ng-model="theme.addon_dissoc" optlab="Disable Social Buttons" ng-show="[1,8].indexOf(theme.tpl) != -1"></optchk>
	</div>
</div>