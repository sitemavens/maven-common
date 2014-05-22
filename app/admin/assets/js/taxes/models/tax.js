define([],function(){
	
	var Tax = Backbone.Model.extend({
		action: 'taxEntryPoint',
		defaults: {
			enabled:true,
			forShipping:false,
			compound:false
		},
		// Constructor
		initialize: function() {

		},
		parse:function(response){
			return response;
		},
		validation:{
			name:{
				required:true
			},
			value:{
				required:true,
				pattern:'number'				
			},
			enabled:{
				required:true
			}
		}
	});
	
	return Tax;

});





