define(['models/promotion'],function( Promotion ){
	
	var Promotions = Backbone.Collection.extend({
		model: Promotion,
		//action:'promotionEntryPoint',
		initialize: function() {			
		}
	});
	
	return Promotions;
	
});





