'use strict';
angular.module('mavenApp')
		.controller('ProfileCtrl',
				['$scope', '$location', '$modal', 'Profile', 'ProfileFilter', 'ProfileToWp',
					function($scope, $location, $modal, Profile, ProfileFilter, ProfileToWp) {
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

						$scope.linkProfile = function(idx) {
							$scope.mailtoRegister = $scope.Profiles[idx].email;
							$scope.registrationContainer = !$scope.registrationContainer;

						};

						$scope.toggleLinkToWp = function() {
							$scope.registrationContainer = !$scope.registrationContainer;
						};

						$scope.open = function(idx) {
							var isWpUser = $scope.hasRoles($scope.Profiles[idx].roles);
							if (isWpUser) {
								$scope.message = "Do you wish to link this profile with a Wordpress User?"
							} else {
								$scope.message = "Do you wish to create a Wordpress user and link it with this profile?"
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
										return $scope.Profiles[idx].email;
									}
								}
							});

						};

					}]);