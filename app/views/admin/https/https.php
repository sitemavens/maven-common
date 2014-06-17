<form role="form"  name="formHttps" ng-submit="saveHttps(https)">

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">General</div>
					<div class="panel-body">

						<div class="alert alert-danger" ng-if="formHttps.$invalid||httpsObj.error">
							<span ng-show="formHttps.$error.invalid">Invalid elements</span>
							<span ng-show="httpsObj.error">An error has occurred. Settings were not saved.</span>
						</div>
						<div class="alert alert-success" ng-if="!httpsObj.error&&httpsObj.updated">
							<span ng-show="httpsObj.updated">Settings were updated succesfully.</span>
						</div>
						
						<p>
							Please select which pages should be used as secure using https protocol on them.
						</p>
						
						<div class="form-group" ng-repeat="page in httpsObj.pageList">
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