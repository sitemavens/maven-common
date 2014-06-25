'use strict';
angular.module('mavenApp')
	.controller('ProfileCtrl',
		['$scope', '$location', 'Profile', 'ProfileFilter',
			function($scope, $location, Profile, ProfileFilter) {
				
				$scope.getPage = function() {
					Profile.getPage(ProfileFilter, function(result) {
						console.log(result);
						$scope.Profiles = result.items;
						$scope.totalItems = result.totalItems;
					});
				};

				$scope.ProfileFilter = ProfileFilter;
				$scope.getPage();

				$scope.selectPage = function(page) {
					ProfileFilter.page = page;
					console.log(page);
					$scope.getPage();
				};


				$scope.newProfile = function() {
					$location.path('profiles/new');
				};



				$scope.editProfile = function(id) {
					$location.path('profiles/edit/' + id);
				};

				$scope.deleteProfile = function(idx) {
					var profile = $scope.Profiles[idx];
					profile.$delete().then(
						function(data) {
							$scope.Profiles.splice(idx, 1);
						});
				};
			}]);