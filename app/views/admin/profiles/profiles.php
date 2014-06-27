<h1>Profiles <button class="btn btn-default" ng-click="newProfile()">New</button></h1>
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
        <tr ng-repeat="profile in Profiles">
            <td>
                <button class="btn btn-primary btn-xs" ng-click="editProfile(profile.id)">Edit</button>
                <button class="btn btn-info btn-xs" ng-click="deleteProfile($index)">Delete</button>	
            </td>
            <td>
		    <img ng-show="hasRoles(profile.roles)" src=""/>
		    <img ng-hide="hasRoles(profile.roles)" src=""/>
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
<pagination total-items="totalItems" ng-model="ProfileFilter.page" max-size="5" class="" boundary-links="true" class="pagination-sm"
            ng-change="selectPage(ProfileFilter.page)"></pagination>
