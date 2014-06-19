'use strict';

angular.module('mavenApp')
	.controller('ProfileEditCtrl',
		['$scope', '$routeParams', '$location', 'Profile',
			function($scope, $routeParams, $location, Profile) {
				$scope.oneAtATime = true;
				$scope.profile = {};
				$scope.addresses = CachedAddresses;
				$scope.countries = CachedCountries;
				$scope.radioModel = 'false';
				$scope.newAddress = {};


				
				if ($routeParams.id) {
					$scope.profile = Profile.get({id: $routeParams.id});
				} else {
					$scope.profile = new Profile({enabled: true});
					$scope.profile.addresses = [];
				}
				$scope.saveProfile = function() {
					console.log('saving?');
					$scope.profile.$save();
				};
				$scope.cancelEdit = function() {
					$location.path('/profiles/');
				};
				$scope.deleteAddress = function(idx) {
					$scope.profile.addresses.splice(idx, 1);
						
				};
				console.log($scope.profile);
				$scope.addAddress = function(address) {
					$scope.addressExists = {};
					$scope.addressExists.name = '';
					$scope.addressExists.status = false;
					angular.forEach($scope.profile.addresses, function(profileAddress) {
						if (profileAddress.type == address.type) {
							$scope.addressExists.status = true;
							return false;
						}});
					if (!$scope.addressExists.status){
						$scope.profile.addresses.push(address);
					}
					$scope.addressExists.name = $scope.getAddressTypeName(address.type);
					console.log($scope.addressExists);
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