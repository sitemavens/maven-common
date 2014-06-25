<h1>Orders</h1>

<table class="table table-striped">
	<thead>
		<tr>
			<th></th>
			<th>Status</th>
			<th>Number</th>
			<th>Customer</th>
			<th>Date</th>
			<th>Total</th>

		</tr>
	</thead>
	<tbody>
		<tr ng-repeat="order in orders">
			<td>
				<button class="btn btn-success btn-xs" ng-click="editOrder(order.id)">Edit</button>
				<a class="btn btn-info btn-xs" target="_blank" ng-href="{{printUrl(order.id)}}">Print</a>
				<button class="btn btn-primary btn-xs" ng-click="deleteOrder($index)">Delete</button>	
			</td>
			<td>
				<img ng-src="{{order.status.imageUrl}}" />{{order.status.name}}
			</td>
			<td>
				{{order.number}}
			</td>
			<td>
				{{order.contact.firstName}} {{order.contact.lastName}}
			</td>
			<td>
				{{order.orderDate}}
			</td>
			<td>
				{{order.total|currency}}
			</td>
		</tr>
	</tbody>
</table>
<pagination total-items="totalItems" ng-model="OrderFilter.page" max-size="5" class="" boundary-links="true" class="pagination-sm"
            ng-change="selectPage(OrderFilter.page)"></pagination>