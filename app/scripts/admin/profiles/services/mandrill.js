angular.module('mavenApp.services').factory('Mandrill', ['$http', function($http) {

		return {
			getMessages: function(id) {
				return $http.get('/wp-json/maven/profile/' + id + '/mandrill');
			}
		};

	}]);
