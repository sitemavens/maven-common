define([],function(){
	
	var Category = Backbone.Model.extend({
		action: 'entryPoint',
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
	
	return Category;

});





