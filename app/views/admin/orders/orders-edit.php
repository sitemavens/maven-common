<h1>Order #{{order.number}} <span ng-show="order.transactionId !== ''">| <small>Transaction ID:<em>{{order.transactionId}}</em></small></span> | <small>Status: {{order.status.name}} <img ng-src="{{order.status.imageUrl}}"/></small></h1>
<table class="table panel panel-default">
	<thead class="panel-heading">
		<tr>
			<td>Items Ordered</td>
			<td>Quantity</td>
			<td>Item Price</td>
			<td>Item Total</td>
		</tr>
	</thead>
	<tbody>
		<tr ng-repeat="item in order.items">
			<td>{{item.name}}</td>
			<td>{{item.quantity}}</td>
			<td>{{item.price|currency}}</td>
			<td>{{calculateTotal(item) | currency}}</td>
		</tr>
		<tr>
			<td rowspan="4" colspan="2">
				<div><strong>Notes</strong></div>
				<textarea rows="5" class="large-text" ng-model="order.description"></textarea>
			</td>
			<td><strong>Subtotal:</strong></td>
			<td>{{order.subtotal|currency}}</td>
		</tr>
		<tr>
			<td><strong>Discount/s:</strong></td>
			<td>
				<div>-{{order.discountAmount|currency}}</div>
				<div ng-repeat="promotion in order.promotions| orderBy:'discountAmount':true">
					<small><em>{{promotion.name}} (-{{promotion.discountAmount|currency}})</em></small>
				</div>
			</td>
		</tr>
		<tr>
			<td><strong>Shipping:</strong></td>
			<td>
				{{order.shippingAmount|currency}}
			</td>
		</tr>
		<tr>
			<td><strong>Total</strong></td>
			<td>{{order.total|currency}}</td>
		</tr>
	</tbody>
</table>
<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Contact</div>
			<div class="panel-body">
				<div class="pull-right"><img ng-src="{{order.contact.profileImage}}"/></div>
				<p><strong>{{order.contact.firstName}} {{order.contact.lastName}}</strong></p>
				<p>{{order.contact.phone}}</p>
				<p><em>{{order.contact.email}}</em></p>
				<p>{{order.contact.company}}</p>
				<p>{{order.contact.addresses[0].firstLine}}</p>
				<p>{{order.contact.addresses[0].secondLine}}</p>
				<p>{{order.contact.addresses[0].city}} {{order.contact.addresses[0].state}} {{order.contact.addresses[0].zipcode}}</p>
				<p>{{order.contact.addresses[0].country}}</p>				
			</div>		
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Billing Contact</div>
			<div class="panel-body">
				<div class="pull-right"><img ng-src="{{order.billingContact.profileImage}}"/></div>
				<p><strong>{{order.billingContact.firstName}} {{order.billingContact.lastName}}</strong></p>
				<p>{{order.billingContact.phone}}</p>
				<p><em>{{order.billingContact.email}}</em></p>
				<p>{{order.billingContact.company}}</p>
				<p>{{order.billingContact.addresses[0].firstLine}}</p>
				<p>{{order.billingContact.addresses[0].secondLine}}</p>
				<p>{{order.billingContact.addresses[0].city}} {{order.billingContact.addresses[0].state}} {{order.billingContact.addresses[0].zipcode}}</p>
				<p>{{order.billingContact.addresses[0].country}}</p>	
			</div>		
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Shipping Contact</div>
			<div class="panel-body">
				<div class="pull-right"><img ng-src="{{order.shippingContact.profileImage}}"/></div>
				<p><strong>{{order.shippingContact.firstName}} {{order.shippingContact.lastName}}</strong></p>
				<p>{{order.shippingContact.phone}}</p>
				<p><em>{{order.shippingContact.email}}</em></p>
				<p>{{order.shippingContact.company}}</p>
				<p>{{order.shippingContact.addresses[0].firstLine}}</p>
				<p>{{order.shippingContact.addresses[0].secondLine}}</p>
				<p>{{order.shippingContact.addresses[0].city}} {{order.shippingContact.addresses[0].state}} {{order.shippingContact.addresses[0].zipcode}}</p>
				<p>{{order.shippingContact.addresses[0].country}}</p>
			</div>		
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Credit Card</div>
			<div class="panel-body">
				<p>{{order.creditCard.type}} XXXX-XXXX-XXXX-{{order.creditCard.number}} </p>
				<p>Expiration {{order.creditCard.month}}/{{order.creditCard.year}}</p>
			</div>		
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Shipment Information <button class="btn btn-xs pull-right" ng-hide="showSendShipment" ng-click="showSendShipment = true">Send</button></div>
			<div class="panel-body" ng-hide="showSendShipment">
				<p>{{order.shippingCarrier}}</p>
				<p><a ng-href="{{order.shippingTrackingCodeUrl}}">{{order.shippingTrackingCode}}</a></p>

			</div>
			<div class="panel-body" ng-show="showSendShipment">
				<p><input type="text" class="form-control" ng-model="order.shippingCarrier" placeholder="Carrier"></p>
				<p><input type="text" class="form-control" ng-model="order.shippingTrackingCode" placeholder="Tracking Code"></p>
				<p><input type="url" class="form-control" ng-model="order.shippingTrackingCodeUrl" placeholder="Tracking Code Url"></p>
				<p class="pull-right">
					<button class="btn btn-xs btn-success">Send</button>
					<button class="btn btn-xs" ng-click="showSendShipment = false">Cancel</button>
				</p>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Order Events</div>
			<div class="panel-body">
				<table class="table table-condensed">
					<tbody>
						<tr ng-repeat="state in order.statusHistory|orderBy:'timestamp':true">
							<td><img ng-src="{{state.imageUrl}}" /></td>
							<td>{{state.timestamp}}</td>
							<td>{{state.name}}</td>
							<td>{{state.statusDescription}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>