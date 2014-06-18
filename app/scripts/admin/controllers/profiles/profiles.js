'use strict';
angular.module('mavenApp')
	.controller('ProfileCtrl',
		['$scope', '$location', 'Profile',
			function($scope, $location, Profile) {
				$scope.Profiles = Profile.query();


				$scope.newProfile = function() {
					$location.path('profiles/new');
				};

				$scope.editProfile = function(id) {
					$location.path('profiles/edit/' + id);
				};

				$scope.deleteProfile = function(idx) {
					var profile = $scope.Profiles[idx];
					profile.$delete().then(
						function( data ) {
							$scope.Profiles.splice(idx, 1);
						});
				};
			}]);