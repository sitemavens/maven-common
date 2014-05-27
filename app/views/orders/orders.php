<h1>Orders</h1>

<table class="table table-striped">
	<thead>
		<tr>
			<th></th>
			<th>Status</th>
			<th>Number</th>
			<th>Total</th>

		</tr>
	</thead>
	<tbody>
		<tr ng-repeat="order in orders">
			<td>
				<button class="btn btn-primary btn-xs" ng-click="editOrder(order.id)">Edit</button>
				<button class="btn btn-info btn-xs" ng-click="deleteOrder($index)">Delete</button>	
			</td>
			<td>
				<img ng-src="{{order.status.imageUrl}}" />{{order.status.name}}
			</td>
			<td>
				{{order.number}}
			</td>
			<td>
				{{order.total|currency}}
			</td>
		</tr>
	</tbody>
</table>
<div class="row">
	<div class="col-md-offset-8 col-md-4">

		<div class="btn-group">
			<button type="button" ng-click="page(-1)" class="btn btn-default">Prev</button>
			<button type="button" ng-click="page(1)" class="btn btn-default">Next</button>
		</div>

	</div>
</div>