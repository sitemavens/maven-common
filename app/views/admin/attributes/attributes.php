<h1>Attributes <button class="btn btn-default" ng-click="newAttr()">New</button></h1>
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
            <td>
                <button class="btn btn-primary btn-xs" ng-click="editAttr(attr.id)">Edit</button>
                <button class="btn btn-info btn-xs" ng-click="deleteAttr($index)">Delete</button>	
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
