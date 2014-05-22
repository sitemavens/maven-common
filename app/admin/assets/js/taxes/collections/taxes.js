define(['models/tax','pageable'],function( Tax ){
	
	var Taxes = Backbone.MavenCollection.extend({
		model: Tax,
		action:'taxEntryPoint',
		initialize: function() {			
		}
	});
	
	return Taxes;
	
});





