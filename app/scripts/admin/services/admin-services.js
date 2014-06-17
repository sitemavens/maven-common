'use strict';
var admin = angular.module('mavenApp.services', ['ngResource']);
admin.factory('TaxesFilterService', [function() {
		return {
			search: null
		};
	}]);
admin.factory('TaxesService', ['$http', function($http) {

		return {
			get: function(taxId, callback) {
				var data = {
					action: 'mvn_getTax',
					taxId: taxId
				};
				$http({
					cache: false,
					method: 'POST',
					url: Maven.ajaxUrl,
					data: jQuery.param(data), // pass in data as strings $.param(data);
				}).success(function(data) {
					return callback(data);
				});
			},
			getList: function(filter, callback) {
				var data = {
					action: 'mvn_getTaxes',
					search: filter.search
				};
				$http({
					cache: false,
					method: 'POST',
					url: Maven.ajaxUrl,
					data: jQuery.param(data), // pass in data as strings $.param(data);
				}).success(function(data) {
					return callback(data);
				});
			}
		};
	}]);
admin.factory('Tax', ['$resource', function($resource) {
		return $resource('/wp-json/maven/taxes/:id', {id: '@id'});
	}]);

admin.factory('Promotion', ['$resource', function($resource) {
		return $resource('/wp-json/maven/promotions/:id', {id: '@id'},
		{export: {method: 'GET', params: {export: true}, isArray: false}});
	}]);

/* Helper to filter Order list*/
admin.factory('OrderFilter', [function() {
		return {
			page: 0,
			number: null,
			status: null
		};
	}]);
/* Order Services */
admin.factory('Order', ['$resource', function($resource) {
		return $resource('/wp-json/maven/orders/:id', {id: '@id'});
	}]);
//this allow for orders to be loaded before controller is instanciated
admin.factory('OrderLoader', ['Order', '$route', '$q',
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
/* End Order Services */
admin.factory('Https', ['$http', function($http) {

		return {
			getPages: function() {
				return $http.get('/wp-json/maven/https/');
			},
			save: function(pages) {
				return $http.post('/wp-json/maven/https/', pages);
			}
		};
	}
	]);

admin.factory('Setting', ['$resource', function($resource) {
		return $resource('/wp-json/maven/settings/:id', {id: '@id'}, {
			get: {
				method: "GET",
				cache: true
			}
		});
	}]);

/*services.factory('Forms', ['$http', function($http) {
 return {
 getForms: function(callback) {
 
 $http({
 cache: true,
 method: 'POST',
 url: GFSeoMk.ajaxUrl,
 data: 'action=getForms' // pass in data as strings $.param(data);
 }).success(function(response) {
 return callback(response);
 });
 
 }
 };
 }]);
 
 services.factory('FormsLoader', ['$http', '$q',
 function($http, $q) {
 return function() {
 var delay = $q.defer();
 $http({
 cache: true,
 method: 'POST',
 url: GFSeoMk.ajaxUrl,
 data: 'action=getForms' // pass in data as strings $.param(data);
 }).success(function(response) {
 delay.resolve(response);
 }).error(function() {
 delay.reject('Unable to fetch Forms');
 });
 return delay.promise;
 };
 }]);
 
 services.factory('MetricsLoader', ['$http', '$q',
 function($http, $q) {
 return function() {
 var delay = $q.defer();
 $http({
 cache: true,
 method: 'POST',
 url: GFSeoMk.ajaxUrl,
 data: 'action=getMetricsDefinition' // pass in data as strings $.param(data);
 }).success(function(response) {
 delay.resolve(response);
 }).error(function() {
 delay.reject('Unable to fetch Metrics');
 });
 return delay.promise;
 };
 }]);
 
 services.factory('Metrics', ['$http', function($http) {
 
 return {
 getData: function(filter, callback) {
 var data = {
 action: 'getMetric',
 stat: [filter.stat],
 from: filter.from,
 to: filter.to,
 interval: filter.interval,
 forms: []
 };
 if (filter.vsStat) {
 data.stat.push(filter.vsStat);
 }
 for (var index = 0; index < filter.forms.length; ++index) {
 if (filter.forms[index].selected) {
 data.forms.push(filter.forms[index].id);
 }
 }
 
 $http({
 cache: true,
 method: 'POST',
 url: GFSeoMk.ajaxUrl,
 data: jQuery.param(data), // pass in data as strings $.param(data);
 }).success(function(data) {
 return callback(data);
 });
 },
 getMetrics: function(callback) {
 
 $http({
 cache: true,
 method: 'POST',
 url: GFSeoMk.ajaxUrl,
 data: 'action=getMetricsDefinition' // pass in data as strings $.param(data);
 }).success(function(response) {
 return callback(response);
 });
 
 },
 getAllMetrics: function(filter, callback) {
 var data = {
 action: 'getAllMetrics',
 from: filter.from,
 to: filter.to,
 interval: filter.interval,
 forms: []
 };
 for (var index = 0; index < filter.forms.length; ++index) {
 if (filter.forms[index].selected) {
 data.forms.push(filter.forms[index].id);
 }
 }
 
 $http({
 cache: true,
 method: 'POST',
 url: GFSeoMk.ajaxUrl,
 data: jQuery.param(data), // pass in data as strings $.param(data);
 }).success(function(data) {
 return callback(data);
 });
 }
 };
 }]);
 
 services.factory('Referrals', ['$http', function($http) {
 
 return {
 getData: function(filter, callback) {
 var data = {
 action: 'getReferrals',
 from: filter.from,
 to: filter.to
 };
 
 $http({
 cache: true,
 method: 'POST',
 url: GFSeoMk.ajaxUrl,
 data: jQuery.param(data), // pass in data as strings $.param(data);
 }).success(function(data) {
 return callback(data);
 });
 }
 };
 }]);
 */