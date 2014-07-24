'use strict';

angular.module('mavenApp')
		.controller('PromotionsEditCtrl',
				['$scope', '$routeParams', '$location', 'Promotion',
					function($scope, $routeParams, $location, Promotion) {
						$scope.promotion = {};
						$scope.sections = CachedSections;
						$scope.types = CachedTypes;
						$scope.format = 'dd-MMMM-yyyy';
						$scope.open = function($event) {
							$event.preventDefault();
							$event.stopPropagation();
							$scope.opened = true;
						};

						if ($routeParams.id) {
							$scope.promotion = Promotion.get({id: $routeParams.id});
						} else {
							$scope.promotion = new Promotion({enabled: true});
						}

						$scope.openDatePicker = function($event, input) {
							$event.preventDefault();
							$event.stopPropagation();
							if (input == 'from') {
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
							$scope.promotion.$save(function(data) {
								console.log(data);
								$location.path('/promotions/edit/' + data.id);
							});

						};

						$scope.cancelEdit = function() {
							$location.path('/promotions/');
						};
					}]);


