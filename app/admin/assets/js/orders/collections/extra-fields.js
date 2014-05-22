define(['models/extra-field'],function( ExtraField ){
	
	var ExtraFields = Backbone.Collection.extend({
		model: ExtraField,
		//action:'orderEntryPoint',
		initialize: function() {			
		}
	});
	
	return ExtraFields;
	
});





