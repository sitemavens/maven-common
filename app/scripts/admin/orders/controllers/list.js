'use strict';
angular.module('mavenApp')
	.controller('OrdersCtrl',
		['$scope', '$location', 'Order', 'OrderFilter',
			function($scope, $location, Order, OrderFilter) {

				$scope.getPage = function() {
					Order.getPage(OrderFilter, function(result) {
						$scope.orders = result.items;
						$scope.totalItems = result.totalItems;
					});
				};

				$scope.OrderFilter = OrderFilter;
				$scope.getPage();


				$scope.editOrder = function(orderId) {
					$location.path('orders/edit/' + orderId);
				};

				$scope.selectPage = function(page) {
					OrderFilter.page = page;
					$scope.getPage();
				};

				$scope.printUrl = function(id) {
					return Maven.printUrl + 'order/' + id;
				};
			}]);