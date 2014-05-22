define([ 'text!templates/gateway-navigate.html','localization']
	,function( GatewayNavigateTlt ,localization ){
		
		var GatewayNavigateView = Backbone.View.extend({

			translation: null,
			template: _.template( GatewayNavigateTlt ),
			authorizationType: [null, 
										{id:'AUTH_CAPTURE', name:'Authorize and Capture'},
										{id:'AUTH_ONLY', name:'Authorize only'},
										{id:'PRIOR_AUTH_CAPTURE', name:'PRIOR_AUTH_CAPTURE'},
										{id:'CAPTURE_ONLY', name:'Capture only'},
										{id:'CREDIT', name:'Credit'}
										
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
						
				
				},
				'#transactionKey':'transactionKey',
				'#loginLive':'login'
			},
			
			initialize: function( options ) {

			},
			
		 
			render: function(){
		
				this.$el.html(this.template(localization.toJSON()));
				
				this.stickit();
				return this;
			
			}

		});
		return GatewayNavigateView;
	})
