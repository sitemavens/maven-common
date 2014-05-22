define(['models/order','pageable'],function( Order ){
	
	var Orders = Backbone.MavenCollection.extend({
		model: Order,
		action:'orderEntryPoint',
		initialize: function() {			
		},
		state:{
			sortKey:'orderDate',
			order:1	
		}
	});
	
	return Orders;
	
});





