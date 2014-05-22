define([],function(){
	
	var Address = Backbone.Model.extend({
		//action: 'orderEntryPoint',
		defaults: {
			//id:Math.floor((Math.random()*100000)+1)
		},
		// Constructor
		initialize: function() {

		},
		validation:{
			/*displayName: {
				required:true
			},
			website:{
				pattern:'url',
				required:false
			}*/
		}
	});
	
	return Address;

});





