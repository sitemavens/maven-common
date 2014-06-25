var app = angular.module('mavenApp.services');

/* Helper to filter Order list*/
app.factory('PromotionFilter', [function() {
		return {
			page: 1,
			number: null,
			status: null
		};
	}]);