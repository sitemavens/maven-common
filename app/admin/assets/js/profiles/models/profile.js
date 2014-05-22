define([],function(){
	
	var Profile = Backbone.Model.extend({
		action: 'profileEntryPoint',
		defaults: {
			statusImageUrl:''
		},
		// Constructor
		initialize: function() {

		},
		validation:{
			email:{
				pattern:'email',
				required:true
			},
			zip:{
				pattern:'number',
				required:false
			}
		}
	});
	
	return Profile;

});





