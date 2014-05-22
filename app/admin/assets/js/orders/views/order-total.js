define(['jquery','localization','models/order-total', 'text!templates/order-total.html'],
	function($, localization,OrderTotal, htmlTemplate){
	
		var OrderTotalView = Backbone.View.extend({
			template: _.template(htmlTemplate),
			model:OrderTotal,
			initialize:function(){
				this.model=new OrderTotal({
					id:1
				});
				var self=this;
				this.model.fetch({
					success:function(){
						self.render();	
					}
				});
			},
			bindings:{
				'#total':{
					observe:'total',
					updateMethod: 'html',
					onGet:function(value){
						return '$&nbsp;'+value.toFixed(2);
					}
				},
				'#count':'count',
				'#completedCount':'completedCount'				
			},
			//tagName: 'li',
			render: function() {
				this.$el.html(this.template(localization.toJSON()));
				
				/*Bind model to view*/
				this.stickit();
			
				return this;
			}
		});
	
		return OrderTotalView;

	});





