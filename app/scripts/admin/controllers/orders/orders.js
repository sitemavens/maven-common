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

					$scope.orders = Order.query(OrderFilter);
				}

				$scope.printUrl = function(id) {
					return Maven.printUrl + 'order/' + id;
				}
				/*$scope.deleteTax = function(idx) {
				 var tax = $scope.taxes[idx];
				 tax.$delete().then(
				 function() {
				 $scope.taxes.splice(idx, 1);
				 });
				 }*/
			}]);