<h1>Promotions </h1>
<div class="form-horizontal">
	<div class="form-group">
		<label for="enabled" class="col-sm-2 control-label">Enabled</label>
		<div class="col-sm-10">
			<input type="checkbox" class="form-control" id="enabled" ng-model="promotion.enabled" />
		</div>
	</div>
	<div class="form-group">
		<label for="section" class="col-sm-2 control-label">Section</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="section" ng-model="promotion.section" />
		</div>
	</div>
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" ng-model="promotion.name" />
		</div>
	</div>
	<div class="form-group">
		<label for="code" class="col-sm-2 control-label">Code</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="code" ng-model="promotion.code" />
		</div>
	</div>
	<div class="form-group">
		<label for="type" class="col-sm-2 control-label">Type</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="type" ng-model="promotion.type" />
		</div>
	</div>
	<div class="form-group">
		<label for="value" class="col-sm-2 control-label">Value</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="value" ng-model="promotion.value" />
		</div>
	</div>
	<div class="form-group">
		<label for="limitOfUse" class="col-sm-2 control-label">Limit of Use</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="limitOfUse" ng-model="promotion.limitOfUse" />
		</div>
	</div>
	<div class="form-group">
		<label for="uses" class="col-sm-2 control-label">Uses</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="uses" ng-model="promotion.uses" />
		</div>
	</div>
	<div class="form-group">
		<label for="from" class="col-sm-2 control-label">From</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="from" ng-model="promotion.from" />
		</div>
	</div>
	<div class="form-group">
		<label for="to" class="col-sm-2 control-label">To</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="to" ng-model="promotion.to" />
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Description</label>
		<div class="col-sm-10">
			<textarea class="form-control" id="description" ng-model="promotion.description" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button ng-click="savePromotion()" class="btn btn-primary">Save</button>
			<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
		</div>
	</div>
</div>