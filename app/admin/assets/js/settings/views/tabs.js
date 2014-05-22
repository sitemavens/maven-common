define(['jquery','localization','text!templates/tabs.html',
		'views/general-settings',
		'views/gateway-settings',
		'views/tracking-settings',
		'views/email-settings',
		'views/licenses-settings',
		'views/maillist-settings',
		'views/mail-provider-settings',
		'views/socialnetwork-settings'
	]
	,function( $,localization, tabsTlt, GeneralSettingsView, GatewaySettingsView, 
			TrackingSettingsView, 
			EmailSettingsView, 
			LicensesSettingsView, 
			MaillistSettingsView,
			MailProviderSettingsView,
			SocialNetworksSettingsView
		){
		
		var TabsView = Backbone.View.extend({

			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			template: _.template(tabsTlt),
			collection: null,
			translation: null,
			settingsView:null,
			gatewaySettingsView:null,
			trackingSettingsView:null,
			emailSettingsView:null,
			socialNetworkSettingView:null,
			settings:null,
			maillistView:null,
			mailProviderSettingsView:null,
			
			// Constructor
			initialize: function( options ) {
			    
				//this.model.bind('change', this.render);
				this.settings = options.settings;
				
			},
			
			events: {
				
			},

			
			render: function(){
				//var self = this;
				
				this.$el.html(this.template(localization.toJSON()));
				
				//return this;
				// We need to create the tabs elements.
				
				this.settingsView = new GeneralSettingsView( {  model: this.settings.getGeneralSetting( ) } );
				this.gatewaySettingsView = new GatewaySettingsView( { model: this.settings.getGatewaySetting()  } );
				//this.trackingSettingsView = new TrackingSettingsView( { model: this.settings.getTrackingSetting()  } );
				this.emailSettingsView = new EmailSettingsView( { model: this.settings.getMailSetting()  } );
				this.licenseView = new LicensesSettingsView({model:this.settings.getLicensingSetting()});
				this.maillistView = new MaillistSettingsView( {model:this.settings.getMaillistSetting() } );
				this.mailProviderSettingsView = new MailProviderSettingsView( {model:this.settings.getEmailProvidersSetting() });
				this.socialNetworkSettingView = new SocialNetworksSettingsView({model:this.settings.getSocialNetworkSetting()});
				
				
				
				//generalTab.html(settings.render().el );
				this.settingsView.setElement(this.$('#tabs-general')).render();
				this.gatewaySettingsView.setElement(this.$('#tabs-gateways')).render();
				//this.trackingSettingsView.setElement(this.$('#tabs-tracking')).render();
				this.emailSettingsView.setElement(this.$('#tabs-emails')).render();
				this.licenseView.setElement(this.$('#tabs-licensing')).render();
				this.maillistView.setElement(this.$('#tabs-maillist')).render();
				this.mailProviderSettingsView.setElement(this.$('#tabs-email-providers')).render();
				this.socialNetworkSettingView.setElement(this.$('#tabs-socialnetworks')).render();
				
				
				return this;
			}

		});
		return TabsView;
	})
