define([ 'text!templates/general-settings.html','localization','models/license','jquery','tagsInput']
	,function(  GeneralSettingsTlt ,localization, License, $ ){
		
		var GeneralSettingsView = Backbone.View.extend({

			translation: null,
			template: _.template( GeneralSettingsTlt ),
			
			/* Bind controls to model attributes */
			bindings: {
				'#exceptionNotification': 'exceptionNotification',
				'#organizationName':'organizationName',
				'#signature':'signature',
				'#loginPage':'loginPage',
				'#license': {
					observe: 'license',
					onGet:function(value, options){
							
						this.licenseBox( value==="" );
							
						return value;
					}
				},
					
				'#activeThemeName': {
					observe: 'activeThemeName',
					selectOptions: {
						collection:function() {
							// Prepend null or undefined for an empty select option and value.
							return [null, 
							{
								id:'conquer', 
								name:'Conquer'
							}];
						},
						labelPath: 'name',
						valuePath:'id'
					}
				},
				'#gridRows':'gridRows'
			},
			events: {
				"click #save": "saveSettings"
			},
			saveSettings: function(){
				
				var loginPage = this.$('#loginPage').val();

				this.model.set('loginPage',loginPage);
				var registrationThankYouPage=this.$('#registrationThankYouPage').val();

				this.model.set('registrationThankYouPage',registrationThankYouPage);
				//Since stickit doesn't work with tagsInput, we have to refresh the value manually
				this.model.set('exceptionNotification', $('#exceptionNotification').val());
			
				this.model.save();
				
				return false;
				
			},
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			//el: '.settings',
			// Constructor
			initialize: function( options ) {

			},
			
			render: function(){

				this.$el.html(this.template(localization.toJSON()));
				
				/*Bind model to view*/
				this.stickit();
				
				var loginPages = $(WpPagesDropDown);
				loginPages.addClass('input-large');
				loginPages.val(this.model.get('loginPage'));
				this.$('#login-page').append( loginPages );
				
				var thankYouPages = $(WpThankYouPagesDropDown);
				thankYouPages.addClass('input-large');
				thankYouPages.val(this.model.get('registrationThankYouPage'));
				this.$('#registration-thank-you-page').append(thankYouPages);
				
				
				
				$('#exceptionNotification').tagsInput({
					width: 'auto',
					defaultText: localization.get('addEmail')
				});
				
				return this;
			
			}

		});
		return GeneralSettingsView;
	})
