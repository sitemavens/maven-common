<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedAddresses", $cachedAddresses ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedCountries", $cachedCountries ); ?>
<h1>Profiles </h1>
<div class="form-group">
	<tabset>
		<tab heading="Personal Info">
			<div class="form-horizontal profile">
				<div class="form-group">
					<label for="salutation" class="col-sm-2 control-label">Salutation</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="salutation" ng-model="profile.salutation" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="fname" class="col-sm-2 control-label">First Name</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="fname" ng-model="profile.firstName" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="lname" class="col-sm-2 control-label">Last Name</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="lname" ng-model="profile.lastName" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-sm-2 control-label">Email</label>
					<div class="col-sm-10">
						<input type="email" class="form-control" id="email" ng-model="profile.email" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="phone" class="col-sm-2 control-label">Phone</label>
					<div class="col-sm-10">
						<input type="tel" class="form-control" id="phone" ng-model="profile.phone" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="company" class="col-sm-2 control-label">Company</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="company" ng-model="profile.company" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="company" class="col-sm-2 control-label">Notes</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="company" ng-model="profile.notes" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="wholesale" class="col-sm-2 control-label">Wholesale</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="wholesale" ng-model="profile.wholesale" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="adminNotes" class="col-sm-2 control-label">Admin Notes</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="adminNotes" ng-model="profile.adminNotes" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="creationOn" class="col-sm-2 control-label">Creation Date</label>
					<div class="col-sm-10">
						<input type="date" class="form-control" id="creationOn" ng-model="profile.createdOn" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<label for="lastUpdate" class="col-sm-2 control-label">Last Update</label>
					<div class="col-sm-10">
						<input type="date" class="form-control" id="lastUpdate" ng-model="profile.lastUpdate" placeholder="">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button ng-click="saveProfile()" class="btn btn-primary">Save</button>
						<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
					</div>
				</div>
			</div>
		</tab>

		<tab heading="Photo">Static content</tab>

		<tab heading="Address">
			<div class="form-horizontal profile">

				<div class="form-group">
					<label for="addressSelect" class="col-sm-2 control-label">Address</label>
					<div class="col-sm-6">
						<select class="form-control" ng-model="newAddress.type" ng-change="addAddress(newAddress)"
							ng-options="Address.id as Address.name for Address in addresses" id="addressSelect" />
					</div>
				</div>

			</div>
			<div class="form-group">
				<accordion close-others="oneAtATime">
					<accordion-group is-open="status.isFirstOpen" 
							 is-disabled="status.isFirstDisabled" ng-repeat="address in profile.addresses track by address.type"> 
						<accordion-heading>
							<span class="glyphicon glyphicon-home" >{{getAddressTypeName(address.type)}}</span>
							<span ng-show="address.firstLine"> - {{address.firstLine}} </span>
							<span ng-show="address.city">- {{address.city}}</span>
							<span ng-show="address.state"> - {{address.state}}</span>
							<span ng-show="address.country"> - {{address.country}}</span>
						</accordion-heading>
						<div class="form-group">
							<label for="addressTypeEdit" class="col-sm-2 control-label">Type</label>
							<div class="col-sm-10">
								<select class="form-control" ng-model="address.type"
									ng-options="addressType.id as addressType.name for addressType in addresses" id="addressTypeEdit" />
							</div>
						</div>
						<div class="form-group">
							<label for="addressIsPrimary" class="col-sm-2 control-label">Primary Address</label>
							<div class="col-sm-10">
								<label class="btn btn-success" ng-model="address.primary" btn-radio="'true'" uncheckable>Yes</label>
								<label class="btn btn-success" ng-model="address.primary" btn-radio="'false'" uncheckable>No</label>
							</div>
						</div>
						<div class="form-group">
							<label for="addressNameEdit" class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressNameEdit" ng-model="address.name" placeholder="">
							</div>
						</div>
						<div class="form-group">
							<label for="addressAddressEdit" class="col-sm-2 control-label">Address</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressAddressEdit" ng-model="address.firstLine" placeholder="">
							</div>
						</div>
						<div class="form-group">
							<label for="addressAddressSecondaryEdit" class="col-sm-2 control-label">Address 2</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressAddressSecondaryEdit" ng-model="address.secondLine" placeholder="">
							</div>
						</div>
						<div class="form-group">
							<label for="addressNeighborhoodEdit" class="col-sm-2 control-label">Neighborhood</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressNeighborhoodEdit" ng-model="address.neighborhood" placeholder="">
							</div>
						</div>
						<div class="form-group">
							<label for="addressCityEdit" class="col-sm-2 control-label">City</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressCityEdit" ng-model="address.city" placeholder="">
							</div>
						</div>	
						<div class="form-group">
							<label for="addressStateEdit" class="col-sm-2 control-label">State</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressStateEdit" ng-model="address.state" placeholder="">
							</div>
						</div>	
						<div class="form-group">
							<label for="addressCountryEdit" class="col-sm-2 control-label">Country</label>
							<div class="col-sm-10">
								<select class="form-control" ng-model="address.country"
									ng-options="countryI as country.name for (countryI, country) in countries" id="addressSelect" />
							</div>
						</div>	
						<div class="form-group">
							<label for="addressZIPEdit" class="col-sm-2 control-label">ZIP</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressZIPEdit" ng-model="address.zipcode" placeholder="">
							</div>
						</div>	
						<div class="form-group">
							<label for="addressNotesEdit" class="col-sm-2 control-label">Notes</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressNotesEdit" ng-model="address.notes" placeholder="">
							</div>
						</div>	
						<div class="form-group">
							<label for="addressPhoneEdit" class="col-sm-2 control-label">Phone</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressPhoneEdit" ng-model="address.phone" placeholder="">
							</div>
						</div>	
						<div class="form-group">
							<label for="addressalternativePhoneEdit" class="col-sm-2 control-label">Alternative Phone</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="addressalternativePhoneEdit" ng-model="address.phoneAlternative" placeholder="">
							</div>
						</div>
					</accordion-group>

				</accordion>
			</div>
		</tab>

		<tab heading="WP User">Static content</tab>  

		<tab heading="Roles">Static content</tab>

	</tabset>
</div>
