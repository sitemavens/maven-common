var app = angular.module('mavenApp.services');

/* Order Services */
app.factory('Order', ['$resource', function($resource) {
		return $resource('/wp-json/maven/orders/:id', {id: '@id'}, {
			getPage: {
				method: 'GET',
				isArray: false,
				transformResponse: function(data, header) {
					var response = {};
					response.items = angular.fromJson(data);
					//console.log(header());
					response.totalItems = header('x-totalitems');
					return response;
				}
			}});
	}]);