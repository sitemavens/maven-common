<div ng-app="mavenApp" class="wrap">

	<div class="header" ng-controller='MainNavCtrl'>
		<ul class="nav nav-pills pull-right" >
			<li class="" ng-class="{active:isActive('/')}"><a ng-href="#/"><i class="glyphicon glyphicon-dashboard"></i>&nbsp;Dashboard</a></li>
			<li class="" ng-class="{active:isActive('/orders')}"><a ng-href="#/orders"><i class="glyphicon glyphicon-shopping-cart"></i>&nbsp; Orders</a></li>
			<li class="" ng-class="{active:isActive('/promotions')}"><a ng-href="#/promotions"><i class="glyphicon glyphicon-tags"></i>&nbsp;Promotions</a></li>
			<li class="" ng-class="{active:isActive('/taxes')}"><a ng-href="#/taxes"><i class="glyphicon glyphicon-flag"></i>&nbsp;Taxes</a></li>
			<li class="" ng-class="{active:isActive('/settings')}"><a ng-href="#/settings"><i class="glyphicon glyphicon-cog"></i>&nbsp;Settings</a></li>
		</ul>
		<h1 class="text-muted"><img src="http://www.sitemavens.com/wp-content/uploads/2014/05/SM-logo-transparent_rev.png" alt="SiteMavens"></h1>
	</div>

	<!-- Add your site or application content here -->
	<div data-loading >Loading...</div>
	<div ng-view></div>
</div>
