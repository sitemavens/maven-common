'use strict';

angular.module('mavenApp').controller('MainNavCtrl', ['$scope', '$location', function($scope, $location) {
 
		$scope.isActive = function (viewLocation) { 
			return viewLocation === $location.path();
		};
		 
	}]);
