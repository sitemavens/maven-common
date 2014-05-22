define([],function(){
	
	var OrderItem = Backbone.Model.extend({
		//action: 'orderEntryPoint',
		defaults: {
		},
		// Constructor
		initialize: function() {

		},
		validation:{
			/*displayName: {
				required:true
			},
			website:{
				pattern:'url',
				required:false
			}*/
		}
	});
	
	return OrderItem;

});





