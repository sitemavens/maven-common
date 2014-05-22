define([],function(){
	
	var EmailTest = Backbone.Model.extend({
		action:'emailEntryPoint',	
		defaults: {
			
			emailProvider: "",
			to: "",
			cc: "",
			bcc: "",
			subject: "",
			message: ""
		},
		// Constructor
		initialize: function() {

		},

		// Any time a Model attribute is set, this method is called
		validate: function(attrs) {

		}
	
	});
	
	return EmailTest;

});
