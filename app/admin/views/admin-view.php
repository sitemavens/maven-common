<div ng-app="mavenApp">

	<div class="header" ng-controller='MainNavCtrl'>
		<ul class="nav nav-pills pull-right" >
			<li class="" ng-class="{active:isActive('/')}"><a ng-href="#/">Dashboard</a></li>
			<li class="" ng-class="{active:isActive('/taxes')}"><a ng-href="#/taxes">Taxes</a></li>
			<li class="" ng-class="{active:isActive('/settings')}"><a ng-href="#/settings">Settings</a></li>
		</ul>
		<h3 class="text-muted">Maven Common (SiteMavens &copy;) {{isSettings}}</h3>
	</div>

	<!-- Add your site or application content here -->
	<div data-loading >Loading...</div>
	<div class="container" ng-view></div>
</div>
