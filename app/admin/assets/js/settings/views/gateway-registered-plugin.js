define([ 'text!templates/gateway-registered-plugin.html','localization']
	,function( GatewayRegisteredPluginTlt ,localization ){
		
		var GatewayRegisteredPluginView = Backbone.View.extend({

			translation: null,
			template: _.template( GatewayRegisteredPluginTlt ),
			
			/* Bind controls to model attributes */
			bindings: {
				'#value':'value',
				'#name':{
					 observe: 'name',
					 updateMethod: 'html'
				}
			},
			
			initialize: function( options ) {

			},
			
			render: function(){
		
				this.$el.html(this.template(localization.toJSON()));
				
				// We need to set the plugin image
				this.$el.find('#avatar').attr('src',this.model.get('img'));
				
				this.stickit();
				
				return this;
			
			}

		});
		return GatewayRegisteredPluginView;
	})
