define([ 'jquery','localization', 
	'views/plugin-license', 'models/Option']
	,function( $, localization, PluginLicenseView, Option){
		
		var LicensesSettingsView = Backbone.View.extend({

			el:'#tabs-licensing',
			
			initialize: function( options ) {

			},
			
			render: function(){
				
				var that = this;
				// We need to render the registered plugins
				var items = this.model.get('registeredPluginsLicensing');
				
				_.each( items ,function ( value, key  ){
					
					var item = new Option();
					item.set('id',key);
					item.set('value',value);
					
					//TODO: We have to do it more dynamic
					switch( key ){
						
						case "mavencommon":
							item.set('name','Maven Common');
							item.set('img',Maven.adminImagesPath+"logos/maven.png");
							break;
						case "mavendonations":
							item.set('name','Maven Donations');
							item.set('img',Maven.adminImagesPath+"logos/maven-donations.png");
							break;
						case "mavenevents":
							item.set('name','Maven Events');
							item.set('img',Maven.adminImagesPath+"logos/maven-events.png");
							break;
						case "mavenshop":
							item.set('name','Maven Shop');
							item.set('img',Maven.adminImagesPath+"logos/maven-shop.png");
							break;
							
					}
					
					view = new PluginLicenseView({
						model:item
					});
				
					
					
					that.$el.append( view.render().el );
					
				});
				
				return this;
			
			}
		});
		return LicensesSettingsView;
	})
