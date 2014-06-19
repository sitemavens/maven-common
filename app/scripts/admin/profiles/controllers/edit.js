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
				console.log($scope.profile);
				$scope.saveProfile = function() {
					console.log('saving?');
					$scope.profile.$save();
				};
				$scope.cancelEdit = function() {
					$location.path('/profiles/');
				};
				$scope.addAddress = function(address) {
					$scope.profile.addresses.push(address);
					$scope.newAddress = {};

				};
				
				$scope.getAddressTypeName = function(id){
					var name="";
					angular.forEach($scope.addresses, function (address){
						console.log(address.id);
						if ( address.id === id){
							name = address.name;
							return;
						}
					});
					
					return name;
				};
			}]);