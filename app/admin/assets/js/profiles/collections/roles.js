define(['models/role'],function( Role ){
	
	var Roles = Backbone.Collection.extend({
		model: Role,
		initialize: function() {			
		}
	});
	
	return Roles;
	
});





