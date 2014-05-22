define([], function() {

	var Address = Backbone.Model.extend({
		//action: 'donorEntryPoint',
		defaults: {
			type: DefaultAddressType,
			primary: false
		},
		// Constructor
		initialize: function() {

		},
		validation: {
			type: {
				required: true
			},
			name: {
				required: false
			},
			description: {
				required: false
			},
			firstLine: {
				required: false
			},
			secondLine: {
				required: false
			},
			neighborhood: {
				required: false
			},
			city: {
				required: false
			},
			state: {
				required: false
			},
			country: {
				required: false
			},
			zipcode: {
				//minLength: 4,
				required: false//IMPORTANT: by default validators are required
			}
		}
	});

	return Address;

});





