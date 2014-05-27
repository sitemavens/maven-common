'use strict';
angular.module('mavenApp')
	.controller('TaxesCtrl',
		['$scope', '$location', 'TaxesFilterService', 'Tax',
			function($scope, $location, TaxesFilterService, Tax) {
				$scope.filter = TaxesFilterService;

				$scope.taxes = Tax.query();


				$scope.newTax = function() {
					$location.path('taxes/new');
				}

				$scope.editTax = function(taxId) {
					$location.path('taxes/edit/' + taxId);
				}

				$scope.deleteTax = function(idx) {
					var tax = $scope.taxes[idx];
					tax.$delete().then(
						function() {
							$scope.taxes.splice(idx, 1);
						});
				}
			}]);