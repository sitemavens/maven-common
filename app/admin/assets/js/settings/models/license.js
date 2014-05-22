define([],function(){
	
	var License = Backbone.Model.extend({
		action:'entryPointLicense',	
		defaults: {
			
			value: ""
			 
		},
		// Constructor
		initialize: function() {

		},

		// Any time a Model attribute is set, this method is called
		validate: function(attrs) {

		}
	
	});
	
	return License;

});
