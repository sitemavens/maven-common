var app = angular.module('mavenApp.services');


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