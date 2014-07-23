'use strict';

angular.module('mavenApp').service('MessageTypes', [function() {
		return {
			success: 'success',
			info: 'info',
			warning: 'warning',
			danger: 'danger'
		};
	}]);

angular.module('mavenApp').factory('messageInterceptor', ['$q', '$rootScope', 'MessageTypes', function($q, $rootScope, MessageTypes) {
		return function(promise) {
			return promise.then(function(response) {
				// Do nothing
				if (response.config.method !== 'GET') {
					//If not get, we show a success message
					$rootScope.$broadcast('message:add', {type: MessageTypes.success, text: response.statusText});
					//show success message
					//console.log('successs', response);
				}
				return response;
			}, function(response) {
				// My notify service updates the UI with the error message
				//notifyService(response);
				if (response.status === 400) {
					//error
					//console.log("error", response);
				} else
				if (response.status === 401) {
					//unauthorized
					//console.log("unauthorized", response);

				} else
				if (response.status === 404) {
					//not found
					//console.log("not found", response);
				} else
				if (response.status === 500) {
					//server error
					//console.log("server error", response);
				} else {
					//unhandled error
					// Also log it in the console for debug purposes
					//console.log("error", response);
				}
				$rootScope.$broadcast('message:add', {type: MessageTypes.danger, text: response.statusText});
				return $q.reject(response);
			});
		};
	}])

angular.module('mavenApp').controller('MessagesCtrl', ['$scope', '$rootScope', function($scope, $rootScope) {

		$scope.messages = [];

		//register message interceptor
		$rootScope.$on('message:add', function(event, data) {
			$scope.messages.push(data);
		});

		$scope.removeMessage = function(idx) {
			$scope.messages.splice(idx, 1);
		};

		/*$scope.messages.push({type: MessageTypes.success, text: 'tipo success'});
		 $scope.messages.push({type: MessageTypes.warning, text: 'tipo warning'});
		 $scope.messages.push({type:  MessageTypes.danger, text: 'tipo danger'});*/

	}]);
