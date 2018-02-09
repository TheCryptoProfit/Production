<div id="theme" class="chimpmate_box">
	<div class="chimpmate_item">
		<div class="itemhead">
			<h2>Themes</h2>
		</div>
		<div class="chimpmate_group chimpmate_table_cont">
			<div class="chimpmate_table chimpmate_tableth">
				<div class="chimpmate_tablehg">
					<div class="chimpmate_tabler">
						<div>No</div>
						<div>Theme</div>
						<div>
							<div class="optstab">
								<input type="text" class="chimpmate_tabtext" required ng-model="tnav.q" placeholder="Search">
								<div class="bar"></div>
							</div>
						</div>
						<div></div>
					</div>
				</div>
				<div class="chimpmate_tablerg">
					<div class="chimpmate_tabler" ng-repeat="th in data.themes | filter:tnav.q | startFrom:tnav.cp*tnav.ps | limitTo:tnav.ps">
						<div>{{tnav.cp*tnav.ps + $index + 1}}</div>
						<div>
							{{th.name}}
						</div>
						<div>
							<div class="mul_ico mul_edit" ng-click="theme_ctrl.edit(th.id)"></div>
						</div>
						<div></div>
					</div>
				</div>
			</div>
			<div class="chimpmate_table_foot">
				<div class="chimpmate_paginate">
					<div class="chimpmate_pagea chimpmate_pager" ng-click="tnav.next()"></div>
					<div class="chimpmate_paget">{{"Page "+(tnav.cp+1)+" of "+tnav.tp()}}</div>
					<div class="chimpmate_pagea chimpmate_pagel" ng-click="tnav.prev()"></div>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
	<div class="chimpmate_edit"></div>
	<div ng-if="theme_ctrl.esel > -1" ng-include="theme_ctrl.econ"></div>
</div>