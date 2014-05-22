define(['models/profile','pageable'],function( Profile ){
	
	var Profiles = Backbone.MavenCollection.extend({
		model: Profile,
		action:'profileEntryPoint',
		initialize: function() {
		}
	});
	
	return Profiles;
	
});





