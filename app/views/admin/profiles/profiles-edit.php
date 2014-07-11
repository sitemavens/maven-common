<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "DefaultRole", $cachedDefaultRole ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedAddresses", $cachedAddresses ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedCountries", $cachedCountries ); ?>
<h1>Profiles </h1>
<div class="form-group">
	<ng-form name="profileForm">
		<tabset>
			<tab heading="Personal Info">

				<div class="form-horizontal profile profile-edition">
					<ng-form name="profileStepOneForm">
						<div class="form-group">
							<label for="salutation" class="col-sm-2 control-label">Salutation</label>
							<div class="col-sm-10">
								<select class="form-control" name="profileSalutation" ng-model="profile.salutation"
										ng-options="salutation.id as salutation.value for salutation in salutations" id="addressSelect"></select>
							</div>
						</div>
						<div class="form-group">
							<label for="fname" class="col-sm-2 control-label">First Name</label>
							<div class="col-sm-10">
								<input type="text" class="form-control"   id="fname" ng-model="profile.firstName" placeholder="">
							</div>
						</div>
						<div class="form-group">
							<label for="lname" class="col-sm-2 control-label">Last Name</label>
							<div class="col-sm-10">
								<input type="text" class="form-control"   id="lname" ng-model="profile.lastName" placeholder="">
							</div>
						</div>
						<div class="form-group" show-errors>
							<label for="email" class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" required name="profileEmail" id="email" ng-model="profile.email" placeholder="">
								<p class="help-block" ng-if="profileForm.profileStepOneForm.profileEmail.$error.required">The profile's email is required</p>
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-sm-2 control-label">Phone</label>
							<div class="col-sm-10">
								<input type="tel" class="form-control"  id="phone" ng-model="profile.phone" placeholder="">
							</div>
						</div>
						<div class="form-group">
							<label for="company" class="col-sm-2 control-label">Company</label>
							<div class="col-sm-10">
								<input type="text" class="form-control"   id="company" ng-model="profile.company" placeholder="">
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
								<label class="btn btn-success" ng-model="profile.wholesale" btn-radio="true" >Yes</label>
								<label class="btn btn-default" ng-model="profile.wholesale" btn-radio="false" >No</label>
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
								<input type="date" class="form-control" id="creationOn" ng-model="profile.createdOn" placeholder="" ng-disabled="true">
							</div>
						</div>
						<div class="form-group">
							<label for="lastUpdate" class="col-sm-2 control-label">Last Update</label>
							<div class="col-sm-10">
								<input type="date" class="form-control" id="lastUpdate" ng-model="profile.lastUpdate" placeholder="" ng-disabled="true">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button ng-click="saveProfile()" class="btn btn-primary">Save</button>
								<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</ng-form>
				</div>
			</tab>

		<!--<tab heading="Photo"></tab>-->

			<tab heading="Address">
				<div class="form-horizontal profile profile-edition">
					<ng-form name="profileStepTwoForm">
						<div class="form-group">
							<label for="addressSelect" class="col-sm-2 control-label">Address</label>
							<div class="col-sm-5 nopadding">
								<select class="form-control" ng-model="newAddress.type"
										ng-options="Address.id as Address.name for Address in addresses" id="addressSelect" />
							</div>
							<div class="col-sm-1">
								<button ng-click="addAddress(newAddress)" class="btn btn-primary">Add Address</button>
							</div>
							<div class="col-sm-4" ng-show="addressExists.status">
								<alert type="danger">You already have a {{addressExists.name}} address</alert>	
							</div>
							<div class="col-sm-4" ng-show="alertPrimaryAddress">
								<alert type="danger">You must have a primary address</alert>	
							</div>
						</div>
				</div>
				<div class="form-group">
					<accordion close-others="oneAtATime">
						<accordion-group is-open="status.isFirstOpen" 
										 is-disabled="status.isFirstDisabled" ng-repeat="address in profile.addresses track by address.type"> 
							<accordion-heading>
								<span class="glyphicon glyphicon-home" > {{getAddressTypeName(address.type)}}</span>
								<span ng-show="address.firstLine"> - {{address.firstLine}} </span>
								<span ng-show="address.city">- {{address.city}}</span>
								<span ng-show="address.state"> - {{address.state}}</span>
								<span ng-show="address.country"> - {{address.country}}</span>
								<button id="delete-address" class="btn btn-info btn-xs profile-edit-address" ng-click="deleteAddress($index, $event)">Delete</button>	
							</accordion-heading>

							<div class="form-group">
								<label for="addressTypeEdit" class="col-sm-2 control-label">Type</label>
								<div class="col-sm-10">
									<select class="form-control" name="profileType" ng-model="address.type"
											ng-options="addressType.id as addressType.name for addressType in addresses" id="addressTypeEdit"></select>
								</div>
							</div>
							<div class="form-group">
								<label for="addressIsPrimary" class="col-sm-2 control-label">Primary Address</label>
								<div class="col-sm-10">
									<label class="btn btn-success" ng-change="changeToPrimaryAddress(address)" ng-model="address.primary" btn-radio="true" uncheckable>Yes</label>
									<label class="btn btn-success" ng-change="changeToPrimaryAddress(address)" ng-model="address.primary" btn-radio="false" uncheckable>No</label>
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
											ng-options="countryI as country.name for (countryI, country) in countries" id="addressSelect"></select>
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
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button ng-click="saveProfile()" class="btn btn-primary">Save</button>
							<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
						</div>
					</div>
					</ng-form>
				</div>
			</tab>

			<tab heading="WP User">
				<div class="form-horizontal profile profile-edition">
					<div class="form-group">
						<div class="col-sm-1"></div>
						<div class="col-sm-6">
							<alert type="info">
								<p>
									<span>A Wordpress user means that the user will be able to log in to your system</span></br>
									<span>It will be associated with the <strong>"{{defaultRole.name}}"</strong> role.</span>
								</p>
							</alert>	
						</div>
					</div>
					<div class="form-group" ng-show="profile.isWpUser">
						<label for="" class="col-sm-2 control-label"></label>
						<div class="col-sm-4">
							<span>This Profile is already associated with a Wordpress User.</span>
						</div>
					</div>
					<div class="form-group" ng-hide="profile.isWpUser">
						<label for="" class="col-sm-2 control-label">Register</label>
						<div class="col-sm-4">
							<label class="btn btn-success" ng-model="profile.register" btn-radio="true">Yes</label>
							<label class="btn btn-default" ng-model="profile.register" btn-radio="false">No</label>
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-2 control-label">WP Username: </label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="" ng-model="profile.userName" placeholder="" ng-disabled="profile.isWpUser">
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-2 control-label">Password: </label>
						<div class="col-sm-4">
							<input type="password" class="form-control" id="" ng-model="profile.password" placeholder="">
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-2 control-label">Confirm Password: </label>
						<div class="col-sm-4">
							<input type="password" class="form-control" id="" ng-model="profile.confirmPassword" placeholder="">
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

			<tab heading="Roles">
				<div class="form-horizontal profile profile-edition">
					<div class="col-sm-6" ng-hide="profile.register || profile.isWpUser">
						<alert type="info">
							<p>
								<span>The profile need to be asociated with a Worpress User to asign roles.</span></br>
							</p>
						</alert>	
					</div>
					<div class="form-group" ng-show="profile.register || profile.isWpUser" ng-repeat="rol in listOfRoles">
						<label for="" class="col-sm-2 control-label">{{rol.name}}: </label>
						<div class="col-sm-4">
							<input type="checkbox" class="form-control" id="" ng-model="rol.status" ng-click="selectRol(rol.id, $index)">
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
			<tab heading="Orders">
				<div class="form-horizontal profile profile-edition">
					<thead>
						<tr>
							<th>Status</th>
							<th>Number</th>
							<th>Date</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="order in profile.orders">
							<td>
								<img ng-src="{{order.status.imageUrl}}" />{{order.status.name}}
							</td>
							<td>
								{{order.number}}
							</td>
							<td>
								{{order.orderDate}}
							</td>
							<td>
								{{order.total|currency}}
							</td>
						</tr>
					</tbody>
				</div>
			</tab>

		</tabset>
	</ng-form>
</div>
