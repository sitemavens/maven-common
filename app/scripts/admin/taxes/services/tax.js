var app = angular.module('mavenApp.services');

app.factory('Tax', ['$resource', function($resource) {
		return $resource('/wp-json/maven/taxes/:id', {id: '@id'});
	}]);