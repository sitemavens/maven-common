'use strict';

angular.module('mavenApp')
		.controller('HttpsCtrl', ['$scope', 'Https', function($scope, Https) {
				$scope.httpsObj = {};
				$scope.httpsObj.error = false;
				$scope.httpsObj.updated = false;
				Https.getPages().then(function( result ){ $scope.httpsObj.pageList = result.data; });
 
				$scope.showMessage = function(data, error){
					if( error ){
						$scope.httpsObj.error = true;
						$scope.httpsObj.updated = false;
					}else{
						$scope.httpsObj.updated = true;
						$scope.httpsObj.error = false;
					}
				};
 
				$scope.saveHttps = function(){
					Https.save( $scope.httpsObj.pageList, $scope.showMessage );
				};
			}]
);