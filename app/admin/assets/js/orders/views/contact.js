// orders/app/views/order-edit.js
define(['jquery','localization','notifications','spinner','text!templates/contact.html', 'models/contact', 'models/address','collections/addresses', 'views/address'],
	function($,localization,notifications,spinner, ContactTemplate, Contact, Address, Addresses, AddressView){
	
		return  Backbone.View.extend({
			template: _.template(ContactTemplate),
			//countries:null,
			model: Contact,
			addressView:null,
			title:'',
			initialize: function (options) {
				this.model = new Contact();
				this.model.set(options.data);
				this.title = options.title;
				if(!this.model.id){
					spinner.stop();	
				}
				
				this.render();	
			},
			events:{
			},
			bindings: {
				'#contactName'		:{
					observe:['salutation','firstName','lastName' ],
					updateMethod:'html',
					onGet:function(values, options){
						var result='';
						if(values[0])
							result+=values[0]+'&nbsp;';
						
						return result+ values[1] + '&nbsp;' +values[2];
					}
				},
				'#contactEmail'		: 'email', 					
				'#contactCompany'	: 'company',
				'#contactPhone'		: 'phone'
			},
			replaceImage: function(url) {
				this.$('#contactImage').attr('src', url);
			},
			render: function () {
				//var self=this;
				$(this.el).html(this.template(localization.toJSON()));
		
				/*Bind model to view*/
				this.stickit();
				
				this.$('.head-title').html(this.title);
				
				if (this.model.get('profileImageUrl'))
					this.replaceImage(this.model.get('profileImageUrl'));
				else
					this.replaceImage(Maven.noPhotoUrl);
				
				//recover the address
				var addresses=new Addresses();
				addresses.reset(this.model.get('addresses'));
				if(addresses.length>0){
					this.addressView=new AddressView({
						model:addresses.first(),
						el:this.$('#addressContainer')
					});
				}
				
				return this;
			}
		});
	});
