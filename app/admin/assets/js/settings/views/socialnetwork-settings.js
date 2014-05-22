define([ 'jquery',
	'text!templates/socialnetwork-settings.html',
	'localization', 
	'views/socialnetwork-facebook',
	'models/Option',
	'collections/socialnetworks',
	'views/tracker-registered',
	'toggleButtons']
	,function( $, 
				SocialNetworkSettingsTlt,
				localization, 
				SocialNetworkFacebookView,
				Option, 
				SocialNetworks,
				TrackerRegisteredView
			){
		
		var SocialNetworkSettingsView = Backbone.View.extend({

			translation: null,
			template: _.template( SocialNetworkSettingsTlt ),
			socialNetworks : null,
			registeredSocialNetworksModels:[],
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
				this.socialNetworks.update();
				
				
				var items = this.model.get('enabledSocialNetworks');
				
				// We need to mach the models with the main model
				_.each( this.registeredSocialNetworksModels, function( model ){
					
					items[model.get('id')] =   model.get('value') === false?0:1;
					
				});
				
				
				this.model.save();
				
				
				return false;
				
			},
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			//el: '.settings',
			// Constructor
			initialize: function( options ) {

				// Read all the gateways
				this.socialNetworks = new SocialNetworks();
				this.socialNetworks.reset(MavenSocialNetworks);
				
			},
			
			render: function(){
				
				var that = this;
				
				this.$el.html(this.template(localization.toJSON()));
				
				
				var facebookView = new SocialNetworkFacebookView( {
					model: this.socialNetworks.get('facebook')
				} );
				
				 
				this.$el.prepend(facebookView.render().el);
				
				// We need to render the registered plugins
				//var items = this.trackers;//this.model.get('enabledTrackers');
				var enabledSocialNetworks = this.model.get('enabledSocialNetworks');
				this.socialNetworks.each( function( tracker ){
				
					var item = new Option();
					var key = tracker.get('id');
					item.set('id',key);
					
					//TODO: We have to do it more dynamic
					switch( key ){
						case "facebook":
							item.set('name','Facebook');
							item.set('img',Maven.adminImagesPath+"logos/facebook.png");
							
							break;
					}
					
					item.set('value',enabledSocialNetworks[key]===false||enabledSocialNetworks[key]==='0'?0:1 );
						 
					var view = new TrackerRegisteredView( {
						model:item
					});
					
					// We save the model, so we can update it later
					that.registeredSocialNetworksModels.push( item );
					
					that.$el.find('#registered-socialnetworks').append( view.render().el );
					
				});
				 
					 
				/*Bind model to view*/
				this.stickit();
				
				
				this.$('.toggle-button').toggleButtons({
					width: 100
				});


				return this;
				
				
				
				
			
			}

		});
		return SocialNetworkSettingsView;
	})
