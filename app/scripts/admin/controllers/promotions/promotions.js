'use strict';
angular.module('mavenApp')
	.controller('PromotionsCtrl',
		['$scope', '$location', 'Promotion',
			function($scope, $location, Promotion) {

				$scope.promotions = Promotion.query();


				$scope.newPromotion = function() {
					$location.path('promotions/new');
				}

				$scope.editPromotion = function(id) {
					$location.path('promotions/edit/' + id);
				}

				$scope.deletePromotion = function(idx) {
					var promotion = $scope.promotions[idx];
					promotion.$delete().then(
						function() {
							$scope.promotions.splice(idx, 1);
						});
				}				
			}]);