<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "DefaultRole", $cachedDefaultRole ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedAddresses", $cachedAddresses ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedCountries", $cachedCountries ); ?>
<h2>Profiles </h2>
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
								<input type="checkbox" id="enabled" ng-model="profile.wholesale" />
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

			<tab heading="Addresses">
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
						<accordion-group is-open="address.show" 
										 is-disabled="status.isFirstDisabled" ng-repeat="address in profile.addresses track by address.type"> 
							<accordion-heading>
								<span class="glyphicon glyphicon-home" > {{getAddressTypeName(address.type)}}</span>
								<span ng-show="address.firstLine"> - {{address.firstLine}} </span>
								<span ng-show="address.city">- {{address.city}}</span>
								<span ng-show="address.state"> - {{address.state}}</span>
								<span ng-show="address.country"> - {{address.country}}</span>
								<button id="delete-address" class="btn btn-danger btn-xs profile-edit-address delete-address" ng-click="deleteAddress($index, $event)">Delete</button>	
								<a href="" class="profile-edit-address view-hide-address" ng-show="address.show">Hide details</a>
								<a href="" class="profile-edit-address view-hide-address" ng-hide="address.show">View details</a>
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
									<input type="checkbox" ng-change="changeToPrimaryAddress(address)" id="enabled" ng-model="address.primary" />
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

			<tab heading="WP User" ng-if="!hideSections">
				<div class="form-horizontal profile profile-edition">
					<div class="form-group" ng-if='!profile.isWpUser'>
						<div class="col-sm-2"></div>
						<div class="col-sm-4">
							<alert type="info">
								<p>
									<span>A Wordpress user means that the user will be able to log in to your system</span></br>
									<span ng-if='!profile.userExists'>It will be associated with the <strong>"{{defaultRole.name}}"</strong> role.</span>
									<span ng-if='profile.userExists'>It will be associated with his/her current roles in the system"</span>
								</p>
							</alert>	
						</div>
					</div>
					<div class="form-group" ng-if="profile.userExists && !profile.isWpUser">
						<label for="" class="col-sm-2 control-label"></label>
						<div class="col-sm-4">
							<span>A Wordpress user with that email has been found, do you want to link it?</span></br>
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
							<input type="checkbox" ng-change="enabledRegistration()" ng-model="profile.register" />
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-2 control-label">WP Username: </label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="" ng-model="profile.userName" placeholder="" ng-disabled="profile.isWpUser || profile.userExists">
						</div>
					</div>
					<div ng-if="!profile.userExists">
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
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button ng-click="saveProfile()" class="btn btn-primary">Save</button>
							<button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
						</div>
					</div>
				</div>
			</tab>  

			<tab heading="Roles" ng-if="!hideSections && profile.isWpUser">
				<div class="form-horizontal profile profile-edition">
					<div class="col-sm-6" ng-hide="profile.isWpUser">
						<alert type="info">
							<p>
								<span>The profile need to be asociated with a Worpress User to asign roles.</span></br>
							</p>
						</alert>	
					</div>
					<div class="form-group" ng-show="profile.isWpUser" ng-repeat="rol in listOfRoles">
						<label for="" class="col-sm-2 control-label">{{rol.name}}: </label>
						<div class="col-sm-4">
							<input type="checkbox"  id="" ng-model="rol.status" ng-click="selectRol(rol.id, $index)">
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
			<tab heading="Orders" ng-if="!hideSections">
				<table class="table table-striped">
					<thead>
						<tr>
							<th></th>
							<th>Number</th>
							<th>Date</th>
							<th>Total</th>
							<th>Status</th>
						</tr>
					</thead>
					<tr class="no-items-maven" ng-if="profile.orders.length === 0">
						<td class="colspanchange" colspan="5">
							No Orders Found
						</td>
					</tr>
					<tbody ng-repeat="order in profile.orders| orderBy:'-orderDate'">
						<tr>
							<td class="row-actions maven">
								<span class="edit" ng-hide="orderDetail[$index]">
									<a class="list-view" ng-click="showDetail($index)">View Detail</a>
								</span>
								<span class="edit" ng-show="orderDetail[$index]">
									<a class="list-view" ng-click="showDetail($index)">Hide Detail</a>
								</span>
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
							<td>
								<img ng-src="{{order.status.imageUrl}}" />{{order.status.name}}
							</td>
						</tr>
						<tr ng-show="orderDetail[$index]">
							<td colspan="5" style="padding: 0">
								<table class="table table-striped">
									<thead>
										<tr>
											<th></th>
											<th style="width: 15%;">Quantity</th>
											<th style="width: 30%;">Price</th>
											<th style="width: 37%;">Total</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="item in order.items">
											<td>
												{{item.name}}
											</td>
											<td>
												{{item.quantity}}
											</td>
											<td>
												{{item.price}}
											</td>
											<td>
												{{item.quantity * item.price}}
											</td>
										</tr>	
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</tab>
			<tab ng-if="!hideSections">
				<tab-heading class="gravityForm-icon"><img ng-src="{{imageUrl}}/gravityformlogo.png"/> GF Entries</tab-heading>

				<ul class="list-group">
					<li ng-if="profile.gfEntries.length === 0" class="list-group-item">
						<div>
							<div class="panel-heading" ><span>No Gravity Form entries for this profile</span> </div>
						</div>
					</li>
					<li ng-repeat="form in profile.gfEntries" class="list-group-item" >
						<div class="panel panel-default">
							<!-- Default panel contents -->
							<div class="panel-heading" > <a target="_blank" ng-href="{{form.link}}">Form Name: <span ng-bind="form.formName"></span></a> </div>
							<!-- Table -->
							<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th ng-repeat="field in form.fields| limitTo:5" ng-bind="field.label"> </th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="entry in form.entries">
										<td ng-repeat="value in entry.values| limitTo:6" >
											<a ng-if="$index == 0" target="_blank" ng-href="{{value.value}}" ng-bind="entry.id"></a>
											<span ng-if="$index != 0" ng-bind="value.value"></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</li>
				</ul>
			</tab>
			<tab ng-if="!hideSections">
				<tab-heading class="gravityForm-icon"><img style="width:40px;" ng-src="{{imageUrl}}/mandrill-logo.png"/> Mandrill messages</tab-heading>

				<ul class="list-group">
					<li ng-if="profile.mandrillMessages.length === 0" class="list-group-item">
						<div>
							<div class="panel-heading" ><span>No Mandrill Messages for this profile</span> </div>
						</div>
					</li>
					<li class="list-group-item" ng-if="profile.mandrillMessages" >
						<div  class="panel panel-default">
							<!-- Default panel contents -->
							<div class="panel-heading" >  Messages </div>
							<!-- Table -->
							<table class="table">
								<thead>
									<tr>
										<th>Date</th>
										<th>Subject</th>
										<th>Opens</th>
										<th>Clicks</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="message in profile.mandrillMessages">
										<td>
											<span  ng-bind="message.ts"></span>
										</td>
										<td>
											<span  ng-bind="message.subject"></span>
										</td>
										<td>
											<span  ng-bind="message.opens"></span>
										</td>
										<td>
											<span  ng-bind="message.clicks"></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</li>
				</ul>
			</tab>
		</tabset>
	</ng-form>
</div>
