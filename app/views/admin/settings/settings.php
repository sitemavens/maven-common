<form role="form"  name="formSettings" ng-submit="saveSettings(settings)">
	<tabSet ng-show="setting">
		<tab heading="General">
 
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
									<textarea required  rows="5" class="form-control" ng-model="setting.signature" name="signature" ></textarea>
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
				</div>
			</div>

		</tab>
		<tab heading="eCommerce">
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Order</div>
					<div class="panel-body">
						<div class="form-group"  >
							<label for="">Order Handling Fee: $</label>
							<input type="text" ng-model="setting.orderHandlingFee" /> 
						</div>
						<div class="form-group"  >
							<label for="">Next Order Number:</label>
							<input type="text"  /> 
							<p class="help-block">Set the next order number to sync with your accounting systems.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Pricing</div>
					<div class="panel-body">
						<div class="form-group"  >
							<label for="">Currency:</label>
							<?php 
							$countries = \Maven\Core\CountriesApi::getAllCountries( false );
							\Maven\Core\UI\HtmlComponent::jSonVar('CountriesCached', $countries); 
							?>
							<select  required ng-model="setting.currencyCountry" class="form-control" ng-options="countryI as country.name + ' ' + '(' + country.currency.symbol + ')' for (countryI,country) in countries" ></select>
						</div>
						<div class="form-group"  >
							<label for="">Display Format:</label>
							<?php 
							$currencyFormats = \Maven\Core\CurrencyApi::getCurrencyFormats( );
							\Maven\Core\UI\HtmlComponent::jSonVar('CurrencyFormatsCached', $currencyFormats); 
							?>
							<select  required ng-model="setting.currencyDisplayFormat" class="form-control" ng-options="currencyI as currency.example for (currencyI, currency) in currencyFormats" ></select>
						</div>
						<div class="form-group"  >
							<label for="">Thousand Separator:</label>
							<input type="text" ng-model="setting.currencyThousandSeparator" /> 
						</div>
						<div class="form-group"  >
							<label for="">Decimal Separator:</label>
							<input type="text" ng-model="setting.currencyDecimalSeparator" /> 
						</div>
						<div class="form-group"  >
							<label for=""># of Decimal Digits:</label>
							<input type="text" ng-model="setting.currencyDecimalDigits" /> 
						</div>
					</div>
				</div>
			</div>
		</tab>
		<tab heading="Gateways">
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">General</div>
					<div class="panel-body">
						<div class="form-group"  >
							<label for="">Active Gateway</label>
							<select ng-model="setting.activeGateway"  ng-options="gate.key as gate.name for gate in gateways"></select>
						</div>
						<div class="form-group"  >
							<label for="">Is testing mode</label>
							<input type="checkbox" ng-model="setting.gatewayTestingMode" />
						</div>
					</div>
				</div>

				
			</div>
			<div class="col-md-4">
				<div class="panel panel-default" ng-repeat="gate in gateways| filter:{hasSettings:true}">
					<div class="panel-heading" ng-bind="gate.name"></div>
					<div class="panel-body">
						<div class="form-group" ng-repeat="gSetting in gate.settings" ng-class="{'has-error':innerForm.theInput.$error.required}">
							<ng-form name="innerForm" >

								<label for="{{gSetting.name}}" ng-bind="gSetting.label"></label>			
								<div ng-switch on="gSetting.type">
									<input ng-switch-when="password" ng-required="{{gSetting.required}}" type="{{gSetting.type}}" class="form-control" ng-model="gSetting.value" name="theInput" />
									<input ng-switch-when="input" ng-required="{{gSetting.required}}" type="{{gSetting.type}}" class="form-control" ng-model="gSetting.value" name="theInput" />
									<select ng-switch-when="dropdown" ng-required="{{gSetting.required}}"  class="form-control" ng-model="gSetting.value" name="theInput" ng-options="gOption.id as gOption.name for gOption in gSetting.options" />
								</div>
								<div ng-show="innerForm.theInput.$dirty && formSettings.theInput.$invalid">
									<span class="error" ng-show="innerForm.theInput.$error.required">The field is required.</span>
								</div>
							</ng-form>																											
						</div>
					</div>
				</div>
			</div>
		</tab>
	</tabSet>

	<div class="row">
		<div class="col-md-1">
			<button type="submit" ng-disabled="formSettings.$invalid" ng-click="saveSettings" class="btn btn-primary">Submit</button>
		</div>
	</div>
</form>