'use strict';
angular.module('mavenApp')
	.controller('AttrCtrl',
		['$scope', '$location', 'Attribute', 'AttributeFilter',
			function($scope, $location, Attribute, AttributeFilter) {
				
				$scope.getPage = function() {
					Attribute.getPage(AttributeFilter, function(result) {
						console.log(result);
						$scope.Attributes = result.items;
						$scope.totalItems = result.totalItems;
					});
				};

				$scope.AttributeFilter = AttributeFilter;
				$scope.getPage();

				$scope.selectPage = function(page) {
					AttributeFilter.page = page;
					console.log(page);
					$scope.getPage();
				};

				$scope.newAttr = function() {
					$location.path('attributes/new');
				};

				$scope.editAttr = function(id) {
					$location.path('attributes/edit/' + id);
				};

				$scope.deleteAttr = function(idx) {
					var attr = $scope.Attributes[idx];
					attr.$delete().then(
						function(data) {
							$scope.Attributes.splice(idx, 1);
						});
				};
			}]);