define([],function( ){
	
	var Country = Backbone.Model.extend({
		action:'countryEP',	
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
	
	var Countries = Backbone.Collection.extend({
		action:'countryEP',	
		model: Country
	});

	
	return Countries;

});



