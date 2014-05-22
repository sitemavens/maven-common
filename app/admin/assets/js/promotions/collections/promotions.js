define(['models/promotion','pageable'],function( Promotion ){
	
	var Promotions = Backbone.MavenCollection.extend({
		model: Promotion,
		action:'promotionEntryPoint',
		initialize: function() {			
		}
	});
	
	return Promotions;
	
});





