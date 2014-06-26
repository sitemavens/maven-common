<h1>Taxes <button class="btn btn-default" ng-click="newTax()">New</button></h1>

<table class="table table-striped">
	<thead>
		<tr>
			<th></th>
			<th>Status</th>
			<th>Name</th>
			<th>Country</th>
			<th>State</th>
			<th>Value</th>
		</tr>
	</thead>
	<tbody>
		<tr ng-repeat="tax in taxes">
			<td>
				<button class="btn btn-primary btn-xs" ng-click="editTax(tax.id)">Edit</button>
				<button class="btn btn-info btn-xs" ng-click="deleteTax($index)">Delete</button>	
			</td>
			<td>
				<img ng-src="{{tax.statusImageUrl}}" />
			</td>
			<td>
				{{tax.name}}
			</td>
			<td>
				{{tax.country}}
			</td>
			<td>
				{{tax.state}}
			</td>
			<td>
				{{tax.value}}
			</td>
		</tr>
	</tbody>
</table>
