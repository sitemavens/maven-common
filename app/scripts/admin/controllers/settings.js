'use strict';

angular.module('mavenApp')
		.controller('SettingsCtrl', ['$scope', 'Setting', function($scope, Setting) {

				$scope.setting = Setting.get(1);
 
				$scope.saveSettings = function(){
					$scope.setting.id = 1;
					$scope.setting.$save();
				};
			}]
);