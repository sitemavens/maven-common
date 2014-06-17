'use strict';

angular.module('mavenApp')
	.controller('RolesEditCtrl',
		['$scope', '$routeParams', '$location', 'Rol',
			function($scope, $routeParams, $location, Rol) {
				$scope.Rol = {};
				console.log($routeParams.id);
				if ($routeParams.id) {
					$scope.rol = Rol.get({id: $routeParams.id});
				} else {
					$scope.rol = new Rol({enabled: true});
				}

				$scope.saveRol = function() {
					console.log('saving?');
					$scope.rol.$save();
				}

				$scope.cancelEdit = function() {
					$location.path('/roles/');
				}
			}]);


