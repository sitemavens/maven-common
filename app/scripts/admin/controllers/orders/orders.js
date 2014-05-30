'use strict';
angular.module('mavenApp')
	.controller('OrdersCtrl',
		['$scope', '$location', 'Order', 'OrderFilter',
			function($scope, $location, Order, OrderFilter) {

				$scope.orders = Order.query(OrderFilter);


				$scope.editOrder = function(orderId) {
					$location.path('orders/edit/' + orderId);
				}

				$scope.page = function(offset) {
					var page = OrderFilter.page;

					page += offset;
					if (page < 0)
						page = 0;

					OrderFilter.page = page;

					Order.query(OrderFilter, function(orders) {
						$scope.orders = orders;
					});
				}

				$scope.printUrl = function(id) {
					return Maven.printUrl + 'order/' + id;
				}
			}]);