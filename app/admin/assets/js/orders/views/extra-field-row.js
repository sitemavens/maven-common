// donations/app/views/donation.js
define(['jquery','localization','text!templates/extra-field-row.html'],
	function($, localization ,ExtraFieldRowTlt){
		
		var ExtraFieldRowView=  Backbone.View.extend({
			tagName: "div",
			//className: "control-group",
			template:  _.template(ExtraFieldRowTlt),
			collection:null,
			events:{
			},
			bindings: {
				'#label'	: 'label',
				'#value'	: 'value'
			},
			initialize: function (options) {
				
			},
			render: function () {
			
				$(this.el).append(this.template(localization.toJSON())) ;
			
				this.stickit();
				
				return $(this.el);
			}
		});
	
		return ExtraFieldRowView;
	});
