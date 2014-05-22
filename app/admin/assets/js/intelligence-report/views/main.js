define(['jquery','localization','spinner', 'views/general-tab','text!templates/main.html', 'models/setting', 'collections/settings']
	,function( $, localization, spinner, GeneralTabView, MainTlt, Setting, Settings){
		
	var MainView = Backbone.View.extend({
		// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
		el: '#mainContainer',
		settings:null,
		template: _.template(MainTlt),
		generalTabView:null,
		events: {
			
		},
		// Constructor
		initialize: function() {
			
			_.bindAll(this, 'render');

			this.settings = new Settings();
			this.settings.on('reset',this.render, this);
			this.settings.reset(SavedSettings);
			
				
		},
		render: function() {
	
			this.$el.html(this.template(localization.toJSON()));

			this.generalTabView = new GeneralTabView({ model: this.settings.getGeneralSetting() });
			
			this.generalTabView.setElement( this.$('#tabs-general') ).render();
				
			spinner.stop();

			return this;
		}
		
	});
	return MainView;
});
