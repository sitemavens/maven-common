define([ 'text!templates/mailproviders-generic.html',
		 'text!templates/mailproviders-generic-setting.html',
		 'models/Option',
		'localization']
	,function( 
			MailprovidersTlt,
			MailprovidersSettingTlt,
			Option,
			localization ){
		
		var MailProviderGenericView = Backbone.View.extend({

			translation: null,
			template: _.template( MailprovidersTlt ),
									
			/* Bind controls to model attributes */
			bindings: {
			},
			
			initialize: function( options ) {

			},
		 
			render: function(){
		
				this.$el.html(this.template(this.model.toJSON()));
				var that = this;
				var settingTlt = _.template( MailprovidersSettingTlt );
				
				var settings = this.model.get('settings');
				_.each( settings, function (setting){
					var opt = new Option();
					opt.set('name',setting.label);
					opt.set('html',setting.html);
					
					that.$('.html-controls').append(settingTlt(opt.toJSON() ));
				});
				
				return this;
			
			}

		});
		return MailProviderGenericView;
	})
