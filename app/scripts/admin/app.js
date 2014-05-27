'use strict';

angular
	.module('mavenApp', [
		'ngCookies',
		'ngResource',
		'ngSanitize',
		'ngRoute',
		'mavenApp.services'
	])
	.config(function($routeProvider) {
		$routeProvider
			.when('/', {
				templateUrl: Maven.viewsUrl + 'dashboard.html',
				controller: 'DashboardCtrl'
			})
			.when('/orders', {
				templateUrl: Maven.viewsUrl + 'orders/orders.php',
				controller: 'OrdersCtrl'
			})
			.when('/orders/edit/:id', {
				templateUrl: Maven.viewsUrl + 'orders/orders-edit.php',
				controller: 'OrdersEditCtrl'
			})
			.when('/taxes', {
				templateUrl: Maven.viewsUrl + 'taxes/taxes.html',
				controller: 'TaxesCtrl'
			})
			.when('/taxes/new', {
				templateUrl: Maven.viewsUrl + 'taxes/taxes-edit.html',
				controller: 'TaxesEditCtrl'
			})
			.when('/taxes/edit/:id', {
				templateUrl: Maven.viewsUrl + 'taxes/taxes-edit.html',
				controller: 'TaxesEditCtrl'
			})
			.when('/settings', {
				templateUrl: Maven.viewsUrl + 'settings.html',
				controller: 'SettingsCtrl'
			})
			.otherwise({
				redirectTo: '/'
			});
	});
