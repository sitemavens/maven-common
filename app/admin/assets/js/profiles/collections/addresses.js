define(['models/address'], function(Address) {

	var Addresses = Backbone.Collection.extend({
		model: Address,
		//action:'profileEntryPoint',
		initialize: function() {
			this.on("change:primary", this.changePrimary);
		},
		changePrimary: function(model, val, options) {
			if (val) {
				this.each(function(modelo) {
					if (modelo !== model) {
						modelo.set('primary', false);
					}
				}, model)
				//console.log(model);
			}
		}
	});

	return Addresses;

});





