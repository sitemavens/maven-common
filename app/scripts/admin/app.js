'use strict';

angular
	.module('mavenApp', [
		'ngCookies',
		'ngResource',
		'ngSanitize',
		'ngRoute',
		'ui.bootstrap',
		'mavenApp.services'
	])
	.config(function($routeProvider) {
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
				controller: 'OrdersEditCtrl',
				resolve: {
					order: ['OrderLoader', function(OrderLoader) {
							return OrderLoader();
						}]
				}
			})
			.when('/promotions', {
				templateUrl: Maven.adminViewsUrl + 'promotions/promotions.php',
				controller: 'PromotionsCtrl'
			})
			.when('/promotions/new', {
				templateUrl: Maven.viewHandlerUrl + 'maven/promotions-edit',
				controller: 'PromotionsEditCtrl'
			})
			.when('/promotions/edit/:id', {
				templateUrl: Maven.viewHandlerUrl + 'maven/promotions-edit',
				controller: 'PromotionsEditCtrl'
			})
			.when('/taxes', {
				templateUrl: Maven.adminViewsUrl + 'taxes/taxes.php',
				controller: 'TaxesCtrl'
			})
			.when('/https', {
				templateUrl: Maven.adminViewsUrl + 'https/https.php',
				controller: 'HttpsCtrl'
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
