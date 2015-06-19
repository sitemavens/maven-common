'use strict';

angular.module('mavenApp')
.controller('PromotionsEditCtrl',
['$scope', '$routeParams', '$location', 'Promotion',
function($scope, $routeParams, $location, Promotion) {
	$scope.promotion = {};
    $scope.rules = [];
	$scope.sections = CachedSections;
	$scope.types = CachedTypes;
	$scope.format = 'dd-MMMM-yyyy';
	$scope.promotionRules = [
		{label:'Item Amount', value:'item_amount'},
		{label:'Item Category', value:'item_category'},
		{label:'Item Id', value:'item_id'},
		{label:'Item Name', value:'item_name'},
		{label:'Item Quantity', value:'item_quantity'}
	];
	$scope.promotionConditions = [
		{label:'Is equal to', value:'is_equal_to'},
		{label:'Is not equal to', value:'is_not_equal_to'},
		{label:'Is greater than', value:'is_greater_than'},
		{label:'Is greater or equal than', value:'is_greater_or_equal_than'},
		{label:'Is lower than', value:'is_less_than'},
		{label:'Is lower or equal than', value:'is_less_or_equal_than'},
		{label:'Contains', value:'contain'},
		{label:'Does not contain', value:'not_contain'},
		{label:'Begins With', value:'begins_with'},
		{label:'Ends with', value:'ends_with'},
		{label:'In Category', value:'in_category'},
		{label:'Not in category', value:'not_in_category'}
	];
	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = true;
	};

	if ($routeParams.id) {
		$scope.promotion = Promotion.get({id: $routeParams.id});
		$scope.promotion.$promise.then(function(data) {
			$scope.rules = data.rules;
		});
	} else {
		$scope.promotion = new Promotion({enabled: true});
	}

	$scope.openDatePicker = function($event, input) {
		$event.preventDefault();
		$event.stopPropagation();
		if (input == 'from') {
			$scope.openTo = false;
			$scope.openFrom = true;
		} else {
			$scope.openFrom = false;
			$scope.openTo = true;
		}
	};

	$scope.savePromotion = function() {
		$scope.$broadcast('show-errors-check-validity');
		if ($scope.promotionForm.$invalid) {
			return;
		}
		if ($scope.promotion.section === 'item') {
        	$scope.promotion.rules = $scope.rules;
    	} else {
    		$scope.promotion.rules = {};
    	}
		$scope.promotion.$save(function(data) {
			$location.path('/promotions/edit/' + data.id);
		});

	};

	$scope.cancelEdit = function() {
		$location.path('/promotions/');
	};

    $scope.addRule = function() {
        var rule = {rule:'', condition:'', value:''};
        $scope.rules.push(rule);
    };

    $scope.removeRule = function(idx) {
        $scope.rules.splice(idx, 1);
    };


}]);


