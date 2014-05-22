define(['models/Theme'],function( Theme ){
	
	var Themes = Backbone.Collection.extend({
		action:'entryPointThemes',	
		model: Theme
		
	});

	
	return Themes;

});
