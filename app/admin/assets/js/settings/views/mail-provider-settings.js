define(['jquery',
	'text!templates/mailproviders-settings.html',
	'localization',
	'models/Option',
	'models/email-provider',
	'collections/email-providers',
	'views/mail-provider-generic',
	'toggleButtons']
		, function($,
		MailProvidersSettingsTlt,
		localization,
		Option,
		EmailProvider,
		EmailProviders,
		MailProviderGenericView
		) {

	var MailProvidersSettingsView = Backbone.View.extend({
		translation: null,
		template: _.template(MailProvidersSettingsTlt),
		mailProviders: null,
		mailProvidersViews: [],
		events: {
			"click #save": "saveSettings",
			"click .widget .tools .icon-chevron-down, .widget .tools .icon-chevron-up ": 'toggleWindow'
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
			'#emailProvider': {
				observe: 'emailProvider',
				selectOptions: {
					collection: function() {
						return MavenEmailProviders;
					},
					labelPath: 'label',
					valuePath: 'id'
				},
				onSet: 'emailProviderChange'
			}
		},
		emailProviderChange: function(value, options) {

			this.showMandrillWarning(value);

			return value;
		},
		showMandrillWarning: function(provider) {
			if (provider === 'mandrill')
				this.$('#mandrill-warning').fadeIn('slow');
			else
				this.$('#mandrill-warning').fadeOut('slow');
		},
		saveSettings: function() {

			var that = this;
			var modelToSave = new EmailProvider();
			
			this.mailProviders.each( function( provider ){
				
				settings = provider.get('settings');
				_.each( settings,function (setting){
					var view = provider.get('view');
					var val = view.$el.find('#'+setting.id).val();
					setting.value = val;
				})
				
				modelToSave.set(provider.id,settings);
			});

			//It is just to fire an update event
			modelToSave.set('id','mailProvider');
			modelToSave.set('emailProvider',this.model.get('emailProvider'));
			modelToSave.save();
			
			return false;

		},
		// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
		//el: '.settings',
		// Constructor
		initialize: function(options) {

			// Read all the gateways
			this.mailProviders = new EmailProviders();
			this.mailProviders.reset(MavenEmailProviders);

		},
		render: function() {

			var that = this;

			this.$el.html(this.template(localization.toJSON()));

			this.mailProviders.each ( function( mailProvider ){
				var mailProviderGenericView = new MailProviderGenericView({
					model:mailProvider
				});
				that.$el.prepend(mailProviderGenericView.render().el);
				
				mailProvider.set('view',mailProviderGenericView);
			});
			
			/*Bind model to view*/
			this.stickit();

			this.showMandrillWarning( this.model.get('emailProvider') );
			
			return this;

		}

	});
	return MailProvidersSettingsView;
})
