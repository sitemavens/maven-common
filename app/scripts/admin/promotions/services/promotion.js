var app = angular.module('mavenApp.services');

app.factory('Promotion', ['$resource', function($resource) {
		return $resource('/wp-json/maven/promotions/:id', {id: '@id'},
		{export: {method: 'GET', params: {export: true}, isArray: false}});
	}]);