var app = angular.module('mavenApp.services');

/* Order Services */
app.factory('Order', ['$resource', function($resource) {
		return $resource('/wp-json/maven/orders/:id', {id: '@id'});
	}]);