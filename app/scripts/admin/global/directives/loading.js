'use strict';

angular.module('mavenApp')
	.directive('loading', ['$http', function($http) {
		return {
			link: function(scope, elm, attrs)
			{
				scope.isLoading = function() {
					return $http.pendingRequests.length > 0;
				};

				scope.$watch(scope.isLoading, function(v)
				{
					if (v) {
						elm.show();
					} else {
						elm.hide();
					}
				});
			}
		};
	}]);
