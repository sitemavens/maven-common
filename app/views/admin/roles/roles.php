<h1>Roles <button class="btn btn-default" ng-click="newRol()">New</button></h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="rol in roles">
            <td>
                <button class="btn btn-primary btn-xs" ng-click="editRol(rol.id)">Edit</button>
                <button class="btn btn-info btn-xs" ng-click="deleteRol($index)">Delete</button>	
            </td>
            <td>
                {{rol.name}}
            </td>
        </tr>
    </tbody>

</table>
