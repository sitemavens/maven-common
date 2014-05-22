define(['jquery', 'text!templates/gateway-settings.html', 'localization',
	'collections/gateways',
	'views/gateway-default',
	'views/gateway-dummy',
	'views/gateway-authorizenet',
	'views/gateway-offline',
	'views/gateway-navigate',
	'views/gateway-registered-plugin',
	'models/Option',
	'toggleButtons']
	, function($, GatewaySettingsTlt, localization, Gateways, GatewayDefaultView,
		GatewayDummyView, GatewayAuthorizeNetView,
		GatewayOfflineView,
		GatewayNavigateView,
		GatewayRegisteredPluginView,
		Option) {

		var GatewaySettingsView = Backbone.View.extend({
			translation: null,
			template: _.template(GatewaySettingsTlt),
			gateways: null,
			registeredPluginsModels: [],
			events: {
				"click #save": "saveSettings",
				"click .widget .tools .icon-chevron-down, .widget .tools .icon-chevron-up ": 'toggleWindow',
				//'change #activeGateway' : 'reorderViews'
			},
			views: null,
			toggleWindow: function(element) {

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
				'#activeGateway': {
					observe: 'activeGateway',
					selectOptions: {
						collection: function() {
							// Prepend null or undefined for an empty select option and value.
							return [null,
								{
									id: 'default',
									name: 'Default'
								},
								{
									id: 'authorize.net',
									name: 'Authorize.Net'
								},
								{
									id: 'dummy',
									name: 'Dummy'
								},
								{
									id: 'offline',
									name: 'Offline'
								},
								{
									id: 'navigate',
									name: 'Navigate'
								}];
						},
						labelPath: 'name',
						valuePath: 'id'
					}

				}
			},
			saveSettings: function() {

				var that = this;

				// We have to update all the gateways
				this.gateways.update();


				var items = that.model.get('registeredPluginsGateway');

				// We need to mach the models with the main model
				_.each(this.registeredPluginsModels, function(model) {
					items[model.get('id')] = model.get('value') == 'false' || model.get('value') == false ? 0 : 1;

				});



				this.model.save();


				return false;

			},
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			//el: '.settings',
			// Constructor
			initialize: function(options) {

				// Read all the gateways
				this.gateways = new Gateways();
				this.gateways.reset(MavenGateways);

				_.bindAll(this, 'saveSettings', 'toggleWindow');

				this.model.bind('change:activeGateway', this.reorderViews);

			},
			reorderViews: function(e) {

				//var activeGateway = $(e.currentTarget).val().toLowerCase();
				var activeGateway = this.model.get('activeGateway');
				var firstView = null;

				_.each(this.views, function(view, key) {

					var widget = view.$el.find(".widget .widget-body");

					if (activeGateway == key) {
						firstView = view;
					}
					else {
						if (view.$el.attr('class') != "icon-chevron-down") {
							view.$el.find('.tools a').removeClass("icon-chevron-down").addClass("icon-chevron-up");
							widget.slideUp(200);
						}
					}
				})

				//firstView.stick( false );

				//Add the view at the beginning
				this.$el.prepend(firstView.el)

				this.showView(firstView);

				this.updateRecurringSetting(firstView.model.get('manageProfile'));

			},
			updateRecurringSetting: function(recurringEnabled) {

				// We need to update the settings model
				this.model.set('recurringEnabled', recurringEnabled);

				this.showRecurringMessage(recurringEnabled);

			},
			showRecurringMessage: function(recurring) {
				if (recurring) {
					this.$('#recurring').removeClass('alert-warning').addClass('alert-success');
					this.$('#recurring-message').html(localization.get('recurringEnabled'));

				} else {
					this.$('#recurring').removeClass('alert-success').addClass('alert-warning');
					this.$('#recurring-message').html(localization.get('recurringDisabled'))
				}

			},
			render: function() {

				var that = this;

				this.$el.html(this.template(localization.toJSON()));

				//We need to know the active gateway so we can place it in first place and open
				var activeGateway = this.model.get('activeGateway');

				var gdView = new GatewayDefaultView({
					model: this.gateways.get('default'),
					activeGateway: activeGateway
				});
				var gDummyView = new GatewayDummyView({
					model: this.gateways.get('dummy'),
					activeGateway: activeGateway
				});
				var gAuthorizeNetView = new GatewayAuthorizeNetView({
					model: this.gateways.get('authorize.net'),
					activeGateway: activeGateway
				});

				var gOfflineView = new GatewayOfflineView({
					model: this.gateways.get('offline'),
					activeGateway: activeGateway
				});

				var navigateView = new GatewayNavigateView({
					model: this.gateways.get('navigate'),
					activeGateway: activeGateway
				});


				this.views = {
					'default': gdView,
					'dummy': gDummyView,
					'authorize.net': gAuthorizeNetView,
					'offline': gOfflineView,
					'navigate': navigateView
				};

				var firstView = null;

				_.each(this.views, function(view, key) {

					if (activeGateway == key) {
						firstView = view;
					}
					else
						that.$el.prepend(view.render().el);


				});

				if (firstView != null) {
					this.$el.prepend(firstView.render().el);

					this.showView(firstView);

					this.updateRecurringSetting(firstView.model.get('manageProfile'));
				}

				// We need to render the registered plugins
				var items = this.model.get('registeredPluginsGateway');

				_.each(items, function(value, key) {


					var item = new Option();
					item.set('id', key);
					item.set('value', value == 'false' || value == '0' ? false : true);

					//TODO: We have to do it more dynamic
					switch (key) {
						case "mavencommon":
							item.set('name', 'Maven Common');
							item.set('img', Maven.adminImagesPath + "logos/maven.png");
							break;
						case "mavendonations":
							item.set('name', 'Maven Donations');
							item.set('img', Maven.adminImagesPath + "logos/maven-donations.png");
							break;
						case "mavenevents":
							item.set('name', 'Maven Events');
							item.set('img', Maven.adminImagesPath + "logos/maven-events.png");
							break;
						case "mavenshop":
							item.set('name', 'Maven Shop');
							item.set('img', Maven.adminImagesPath + "logos/maven-shop.png");
							break;
					}

					var view = new GatewayRegisteredPluginView({
						model: item
					});

					// We save the model, so we can update it later
					that.registeredPluginsModels.push(item);

					that.$el.find('#registered-plugins').append(view.render().el);
				})


				this.$('.text-toggle-button').toggleButtons({
					width: 100,
					label: {
						enabled: "Test",
						disabled: "Live"
					}
				});


				//registered-plugins
				//this.$('.basic-toggle-button').toggleButtons();

				/*Bind model to view*/
				this.stickit();

				return this;

			},
			showView: function(firstView) {

				// Change the icon
				firstView.$el.find('.tools a').removeClass("icon-chevron-up").addClass("icon-chevron-down");

				// Open the first view
				firstView.$el.find(".widget .widget-body").fadeIn("slow");

			}


		});
		return GatewaySettingsView;
	})
