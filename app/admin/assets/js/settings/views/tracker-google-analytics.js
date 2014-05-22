define([ 'text!templates/tracker-google-analytics.html','localization']
	,function( TrackerGoogleAnalyticsTlt ,localization ){
		
		var TrackerGoogleAnalyticsView = Backbone.View.extend({

			template: _.template( TrackerGoogleAnalyticsTlt ),
			
			/* Bind controls to model attributes */
			bindings: {
				'#analyticsAccountId':'analyticsAccountId',
				'#domain':'domain'
			},
			
			initialize: function( options ) {

			},
			
			render: function(){
		
				this.$el.html(this.template(localization.toJSON()));
				
				this.stickit();
				
				return this;
			
			}

		});
		return TrackerGoogleAnalyticsView;
	})
