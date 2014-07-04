'use strict';

angular.module('mavenApp')
		.controller('AttrEditCtrl',
				['$scope', '$routeParams', '$location', 'Attribute',
					function($scope, $routeParams, $location, Attribute) {
						$scope.Attribute = {};
						if ($routeParams.id) {
							$scope.attr = Attribute.get({id: $routeParams.id});
						} else {
							$scope.attr = new Attribute({enabled: true});
						}
						$scope.saveAttr = function() {
							$scope.$broadcast('show-errors-check-validity');
							if ($scope.attributesForm.$invalid) {
								return;
							}
							$scope.attr.$save();
						};
						$scope.cancelEdit = function() {
							$location.path('/attributes/');
						};
					}]);


