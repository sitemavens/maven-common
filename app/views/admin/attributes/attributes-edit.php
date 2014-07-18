<h2>Attributes </h2>
<div class="form-horizontal">
	<ng-form name="attributesForm">
		<div class="form-group" show-errors>
			<label for="name" class="col-sm-2 control-label">Name</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" required name="attrName" id="name" ng-model="attr.name" placeholder="">
				<p class="help-block" ng-if="attributesForm.attrName.$error.required">The attribute's name is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="defaultAmount" class="col-sm-2 control-label">Default Price</label>
			<div class="col-sm-10">
				<input type="text" only-digits class="form-control" required name="attrDefaultPrice" id="defaultAmount" ng-model="attr.defaultAmount" placeholder="">
				<p class="help-block" ng-if="attributesForm.attrDefaultPrice.$error.required">The attribute's default price is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="defaultWholesaleAmount" class="col-sm-2 control-label">Default Wholesale Price</label>
			<div class="col-sm-10">
				<input type="text" only-digits class="form-control" required name="attrDefaultWholesalePrice" id="defaultWholesaleAmount" ng-model="attr.defaultWholesaleAmount" placeholder="">
				<p class="help-block" ng-if="attributesForm.attrDefaultWholesalePrice.$error.required">The attribute's default wholesale price is required</p>
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Description</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="description" ng-model="attr.description" placeholder="">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button ng-click="saveAttr()" class="btn btn-primary">Save</button>
				<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
			</div>
		</div>
	</ng-form>
</div>