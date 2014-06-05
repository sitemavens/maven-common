'use strict';

angular.module('mavenApp')
	.controller('PromotionsEditCtrl',
		['$scope', '$routeParams', '$location', 'Promotion',
			function($scope, $routeParams, $location, Promotion) {
				$scope.promotion = {};

				if ($routeParams.id) {
					$scope.promotion = Promotion.get({id: $routeParams.id});
				} else {
					$scope.promotion = new Promotion({enabled: true});
				}

				$scope.savePromotion = function() {
					//console.log('saving?');
					$scope.promotion.$save();
					/*Tax.save({id: $scope.tax.id}, $scope.tax,
					 function(tax) {
					 $location.path('/taxes/edit/' + tax.id).replace();
					 });*/


				}

				$scope.cancelEdit = function() {
					$location.path('/promotions/');
				}
			}]);


