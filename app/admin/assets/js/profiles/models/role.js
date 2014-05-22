define([], function() {

	var Role = Backbone.Model.extend({
		defaults: {
			selected: false
		},
		// Constructor
		initialize: function() {

		},
		validation: {
			name: {
				required: true
			}
		}
	});

	return Role;

});






