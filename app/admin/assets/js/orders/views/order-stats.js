define(['views/order-stat'],function( OrderStatView ){
	
	var OrderStatsView = Backbone.View.extend({
		initialize: function(options) {
			this.el=options.el;
			this.collection.on('all', this.render, this);
			this.render();
		},
		render: function() {
			this.$el.empty()
			this.collection.each(function(orderStat) {
				this.$el.append((new OrderStatView({model: orderStat})).render().el);
			}, this);
			
			return this;
		}
	});
	return OrderStatsView;
});


