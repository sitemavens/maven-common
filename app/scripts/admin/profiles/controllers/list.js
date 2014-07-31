'use strict';
angular.module('mavenApp')
		.controller('ProfileCtrl',
				['$scope', '$location', '$modal', 'Profile', 'ProfileFilter', 'ProfileToWp', 'ProfileWpUser',
					function($scope, $location, $modal, Profile, ProfileFilter, ProfileToWp, ProfileWpUser) {
						$scope.imageUrl = Maven.imagesUrl;
						$scope.registrationContainer = false;
						$scope.mailtoRegister = '';
						$scope.linktoWp = ProfileToWp;
						$scope.getPage = function() {
							Profile.getPage(ProfileFilter, function(result) {
								$scope.result = result;
								$scope.Profiles = result.items;
								$scope.totalItems = result.totalItems;
							});
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

						$scope.open = function(idx) {
							ProfileWpUser.get({id: $scope.Profiles[idx].email}, function(result) {
								$scope.isWpUser = result.isWpUser;
								$scope.userExists = result.userExists;
							});
							if (!$scope.isWpUser) {
								if ($scope.userExists) {
									$scope.message = "Do you wish to link this profile with a Wordpress User?";
								} else {
									$scope.message = "Do you wish to create a Wordpress user and link it with this profile?";
								}
							} else {
								$scope.message = "Do you wish to remove the link within this profile and its Wordpress User?";
							}
							$modal.open({
								scope: $scope,
								templateUrl: 'myModalContent.html',
								controller: 'ModalInstanceCtrl',
								resolve: {
									message: function() {
										return $scope.message;
									},
									id: function() {
										return $scope.Profiles[idx].id;
									},
									email: function() {
										return $scope.Profiles[idx].email;
									}
								}
							});

						};

					}]);