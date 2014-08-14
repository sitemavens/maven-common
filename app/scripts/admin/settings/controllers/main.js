'use strict';

var app = angular.module('mavenApp.controllers');

app.controller('SettingsController', ['$scope', 'Setting', 'Gateway', function($scope, Setting, Gateway) {

		$scope.gatewaySettings = {
			active: 'dummy'
		};
		
		$scope.countries = CountriesCached;
		$scope.currencyFormats = CurrencyFormatsCached;

		Setting.get().then(function(result) {
			$scope.setting = result.data;
		});

		Gateway.get().then(function(result) {
			$scope.gateways = result.data;

		});

		$scope.saveSettings = function() {
			Setting.save($scope.setting).then(function(result) {
				Gateway.save($scope.gateways);
			});
		};
	}]
		);
