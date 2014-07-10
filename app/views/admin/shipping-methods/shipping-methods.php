<h1>Shipping Methods <button class="btn btn-default" ng-click="new()">New</button></h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="item in items">
            <td class="row-actions maven">
				<span class="edit">
				<a class="list-view" ng-click="edit(item.id)">Edit</a>
				|
				</span>
				<span class="trash">
				<a class="list-view delete" ng-click="delete($index)">Delete</a>
				</span>
			</td>
            <td>
                {{item.name}}
            </td>
            <td>
                {{item.type}}
            </td>
        </tr>
    </tbody>
</table>
<pagination total-items="totalItems" ng-model="ShippingMethodFilter.page" max-size="5" class="" boundary-links="true" class="pagination-sm"
            ng-change="selectPage(ShippingMethodFilter.page)"></pagination>
