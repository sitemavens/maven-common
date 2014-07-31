'use strict';
angular.module('mavenApp')
		.controller('ModalInstanceCtrl',
				['$scope', '$modalInstance', 'message', 'id', 'ProfileToWp',
					function($scope, $modalInstance, message, id, ProfileToWp) {

						$scope.message = message;
						$scope.ok = function() {
							ProfileToWp.registerProfile(id).then(function(data) {
							console.log(data);
							});
						};

						$scope.cancel = function() {
							$modalInstance.dismiss('cancel');
						};


					}]);

