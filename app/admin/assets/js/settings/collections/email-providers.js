define(['models/email-provider'],function( EmailProvider ){
	
	var EmailProviders = Backbone.Collection.extend({
		action:'entryPointEmailProviders',	
		model: EmailProvider
		
	});

	
	return EmailProviders;

});
