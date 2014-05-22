define(['models/setting'],function( Setting ){
	
	var Settings = Backbone.Collection.extend({
		action:'entryPoint',	
		model: Setting,
		
		getGeneralSetting: function(){
			
			var gs = new Setting();
			
			var sendReportTo = this.get('sendReportTo');
		
			gs.set('id','sendReportTo');
			gs.set('sendReportTo',sendReportTo.get('value'));
			
			var enabled = this.get('enabled');
		
			gs.set('id','enabled');
			gs.set('enabled',enabled.get('value'));
			
			var daysOfTheWeek = this.get('daysOfTheWeek');
		
			gs.set('id','daysOfTheWeek');
			gs.set('daysOfTheWeek',daysOfTheWeek.get('value'));
			
			return gs;
		}
				
	});

	
	return Settings;

});
