define(['dateJS'], function() {

	var Promotion = Backbone.Model.extend({
		action: 'promotionEntryPoint',
		defaults: {
			enabled: true,
			exclusive: false,
			limitOfUse: 0,
			uses: 0
		},
		// Constructor
		initialize: function() {

		},
		parse: function(response) {
			//parse to date php/mysql
			if (response.from != undefined && response.from.length > 0 && response.from != '0000-00-00') {
				response.from = Date.parse(response.from).toString('yyyy-MM-dd');
			} else {
				response.from = null;
			}
			if (response.to != undefined && response.to.length > 0 && response.to != '0000-00-00') {
				response.to = Date.parse(response.to).toString('yyyy-MM-dd');
			} else {
				response.to = null;
			}

			return response;
		},
		validation: {
			section: {
				required: true
			},
			name: {
				required: true
			},
			code: {
				required: true
			},
			type: {
				required: true
			},
			value: {
				required: true,
				pattern: 'number'
			},
			enabled: {
				required: true
			},
			exclusive: {
				required: true
			}
		}
	});

	return Promotion;

});





