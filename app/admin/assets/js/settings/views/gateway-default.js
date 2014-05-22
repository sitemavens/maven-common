define([ 'text!templates/gateway-default.html','localization']
	,function( GatewayDefaultTlt ,localization ){
		
		var GatewayDefaultView = Backbone.View.extend({

			translation: null,
			template: _.template( GatewayDefaultTlt ),
			
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
		return GatewayDefaultView;
	})
