<h2>Orders</h2>
<p class="orders-header"><span class="col-md-2"><b>{{totalItems}}</b> Orders</span><span class="col-md-2"><b>{{ordersTotal|currency}}</b> Total Sales</span><span class="col-md-2"><b>{{ordersTotal / totalItems |currency}}</b> Average Sale</span></p>
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
			<td class="row-actions maven">
				<span class="edit">
					<a class="list-view" ng-click="editOrder(order.id)">Edit</a>
					|
				</span>
				<span class="edit">
					<a class="list-view" target="_blank" ng-href="{{printUrl(order.id)}}">Print</a>
					|	
				</span>
				</span>
				<span class="trash">
					<a class="list-view delete" ng-click="deleteOrder($index)">Delete</a>
				</span>
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