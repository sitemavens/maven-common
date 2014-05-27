'use strict';

angular.module('mavenApp')
	.controller('OrdersEditCtrl',
		['$scope', '$routeParams', '$location', 'Order',
			function($scope, $routeParams, $location, Order) {
				$scope.order = {};

				if ($routeParams.id) {
					$scope.order = Order.get({id: $routeParams.id});
				} else {
					$scope.order = new Order({number: 0});
				}

				$scope.saveOrder = function() {
					//console.log('saving?');
					$scope.order.$save();
					/*Tax.save({id: $scope.tax.id}, $scope.tax,
					 function(tax) {
					 $location.path('/taxes/edit/' + tax.id).replace();
					 });*/


				};

				$scope.cancelEdit = function() {
					$location.path('/orders/');
				};
			}]);


