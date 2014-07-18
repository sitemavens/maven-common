<h2>Roles </h2>
<div class="form-horizontal">
	<ng-form name="rolesForm">
		<div class="form-group" show-errors>
			<label for="name" class="col-sm-2 control-label">Name</label>
			<div class="col-sm-6">
				<input type="input" required class="form-control" id="name" name="roleName" ng-model="rol.name" placeholder="Rol Name">
				<p class="help-block" ng-if="rolesForm.roleName.$error.required">The role's name is required</p>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button ng-click="saveRol()" class="btn btn-primary">Save</button>
				<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
			</div>
		</div>
	</ng-form>
</div>