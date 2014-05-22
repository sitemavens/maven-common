define([],function(){
	
	var GeneralSetting = Backbone.Model.extend({
		action:'entryPoint',	
		defaults: {
//			properties: [
//				'exceptionNotification','activeThemeName','activeGateway','registeredPluginsGateway','enabledTrackers'
//			]
		},
		// Constructor
		initialize: function() {

		},

		// Any time a Model attribute is set, this method is called
		validate: function(attrs) {

		}
	
	});
	
	return GeneralSetting;

});
