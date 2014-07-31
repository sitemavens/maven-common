'use strict';
angular.module('mavenApp')
		.controller('ModalInstanceCtrl',
				['$scope', '$modalInstance', '$http', 'message', 'id', 'email',
					function($scope, $modalInstance, $http, message, id, email) {
						$scope.message = message;
						$scope.ok = function() {
							$http.post('/wp-json/maven/profiletowpuser/' + id).success(function(data) {
								console.log(data);
								if (data === '""') {
									$scope.message = "The Profile has been associated";
								} else if (data === '"removed"') {
									$scope.message = "The Profile is no longer associated with a Wordpress User";
								} else {
									$scope.message = "An Wordpress user has been created, the username is: " + email + " and the password is: " + data;
								}
								$scope.getPage();

							});

						};
						$scope.cancel = function() {
							$modalInstance.dismiss('cancel');
						};
					}]);

