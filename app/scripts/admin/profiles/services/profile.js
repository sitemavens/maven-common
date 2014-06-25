var app = angular.module('mavenApp.services');

app.factory('Profile', ['$resource', function($resource) {
		return $resource('/wp-json/maven/profiles/:id', {id: '@id'}, {
			getPage: {
				method: 'GET',
				isArray: false
			}
		});
	}]);

app.factory('ProfileAddress', ['$resource', function($resource) {
		return $resource('/wp-json/maven/profileaddress/:id', {id: '@id'});
	}]);