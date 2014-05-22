define(['text!templates/email-settings.html', 'localization', 'jquery', 
	'views/email-tester', 'text!templates/email-template.html', 'wpuploader',
	'jqueryForm', 'tagsInput']
		, function(EmailSettingsTlt, localization, $, EmailTesterView, EmailTemplateTlt, wpuploader) {

	var EmailSettingsView = Backbone.View.extend({
		translation: null,
		el: 'tabs-emails',
		template: _.template(EmailSettingsTlt),
		testerModalView: null,
		/* Bind controls to model attributes */
		bindings: {
			'#senderEmail': 'senderEmail',
			'#senderName': 'senderName',
			'#contactEmail': 'contactEmail',
			'#bccNotificationsTo': 'bccNotificationsTo',
			'#emailBackgroundColor':'emailBackgroundColor'
//			'#emailTemplate': {
//				observe: 'emailTemplate',
//				selectOptions: {
//					collection: function() {
//						return MavenEmailTemplates;
//					},
//					labelPath: 'name',
//					valuePath: 'id'
//				}
//			}
		},
		events: {
			"click #save": "saveSettings",
			"click #test-email": "testEmail",
			"click .email-template": 'changeActiveEmailTemplate',
			'click .upload_image_button': 'showUploader'
		},
		changeActiveEmailTemplate: function ( ev ){
			
			$('.email-template').removeClass('active');
			$(ev.target).closest('.email-template').addClass('active');
			
			return false;
			
		},
		showUploader: function() {
			var that = this;

			wpuploader.show(
					function(attachment) {
				 
						that.model.set('organizationLogo', attachment.id);
						that.replaceImage(attachment.url);
					});

		},
		testEmail: function() {

			if (!this.testerModalView) {
				this.testerModalView = new EmailTesterView();
				this.testerModalView.render();
			}
			else
				this.testerModalView.show();


		},
		saveSettings: function() {

			//Since stickit doesn't work with tagsInput, we have to refresh the value manually
			this.model.set('bccNotificationsTo', $('#bccNotificationsTo').val());
			
			// Get the email template 
			this.model.set('emailTemplate', $('.email-template.active input').val() );

			this.model.save();

			return false;

		},
		// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
		//el: '.settings',
		// Constructor
		initialize: function( options ) {

		},
		replaceImage: function(url) {
			this.$('.preview').attr('src', url);
		},
		render: function() {

			var that = this;
			this.$el.html(this.template(localization.toJSON()));

			_.each(MavenEmailTemplates,function(item){
				
				item.activeClass = '';
				if ( that.model.get('emailTemplate')==item.id)
					item.activeClass = 'active';
				
				html = _.template( EmailTemplateTlt );
				html = html(item);
				
				$('#emailTemplates').append( html );
				
			});

			if (this.model.get('organizationLogoUrl'))
				this.replaceImage(this.model.get('organizationLogoUrl'));
			else
				this.replaceImage(Maven.noPhotoUrl);

			/*Bind model to view*/
			this.stickit();

			$('#bccNotificationsTo').tagsInput({
				width: 'auto',
				defaultText: localization.get('addEmail')
			});


			 
			
			
			return this;

		}

	});
	return EmailSettingsView;
})
