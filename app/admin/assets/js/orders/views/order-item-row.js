// donations/app/views/donation.js
define(['jquery','localization','text!templates/order-item-row.html'],
	function($, localization ,OrderItemRowTlt){
		
		var OrderItemRowView =  Backbone.View.extend({
			tagName: "tr",
			template:  _.template(OrderItemRowTlt),
			quantity:0,
			price:0,
			bindings: {
				'#name'		: {
					observe:'name',
					updateMethod: 'html'
				},
				'#quantity'	: {
					observe:'quantity',
					onGet:function(value,options){
						//save value
						this.quantity=value;
						return value;
					}
				},
				'#price'	: {
					observe:'price',
					updateMethod:'html',
					onGet:function(value,options){
						//save value
						this.price = value;
						return "$&nbsp;" + parseFloat(value).toFixed(2);
					}
				}
			},
			initialize: function (options) {
				//_.bindAll(this);
			},
			render: function () {
			
				$(this.el).append(this.template(localization.toJSON())) ;
			
				this.stickit();
				
				//put item total
				var temp=this.price * this.quantity;
				this.$('#item-total').html("$&nbsp;" + parseFloat(temp).toFixed(2));
				
				return $(this.el);
			}
		});
	
		return OrderItemRowView;
	});
