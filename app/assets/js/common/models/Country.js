define([],function(){
	
	var Country = Backbone.Model.extend({
		action:'entryPoint',	
		defaults: {
			/*id:'',
			name: "",
			currencyCode: "",
			currencySymbol: "",
			units: ""*/
		},
		// Constructor
		initialize: function() {

		},

		// Any time a Model attribute is set, this method is called
		validate: function(attrs) {

		}
	
	});
	
	return Country;

});


