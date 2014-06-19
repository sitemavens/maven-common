'use strict';
angular.module('mavenApp')
	.controller('AttrCtrl',
		['$scope', '$location', 'Attribute',
			function($scope, $location, Attribute) {
				$scope.Attributes = Attribute.query();


				$scope.newAttr = function() {
					$location.path('attributes/new');
				};

				$scope.editAttr = function(id) {
					$location.path('attributes/edit/' + id);
				};

				$scope.deleteAttr = function(idx) {
					var attr = $scope.Attributes[idx];
					attr.$delete().then(
						function( data ) {
							$scope.Attributes.splice(idx, 1);
						});
				};
			}]);