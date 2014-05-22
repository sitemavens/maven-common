define([ 'text!templates/tracker-registered.html','localization']
	,function( TrackerRegisteredTlt ,localization ){
		
		var TrackerRegisteredPluginView = Backbone.View.extend({

			translation: null,
			template: _.template( TrackerRegisteredTlt ),
			
			/* Bind controls to model attributes */
			bindings: {
				'#value':{
					observe:'value'
				},
				'#name':{
					 observe: 'name',
					 updateMethod: 'html'
				}
			},
			
			initialize: function( options ) {

			},
			
			render: function(){
		
				this.$el.html(this.template(localization.toJSON()));
				
				this.stickit();
				
				// We need to set the plugin image
				this.$el.find('#avatar').attr('src',this.model.get('img'));
				
				if ( this.model.get('value')===1)
					this.$el.find('#value').attr('checked','checked');
				
				
				
				
				 
				return this;
			
			}

		});
		return TrackerRegisteredPluginView;
	})
