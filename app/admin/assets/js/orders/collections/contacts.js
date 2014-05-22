define(['models/contact'],function( Contact ){
	
	var Contacts = Backbone.Collection.extend({
		model: Contact,
		//action:'orderEntryPoint',
		initialize: function() {			
		}
	});
	
	return Contacts;
	
});

