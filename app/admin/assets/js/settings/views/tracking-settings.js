define([ 'jquery',
	'text!templates/tracking-settings.html',
	'localization', 
	'views/tracker-google-analytics',
	'models/Option',
	'collections/trackers',
	'views/tracker-registered',
	'views/tracker-segment-io',
	'toggleButtons']
	,function( $, 
				TrackingSettingsTlt,
				localization, 
				TrackerGoogleAnalyticsView,
				Option, 
				Trackers, 
				TrackerRegisteredView ,
				TrackerSegmentIoView
			){
		
		var TrackingSettingsView = Backbone.View.extend({

			translation: null,
			template: _.template( TrackingSettingsTlt ),
			trackers : null,
			registeredTrackersModels:[],
			events: {
				"click #save": "saveSettings",
				"click .widget .tools .icon-chevron-down, .widget .tools .icon-chevron-up ": 'toggleWindow'
			},
			views: null,
			toggleWindow : function( element ){
				
				var el = jQuery(element.target).closest(".widget").children(".widget-body");
				
				if (jQuery(element.target).hasClass("icon-chevron-down")) {
					jQuery(element.target).removeClass("icon-chevron-down").addClass("icon-chevron-up");
					el.slideUp(200);
				} else {
					jQuery(element.target).removeClass("icon-chevron-up").addClass("icon-chevron-down");
					el.slideDown(200);
				}
			},
			/* Bind controls to model attributes */
			bindings: {
				
			},
			saveSettings: function(){
				
				// We have to update all the trackers
				this.trackers.update();
				
				
				var items = this.model.get('enabledTrackers');
				
				// We need to mach the models with the main model
				_.each( this.registeredTrackersModels, function( model ){
					
					items[model.get('id')] =   model.get('value')=='false'||model.get('value')==false?0:1 ;
					
				});
				
				
				this.model.save();
				
				
				return false;
				
			},
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			//el: '.settings',
			// Constructor
			initialize: function( options ) {

				// Read all the gateways
				this.trackers = new Trackers();
				this.trackers.reset(MavenTrackers);
				
			},
			
			render: function(){
				
				var that = this;
				
				this.$el.html(this.template(localization.toJSON()));
				
				
				var googleAnalyticsView = new TrackerGoogleAnalyticsView( {
					model: this.trackers.get('googleanalytics')
				} );
				
				 
				this.$el.prepend(googleAnalyticsView.render().el);
				
				var segmentIoView = new TrackerSegmentIoView( {
					model: this.trackers.get('segment.io')
				} );
				
				 
				this.$el.prepend(segmentIoView.render().el);

				// We need to render the registered plugins
				//var items = this.trackers;//this.model.get('enabledTrackers');
				var enabledTrackers = this.model.get('enabledTrackers');
				this.trackers.each( function( tracker ){
				
					var item = new Option();
					var key = tracker.get('id');
					item.set('id',key);
					
					//TODO: We have to do it more dynamic
					switch( key ){
						case "googleanalytics":
							item.set('name','Google Analytics');
							item.set('img',Maven.adminImagesPath+"logos/ga.png");
							
							break;
						case "segment.io":
							item.set('name','Segment.io');
							item.set('img',Maven.adminImagesPath+"logos/segment-io.png");
							
							break;
					}
					
					item.set('value',enabledTrackers[key]===false||enabledTrackers[key]==='0'?0:1 );
						 
					var view = new TrackerRegisteredView( {
						model:item
					});
					
					// We save the model, so we can update it later
					that.registeredTrackersModels.push( item );
					
					that.$el.find('#registered-trackers').append( view.render().el );
					
				});
				 
					 
				/*Bind model to view*/
				this.stickit();
				
				
				this.$('.toggle-button').toggleButtons({
					width: 100
				});


				return this;
				
				
				
				
			
			}

		});
		return TrackingSettingsView;
	})
