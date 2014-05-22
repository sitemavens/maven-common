// orders/app/views/order-row.js
define(['jquery','localization','notifications','text!templates/order-row.html'],
	function($, localization, notifications ,OrderRowTlt){


		var OrderRowView=  Backbone.View.extend({
			tagName: "tr",
			template:  _.template(OrderRowTlt),
			editRoute:_.template("order/edit/{{ id }}"),
			events:{
				'click button[id=edit]'			: 'editOrder',
				'click a[id=confirmDeleteButton]'	: 'showDelete',
				'click button[id=performDelete]'	: 'deleteOrder'
			},
			initialize: function () {
				_.bindAll(this,'render','editOrder','showDelete','deleteOrder');
				this.model.bind('change',this.render)
			},
			bindings: {
				'#number'	:'number',
				'#orderDate'	:{
					observe:'orderDate',
					onGet:function(value, options){
						//console.time('Parse');
						var d = Date.parse(value);
						//console.timeEnd('Parse');
						return d.toString('MMMM d, yyyy');
					}
				},
				
				'#total'	:'total',
				'#customer'	:{
					observe:'contact',
					onGet:function(value, options){
						if(value){		
							var salutation = value.salutation?value.salutation+' ':'';
							var lastName = value.lastName?value.lastName+', ':'';
							var firstName = value.firstName?value.firstName:'';
						
							return salutation+lastName+firstName;
						}else{
							return '';
						}
								
					}
				},
				'#statusImage'	:{
					attributes:[
					{
						name:'src',
						observe:'status',
						onGet:function(value, options){
							if(value)
								return value.imageUrl;
							return '';
						}
					},
					{
						name:'alt',
						observe:'status',
						onGet:function(value, options){
							if(value)
								return value.name;
								
							return localization.get('unknownStatus');
						}
					}
					]
				}
			},
			render: function () {
			
				$(this.el).append(this.template(localization.toJSON())) ;
			
				this.stickit();
				
				return $(this.el);
			},
			showDelete:function(){
				this.$('#confirmDeleteModal').modal();
			},
			destroy_view: function() {
				//animate the destroy
				$(this.el).toggleClass('error'); 
				var self=this;
				$(this.el).fadeOut('slow',function(){
					//COMPLETELY UNBIND THE VIEW
					self.undelegateEvents();

					self.$el.removeData().unbind(); 

					//Remove view from DOM
					self.remove();  
					Backbone.View.prototype.remove.call(self);
				});
			

			},
			editOrder:function(){
				Backbone.history.navigate(this.editRoute(this.model.toJSON()),{
					trigger:true
				});
			},
			deleteOrder:function(){
				var self=this;
			
				this.model.destroy({
					success: function () {
						//Show notification
						notifications.showDelete(localization.get('OrderDeleted'));
						self.destroy_view();					
					}
				});
			}
		});
	
		return OrderRowView;
	});




