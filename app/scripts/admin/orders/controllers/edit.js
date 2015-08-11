'use strict';

angular.module('mavenApp')
	.controller('OrdersEditCtrl',
		['$scope', '$location', '$http', '$route', 'order',
			function($scope, $location, $http, $route, order) {
				var prevShippingInfo = {};
				$scope.orderStatuses = [];
				$scope.order = order;
				$scope.order.currentStatus = $scope.order.status;
				$scope.order.newStatus = $scope.order.currentStatus.id;
				$scope.showSendShipment = false;
				prevShippingInfo.shippingCarrier = $scope.order.shippingCarrier;
				prevShippingInfo.shippingTrackingCode = $scope.order.shippingTrackingCode;
				prevShippingInfo.shippingTrackingUrl = $scope.order.shippingTrackingUrl;
				
				$http.get('/wp-json/maven/orders/statuses').then(function(response) {
					$scope.orderStatuses = response.data;
				});

				$http.get('/wp-json/maven/orders/countries').then(function(response) {
					$scope.countriesNames = response.data;
				});
				
				$scope.saveOrder = function() {
					//disable send notice
					$scope.order.sendNotice = false;
					$scope.order.$save().then(function(response) {
						$route.reload();
					});					
				};
				
				$scope.getShippingAddress = function() {
					var shippingAddress = {};
					angular.forEach($scope.order.shippingContact.addresses, function(address) {
						if (address.type === 'shipping') {
							shippingAddress = address;
						};
					});
					return shippingAddress;
				};
				
				$scope.getBillingAddress = function() {
					var billingAddress = {};
					angular.forEach($scope.order.billingContact.addresses, function(address) {
						if (address.type === 'billing') {
							billingAddress =  address;
						};
					});
					return billingAddress;
				};
				
				$scope.shippingContactAddress = $scope.getShippingAddress();
				$scope.billingContactAddress = $scope.getBillingAddress();
				
				$scope.calculateTotal = function(item) {
					return item.quantity * item.price;
				};
				$scope.cancelEdit = function() {
					$location.path('/orders/');
				};

				$scope.showSendForm = function() {
					$scope.showSendShipment = true;
					$scope.order.shippingCarrier = '';
					$scope.order.shippingTrackingCode = '';
					$scope.order.shippingTrackingUrl = '';
				};

				$scope.sendShipmentInformation = function() {
					$scope.order.sendNotice = true;
					$scope.order.$save().then(function() {
						$scope.showSendShipment = false;
					});
				};

				$scope.cancelSend = function() {
					$scope.showSendShipment = false;
					$scope.order.shippingCarrier = prevShippingInfo.shippingCarrier;
					$scope.order.shippingTrackingCode = prevShippingInfo.shippingTrackingCode;
					$scope.order.shippingTrackingUrl = prevShippingInfo.shippingTrackingUrl;
				};
				
				$scope.changedStatus = function() {
					$scope.order.currentStatus = $scope.orderStatuses[$scope.order.newStatus];
				};
				
			}]);
