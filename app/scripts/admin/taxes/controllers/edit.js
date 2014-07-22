'use strict';

angular.module('mavenApp')
		.controller('TaxesEditCtrl',
				['$scope', '$routeParams', '$location', 'Tax',
					function($scope, $routeParams, $location, Tax) {
						$scope.tax = {};
						$scope.countries = CachedCountries;
						if ($routeParams.id) {
							$scope.tax = Tax.get({id: $routeParams.id});
						} else {
							$scope.tax = new Tax({enabled: true});
						}

						$scope.saveTax = function() {
							$scope.$broadcast('show-errors-check-validity');
							if ($scope.taxesForm.$invalid) {
								return;
							}
							
							$scope.tax.$save(function(){
							});
							/*Tax.save({id: $scope.tax.id}, $scope.tax,
							 function(tax) {
							 $location.path('/taxes/edit/' + tax.id).replace();
							 });*/


						};

						$scope.cancelEdit = function() {
							$location.path('/taxes/');
						};
					}]);


