define(['models/page'],function( Page ){
	
	var Pages = Backbone.Collection.extend({
		model: Page,
		//action:'categoryEntryPoint',
		initialize: function() {			
		}
	});
	
	return Pages;
	
});








