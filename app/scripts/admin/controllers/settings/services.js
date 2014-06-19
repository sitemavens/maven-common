var app = angular.module('mavenApp.services');

app.factory('Setting', ['$http', function($http) {
		return {
			get:function(){
				return $http.get('/wp-json/maven/settings');
			},
			save:function( settings ){
				return $http.post('/wp-json/maven/settings',settings);
			}
		};
	}]);

app.factory('Gateway', ['$http', function($http) {
		
		return {
			get:function(){
				return $http.get('/wp-json/maven/gateways');
			},
			save:function( gateways ){
				return $http.post('/wp-json/maven/gateways',gateways);
			}
		};
		
	}]);