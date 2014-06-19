var app = angular.module('mavenApp.services');

app.factory('Attribute', ['$resource', function($resource) {
		return $resource('/wp-json/maven/attributes/:id', {id: '@id'});
	}]);