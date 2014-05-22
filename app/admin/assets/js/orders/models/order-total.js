define([],function(){
	
	var OrderTotal = Backbone.Model.extend({
		action:'orderStatsEntryPoint',
		defaults: {
			count: 0,
			total: 0
		},
		// Constructor
		initialize: function() {

		}
	});
	
	return OrderTotal;

});


