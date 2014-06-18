'use strict';

var app = angular.module('mavenApp.controllers');

app.controller('SettingsController', ['$scope', 'Setting', 'Gateway', function($scope, Setting, Gateway) {

		$scope.gatewaySettings = {
			active: 'dummy'
		};

		Setting.get().then(function(result) {
			$scope.setting = result.data;
		});

		Gateway.get().then(function(result) {
			$scope.gateways = result.data;
			console.log(result.data);
		});

		$scope.saveSettings = function() {
			Setting.save($scope.setting);
		};
	}]
		);
