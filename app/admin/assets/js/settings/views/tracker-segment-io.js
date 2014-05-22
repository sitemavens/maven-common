define([ 'text!templates/tracker-segment-io.html','localization']
	,function( TrackerSegmentIoTlt ,localization ){
		
		var TrackerSegmentIoView = Backbone.View.extend({

			template: _.template( TrackerSegmentIoTlt ),
			
			/* Bind controls to model attributes */
			bindings: {
				'#segmentIoKey':'segmentIoKey'
			},
			
			initialize: function( options ) {

			},
			
			render: function(){
		
				this.$el.html(this.template(localization.toJSON()));
				
				this.stickit();
				
				return this;
			
			}

		});
		return TrackerSegmentIoView;
	})
