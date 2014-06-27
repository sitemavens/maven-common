'use strict';
angular.module('mavenApp')
	.controller('ProfileCtrl',
		['$scope', '$location', 'Profile', 'ProfileFilter',
			function($scope, $location, Profile, ProfileFilter) {
				$scope.imageUrl = Maven.imagesUrl;
				$scope.getPage = function() {
					Profile.getPage(ProfileFilter, function(result) {
						$scope.result = result;
						$scope.Profiles = result.items;
						$scope.totalItems = result.totalItems;
					});
				};
				$scope.hasRoles = function(roles) {
					if (roles.length > 0) {
						return true;
					}
					return false;
				};
				$scope.ProfileFilter = ProfileFilter;
				$scope.getPage();

				$scope.selectPage = function(page) {
					ProfileFilter.page = page;
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
					$scope.result.$delete({id: profile.id}).then(
						function(data) {
							$scope.Profiles.splice(idx, 1);
						});
				};
			}]);