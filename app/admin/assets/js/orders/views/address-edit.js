// orders/app/views/order-edit.js
define(['jquery','localization','notifications','spinner','text!templates/address-edit.html', 'models/address'],
	function($,localization,notifications,spinner, AddressEditTemplate, Address){
	
		return  Backbone.View.extend({
			template: _.template(AddressEditTemplate),
			//countries:null,
			model: Address,
			initialize: function (options) {
				this.model=new Address();
				this.model.set(options.data);
				if(!this.model.id){
					spinner.stop();	
				}
				this.render();	
			},
			events:{
			},
			bindings: {
				'#firstLine'		: 'firstLine',
				'#secondLine'		: 'secondLine',
				'#city'			: 'city',
				'#state'		: 'state',
				'#country'		: 'country',
				'#zipcode'		: 'zipcode'
			},
			render: function () {
				//var self=this;
				$(this.el).html(this.template(localization.toJSON()));
		
				/*Bind model to view*/
				this.stickit();
				/*Bind Validation*/
				Backbone.Validation.bind(this,{
					//Important! this allow models to be updated with invalid values.
					//This way the validation behave correctly when the form fields 
					//are invalid
					forceUpdate:true
				});
	
				return this;
			}
		});
	});
