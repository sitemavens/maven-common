<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedCountries", $cachedCountries ); ?>
<h2>Taxes </h2>
<div class="form-horizontal">
	<ng-form name="taxesForm">
		<div class="form-group">
			<label for="enabled" class="col-sm-2 control-label">Enabled</label>
			<div class="col-sm-10">
				<input type="checkbox" id="enabled" ng-model="tax.enabled">
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="name" class="col-sm-2 control-label">Name</label>
			<div class="col-sm-10">
				<input type="text" required name="taxName" class="form-control" id="name" ng-model="tax.name" placeholder="Tax Name">
				<p class="help-block" ng-if="taxesForm.taxName.$error.required">The tax's name is required</p>
			</div>
		</div>
		<div class="form-group"  show-errors>
			<label for="country" class="col-sm-2 control-label">Country</label>
			<div class="col-sm-10">
				<select class="form-control" required name="taxCountry" ng-model="tax.country"
						ng-options="countryI as country.name for (countryI, country) in countries" id="addressSelect"></select>
				<p class="help-block" ng-if="taxesForm.taxCountry.$error.required">The tax's country is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="state" class="col-sm-2 control-label">State</label>
			<div class="col-sm-10">
				<input type="text" required name="taxState" class="form-control" id="state" ng-model="tax.state">
				<p class="help-block" ng-if="taxesForm.taxState.$error.required">The tax's country is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="value" class="col-sm-2 control-label">Value</label>
			<div class="col-sm-10">
				<input type="text" only-digits required name="taxValue" class="form-control" id="value" ng-model="tax.value">
				<p class="help-block" ng-if="taxesForm.taxValue.$error.required">The tax's value is required</p>
			</div>
		</div>
		<div class="form-group">
			<label for="forShipping" class="col-sm-2 control-label">For shipping</label>
			<div class="col-sm-10">
				<input type="checkbox" id="value" ng-model="tax.forShipping">
			</div>
		</div>
		<div class="form-group">
			<label for="compound" class="col-sm-2 control-label">Compound</label>
			<div class="col-sm-10">
				<input type="checkbox" id="compound" ng-model="tax.compound">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button ng-click="saveTax()" class="btn btn-primary">Save</button>
				<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
			</div>
		</div>
	</ng-form>
</div>