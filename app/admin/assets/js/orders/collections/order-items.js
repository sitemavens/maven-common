define(['models/order-item'],function( OrderItem ){
	
	var OrderItems = Backbone.Collection.extend({
		model: OrderItem,
		//action:'orderEntryPoint',
		initialize: function() {			
		}
	});
	
	return OrderItems;
	
});





