
<div ng-app="mavenApp" >
	<div class="wrap" >
		<div class="header "  ng-controller='MainNavCtrl'>
			<ul class="nav nav-pills subsubsub">
				<li class="" ng-class="{active:isActive('/')}"><a ng-href="#/"><i class="glyphicon glyphicon-dashboard"></i>&nbsp;Dashboard</a></li>
				<li class="" ng-class="{active:isActive('/roles')}"><a ng-href="#/roles"><i class="glyphicon glyphicon-user"></i>&nbsp; Roles</a></li>
				<li class="" ng-class="{active:isActive('/profiles')}"><a ng-href="#/profiles"><i class="glyphicon glyphicon-eye-open"></i>&nbsp; Profiles</a></li>
				<li class="" ng-class="{active:isActive('/orders')}"><a ng-href="#/orders"><i class="glyphicon glyphicon-shopping-cart"></i>&nbsp; Orders</a></li>
				<li class="" ng-class="{active:isActive('/promotions')}"><a ng-href="#/promotions"><i class="glyphicon glyphicon-tags"></i>&nbsp;Promotions</a></li>
				<li class="" ng-class="{active:isActive('/shipping-methods')}"><a ng-href="#/shipping-methods"><i class="glyphicon glyphicon-tags"></i>&nbsp;Shipping</a></li>
				<li class="" ng-class="{active:isActive('/taxes')}"><a ng-href="#/taxes"><i class="glyphicon glyphicon-flag"></i>&nbsp;Taxes</a></li>
				<li class="" ng-class="{active:isActive('/attributes')}"><a ng-href="#/attributes"><i class="glyphicon glyphicon-th-list"></i>&nbsp;Attributes</a></li>
				<li class="" ng-class="{active:isActive('/https')}"><a ng-href="#/https"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Https</a></li>
				<li class="" ng-class="{active:isActive('/settings')}"><a ng-href="#/settings"><i class="glyphicon glyphicon-cog"></i>&nbsp;Settings</a></li>
			</ul>
			<div data-loading class="pull-right" style="padding-top: 12px;"><img src="{{loadingPath}}" alt="loading" />&nbsp;<strong>Loading</strong></div>
		</div>

		<br/>
		<div class="clear" ng-controller="MessagesCtrl">
			<div ng-repeat="message in messages" class="updated maven-updated maven-{{message.data.type}}" role="alert">
				<button type="button" class="close" ng-click="removeMessage($index)" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				{{message.data.text}}
			</div>
		</div>
		<!-- Add your site or application content here -->

		<div ng-view class="clear" ></div>
	</div>
</div>
