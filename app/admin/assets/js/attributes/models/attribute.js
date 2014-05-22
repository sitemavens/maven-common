define([],function(){
	
	var Attribute = Backbone.Model.extend({
		action: 'attributeEntryPoint',
		defaults: {
		},
		// Constructor
		initialize: function() {

		},
		validation:{
			name: {
				required:true
			}
		}
	});
	
	return Attribute;

});





