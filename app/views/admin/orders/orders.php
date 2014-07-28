<?php \Maven\Core\UI\HtmlComponent::jSonComponent( "CachedStatuses", $cachedStatuses ); ?>
<h2>Orders</h2>
<p class="orders-header"><span class="col-md-2"><b>{{totalItems}}</b> Orders</span><span class="col-md-2"><b>{{ordersTotal|currency}}</b> Total Sales</span><span class="col-md-2"><b>{{ordersTotal / totalItems|currency}}</b> Average Sale</span></p>
<input placeholder="Number" ng-change="applyFilters()"  ng-model="OrderFilter.number"/>
<select ng-change="applyFilters()" ng-model="OrderFilter.status" ng-init=""
		ng-options="statusI as status.name for (statusI,status) in cachedStatuses">
	<option value="">Show All</option>
</select>
<div class="btn-group col-sm-3 orders-date-filter no-float " dropdown is-open="status.isopen">
	<button type="button" class="btn btn-primary dropdown-toggle" ng-disabled="disabled">
        {{rangeFilter}} <span class="caret"></span>
	</button>
	<ul class="dropdown-menu col-sm-6" role="menu">
        <li ng-repeat="date in rangeStart"><a ng-click="setDate($index)">{{date.name}}</a></li>
		<li><a ng-click="enableRanges()">Custom Range</a></li>
		<div>


			<div class="col-sm-6" ng-hide="isDisabledRange">
				<label for="from" class="col-sm-2 control-label">Start</label>
				<input ng-change="applyFilters()" type="text" class="form-control" required name="startRange" datepicker-popup="MM/dd/yy" ng-model="startDate" is-open="openStart" close-text="Close" ng-click="openDatePicker($event, 'start')" />
			</div>
		</div>
		<div>

			<div class="col-sm-6" ng-hide="isDisabledRange">
				<label for="from" class="col-sm-2 control-label">End</label>
				<input ng-change="applyFilters()" type="text" class="form-control" required name="endRange" datepicker-popup="MM/dd/yy" ng-model="endDate" is-open="openEnd" close-text="Close" ng-click="openDatePicker($event, 'end')" />
			</div>
		</div>
	</ul>
</div>
<button type="button" class="btn btn-primary dropdown-toggle" ng-click="searchAll()">
	All Orders
</button>
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
		<tr class="no-items-maven" ng-if="totalItems == 0">
			<td class="colspanchange" colspan="6">
				No Record Found
			</td>
		</tr>
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
<div class="text-center">
	<pagination total-items="totalItems" ng-model="OrderFilter.page" max-size="5" class="" boundary-links="true" class="pagination-sm"
				ng-change="selectPage(OrderFilter.page)"></pagination>
</div>