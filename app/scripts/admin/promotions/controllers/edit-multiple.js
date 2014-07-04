'use strict';

angular.module('mavenApp')
		.controller('PromotionsEditMultipleCtrl',
				['$scope', '$routeParams', '$location', 'Promotion',
					function($scope, $routeParams, $location, Promotion) {
						$scope.promotion = {};
						$scope.sections = CachedSections;
						$scope.types = CachedTypes;
						$scope.quantityStatus;

						if ($routeParams.id) {
							$scope.promotion = Promotion.get({id: $routeParams.id});
						} else {
							$scope.promotion = new Promotion({enabled: true});
						}

						$scope.openDatePicker = function($event, input) {
							$event.preventDefault();
							$event.stopPropagation();
							if (input === 'from') {
								$scope.openTo = false;
								$scope.openFrom = true;
							} else {
								$scope.openFrom = false;
								$scope.openTo = true;
							}
						};

						$scope.savePromotion = function() {
							$scope.$broadcast('show-errors-check-validity');
							if ($scope.promotionForm.$invalid) {
								return;
							}
							$scope.promotion.$save().then(function() {
								$location.path('/promotions');
							});

						};

						$scope.cancelEdit = function() {
							$location.path('/promotions/');
						};
					}]);


