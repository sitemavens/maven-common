'use strict';
angular.module('mavenApp')
		.controller('OrdersCtrl',
				['$scope', '$location', 'Order', 'OrderFilter',
					function($scope, $location, Order, OrderFilter) {
						$scope.cachedStatuses = CachedStatuses;
						$scope.isDisabledRange = true;
						$scope.rangeFilter = "No Date Range Selected";
						var today = new Date();
						var year = today.getFullYear();
						var month = today.getMonth();
						$scope.startDate = null;
						$scope.endDate = null;
						var firstDayCurrentMonth = new Date(year, month, 1);
						var lastDayCurrentMonth = new Date(year, month + 1, 0);
						var firstDayLastMonth = new Date(year, month - 1, 1);
						var lastDayLastMonth = new Date(year, month, 0);
						$scope.rangeStart = [
							{name: "Today", value: today},
							{name: "Yesterday", value: new Date(year, month, today.getDate() - 1)},
							{name: "7 Days", value: new Date(year, month, today.getDate() - 7)},
							{name: "Last 30 Days", value: new Date(year, month, today.getDate() - 30)},
							{name: "This Month", value: firstDayCurrentMonth},
							{name: "Last Month", value: firstDayLastMonth}
						];
						$scope.rangeEnd = [
							{name: "Today", value: today},
							{name: "Yesterday", value: today},
							{name: "7 Days", value: today},
							{name: "Last 30 Days", value: today},
							{name: "This Month", value: lastDayCurrentMonth},
							{name: "Last Month", value: lastDayLastMonth}
						];
						$scope.OrderFilter = OrderFilter;
						$scope.OrderFilter.status = "completed";
						$scope.getPage = function() {
							Order.getPage($scope.OrderFilter, function(result) {
								$scope.orders = result.items;
								$scope.totalItems = result.totalItems;
								$scope.ordersTotal = result.ordersTotal;
							});
						};

						$scope.status = {
							isopen: false
						};

						$scope.toggleDropdown = function($event) {
							$event.preventDefault();
							$event.stopPropagation();
							$scope.status.isopen = !$scope.status.isopen;
						};

						$scope.getPage();

						$scope.editOrder = function(orderId) {
							$location.path('orders/edit/' + orderId);
						};
						$scope.applyFilters = function() {
							if ($scope.startDate !== null) {
								$scope.OrderFilter.start = $scope.startDate.toString();
							}
							if ($scope.endDate !== null) {
								$scope.OrderFilter.end = $scope.endDate.toString();
							};
							$scope.getPage();
						};
						$scope.selectPage = function(page) {
							$scope.OrderFilter.page = page;
							$scope.getPage();
						};

						$scope.printUrl = function(id) {
							return Maven.printUrl + 'order/' + id;
						};

						$scope.setDate = function(index) {
							$scope.isDisabledRange = true;
							$scope.startDate = $scope.rangeStart[index].value;
							$scope.endDate = $scope.rangeEnd[index].value;
							$scope.rangeFilter = $scope.rangeStart[index].name;
							$scope.applyFilters();
						};
						$scope.openDatePicker = function($event, input) {
							$event.preventDefault();
							$event.stopPropagation();
							if (input == 'start') {
								$scope.openEnd = false;
								$scope.openStart = true;
							} else {
								$scope.openStart = false;
								$scope.openEnd = true;
							}
						};

						$scope.enableRanges = function() {
							$scope.rangeFilter = "Custom Range";
							$scope.isDisabledRange = false;
						};
						
						$scope.searchAll = function(){
							$scope.OrderFilter.start = null;
							$scope.OrderFilter.end = null;
							$scope.OrderFilter.number = null;
							$scope.OrderFilter.status = null;
							$scope.getPage();
						};

					}]);