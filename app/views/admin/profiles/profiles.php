<h2>Profiles <button class="btn btn-default" ng-click="newProfile()">New</button></h2>
<table class="table table-striped">
	<thead>
		<tr>
			<th></th>
			<th>Status</th>
			<th>Email</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Creation Date</th>
			<th>Last Update</th>
		</tr>
	</thead>
	<tbody>
		<tr class="no-items-maven" ng-if="totalItems == 0">
			<td class="colspanchange" colspan="7">
				No Record Founds
			</td>
		</tr>
		<tr ng-repeat="profile in Profiles">
			<td class="row-actions maven">
				<span class="edit">
					<a class="list-view" ng-click="editProfile(profile.id)">Edit</a>
					|
				</span>
				<span class="trash">
					<a class="list-view delete" ng-click="deleteProfile($index)">Delete</a>
				</span>
			</td>
			<td>
				<img ng-src="{{imageUrl}}/profile-status/status-enabled.png" ng-show="hasRoles(profile.roles)" src=""/>
				<img ng-src="{{imageUrl}}/profile-status/status-disabled.png" ng-hide="hasRoles(profile.roles)" src=""/>
			</td>
			<td>
				{{profile.email}}
			</td>
			<td>
				{{profile.firstName}}
			</td>
			<td>
				{{profile.lastName}}
			</td>
			<td>
				{{profile.createdOn}}
			</td>
			<td>
				{{profile.lastUpdate}}
			</td>
		</tr>
	</tbody>
</table>
<div class="text-center">
	<pagination total-items="totalItems" ng-model="ProfileFilter.page" max-size="5" class="" boundary-links="true" class="pagination-sm"
				ng-change="selectPage(ProfileFilter.page)"></pagination>
</div>
