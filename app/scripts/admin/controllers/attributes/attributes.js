'use strict';
angular.module('mavenApp')
	.controller('AttrCtrl',
		['$scope', '$location', 'mvnAttribute',
			function($scope, $location, mvnAttribute) {
				$scope.mvnAttributes = mvnAttribute.query();


				$scope.newAttr = function() {
					$location.path('attributes/new');
				};

				$scope.editAttr = function(id) {
					$location.path('attributes/edit/' + id);
				};

				$scope.deleteAttr = function(idx) {
					var attr = $scope.mvnAttributes[idx];
					attr.$delete().then(
						function( data ) {
							$scope.mvnAttributes.splice(idx, 1);
						});
				};
			}]);