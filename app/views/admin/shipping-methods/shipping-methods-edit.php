<h2>Shipping method </h2>

<div class="form-horizontal">
	<ng-form name="entityForm">
		<div ng-controller="OrderAmountTiersController">
			<!--{{prueba}}-->
		</div>
		
		<div class="form-group" show-errors>
			<label for="name" class="col-sm-2 control-label">Name</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" required name="name" id="name" ng-model="item.name" placeholder="">
				<p class="help-block" ng-if="entityForm.name.$error.required">The name is required</p>
			</div>
		</div>
		<div class="form-group" show-errors>
			<label for="methodType" class="col-sm-2 control-label">Type</label>
			<div class="col-sm-10">
				<input type="text" only-digits class="form-control" required name="methodType" id="type" ng-model="item.methodType" placeholder="">
				<p class="help-block" ng-if="entityForm.methodType.$error.required">The Type is required</p>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button ng-click="save()" class="btn btn-primary">Save</button>
				<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
			</div>
		</div>
	</ng-form>
</div>