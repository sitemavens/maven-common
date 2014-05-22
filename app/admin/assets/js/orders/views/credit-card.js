// orders/app/views/order-edit.js
define(['jquery','localization','notifications','spinner','text!templates/credit-card.html', 'models/credit-card'],
	function($,localization,notifications,spinner, CreditCardTemplate, CreditCard){
	
		return  Backbone.View.extend({
			template: _.template(CreditCardTemplate),
			//countries:null,
			model: CreditCard,
			title:'',
			initialize: function (options) {
				this.model = new CreditCard();
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
				'#holderName'		: 'holderName',
				'#type'			: {
					observe:['type','number'],
					onGet:function(values){
						return values[0] + ' XXXX-XXXX-XXXX-' + values[1];
					}	
				},
				'#number'		: 'number',
				'#date'			: {
					observe:['month','year'],
					onGet:function(values){
						return 'Expiration ' + values[0] + '/' + values[1];
					}
				}
			},
			
			render: function () {
				//var self=this;
				$(this.el).html(this.template(localization.toJSON()));
				this.$('.head-title').html(this.title);
				if(this.model.get('number')!=''){
					this.$('#emptyCreditCardContainer').empty();
					/*Bind model to view*/
					this.stickit();
				}else{
					this.$('#emptyCreditCard').html(localization.get('emptyCreditCard'));
				}
				
				return this;
			}
		});
	});
