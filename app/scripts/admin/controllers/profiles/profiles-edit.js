'use strict';

angular.module('mavenApp')
	.controller('ProfileEditCtrl',
		['$scope', '$routeParams', '$location', 'Profile',
			function($scope, $routeParams, $location, Profile) {
				$scope.Profile = {};
				if ($routeParams.id) {
					$scope.profile = Profile.get({id: $routeParams.id});
				} else {
					$scope.profile = new Profile({enabled: true});
				}
				$scope.saveProfile = function() {
					console.log('saving?');
					$scope.profile.$save();
				}
				$scope.cancelEdit = function() {
					$location.path('/profiles/');
				}
			}]);