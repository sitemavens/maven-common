'use strict';

angular.module('mavenApp')
	.controller('TaxesEditCtrl',
		['$scope', '$routeParams',
			function($scope, $routeParams) {
				$scope.tax = {};

				if ($routeParams.id) {
					$scope.tax.name = $routeParams.id;
				} else {
					$scope.tax.name = 'nuevo';

				}
			}]);


