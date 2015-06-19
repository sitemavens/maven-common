<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSections', $cachedSections ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedTypes', $cachedTypes ); ?>

<h2>Promotions </h2>
<div class="form-horizontal">
	<ng-form name="promotionForm">
		<div class="form-group">
			<label for="enabled" class="col-sm-2 control-label">Enabled</label>
			<div class="col-sm-6">
				<input type="checkbox" id="enabled" ng-model="promotion.enabled" />
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="section" class="col-sm-2 control-label">Section</label>
			<div class="col-sm-6">
				<select class="form-control" required name="promotionSection" ng-model="promotion.section" ng-options="sectionIndex as section.name for (sectionIndex,section) in sections" id="section"></select>
				<p class="help-block" ng-if="promotionForm.promotionSection.$error.required">The promotion's section is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="name" class="col-sm-2 control-label">Name</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" required id="name" name="promotionName" ng-model="promotion.name" />
				<p class="help-block" ng-if="promotionForm.promotionName.$error.required">The promotion's name is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="code" class="col-sm-2 control-label">Code</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" required name="promotionCode" id="code" ng-model="promotion.code" />
				<p class="help-block" ng-if="promotionForm.promotionCode.$error.required">The promotion's code is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="type" class="col-sm-2 control-label">Type</label>
			<div class="col-sm-6">
				<select class="form-control" required name="promotionType" ng-model="promotion.type" ng-change="selectedType(template)"
						ng-options="typeIndex as type.name for (typeIndex,type) in types" id="type" ></select>
						<p class="help-block" ng-if="promotionForm.promotionType.$error.required">The promotion's type is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="value" class="col-sm-2 control-label">Value</label>
			<div class="col-sm-6">
				<input type="text" only-digits class="form-control" required name="promotionValue" id="value" ng-model="promotion.value" />
				<p class="help-block" ng-if="promotionForm.promotionValue.$error.required">The promotion's value is required</p>
			</div>
		</div>
		<div class="form-group">
			<label for="limitOfUse" class="col-sm-2 control-label">Limit of Use</label>
			<div class="col-sm-6">
				<input type="text" only-digits class="form-control" id="limitOfUse" ng-model="promotion.limitOfUse" />
			</div>
		</div>
		<div class="form-group">
			<label for="uses" class="col-sm-2 control-label">Uses</label>
			<div class="col-sm-6">
				<input type="text" readonly class="form-control" id="uses" ng-model="promotion.uses" />
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<p class="input-group">
					<span class="input-group-btn">

					</span>
				</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="from" class="col-sm-2 control-label">From</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" required name="promotionFrom" datepicker-popup="yyyy/MM/dd" ng-model="promotion.from" is-open="openFrom" close-text="Close" ng-click="openDatePicker($event, 'from')" />
				<p class="help-block" ng-if="promotionForm.promotionFrom.$error.required">The promotion's start date is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="to" class="col-sm-2 control-label">To</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" required name="promotionTo" datepicker-popup="yyyy/MM/dd" ng-model="promotion.to" is-open="openTo" close-text="Close" ng-click="openDatePicker($event, 'to')" />
				<p class="help-block" ng-if="promotionForm.promotionTo.$error.required">The promotion's end date is required</p>
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Description</label>
			<div class="col-sm-6">
				<textarea class="form-control" id="description" ng-model="promotion.description" />
			</div>
		</div>
		<div class="form-group promo-rules" ng-if="promotion.section === 'item'">
			<a for="description" class="col-sm-2 control-label" style="cursor:pointer;" ng-click="addRule()">Add Rules</a>
			<div class="col-sm-6">
				<div ng-repeat="promoRule in rules" ng-form="ruleForm" required>
					<select required class="col-sm-4" ng-model="promoRule.rule" ng-options="rule.value as rule.label for rule in promotionRules"></select>
					<select required class="col-sm-4" ng-model="promoRule.condition" ng-options="condition.value as condition.label for condition in promotionConditions"></select>
					<input required class="col-sm-3" ng-model="promoRule.value"/>
					<span ng-hide="rol.systemRole" class="trash col-sm-1">
						<a ng-click="removeRule($index)" class="list-view delete">Remove</a>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button ng-click="savePromotion()" class="btn btn-primary">Save</button>
				<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
			</div>
		</div>
	</ng-form>
</div>