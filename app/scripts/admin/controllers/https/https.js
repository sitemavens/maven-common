'use strict';

angular.module('mavenApp')
		.controller('HttpsCtrl', ['$scope', 'Https', function($scope, Https) {

				Https.getPages().then(function( result ){ $scope.pageList = result.data; });
 
				$scope.saveHttps = function(){
					Https.save( $scope.pageList );
				};
			}]
);