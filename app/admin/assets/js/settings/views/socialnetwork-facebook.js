define([ 'text!templates/socialnetwork-facebook.html','localization']
	,function( SocialNetworkFacebookTlt ,localization ){
		
		var SocialNetworkFacebookView = Backbone.View.extend({

			template: _.template( SocialNetworkFacebookTlt ),
			
			/* Bind controls to model attributes */
			bindings: {
				'#facebookAppId':'facebookAppId',
				'#facebookSecret':'facebookSecret',
				'#facebookAccessToken' : 'facebookAccessToken'
			},
			
			initialize: function( options ) {

			},
			
			render: function(){
		
				this.$el.html(this.template(localization.toJSON()));
				
				this.stickit();
				
				return this;
			
			}

		});
		return SocialNetworkFacebookView;
	})
