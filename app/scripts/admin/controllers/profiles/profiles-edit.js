'use strict';

angular.module('mavenApp')
	.controller('ProfileEditCtrl',
		['$scope', '$routeParams', '$location', 'Profile',
			function($scope, $routeParams, $location, Profile) {
				$scope.oneAtATime = true;
				$scope.profile = {};
				$scope.addresses = CachedAddresses;
				if ($routeParams.id) {
					$scope.profile = Profile.get({id: $routeParams.id});
				} else {
					$scope.profile = new Profile({enabled: true});
					$scope.profile.addresses = [];
				}
				console.log($scope.profile);
				$scope.saveProfile = function() {
					console.log('saving?');
					$scope.profile.$save();
				};
				$scope.cancelEdit = function() {
					$location.path('/profiles/');
				};
				$scope.addAddress = function(address) {
					$scope.profile.addresses.push(address);
					console.log($scope.profile.addresses);
				};
			}]);