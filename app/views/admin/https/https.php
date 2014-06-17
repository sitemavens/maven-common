<form role="form"  name="formHttps" ng-submit="saveHttps(https)">

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">General</div>
					<div class="panel-body">

						<div class="alert alert-danger" ng-show="formHttps.$invalid">
							<span ng-show="formHttps.$error.invalid">Invalid elements</span>
						</div>
						
						<div class="form-group" ng-repeat="page in pageList">
							<div class="checkbox">
								<label for="{{page.id}}">
									{{page.title}} <input type="checkbox" id="{{page.id}}" class="form-control" name="httpsPages[]" ng-checked="page.https" ng-model="page.https" />
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<button type="submit" ng-disabled="formHttps.$invalid" ng-click="saveHttps" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</div>


</form>