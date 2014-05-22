define(['models/tracker'],function( Tracker ){
	
	var Trackers = Backbone.Collection.extend({
		model: Tracker,
		action:'entryPointTrackers'
	});

	
	return Trackers;

});
