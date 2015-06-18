var app = angular.module('mavenApp.services');

/* Helper to filter Order list*/
app.factory('OrderFilter', [function() {
		return {
			page: 1,
			number: null,
            customer: null,
			status: null,
			start:null,
			end:null
		};
	}]);