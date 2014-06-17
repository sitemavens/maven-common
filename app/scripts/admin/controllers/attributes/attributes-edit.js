'use strict';

angular.module('mavenApp')
	.controller('AttrEditCtrl',
		['$scope', '$routeParams', '$location', 'mvnAttribute',
			function($scope, $routeParams, $location, mvnAttribute) {
				$scope.mvnAttribute = {};
				if ($routeParams.id) {
					$scope.attr = mvnAttribute.get({id: $routeParams.id});
				} else {
					$scope.attr = new mvnAttribute({enabled: true});
				}
				$scope.saveAttr = function() {
					console.log('saving?');
					$scope.attr.$save();
				}
				$scope.cancelEdit = function() {
					$location.path('/attributes/');
				}
			}]);


