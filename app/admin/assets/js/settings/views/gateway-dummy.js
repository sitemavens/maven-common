define([ 'text!templates/gateway-dummy.html','localization']
	,function( GatewayDummyTlt ,localization ){
		
		var GatewayDummyView = Backbone.View.extend({

			translation: null,
			template: _.template( GatewayDummyTlt ),
			
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
		return GatewayDummyView;
	})
