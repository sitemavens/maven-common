<script type="text/ng-template" id="myModalContent.html">
        <div class="modal-header">
            <h3 class="modal-title">Link Profile with Wordpress User</h3>
        </div>
		<div ng-if="!userCreated" class="modal-body">
			<div>
				<span>{{message}}</span>
			</div>
		</div>
        <div ng-if="userCreated" class="modal-body">
			<div>
				<span>{{message}}</span>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-4 control-label">Username: </label>
				<div class="col-sm-2">
					<span ng-bind="userEmail"></span>
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-4 control-label">Password: </label>
				<div class="col-sm-2">
					<span ng-bind="userPassword"></span>
				</div>
			</div>
		</div>
        <div class="modal-footer">
            <button class="btn btn-primary" ng-if="!showClose" ng-click="ok()">OK</button>
            <button class="btn btn-default" ng-if="!showClose"  ng-click="cancel()">Cancel</button>
			<button class="btn btn-default" ng-if="showClose"  ng-click="close()">Close</button>
        </div>
</script>

<h2>Profiles <button class="btn btn-default" ng-click="newProfile()">New</button>
	<input placeholder="Filter by email" ng-change="getPage()"  ng-model="ProfileFilter.email"/>
	<input placeholder="Filter by first name" ng-change="getPage()"  ng-model="ProfileFilter.firstName"/>
	<input placeholder="Filter by last name" ng-change="getPage()"  ng-model="ProfileFilter.lastName"/>
</h2>
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
				No Record Found
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
				<img style="cursor: pointer"  ng-src="{{imageUrl}}/profile-status/status-enabled.png" ng-if="profile.userId !== '0'" ng-click="open($index)" src=""/>
				<img style="cursor: pointer" ng-src="{{imageUrl}}/profile-status/status-disabled.png" ng-if="profile.userId === '0'" ng-click="open($index)" src=""/>
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
