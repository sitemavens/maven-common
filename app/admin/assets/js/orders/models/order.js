define(['dateJS', 'localization'], function(dateJS, localization) {

	var Order = Backbone.Model.extend({
		action: 'orderEntryPoint',
		defaults: {
			orderDate: Date.parse('today').toString('yyyy-MM-dd')
		},
		// Constructor
		initialize: function() {

		},
		validation: {
			shippingCarrier: 'validateShippingFields',
			shippingTrackingCode: 'validateShippingFields',
			shippingTrackingUrl: {
				pattern: 'url',
				required: false
			}

		},
		validateShippingFields: function(value, attr, computedState) {
			//Check if we are setting the shipment notice
			if (this.get('validateShipping')) {
				if (value.length === 0)
					return localization.get('required');
			}
		}
	});
	return Order;
});





