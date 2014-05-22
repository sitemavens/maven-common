define([ 'text!templates/gateway-offline.html','localization']
	,function( GatewayOfflineTlt ,localization ){
		
		var GatewayOfflineView = Backbone.View.extend({

			translation: null,
			template: _.template( GatewayOfflineTlt ),
			
			/* Bind controls to model attributes */
			bindings: {
			
			},
			
			initialize: function( options ) {

			},
			
			render: function(){
		
				this.$el.html(this.template(localization.toJSON()));
				
				return this;
			
			}

		});
		return GatewayOfflineView;
	})
