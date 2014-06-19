'use strict';

angular.module('mavenApp').controller('MainNavCtrl', ['$scope', '$location', function($scope, $location) {

		$scope.isActive = function(viewLocation) {
			var path = $location.path();
			
			//if we are checking the root, make sure its equal
			if (viewLocation === '/')
				return viewLocation === path;

			//if not in the root, make sure that location start with the url 
			//(to allow things like /viewLocation/edit
			return ($location.path().indexOf(viewLocation) === 0);
		};

	}]);
