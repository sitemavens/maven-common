define(['models/Option','models/GeneralSetting'],function( Option,GeneralSetting ){
	
	var Options = Backbone.Collection.extend({
		action:'entryPoint',	
		model: Option,
		 
		printAll:function(){
			_.each(this.models,function(model ){
				console.log(model.get('name'));
			});
		},
		getGroups:function(){
			var groups=[];
			var group = "";
		    
			this.each(function(model){
				if ( group != model.get('group') ){
					group = model.get('group');
					groups.push({
						"group":group
					});
				}
			});
		    
			return groups;
		},
		getLicensingSetting:function(){
			var gs = new GeneralSetting();
			
			var registeredPluginsLicensing = this.get('registeredPluginsLicensing');
			gs.set('registeredPluginsLicensing', registeredPluginsLicensing.get('value'));
			
			return gs;
		},

		getGeneralSetting: function(){
			var gs = new GeneralSetting();
			
			var exceptionNotification = this.get('exceptionNotification');
			var activeThemeName = this.get('activeThemeName');
			var organizationName = this.get('organizationName');
			var signature = this.get('signature');
			var license = this.get('license');
			var loginPage = this.get('loginPage');
			var registrationThankYouPage=this.get('registrationThankYouPage');
			var gridRows=this.get('gridRows');
			 
			gs.set('exceptionNotification', exceptionNotification.get('value' ));
			gs.set('activeThemeName', activeThemeName.get('value' ));
			gs.set('organizationName', organizationName.get('value'));
			gs.set('signature', signature.get('value'));
			gs.set('loginPage', loginPage.get('value'));
			gs.set('registrationThankYouPage', registrationThankYouPage.get('value'));
			gs.set('gridRows', gridRows.get('value'));
			
			gs.set('id', '1');
			return gs;
		},
		getGatewaySetting: function(){
			var gs = new GeneralSetting();
			
			var activeGateway = this.get('activeGateway');
			var registeredPluginsGateway = this.get('registeredPluginsGateway');
			var recurringEnabled = this.get('recurringEnabled');
			
			gs.set('activeGateway', activeGateway.get('value' ));
			gs.set('registeredPluginsGateway', registeredPluginsGateway.get('value' ));
			gs.set('recurringEnabled', recurringEnabled.get('value' ));
			
			gs.set('id', '1');
			return gs;
		}
		,
		getTrackingSetting: function(){
			var gs = new GeneralSetting();
			
			var enabledTrackers = this.get('enabledTrackers');
			
			gs.set('enabledTrackers', enabledTrackers.get('value' ));
			gs.set('id', '1');
			return gs;
		},
		getMailSetting:function(){
			var gs = new GeneralSetting();
			
			var bccNotificationsTo	= this.get('bccNotificationsTo');
			var organizationLogo	= this.get('organizationLogo');
			var organizationLogoUrl	= this.get('organizationLogoUrl');
			var emailTemplate	= this.get('emailTemplate');
			var emailProvider	= this.get('emailProvider');
			var senderEmail		= this.get('senderEmail');
			var senderName		= this.get('senderName');
			var contactEmail	= this.get('contactEmail');
			var emailBackgroundColor = this.get('emailBackgroundColor');
			
			
			gs.set('bccNotificationsTo'	, bccNotificationsTo.get('value' ));
			gs.set('organizationLogo'	, organizationLogo.get('value' ));
			gs.set('organizationLogoUrl'	, organizationLogoUrl.get('value' ));
			gs.set('emailBackgroundColor'	, emailBackgroundColor.get('value' ));
			gs.set('emailTemplate'		, emailTemplate.get('value' ));
			gs.set('emailProvider'		, emailProvider.get('value' ));
			gs.set('id'			, '1');
			gs.set('senderEmail'		, senderEmail.get('value'));
			gs.set('senderName'		, senderName.get('value'));
			gs.set('contactEmail'		, contactEmail.get('value'));
			
			return gs;
		},
		getEmailProvidersSetting:function(){
			var gs = new GeneralSetting();
			
			var emailProvider		= this.get('emailProvider');
			
			gs.set('emailProvider'		, emailProvider.get('value' ));
			gs.set('id'					, '1');
			
			return gs;
		
		},
		getSocialNetworkSetting:function(){
			var gs = new GeneralSetting();
			
			var enabledSocialNetworks = this.get('enabledSocialNetworks');
			
			gs.set('enabledSocialNetworks', enabledSocialNetworks.get('value' ));
			
			gs.set('id', '1');
			
			return gs;
		},
		getMaillistSetting:function(){
			var gs = new GeneralSetting();
			
			var activeMaillist = this.get('activeMaillist');
			
			gs.set('activeMaillist', activeMaillist.get('value' ));
			
			gs.set('id', '1');
			
			return gs;
		}
		
		
	});

	
	return Options;

});
