define(['models/role'],function( Category ){
	
	var Categories = Backbone.Collection.extend({
		model: Category,
		action:'roleEntryPoint',
		initialize: function() {			
		}
	});
	
	return Categories;
	
});





