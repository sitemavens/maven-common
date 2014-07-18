<h2>Attributes <button class="btn btn-default" ng-click="newAttr()">New</button></h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Attribute</th>
            <th>Default Price</th>
            <th>Default Wholesale Price</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="attr in Attributes">
            <td class="row-actions maven">
				<span class="edit">
				<a class="list-view" ng-click="editAttr(attr.id)">Edit</a>
				|
				</span>
				<span class="trash">
				<a class="list-view delete" ng-click="deleteAttr($index)">Delete</a>
				</span>
			</td>
            <td>
                {{attr.name}}
            </td>
            <td>
                {{attr.defaultAmount}}
            </td>
            <td>
                {{attr.defaultWholesaleAmount}}
            </td>
        </tr>
    </tbody>
</table>
<pagination total-items="totalItems" ng-model="AttributeFilter.page" max-size="5" class="" boundary-links="true" class="pagination-sm"
            ng-change="selectPage(AttributeFilter.page)"></pagination>
