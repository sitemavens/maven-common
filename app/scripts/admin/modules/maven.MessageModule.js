angular.module('maven.MessageModule',
	[
		'ngResource',
		'template/maven-message/main.html'
	]).
	config(['$httpProvider', function($httpProvider) {
			$httpProvider.responseInterceptors.push(['$q', function($q) {
					return function(promise) {
						return promise.then(function(response) {
							// Do nothing
							if (response.config.method !== 'GET') {
								//If not get, we show a success message

								alert(response.statusText);
								//show success message
								console.log('successs', response);
							}
							return response;
						}, function(response) {
							// My notify service updates the UI with the error message
							//notifyService(response);
							if (response.status === 400) {
								//error
								console.log("error", response);
							} else
							if (response.status === 401) {
								//unauthorized
								console.log("unauthorized", response);

							} else
							if (response.status === 404) {
								//not found
								console.log("not found", response);
							} else
							if (response.status === 500) {
								//server error
								alert(response.statusText);
								console.log("server error", response);
							} else {
								//unhandled error
								// Also log it in the console for debug purposes
								console.log("error", response);
							}

							return $q.reject(response);
						});
					};
				}]);
		}]).
	run(['$document', '$compile', '$rootScope', function($document, $compile, $rootScope) {
console.log('running');
			// keep this check for backwards compatibility for now
			if (!$document.find('maven-message').length) {
				// Compile message-center element
				var messageCenterElem = $compile('<maven-message></maven-message>')($rootScope);
				// Add element to body 
				$document.find('body').append(messageCenterElem);
			}
		}])
	.directive('mavenMessage', ['$timeout', 'MessageService', function($timeout, MessageService) {
			return {
				restrict: 'E',
				scope: {},
				templateUrl: 'template/maven-message/main.html',
				controller: ['$scope', function($scope) {
						$scope.removeItem = function(message) {
							// Maybe have a reference to the timeout on message for easier cancelling
							message.type ? remove($scope.impMessages, message) : remove($scope.messages, message);
						};
					}],
				link: function(scope) {

					scope.messages = [];
					scope.impMessages = [];
					var queue = [];
					var impQueue = [];

					scope.$on('MessageService.broadcast', function(event, message) {
						var q, list;
						if (message.type) {
							q = impQueue;
							list = scope.impMessages;
						}
						else {
							q = queue;
							list = scope.messages;
						}
						q.push(message);
						if (list.length < MessageService.config.max && q.length === 1) {
							// if it's the first item in queue and the max hasn't been hit yet, then start processing
							processQueue(q, list);
						}
					});

					function processQueue(q, list) {
						if (q.length === 0) {
							return;
						}
						var nextMsg = q.shift();
						list.push(nextMsg);

						$timeout(function() {
							remove(list, nextMsg);
							if (q.length > 0) {
								$timeout(function() {
									processQueue(q, list);
								}, nextMsg.timeout);
							}
						}, nextMsg.timeout);
					}
				}
			};
			function remove(array, item) {
				var index = array.indexOf(item);
				if (index !== -1) {
					array.splice(index, 1);
				}
				return array;
			}
		}]);


angular.module("template/maven-message/main.html", []).run(["$templateCache", function($templateCache) {
		$templateCache.put("template/maven-message/main.html",
			'<div>' +
			'Hola mundo' +
			'</div>');
	}]);

angular.module('maven.MessageModule').factory('MessageService', ['$rootScope', function($rootScope) {

	}]);

