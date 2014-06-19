var app = angular.module('mavenApp.services');

app.factory('Rol', ['$resource', function($resource) {
		return $resource('/wp-json/maven/roles/:id', {id: '@id'});
	}]);