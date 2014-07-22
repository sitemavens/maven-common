<h2>Roles <button class="btn btn-default" ng-click="newRol()">New</button></h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
		<tr class="no-items-maven" ng-if="totalItems == 0">
			<td class="colspanchange" colspan="2">
				No Record Founds
			</td>
		</tr>
        <tr ng-repeat="rol in roles">
            <td class="row-actions maven">
				<span class="edit">
					<a class="list-view" ng-click="editRol(rol.id)">Edit</a>
				</span>
				<span class="trash" ng-hide="rol.systemRole">
					|
					<a class="list-view delete"  ng-click="deleteRol($index)">Delete</a>
				</span>
			</td>
            <td>
                {{rol.name}}
            </td>
        </tr>
    </tbody>

</table>
