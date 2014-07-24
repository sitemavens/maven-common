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
					$rootScope.$broadcast('message:add', {type: MessageTypes.success, text: response.statusText, url: response.config.url});
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
				$rootScope.$broadcast('message:add', {type: MessageTypes.danger, text: response.statusText, url: response.config.url});
				return $q.reject(response);
			});
		};
	}])

angular.module('mavenApp').controller('MessagesCtrl', ['$q', '$scope', '$rootScope', '$timeout', function($q, $scope, $rootScope, $timeout) {
		//register message interceptor
		var insertMessage = true;
		$rootScope.$on('message:add', function(event, data) {
			var id = 0;
			angular.forEach($scope.messages, function(message) {
				if (message.data.url === data.url) {
					if (message.data.type !== data.type) {
						insertMessage = true;
						$scope.removeMessage(id);
					} else {
						insertMessage = false;
					}
				}
				id++;
			});
			if (insertMessage) {
				$scope.messages.push({data: data});
			}
			$scope.checkAlerts();
		});

		//On URL changes reset Messages
		$rootScope.$on('$routeChangeSuccess', function(data) {
			$scope.messages = [];
		});

		$scope.removeMessage = function(idx) {
			$scope.messages.splice(idx, 1);
		};

		//Check the alerts afther a known period of time
		$scope.checkAlerts = function() {
			$timeout(function() {
				$scope.removeAlerts();
			}, 3000);
		};

		//Remove Success alerts of array
		$scope.removeAlerts = function() {
			var id = 0;
			var deferred = $q.defer();
			angular.forEach($scope.messages, function(message) {
				if (message.data.type === 'success') {
					$scope.messages.splice(id, 1);
					deferred.resolve();
				}
				id++;
			});
		};

		/*$scope.messages.push({type: MessageTypes.success, text: 'tipo success'});
		 $scope.messages.push({type: MessageTypes.warning, text: 'tipo warning'});
		 $scope.messages.push({type:  MessageTypes.danger, text: 'tipo danger'});*/

	}]);
