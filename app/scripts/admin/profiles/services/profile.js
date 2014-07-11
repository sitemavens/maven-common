var app = angular.module('mavenApp.services');

app.factory('Profile', ['$resource', function($resource) {
		return $resource('/wp-json/maven/profiles/:id', {id: '@id'}, {
			getPage: {
				method: 'GET',
				isArray: false
			}
		});
	}]);

app.factory('ProfileEdit', ['$resource', function($resource) {
		return $resource('/wp-json/maven/profiles/:id', {id: '@id'});
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
app.factory('ProfileOrders', ['$resource', function($resource) {
		return $resource('/wp-json/maven/profileorders/:id', {id: '@id'},{
			query: {
				method: 'GET',
				isArray: false
			}
		});
	}]);