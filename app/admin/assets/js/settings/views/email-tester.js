define(['text!templates/email-tester.html', 'localization', 'jquery','models/email-test']
		, function(EmailTesterTlt, localization, $, EmailTest) {

	var EmailTesterView = Backbone.View.extend({
		
		translation: null,
		el: '#email-tester-modal',
		template: _.template(EmailTesterTlt),
		
		/* Bind controls to model attributes */
		bindings: {
			'#emailTo':'to',
			'#emailCC':'cc',
			'#emailBCC':'bcc',
			'#subject':'subject',
			'#message':'message',
			'#emailProvider':{
				observe: 'emailProvider',
						selectOptions: {
								collection:function() {
									// Prepend null or undefined for an empty select option and value.
									return [null,
										{id:'mandrill', name:'Mandrill'},
										{id:'wordpress', name:'Wordpress'},
                                        {id:'postmark', name:'Postmark'},
                                        {id:'amazonSes', name:'Amazon Ses'}
									]
								},
								labelPath: 'name',
								valuePath:'id'
							}
			}
		},
		
		events: {
            'click #run-test':'runTest'
        },
		runTest:function(){
			this.model.save({},
				{success: function(model, response){
					
				}});
		},
		show:function(){
			this.$el.modal({'modal': 'show'});
		},
		saveSettings: function() {

			//Since stickit doesn't work with tagsInput, we have to refresh the value manually
			this.model.set('bccNotificationsTo', $('#bccNotificationsTo').val());

			this.model.save();
			
			return false;

		},
		// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
		//el: '.settings',
		// Constructor
		initialize: function(options) {
			
			this.model = new EmailTest();
		},
				
		render: function() {

			this.$el.html(this.template(localization.toJSON()));

			this.$el.modal({'backdrop': 'static'});
			
			this.stickit();
			
			return this;

		}

	});
	return EmailTesterView;
})
