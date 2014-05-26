'use strict';
angular.module('mavenApp')
	.controller('TaxesCtrl',
		['$scope', '$location', 'TaxesFilterService', 'TaxesService',
			function($scope, $location, TaxesFilterService, TaxesService) {
				$scope.filter = TaxesFilterService;

				TaxesService.getList($scope.filter, function(data) {
					$scope.taxes = data;
				});

				$scope.message = "Probando!";


				$scope.newTax = function() {
					$location.path('taxes/new');
				}
			}]);