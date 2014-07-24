'use strict';

angular.module('mavenApp')
		.controller('RolesEditCtrl',
				['$scope', '$routeParams', '$location', 'Rol',
					function($scope, $routeParams, $location, Rol) {
						$scope.Rol = {};
						if ($routeParams.id) {
							$scope.rol = Rol.get({id: $routeParams.id});
						} else {
							$scope.rol = new Rol({enabled: true});
						}
						$scope.saveRol = function() {
							//broadcast to validate form
							$scope.$broadcast('show-errors-check-validity');
							//if form invalid, do nothing
							if ($scope.rolesForm.$invalid) {
								return;
							}

							$scope.rol.$save().then(function(data) {
								$location.path('/roles/edit/' + data.id);
							});
						};
						$scope.cancelEdit = function() {
							$location.path('/roles/');
						};
					}]);


