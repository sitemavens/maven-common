<h1>Promotions <button class="btn btn-default" ng-click="newPromotion()">New</button></h1>

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
			<td class="row-actions maven">
				<span class="edit">
				<a class="list-view" ng-click="editPromotion(promotion.id)">Edit</a>
				|
				</span>
				<span class="trash">
				<a class="list-view delete" ng-click="deletePromotion($index)">Delete</a>
				</span>
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
<pagination total-items="totalItems" ng-model="PromotionFilter.page" max-size="5" class="" boundary-links="true" class="pagination-sm"
            ng-change="selectPage(PromotionFilter.page)"></pagination>
