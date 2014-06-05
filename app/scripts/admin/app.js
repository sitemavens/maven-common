'use strict';

angular
	.module('mavenApp', [
		'ngCookies',
		'ngResource',
		'ngSanitize',
		'ngRoute',
		'mavenApp.services'
	])
	.config(function($routeProvider) {console.log(Maven);
		$routeProvider
			.when('/', {
				templateUrl: Maven.adminViewsUrl + 'dashboard/dashboard.php',
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
				templateUrl: Maven.adminViewsUrl + 'taxes/taxes.php',
				controller: 'TaxesCtrl'
			})
			.when('/taxes/new', {
				templateUrl: Maven.adminViewsUrl + 'taxes/taxes-edit.php',
				controller: 'TaxesEditCtrl'
			})
			.when('/taxes/edit/:id', {
				templateUrl: Maven.adminViewsUrl + 'taxes/taxes-edit.php',
				controller: 'TaxesEditCtrl'
			})
			.when('/settings', {
				templateUrl: Maven.viewHandlerUrl + 'maven/settings/',
				controller: 'SettingsCtrl'
			})
			.otherwise({
				redirectTo: '/'
			});
	});
