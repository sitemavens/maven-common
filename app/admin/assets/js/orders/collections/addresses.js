define(['models/address'],function( Address ){
	
	var Addresses = Backbone.Collection.extend({
		model: Address,
		//action:'orderEntryPoint',
		initialize: function() {			
		}
	});
	
	return Addresses;
	
});

