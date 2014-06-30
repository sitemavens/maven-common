'use strict';

angular.module('mavenApp')
	.controller('ProfileEditCtrl',
		['$scope', '$routeParams', '$location', 'ProfileEdit', 'Profile', 'ProfileWpUser', 'Rol',
			function($scope, $routeParams, $location, Profile, ProfileAddress, ProfileWpUser, Rol) {
				$scope.oneAtATime = true;
				$scope.profile = {};
				$scope.listOfRoles = Array();
				$scope.addresses = CachedAddresses;
				$scope.countries = CachedCountries;
				$scope.radioModel = 'false';
				$scope.newAddress = {};
				$scope.salutations =
					[
						{id: 'dr', value: 'Dr.'},
						{id: 'mr', value: 'Mr.'},
						{id: 'ms', value: 'Ms.'},
						{id: 'mrs', value: 'Mrs.'}
					];

				//$scope.roles;
				if ($routeParams.id) {
					//$scope.profile;
					Profile.get({id: $routeParams.id}, function(data) {
						//if username is empty, default to email
						if (data.userName === null) {
							data.userName = data.email;
						}

						$scope.profile = data;
						ProfileWpUser.get({id: $scope.profile.email}, function(iswpuser) {
							$scope.profile.isWpUser = iswpuser.result;
							$scope.profile.register = false;
						});
						Rol.query(function(data) {
							$scope.roles = data;
							angular.forEach($scope.roles, function(rol) {
								var rolStatus = ({id: rol.id, status: $scope.checkRolIsEnabled(rol.id), name: rol.name});
								$scope.listOfRoles.push(rolStatus);
							});
						});
					});
				} else {
					$scope.profile = new Profile({enabled: true});
					$scope.profile.addresses = [];
					$scope.profile.roles = [];
					$scope.profile.register = false;
				}

				$scope.saveProfile = function() {
					$scope.profile.$save(function() {
						ProfileWpUser.get({id: $scope.profile.email}, function(iswpuser) {
							$scope.profile.isWpUser = iswpuser.result;
						});
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