<h2>Taxes <button class="btn btn-default" ng-click="newTax()">New</button></h2>

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
		<tr class="no-items-maven" ng-if="taxes.length == 0">
			<td class="colspanchange" colspan="6">
				No Record Founds
			</td>
		</tr>
		<tr ng-repeat="tax in taxes">
			<td class="row-actions maven">
				<span class="edit">
					<a class="list-view" ng-click="editTax(tax.id)">Edit</a>
					|
				</span>
				<span class="trash">
					<a class="list-view delete" ng-click="deleteTax($index)">Delete</a>
				</span>
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
