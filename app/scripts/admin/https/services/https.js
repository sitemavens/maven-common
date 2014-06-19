var app = angular.module('mavenApp.services');


app.factory('Https', ['$http', function($http) {

		return {
			getPages: function() {
				return $http.get('/wp-json/maven/https/');
			},
			save: function(pages, callback) {
				$http.post('/wp-json/maven/https/', pages)
						.success(function(data) {
							return callback(data);
						})
						.error(function(data) {
							return callback(data, error);
						});
			}
		};
	}
]);

 