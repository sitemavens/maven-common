'use strict';

angular.module('mavenApp')
		.controller('ShippingMethodsEditCtrl',
				['$scope', '$routeParams', '$location', 'ShippingMethod',
					function($scope, $routeParams, $location, ShippingMethod) {
						$scope.item = {};
						if ($routeParams.id) {
							$scope.item = ShippingMethod.get({id: $routeParams.id});
						} else {
							$scope.item = new ShippingMethod({enabled: true});
						}
						$scope.save = function() {
							$scope.$broadcast('show-errors-check-validity');
							if ($scope.shippingForm.$invalid) {
								return;
							}
							$scope.item.$save().then(function(data) {
								$location.path('/shipping-methods/edit/' + data.id);
							});;
						};
						$scope.cancelEdit = function() {
							$location.path('/shipping-methods/');
						};
					}]);


