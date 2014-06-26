'use strict';

angular.module('mavenApp')
	.controller('ProfileEditCtrl',
		['$scope', '$routeParams', '$location', 'Profile', 'ProfileAddress',
			function($scope, $routeParams, $location, Profile, ProfileAddress) {
				$scope.oneAtATime = true;
				$scope.profile = {};
				$scope.addresses = CachedAddresses;
				$scope.countries = CachedCountries;
				$scope.radioModel = 'false';
				$scope.newAddress = {};
				$scope.salutations = [{id: 'dr', value: 'Dr.'},
					{id: 'mr', value: 'Mr.'},
					{id: 'ms', value: 'Ms.'},
					{id: 'mrs', value: 'Mrs.'}
				];
				if ($routeParams.id) {
					$scope.profile = Profile.get({id: $routeParams.id});
				} else {
					$scope.profile = new Profile({enabled: true});
					$scope.profile.addresses = [];
				}

				$scope.saveProfile = function() {
					$scope.profile.$save();
				};
				$scope.cancelEdit = function() {
					$location.path('/profiles/');
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
						addressToDelete.$delete({id: addressId}).then
					}
					$scope.profile.addresses.splice(idx, 1);
//					$scope.profile.addresses.splice(idx, 1);

				};
				$scope.addAddress = function(address) {
					$scope.addressExists = {};
					$scope.addressExists.name = '';
					$scope.addressExists.status = false;
					angular.forEach($scope.profile.addresses, function(profileAddress) {
						if (profileAddress.type == address.type) {
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