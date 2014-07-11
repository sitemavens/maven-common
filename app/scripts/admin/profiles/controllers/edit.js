'use strict';

angular.module('mavenApp')
		.controller('ProfileEditCtrl',
				['$scope', '$http', '$routeParams', '$location', 'ProfileEdit', 'ProfileOrders', 'Profile', 'ProfileWpUser', 'Rol',
					function($scope, $http, $routeParams, $location, Profile, ProfileOrders, ProfileAddress, ProfileWpUser, Rol) {
						$scope.oneAtATime = true;
						$scope.profile = {};
						$scope.addressExists = {};
						$scope.hasPrimaryAddress;
						$scope.addresses = CachedAddresses;
						$scope.countries = CachedCountries;
						$scope.defaultRole = DefaultRole;
						$scope.radioModel = 'false';
						$scope.newAddress = {};
						$scope.salutations =
								[
									{id: 'dr', value: 'Dr.'},
									{id: 'mr', value: 'Mr.'},
									{id: 'ms', value: 'Ms.'},
									{id: 'mrs', value: 'Mrs.'}
								];
						if ($routeParams.id) {
							//$scope.profile;
							Profile.get({id: $routeParams.id}, function(data) {
								//if username is empty, default to email
								if (data.userName === null) {
									data.userName = data.email;
								}

								$scope.profile = data;
								ProfileOrders.getOrders($scope.profile.id).success(function(data){
									$scope.profile.orders = data;
								});
								ProfileWpUser.get({id: $scope.profile.email}, function(iswpuser) {
									$scope.profile.isWpUser = iswpuser.result;
									$scope.setRegister($scope.profile.isWpUser);
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

						$scope.saveProfile = function() {
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
							$scope.profile.$save(function(data) {
								$scope.profile = data;
								ProfileWpUser.get({id: $scope.profile.email}, function(iswpuser) {
									$scope.profile.isWpUser = iswpuser.result;
									$scope.setRegister($scope.profile.isWpUser);
								});
								$scope.rolQuery();

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
								return;
							}
							angular.forEach($scope.profile.addresses, function(profileAddress) {
								if (profileAddress.primary) {
									$scope.hasPrimaryAddress = true;
									return;
								}
							});
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
					}]);