<h1>Attributes </h1>
<div class="form-horizontal">
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" ng-model="attr.name" placeholder="">
		</div>
	</div>
    	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Default Price</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="defaultAmount" ng-model="attr.defaultAmount" placeholder="">
		</div>
	</div>
    	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Default Wholesale Price</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="defaultWholesaleAmount" ng-model="attr.defaultWholesaleAmount" placeholder="">
		</div>
	</div>
    	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Description</label>
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
</div>