var app = angular.module('mavenApp.services');

//this allow for orders to be loaded before controller is instanciated
app.factory('OrderLoader', ['Order', '$route', '$q',
	function(Order, $route, $q) {
		return function() {
			var delay = $q.defer();
			Order.get({id: $route.current.params.id}, function(order) {
				delay.resolve(order);
			}, function() {
				delay.reject('Unable to fetch order ' + $route.current.params.id);

			});
			return delay.promise;
		};
	}]);