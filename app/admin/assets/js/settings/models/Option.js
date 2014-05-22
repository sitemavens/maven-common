define([],function(){
	
	var Option = Backbone.Model.extend({
		action:'entryPoint',	
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
	
	return Option;

});
