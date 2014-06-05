<h1>Promotions <button class="btn btn-default" ng-click="newPromotion()">New</button>
&nbsp; <button class="btn btn-default" ng-click="exportPromotions()">Export</button>
</h1>

<table class="table table-striped">
	<thead>
		<tr>
			<th></th>
			<th>Status</th>
			<th>Name</th>
			<th>Code</th>
			<th>Section</th>
			<th>Uses</th>
			<th>From</th>
			<th>To</th>
		</tr>
	</thead>
	<tbody>
		<tr ng-repeat="promotion in promotions">
			<td>
				<button class="btn btn-primary btn-xs" ng-click="editPromotion(promotion.id)">Edit</button>
				<button class="btn btn-info btn-xs" ng-click="deletePromotion($index)">Delete</button>	
			</td>
			<td>
				<img ng-src="{{promotion.statusImageUrl}}" />
			</td>
			<td>
				{{promotion.name}}
			</td>
			<td>
				{{promotion.code}}
			</td>
			<td>
				{{promotion.section}}
			</td>
			<td>
				{{promotion.uses}} / {{promotion.limitOfUse==0?'No limit':promotion.limitOfUse}}
			</td>
			<td>
				{{promotion.from}}
			</td>
			<td>
				{{promotion.to}}
			</td>
		</tr>
	</tbody>

</table>
