'use strict';
angular.module('mavenApp')
		.controller('ModalInstanceCtrl',
				['$scope', '$modalInstance', '$http', 'message', 'id', 'email',
					function($scope, $modalInstance, $http, message, id, email) {
						$scope.showClose = false;
						$scope.message = message;
						$scope.userEmail = email;
						$scope.userCreated = false;
						$scope.ok = function() {
							$http.post('/wp-json/maven/profiletowpuser/' + id).success(function(data) {
								console.log(data);
								if (data === '""') {
									$scope.message = "The Profile has been associated";
								} else if (data === '"removed"') {
									$scope.message = "The Profile is no longer associated with a Wordpress User";
								} else {
									$scope.userPassword = data;
									$scope.userCreated = true;
									$scope.message = "An Wordpress user has been created";
								}
								$scope.getPage();
								$scope.showClose = true;
							});

						};
						$scope.close = function() {
							$modalInstance.dismiss('cancel');
						};
						$scope.cancel = function() {
							$modalInstance.dismiss('cancel');
						};
					}]);

