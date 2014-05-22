// orders/app/views/order-edit.js
define(['jquery','localization','notifications','spinner', 'collections/extra-fields','text!templates/extra-field-group.html',
	'views/extra-field-row'],
	function($,localization,notifications,spinner, ExtraFieldsCollection,ExtraFieldGroupTemplate, ExtraFieldRowView ){
	
		return  Backbone.View.extend({
			template: _.template(ExtraFieldGroupTemplate),
			title:null,
			className:'col-3',
			position:0,
			initialize: function (options) {
				//_.bindAll(this);

				this.title=options.title;
				this.position=options.position;
				this.collection=new ExtraFieldsCollection();
				this.collection.reset(options.collection);
				this.render();	
			},
			events:{
			},			
			render: function () {
				//var self=this;
				$(this.el).html(this.template(localization.toJSON()));
		
				if((this.position % 3)==0){
					//this.$('.col-3').addClass('shipto');
					$(this.el).addClass('shipto');
				}
		
				this.$('.head-title').html(this.title);
				
				this.addExtraFields();
				
				//return this;
				return $(this.el)
			},
			addExtraField:function(model){
				var extraFieldRowView = new ExtraFieldRowView({
					model: model
				});
				this.$("#extraFieldsContainer").append(extraFieldRowView.render());
			},
			addExtraFields:function(){
					this.collection.each(this.addExtraField);
				
			}
		});
	});



