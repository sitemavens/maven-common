// orders/app/views/order-edit.js
define(['jquery','localization','notifications','spinner','text!templates/address.html', 'models/address'],
	function($,localization,notifications,spinner, AddressTemplate, Address){
	
		return  Backbone.View.extend({
			template: _.template(AddressTemplate),
			//countries:null,
			model: Address,
			initialize: function (options) {
				/*this.model=new Address();
				this.model.set(options.data);*/
				if(!this.model.id){
					spinner.stop();	
				}
				this.render();	
			},
			events:{
			},
			bindings: {
				'#firstLine'		: {
					observe:'firstLine',
					onGet:function(value){
						if(value) return value;
						return '';
					}
				},
				'#secondLine'		: {
					observe:'secondLine',
					onGet:function(value){
						if(value) return value;
						return '';
					}
				},
				'#city'			: {
					observe:'city',
					onGet:function(value){
						if(value) return value + ',';
						return '';
					}
				},
				'#state'		: {
					observe:'state',
					onGet:function(value){
						if(value) return value;
						return '';
					}
				},
				'#country'		: {
					observe:'country',
					onGet:function(value){
						if(value) return value;
						return '';
					}
				},
				'#zipcode'		: {
					observe:'zipcode',
					onGet:function(value){
						if(value) return value;
						return '';
					}
				}
			},
			render: function () {
				//var self=this;
				$(this.el).html(this.template(localization.toJSON()));
		
				/*Bind model to view*/
				this.stickit();
				
				return this;
			}
		});
	});
