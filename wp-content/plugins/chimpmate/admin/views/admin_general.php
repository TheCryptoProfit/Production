<div id="general" class="chimpmate_box">
	<div class="chimpmate_item genhead simghead">
		<div class="itemhead">
			<h2>Connection Settings</h2>
		</div>
		<div ng-switch="mailserv.isConfig">
			<div ng-switch-when="0">
				<div class="chimpmate_group conftext">
					Connected to MailChimp {{data.mailserv.acc? 'Account : '+  data.mailserv.acc : ''}}
				</div>
				<div class="chimpmate_group">
					<button class="chimpmate_button green material-design" ng-click="mailserv.proc()">Reconfigure</button>
				</div>
			</div>
			<div ng-switch-when="1">
				<opttext ng-model="mailserv.config.key" optlab="API Key"></opttext>
				<div class="chimpmate_group">
					<button class="chimpmate_button blue material-design" ng-click="mailserv.proc()">Next</button>
				</div>
			</div>
			<div ng-switch-when="2">
				<div class="chimpmate_group conftext">
					<div ng-show="mailserv.config.acc">Account : {{mailserv.config.acc}}</div>
				</div>
				<div class="chimpmate_group">
					<button class="chimpmate_button blue material-design" ng-click="mailserv.proc()">Confirm</button>
					<button class="chimpmate_button green material-design" ng-click="mailserv.reset()">Reset</button>
				</div>
			</div>
		</div>
	</div>


<div class="chimpmate_item">
	<div class="itemhead">
		<h2>Custom Forms</h2>
	</div>
	<div class="chimpmate_formcont">
		<div class="chimpmate_formcontin">
			<div class="chimpmate_formbox">
				<div class="chimpmate_group chimpmate_table_cont">
					<div class="chimpmate_table chimpmate_tablefo">
						<div class="chimpmate_tablehg">
							<div class="chimpmate_tabler">
								<div>No</div>
								<div>Form</div>
								<div>List</div>
								<div>Options</div>
								<div></div>
							</div>
						</div>
						<div class="chimpmate_tablerg">
							<div class="chimpmate_tabler" ng-repeat="cform in data.cforms track by $index">
								<div>{{$index + 1}}</div>
								<div>
									<input type="text" class="chimpmate_tabtext" required ng-model="cform.name">
									<div class="bar"></div>
								</div>
								<div>
									<optsel ng-head="cform.list.name" opthead="'Not Selected'" ng-list="data.lists" ng-model="cform.list" optclk="v" optlname="v.name" optchange="listchange"></optsel>
								</div>
								<div>
									<div class="mul_ico mul_edit" ng-click="form.edit(cform.id)"></div>
								</div>
								<div></div>
							</div>
						</div>
					</div>
					<div class="chimpmate_table_foot">
						<div class="chimpmate_conbox green relist" ng-click="form.loadlist()"></div>
						<div style="clear:both"></div>
					</div>
				</div>
			</div>
			<div class="chimpmate_edit"></div>
			<div class="chimpmate_formbox" ng-if="form.step==2">
				<div class="itemhead">
					<h2>Edit Form</h2>
				</div>
					<div class="chimpmate_group chimpmate_table_cont">
					<div class="chimpmate_table chimpmate_tablefi">
						<div class="chimpmate_tablehg">
							<div class="chimpmate_tabler">
								<div></div>
								<div>No</div>
								<div>Field</div>
								<div>Options</div>
								<div></div>
							</div>
						</div>
						<div class="chimpmate_tablerg" as-sortable="sortableOptions" ng-model="form.tform.fields">
							<div class="chimpmate_tabler chimpmate_tablefi" ng-repeat="cfield in form.tform.fields" as-sortable-item class="as-sortable-item mulfieldr">
								<div as-sortable-item-handle class="as-sortable-item-handle"></div>
								<div>{{$index + 1}}</div>
								<div>
									<div class="chimpmate_drop chimpmate_dropf">
										<div class="chimpmate_drop_head"><div>{{cfield.name || (cfields.length?'Select Field':'No Field')}}</div>
											<div class="bar"></div>
										</div>
										<div class="chimpmate_drop_body">
											<div ng-repeat="rfield in edform.fields" ng-click="field.selector($parent.$index,rfield)" ng-class="{
											'drop-dis': edform.chkfldexst(rfield),
											'drop-group': rfield.cat == 'group',
											'drop-sel': rfield.id?rfield.id==cfield.id:rfield.tag==cfield.tag
											}">{{rfield.name}}({{rfield.type}})</div>
										</div>
									</div>
								</div>
								<div>
									<div class="mul_ico mul_del" ng-click="field.del.c($index)" ng-hide="cfield.req"></div>
									<div class="mul_ico mul_req" ng-show="cfield.req"></div>
									<div class="mul_ico mul_edit" ng-show="cfield.name" ng-click="field.edit($index)"></div>
								</div>
								<div class="chimpmate_tablefed">
									<div class="chimpmate_tablefed_box" ng-if="field.ed == $index">
										<div class="chimpmate_tablefed_row" ng-if="(cfield.tag == 'email' || cfield.type == 'text')">
											<label>Icon</label>
											<div class="chimpmate_tablefed_col">
												<div class="ico_sel">
													<div class="chimpmate_drop">
														<div class="chimpmate_drop_head"><div ng-class="cfield.icon"></div>
															<div class="bar"></div>
														</div>
														<div class="chimpmate_drop_body">
															<div class="longcell inone" ng-click="cfield.icon='inone'" ng-class="{'drop-sel': cfield.icon=='inone' }"></div>
															<div class="longcell idef" ng-click="cfield.icon='idef'" ng-class="{'drop-sel': cfield.icon=='idef' }"></div>
															<div ng-repeat="(k, v) in icons" ng-click="cfield.icon=k" class="{{k}}" ng-class="{'drop-sel': k == cfield.icon }"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="chimpmate_tablefed_row" ng-if="cfield.type != 'hidden'">
											<label>Label</label>
											<div class="chimpmate_tablefed_col">
												<input type="text" class="chimpmate_tabtext" required ng-model="cfield.label">
												<div class="bar"></div>
											</div>
										</div>
										<div class="chimpmate_tablefed_row chimpmate_cm" ng-if="cfield.type == 'hidden'">
											<label>Options</label>
											<div class="chimpmate_tablefed_col" ng-if="cfield.cat == 'field'">
												<div class="chimpmate_tablefed_opt" ng-repeat="(k,v) in cfield.opt.choices track by k">
													<label class="hidmcheckbox">
														<input type="radio" ng-model="cfield.value" ng-value="v" ng-if="cfield.type != 'checkboxes'">
														<div></div>
													</label>
													<div>
														{{cfield.opt.choices[k]}}
													</div>
												</div>
												<div ng-if="!cfield.opt.choices || cfield.opt.choices.length == 0" class="chimpmate_tablefed_optemp">Empty</div>
											</div>
											<div class="chimpmate_tablefed_col" ng-if="cfield.cat == 'group'">
												<div class="chimpmate_tablefed_opt" ng-repeat="(k,v) in cfield.groups track by k">
													<label class="hidmcheckbox">
														<input type="checkbox" ng-model="cfield.groups[k].hid" ng-true-value="true" ng-if="cfield.type == 'checkboxes'">
														<input type="radio" ng-model="cfield.value" ng-value="v.id" ng-if="cfield.type != 'checkboxes'">
														<div></div>
													</label>
													<div>
														{{cfield.groups[k].gname}}
													</div>
												</div>
												<div ng-if="!cfield.groups || cfield.groups.length == 0" class="chimpmate_tablefed_optemp">Empty</div>
											</div>
										</div>
										<div class="chimpmate_tablefed_row" ng-hide="cfield.req || cfield.type == 'hidden'">
											<div class="chimpmate_tablefed_col chimpmate_cm">
												<label class="mcheckbox">
													<input type="checkbox" ng-model="cfield.foreq" ng-true-value="true">
													<div>Required Field</div>
												</label>
											</div>
										</div>
										<div class="chimpmate_tablefed_row" ng-if="['text','number','date','birthday','zip','phone','url','imageurl'].indexOf(cfield.type) >= 0">
											<div class="chimpmate_tablefed_col chimpmate_cm">
												<label class="mcheckbox">
													<input type="checkbox" ng-model="cfield.eft" ng-true-value="true" ng-change="field.eftchange($index)">
													<div>Extra Field for Topbar other than email</div>
												</label>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="chimpmate_table_foot">
						<div class="chimpmate_conbox blue add" ng-click="field.add()"></div>
						<div class="chimpmate_conbox ok" ng-click="field.ok()"></div>
						<div class="chimpmate_conbox cancel" ng-click="field.cancel()"></div>
						<div style="clear:both"></div>
					</div>
				</div>
				<div class="chimpmate_group selcon" ng-show="field.sel>-1">
					<div class="selconmsg">Are you sure you want to delete {{form.tform.fields[field.sel].name}}?</div>
					<div class="chimpmate_conbox confirm" ng-click="field.del.y()"></div>
					<div class="chimpmate_conbox decline" ng-click="field.del.n()"></div>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
</div>


	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Other Options</h2>
		</div>
		<div class="chimpmate_group">
			<div class="paper-toggle">
				<input type="checkbox" id="opt-in" ng-model="data.opt_in" ng-true-value="'1'" class="chimpmate_toggle"/>
				<label for="opt-in">Double Opt-in Process</label>
			</div>
			<span class="chimpmate_hint" data-hint="Email Confirmation Message"></span>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>On Successful Subscription</h2>
			<span class="chimpmate_hint headhint" data-hint="What do on Successful Subscription?"></span>
		</div>
		<optrad ng-model="data.suc_sub" optlab="" optval="suc_msg"></optrad>
		<opttext ng-model="data.suc_msg" optlab="Success Message" opthint="Enter a Message to Show Up"></opttext>
		<optrad ng-model="data.suc_sub" optlab="" optval="suc_url"></optrad>
		<opttext ng-model="data.suc_url" optlab="Redirect to URL" opthint="Enter a URL to redirect"></opttext>
		<div class="chimpmate_group chimpmate_cb p3">
			<label><input type="checkbox" ng-true-value="'1'" ng-model="data.suc_url_tab">
			<div class="mcheckbox"></div>Open in new tab</label>
		</div>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Error Messages</h2>
			<span class="chimpmate_hint headhint" data-hint="Set Respective Error Messages"></span>
		</div>
		<opttext ng-model="data.errorrf" optlab="Required Field"></opttext>
		<opttext ng-model="data.errorfe" optlab="Invalid Entry"></opttext>
		<opttext ng-model="data.erroras" optlab="Already subscribed"></opttext>
		<opttext ng-model="data.errorue" optlab="Unknown error"></opttext>
	</div>

	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>User Status</h2>
			<span class="chimpmate_hint headhint" data-hint="Show Subscription form if the user is?"></span>
		</div>
		<optchk ng-model="data.loggedin" optlab="Logged-In"></optchk>
		<optchk ng-model="data.notloggedin" optlab="Not Logged-In"></optchk>
		<optchk ng-model="data.commented" optlab="Commented"></optchk>
		<optchk ng-model="data.notcommented" optlab="Not Commented"></optchk>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Social API Keys</h2>
			<span class="chimpmate_hint headhint" data-hint="Set Social API Keys for Subscribe with Social Logins(wherever applicable)"></span>
		</div>
		<opttext ng-model="data.fb_api" optlab="Facebook App ID"></opttext>
		<opttext ng-model="data.gp_api" optlab="Google App Client ID for Web"></opttext>
		<opttext ng-model="data.ms_api" optlab="Microsoft App Client ID" opttext="Please provide the Redirect URI while creating a Microsoft App as :<br><?php echo WPMCA_PLUGIN_URL. 'assets/ms-oauth.php';?>"></opttext>
	</div>


	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Referrer</h2>
			<span class="chimpmate_hint headhint" data-hint="Only a visitor from those selected website categories, can view the Lightbox/Slider"></span>
			<span class="opt_notice">* RECOMMENDED FOR ADVANCED USERS ONLY :<br> If enabled, the lightbox/slider will not appear if website is accessed directly</span>
		</div>
		<optchk ng-model="data.searchengine" optlab="Search Engine"></optchk>
	</div>
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>User Sync</h2>
			<span class="chimpmate_hint headhint" data-hint="Sync users from Website"></span>
		</div>
		<optchk ng-model="data.usyn_com" optlab="Comment based Sync"></optchk>
		<optsel ng-head="data.usyn_coml.name" opthead="'Not Selected'" ng-list="data.lists" ng-model="data.usyn_coml" optclk="v" optlname="v.name" optlab="List"></optsel>
		<div class="chimpmate_group chimpmate_cb p2" ng-show="data.usyn_com == 1">
			<label><input type="radio" value="1" ng-model="data.usyn_comp">
					With User's permission</label>
			<span class="chimpmate_hint" data-hint="Insert Checkbox near the Comment box"></span>
		</div>
		<opttext ng-model="data.usyn_compt" optlab="Permission Text" optclass="p3" opthint="Text for Checkbox" ng-show="data.usyn_com == 1"></opttext>

		<div class="chimpmate_group chimpmate_cb p2" ng-show="data.usyn_com == 1">
			<label><input type="radio" value="0" ng-model="data.usyn_comp">
					Without User's permission</label>
			<span class="chimpmate_hint" data-hint="Add to list directly"></span>
		</div>
		<div class="chimpmate_group chimpmate_proggroup">
			<button class="chimpmate_button green material-design chimpmate_usync" ng-click="usync.process(1,data.usyn_coml)">Sync Existing</button>
			<span class="chimpmate_hint" data-hint="Sync currently commented users to list"></span>
		</div>
		<optchk ng-model="data.usyn_reg" optlab="Registration based Sync"></optchk>
		<optsel ng-head="data.usyn_regl.name" opthead="'Not Selected'" ng-list="data.lists" ng-model="data.usyn_regl" optclk="v" optlname="v.name" optlab="List"></optsel>
		<div class="chimpmate_group chimpmate_cb p2" ng-show="data.usyn_reg == 1">
			<label><input type="radio" value="1" ng-model="data.usyn_regp">
					With User's permission</label>
			<span class="chimpmate_hint" data-hint="Insert Checkbox near the Sign-up box"></span>
		</div>
		<opttext ng-model="data.usyn_regpt" optlab="Permission Text" optclass="p3" opthint="Text for Checkbox" ng-show="data.usyn_reg == 1"></opttext>
		<div class="chimpmate_group chimpmate_cb p2" ng-show="data.usyn_reg == 1">
			<label><input type="radio" value="0" ng-model="data.usyn_regp">
					Without User's permission</label>
			<span class="chimpmate_hint" data-hint="Add to list directly"></span>
		</div>
		<div class="chimpmate_group p2">
			<select id="usync_role" ng-model="data.usync_role" ng-multi-select multiple="multiple" ng-multi-select-placeholder="User roles" ng-multi-select-filter="false" ng-multi-select-width="300px">
	<?php
	global $wp_roles;
	$all_roles = $wp_roles->roles;
	foreach ($all_roles as $key => $value) {
	echo '<option value="'.$key.'">'.$value['name'].'</option>';
	} ?>
		</select>
		</div>
		<div class="chimpmate_group chimpmate_proggroup">
			<button class="chimpmate_button green material-design usyn_reg" ng-click="usync.process(2,data.usyn_regl,data.usync_role)">Sync Existing</button>
			<span class="chimpmate_hint" data-hint="Sync currently registered users to list"></span>
		</div>
	</div>

	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>News and Updates</h2>
			<span class="chimpmate_hint headhint" data-hint="Get Product Update &amp; News. It's secure and spam free..."></span>
		</div>
		<optchk ng-model="data.get_email_update" optlab="Get Email Updates"></optchk>
		<opttext ng-model="data.email_update" optlab="Email Address"></opttext>
	</div>
</div>