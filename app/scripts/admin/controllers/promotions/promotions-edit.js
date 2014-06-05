'use strict';

angular.module('mavenApp')
	.controller('PromotionsEditCtrl',
		['$scope', '$routeParams', '$location', 'Promotion',
			function($scope, $routeParams, $location, Promotion) {
				$scope.promotion = {};
				$scope.sections = CachedSections;
				$scope.types = CachedTypes;


				if ($routeParams.id) {
					$scope.promotion = Promotion.get({id: $routeParams.id});
				} else {
					$scope.promotion = new Promotion({enabled: true});
				}

				$scope.savePromotion = function() {
					$scope.promotion.$save();

				}

				$scope.cancelEdit = function() {
					$location.path('/promotions/');
				}				
			}]);


