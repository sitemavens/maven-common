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

				$scope.deleteRol = function(idx) {
					var role = $scope.roles[idx];
					role.$delete().then(
						function( data ) {
							$scope.roles.splice(idx, 1);
						});
				};
			}]);