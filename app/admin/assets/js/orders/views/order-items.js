define(['jquery', 'localization','text!templates/order-items.html',
	'collections/order-items', 'views/order-item-row', 'models/order-item']
	, function($, localization, OrderItemsTlt, OrderItems, OrderItemRowView, OrderItem) {

		var OrderItemsView = Backbone.View.extend({
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			template: _.template(OrderItemsTlt),
			//generalTabView:null,
			events: {
				'click #addNewOrderItem'	: 'addNewOrderItem'
			},
			// Constructor
			initialize: function(options) {
				_.bindAll(this,'addNewOrderItem','render','addOne');

				this.collection = new OrderItems();
				this.collection.on('reset',this.render);
				this.collection.on("add", this.addOne);
				this.collection.reset(options.collection);
				//this.collection.fetch();
			},
			addOne: function (model) {
				var orderItemRowView = new OrderItemRowView({
					model: model,
					collection:this.collection
				});
				this.$("#orderItems").append(orderItemRowView.render());
			},
			addAll: function () {
				this.collection.each(this.addOne);
			},
			addNewOrderItem:function(){
				this.collection.add(new OrderItem());
			},
			render: function() {
				this.$el.html(this.template(localization.toJSON()));
				this.addAll();
				
				return this;
			}
		
		});
		return OrderItemsView;
	});
