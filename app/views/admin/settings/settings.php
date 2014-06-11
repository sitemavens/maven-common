<?php \Maven\Core\UI\HtmlComponent::jSonComponent('SettingsCached', $settingsCached); ?>

<form role="form"  name="formSettings" ng-submit="saveSettings(settings)">

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">General</div>
					<div class="panel-body">

						<div class="alert alert-danger" ng-show="formSettings.$invalid">
							<span ng-show="formSettings.$error.required">Required elements</span>
							<span ng-show="formSettings.$error.invalid">Invalid elements</span>
						</div>

						<div class="form-group"  >
							<label for="exceptionNotification">Send error notification to</label>						
							<input required type="text" class="form-control" ng-model="setting.exceptionNotification" name="exceptionNotification" />
						</div>
						<div class="form-group"  >
							<label for="organizationName">Organization Name</label>						
							<input required type="text" class="form-control" ng-model="setting.organizationName" name="organizationName" />
						</div>
						<div class="form-group"  >
							<label for="signature">Signature</label>						
							<input required type="text" class="form-control" ng-model="setting.signature" name="signature" />
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">

				<div class="panel panel-default">
					<div class="panel-heading">Email</div>
					<div class="panel-body">
						<div class="alert alert-danger" ng-show="formSettings.$invalid">
							<span ng-show="formSettings.$error.required">Required elements</span>
							<span ng-show="formSettings.$error.invalid">Invalid elements</span>
						</div>

						<div class="form-group"  >
							<label for="senderEmail">Sender Email</label>						
							<input required type="text" class="form-control" ng-model="setting.senderEmail" name="senderEmail" />
						</div>
						<div class="form-group"  >
							<label for="senderName">Sender Name</label>						
							<input required type="text" class="form-control" ng-model="setting.senderName" name="senderName" />
						</div>
						<div class="form-group"  >
							<label for="contactEmail">Contact Email</label>						
							<input required type="text" class="form-control" ng-model="setting.contactEmail" name="contactEmail" />
						</div>
						<div class="form-group"  >
							<label for="bccNotificationsTo">BCC Notifications To</label>						
							<input required type="text" class="form-control" ng-model="setting.bccNotificationsTo" name="bccNotificationsTo" />
						</div>
						<div class="form-group"  >
							<label for="organizationLogo">Organization Logo</label>						
							<input required type="text" class="form-control" ng-model="setting.organizationLogo" name="organizationLogo" />
						</div>
					</div>
				</div>
				
			</div>

			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Gateways</div>
					<div class="panel-body">
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="row">
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Gateways</div>
					<div class="panel-body">
					</div>
				</div>
			</div>
		</div> -->	
		<div class="row">
			<div class="col-md-4">
				<button type="submit" ng-disabled="formSettings.$invalid" ng-click="saveSettings" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</div>


</form>