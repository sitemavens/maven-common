define(['models/attribute', 'pageable'],function( Attribute ){
	
	var Attributes = Backbone.MavenCollection.extend({
		model: Attribute,
		action:'attributeEntryPoint',
		initialize: function() {			
		}
	});
	
	return Attributes;
	
});





