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

app.factory('ProfileRoles', ['$resource', function($resource) {
		return $resource('/wp-json/maven/profileroles/:id', {id: '@id'});
	}]);

app.factory('ProfileWpUser', ['$resource', function($resource) {
		return $resource('/wp-json/maven/profilewpuser/:id', {id: '@id'});
	}]);

app.factory('ProfileEntries', ['$http', function($http) {
		return {
			getEntries: function(email) {
				return $http.get('/wp-json/maven/profileentries/' + email);
			}
		};
	}]);

app.factory('ProfileOrders', ['$http', function($http) {
		return {
			getOrders: function(id) {
				return $http.get('/wp-json/maven/profileorders/' + id);
			}
		};
	}]);