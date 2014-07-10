'use strict';
angular.module('mavenApp')
	.controller('ShippingMethodsCtrl',
		['$scope', '$location', 'ShippingMethod', 'ShippingMethodFilter',
			function($scope, $location, ShippingMethod, ShippingMethodFilter) {
				
				$scope.getPage = function() {
					ShippingMethod.getPage(ShippingMethodFilter, function(result) {
						$scope.result = result;
						$scope.items = result.items;
						$scope.totalItems = result.totalItems;
					});
				};

				$scope.ShippingMethodFilter = ShippingMethodFilter;
				$scope.getPage();

				$scope.selectPage = function(page) {
					ShippingMethodFilter.page = page;
					$scope.getPage();
				};

				$scope.new = function() {
					$location.path('shipping-methods/new');
				};

				$scope.edit = function(id) {
					$location.path('shipping-methods/edit/' + id);
				};

				$scope.delete = function(idx) {
					var item = $scope.items[idx];
					$scope.result.$delete({id: item.id}).then(
						function(data) {
							$scope.items.splice(idx, 1);
						});
				};
			}]);