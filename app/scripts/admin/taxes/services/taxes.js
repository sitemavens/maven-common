var app = angular.module('mavenApp.services');

app.factory('TaxesService', ['$http', function($http) {

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