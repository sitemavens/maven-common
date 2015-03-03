'use strict';

angular.module('mavenApp')
	.controller('OrdersEditCtrl',
		['$scope', '$location', 'order',
			function($scope, $location, order) {
				var prevShippingInfo = {};
				$scope.order = order;
				$scope.showSendShipment = false;
				prevShippingInfo.shippingCarrier = $scope.order.shippingCarrier;
				prevShippingInfo.shippingTrackingCode = $scope.order.shippingTrackingCode;
				prevShippingInfo.shippingTrackingUrl = $scope.order.shippingTrackingUrl;

				$scope.saveOrder = function() {
					//disable send notice
					$scope.order.sendNotice = false;

					$scope.order.$save();					
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
			}]);
