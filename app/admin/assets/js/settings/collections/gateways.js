define(['models/gateway'],function( Gateway ){
	
	
	var Gateways = Backbone.Collection.extend({
		model: Gateway,
		action: 'entryPoint'
		
		
	});

	
	return Gateways;

});
