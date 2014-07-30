'use strict';

angular.module('mavenApp')
		.controller('ProfileEditCtrl',
				['$scope', '$routeParams', '$location', 'ProfileOrders', 'Profile', 'ProfileAddress', 'ProfileWpUser', 'ProfileEntries', 'Rol',
					function($scope, $routeParams, $location, ProfileOrders, Profile, ProfileAddress, ProfileWpUser, ProfileEntries, Rol) {
						$scope.hideSections = $location.path() === '/profiles/new';
						$scope.oneAtATime = true;
						$scope.profile = {};
						$scope.imageUrl = Maven.imagesUrl;
						$scope.addressExists = {};
						$scope.hasPrimaryAddress;
						$scope.addresses = CachedAddresses;
						$scope.countries = CachedCountries;
						$scope.defaultRole = DefaultRole;
						$scope.radioModel = 'false';
						$scope.newAddress = {};
						$scope.orderDetail = {};
						$scope.salutations =
								[
									{id: 'dr', value: 'Dr.'},
									{id: 'mr', value: 'Mr.'},
									{id: 'ms', value: 'Ms.'},
									{id: 'mrs', value: 'Mrs.'}
								];

						$scope.getProfile = function() {
							if ($routeParams.id) {
								//$scope.profile;
								Profile.get({id: $routeParams.id}, function(data) {
									//if username is empty, default to email
									if (data.userName === null) {
										data.userName = data.email;
									}

									$scope.profile = data;
									if ($scope.profile.userName)
										ProfileOrders.getOrders($scope.profile.id).then(function(response) {
											$scope.profile.orders = response.data;
										});
									ProfileWpUser.get({id: $scope.profile.email}, function(result) {
										$scope.profile.isWpUser = result.isWpUser;
										$scope.profile.userExists = result.userExists;
										$scope.setRegister($scope.profile.isWpUser);
									});
									ProfileEntries.getEntries($scope.profile.email).then(function(response) {
										$scope.profile.gfEntries = response.data;
									});
									$scope.rolQuery();
								});
							} else {
								$scope.profile = new Profile({enabled: true});
								$scope.profile.addresses = [];
								$scope.profile.roles = [];
								$scope.profile.orders = [];
								$scope.profile.register = false;
							}
						};

						$scope.saveProfile = function() {
							if ($scope.profile.userName === undefined) {
								$scope.profile.userName = $scope.profile.email;
							}
							$scope.$broadcast('show-errors-check-validity');
							if ($scope.profileForm.profileStepOneForm.$invalid) {
								return;
							}
							$scope.profileHasPrimaryAddress();
							if ($scope.hasPrimaryAddress) {
								$scope.alertPrimaryAddress = false;
							} else {
								$scope.alertPrimaryAddress = true;
								return;
							}
							$scope.profile.$save().then(function(data) {
								$location.path('/profiles/edit/' + data.profileId);
								$scope.getProfile();
							});
						};

						$scope.cancelEdit = function() {
							$location.path('/profiles/');
						};

						$scope.checkRolIsEnabled = function(id) {
							var isEnabled = false;
							$scope.profile.roles.some(function(entry) {
								if (entry.id === id) {
									isEnabled = true;
									return isEnabled;
								} else {
									isEnabled = false;
								}
							});
							return isEnabled;
						};

						$scope.setRegister = function(data) {
							if (data) {
								$scope.profile.register = true;
							} else {
								$scope.profile.register = false;
							}
						};

						$scope.deleteAddress = function(idx, e) {
							if (e) {
								e.preventDefault();
								e.stopPropagation();
							}
							var addressId = $scope.profile.addresses[idx].id;
							if (addressId !== undefined) {
								var addressToDelete = {};
								addressToDelete = ProfileAddress.get({id: addressId});
								addressToDelete.$delete({id: addressId})
							}
							$scope.profile.addresses.splice(idx, 1);
						};

						$scope.deleteRol = function(id) {
							var index;
							$scope.profile.roles.some(function(entry, i) {
								if (entry.id === id) {
									index = i;
									return true;
								}
							});
							$scope.profile.roles.splice(index, 1);
						};

						$scope.addAddress = function(address) {
							if (Object.keys(address).length === 0) {
								return
							}
							$scope.addressExists = {};
							$scope.addressExists.name = '';
							$scope.addressExists.status = false;
							angular.forEach($scope.profile.addresses, function(profileAddress) {
								if (profileAddress.type === address.type) {
									$scope.addressExists.status = true;
									return false;
								}
							});
							if (!$scope.addressExists.status) {
								address.show = true;
								$scope.profile.addresses.push(address);
							}
							$scope.addressExists.name = $scope.getAddressTypeName(address.type);
							$scope.newAddress = {};
						};

						$scope.changeToPrimaryAddress = function(aObject) {
							angular.forEach($scope.profile.addresses, function(profileAddress) {
								if (profileAddress.type === aObject.type) {
								} else {
									if (aObject.primary) {
										$scope.alertPrimaryAddress = false;
										profileAddress.primary = false;
									} else {
										$scope.alertPrimaryAddress = true;
										$scope.addressExists.status = false;
									}
								}
							});
						};

						$scope.profileHasPrimaryAddress = function() {
							if (Object.keys($scope.profile.addresses).length === 0) {
								$scope.hasPrimaryAddress = true;
							}
							angular.forEach($scope.profile.addresses, function(profileAddress) {
								if (profileAddress.primary) {
									$scope.hasPrimaryAddress = true;
								}
							});
							if ($scope.hasPrimaryAddress) {
								return true;
							}
							$scope.hasPrimaryAddress = false;
							return;
						};

						$scope.addRoles = function(rol) {
							$scope.rolExists = {};
							$scope.rolExists.name = '';
							$scope.rolExists.status = false;
							angular.forEach($scope.profile.roles, function(profileRole) {
								if (profileRole.id === rol.id) {
									$scope.rolExists.status = true;
									return false;
								}
							});
							if (!$scope.rolExists.status) {
								$scope.profile.roles.push(rol);
							}
						};

						$scope.selectRol = function(id, idx) {
							var rol = $scope.roles[idx];

							angular.forEach($scope.listOfRoles, function(r) {
								if (r.id === id) {
									if (r.status === true) {
										$scope.deleteRol(id);
									} else if (r.status === false) {
										$scope.addRoles(rol);
									}
								}
							});
						};

						$scope.isRolSelected = function(id) {
							if ($scope.profile.currentRol.id === id) {
								return true;
							} else {
								return false;
							}

						};

						$scope.rolQuery = function() {
							$scope.listOfRoles = [];
							Rol.query(function(data) {
								$scope.roles = data;
								angular.forEach($scope.roles, function(rol) {
									var rolStatus = ({id: rol.id, status: $scope.checkRolIsEnabled(rol.id), name: rol.name});
									$scope.listOfRoles.push(rolStatus);
								});
								return $scope.listOfRoles;
							});
						};

						$scope.changeRol = function(id) {
							$scope.profile.currentRol = $scope.selectRol(id);
						};

						$scope.getAddressTypeName = function(id) {
							var name = "";
							angular.forEach($scope.addresses, function(address) {
								if (address.id === id) {
									name = address.name;
									return;
								}
							});

							return name;
						};

						$scope.showDetail = function(idx) {
							$scope.orderDetail[idx] = !$scope.orderDetail[idx];
						};

						$scope.getProfile();

					}]);