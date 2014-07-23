'use strict';
angular
	.module('mavenApp', [
		'ngCookies',
		'ngResource',
		'ngSanitize',
		'ngRoute',
		'ui.bootstrap',
		'ui.bootstrap.showErrors',
		'mavenApp.services',
		'mavenApp.controllers'
	])
	.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {
			$httpProvider.responseInterceptors.push('messageInterceptor');
			
			$routeProvider
				.when('/', {
					templateUrl: Maven.adminViewsUrl + 'dashboard/dashboard.php',
					controller: 'DashboardCtrl'
				})
				.when('/roles', {
					templateUrl: Maven.adminViewsUrl + 'roles/roles.php',
					controller: 'RolesCtrl'
				})
				.when('/roles/new', {
					templateUrl: Maven.adminViewsUrl + 'roles/roles-edit.php',
					controller: 'RolesEditCtrl'
				})
				.when('/roles/edit/:id', {
					templateUrl: Maven.adminViewsUrl + 'roles/roles-edit.php',
					controller: 'RolesEditCtrl'
				})
				.when('/profiles', {
					templateUrl: Maven.adminViewsUrl + 'profiles/profiles.php',
					controller: 'ProfileCtrl'
				})
				.when('/profiles/new', {
					templateUrl: Maven.viewHandlerUrl + 'maven/profiles-edit',
					controller: 'ProfileEditCtrl'
				})
				.when('/profiles/edit/:id', {
					templateUrl: Maven.viewHandlerUrl + 'maven/profiles-edit',
					controller: 'ProfileEditCtrl'
				})
				.when('/orders', {
					templateUrl: Maven.viewHandlerUrl + 'maven/orders',
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
				.when('/https', {
					templateUrl: Maven.adminViewsUrl + 'https/https.php',
					controller: 'HttpsCtrl'
				})
				.when('/promotions', {
					templateUrl: Maven.adminViewsUrl + 'promotions/promotions.php',
					controller: 'PromotionsCtrl'
				})
				.when('/promotions/new', {
					templateUrl: Maven.viewHandlerUrl + 'maven/promotions-edit',
					controller: 'PromotionsEditCtrl'
				})
				.when('/promotions/new-multiple', {
					templateUrl: Maven.viewHandlerUrl + 'maven/promotions-multiple-edit',
					controller: 'PromotionsEditMultipleCtrl'
				})
				.when('/promotions/edit/:id', {
					templateUrl: Maven.viewHandlerUrl + 'maven/promotions-edit',
					controller: 'PromotionsEditCtrl'
				})
				.when('/taxes', {
					templateUrl: Maven.adminViewsUrl + 'taxes/taxes.php',
					controller: 'TaxesCtrl'
				})
				.when('/taxes/new', {
					templateUrl: Maven.viewHandlerUrl + 'maven/taxes-edit',
					controller: 'TaxesEditCtrl'
				})
				.when('/taxes/edit/:id', {
					templateUrl: Maven.viewHandlerUrl + 'maven/taxes-edit',
					controller: 'TaxesEditCtrl'
				})
				.when('/attributes', {
					templateUrl: Maven.adminViewsUrl + 'attributes/attributes.php',
					controller: 'AttrCtrl'
				})
				.when('/attributes/new', {
					templateUrl: Maven.adminViewsUrl + 'attributes/attributes-edit.php',
					controller: 'AttrEditCtrl'
				})
				.when('/attributes/edit/:id', {
					templateUrl: Maven.adminViewsUrl + 'attributes/attributes-edit.php',
					controller: 'AttrEditCtrl'
				})
				.when('/shipping-methods', {
					templateUrl: Maven.adminViewsUrl + 'shipping-methods/shipping-methods.php',
					controller: 'ShippingMethodsCtrl'
				})
				.when('/shipping-methods/new', {
					templateUrl: Maven.adminViewsUrl + 'shipping-methods/shipping-methods-edit.php',
					controller: 'ShippingMethodsEditCtrl'
				})
				.when('/shipping-methods/edit/:id', {
					templateUrl: Maven.adminViewsUrl + 'shipping-methods/shipping-methods-edit.php',
					controller: 'ShippingMethodsEditCtrl'
				})
				.when('/settings', {
					templateUrl: Maven.viewHandlerUrl + 'maven/settings',
					controller: 'SettingsController'
				})
				.otherwise({
					redirectTo: '/'
				});
		}]);

angular.module('mavenApp.controllers', ['ngResource']);
angular.module('mavenApp.services', ['ngResource']);