'use strict';
angular.module('mavenApp')
	.controller('PromotionsCtrl',
		['$scope', '$location', 'PromotionPaginate', 'PromotionFilter',
			function($scope, $location, PromotionPaginate, PromotionFilter) {

				$scope.getPage = function() {
					PromotionPaginate.getPage(PromotionFilter, function(result) {
						$scope.result = result;
						$scope.promotions = result.items;
						$scope.totalItems = result.totalItems;
					});
				};

				$scope.PromotionFilter = PromotionFilter;
				$scope.getPage();

				$scope.selectPage = function(page) {
					PromotionFilter.page = page;
					$scope.getPage();
				};


				$scope.newPromotion = function() {
					$location.path('promotions/new');
				}

				$scope.editPromotion = function(id) {
					$location.path('promotions/edit/' + id);
				}

				$scope.deletePromotion = function(idx) {
					var promotion = $scope.promotions[idx];
					$scope.result.$delete({id: promotion.id}).then(
						function() {
							$scope.promotions.splice(idx, 1);
						});
				}
			}]);