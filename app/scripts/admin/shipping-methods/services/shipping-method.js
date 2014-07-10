var app = angular.module('mavenApp.services');

app.factory('ShippingMethod', ['$resource', function($resource) {
		return $resource('/wp-json/maven/shipping-methods/:id', {id: '@id'},{
			getPage: {
				method: 'GET',
				isArray: false
			}
		});
	}]);