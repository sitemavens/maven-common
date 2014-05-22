define(['models/socialnetwork'],function( SocialNetwork ){
	
	var SocialNetworks = Backbone.Collection.extend({
		model: SocialNetwork,
		action:'entryPointSocialNetworks'
	});

	
	return SocialNetworks;

});
