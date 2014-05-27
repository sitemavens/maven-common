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
				templateUrl: Maven.adminViewsUrl + 'dashboard.html',
				controller: 'DashboardCtrl'
			})
			.when('/orders', {
				templateUrl: Maven.adminViewsUrl + 'orders/orders.php',
				controller: 'OrdersCtrl'
			})
			.when('/orders/edit/:id', {
				templateUrl: Maven.adminViewsUrl + 'orders/orders-edit.php',
				controller: 'OrdersEditCtrl'
			})
			.when('/taxes', {
				templateUrl: Maven.adminViewsUrl + 'taxes/taxes.html',
				controller: 'TaxesCtrl'
			})
			.when('/taxes/new', {
				templateUrl: Maven.adminViewsUrl + 'taxes/taxes-edit.html',
				controller: 'TaxesEditCtrl'
			})
			.when('/taxes/edit/:id', {
				templateUrl: Maven.adminViewsUrl + 'taxes/taxes-edit.html',
				controller: 'TaxesEditCtrl'
			})
			.when('/settings', {
				templateUrl: Maven.adminViewsUrl + 'settings.html',
				controller: 'SettingsCtrl'
			})
			.otherwise({
				redirectTo: '/'
			});
	});
