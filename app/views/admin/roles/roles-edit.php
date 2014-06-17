<h1>Roles </h1>
<div class="form-horizontal">
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" ng-model="rol.name" placeholder="Rol Name">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button ng-click="saveRol()" class="btn btn-primary">Save</button>
			<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
		</div>
	</div>
</div>