define(['models/order-status'],function( OrderItem ){
	
	var OrderStatuses = Backbone.Collection.extend({
		model: OrderItem,
		//action:'orderEntryPoint',
		initialize: function() {			
		}
	});
	
	return OrderStatuses;
	
});





