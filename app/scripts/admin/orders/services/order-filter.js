var app = angular.module('mavenApp.services');

/* Helper to filter Order list*/
app.factory('OrderFilter', [function() {
		return {
			page: 0,
			number: null,
			status: null
		};
	}]);