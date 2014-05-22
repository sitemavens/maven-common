define([],function(){
	
	var Theme = Backbone.Model.extend({
		defaults: {
			name: "",
			label: "",
			type: "",
			value: "",
			defaultValue: "",
			group: ""
		},
		// Constructor
		initialize: function() {

		},

		// Any time a Model attribute is set, this method is called
		validate: function(attrs) {

		}
	
	});
	
	return Theme;

});
