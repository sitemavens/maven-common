'use strict';
angular.module('mavenApp')
	.controller('RolesCtrl',
		['$scope', '$location', 'Rol',
			function($scope, $location, Rol) {
				$scope.roles = Rol.query();


				$scope.newRol = function() {
					$location.path('roles/new');
				};

				$scope.editRol = function(id) {
					$location.path('roles/edit/' + id);
				};

				$scope.deleteRol = function(role) {
					console.log(role);
					role.$delete().then(
						function( data ) {
							console.log(data);
							//$scope.roles.splice(idx, 1);
						});
				};
			}]);