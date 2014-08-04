var app = angular.module('mavenApp.services');

/* Helper to filter Order list*/
app.factory('ProfileFilter', [function() {
		return {
			page: 1,
			email: null,
			firstName: null,
			lastName: null
		};
	}]);