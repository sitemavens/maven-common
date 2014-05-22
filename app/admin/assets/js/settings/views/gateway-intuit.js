define([ 'text!templates/gateway-intuit.html','localization']
	,function( GatewayIntuitTlt ,localization ){
		
		var GatewayIntuitView = Backbone.View.extend({

			translation: null,
			template: _.template( GatewayIntuitTlt ),
			authorizationType: [null, 
										{id:'AUTH_CAPTURE', name:'Authorize and Capture'},
										{id:'AUTH_ONLY', name:'Authorize only'}
									],
									
			/* Bind controls to model attributes */
			bindings: {
				'#authorizationType': {
						observe: 'authorizationType',
						selectOptions: {
								collection:function() {
									// Prepend null or undefined for an empty select option and value.
									return this.authorizationType;
								},
								labelPath: 'name',
								valuePath:'id'
							}
				
				} ,
				'#appLogin':'appLogin',
				'#connectionTicket':'connectionTicket'
			},
			
			initialize: function( options ) {

			},
			
		 
			render: function(){
		
				this.$el.html(this.template(localization.toJSON()));
				
				this.stickit();
				return this;
			
			}

		});
		return GatewayIntuitView;
	})
