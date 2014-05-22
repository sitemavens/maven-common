define(['jquery','models/order-stat', 'text!templates/order-stat.html'],function($, OrderStat, htmlTemplate){
	
	var OrderStatView = Backbone.View.extend({
		template: _.template(htmlTemplate),
		model:OrderStat,
		//tagName: 'li',
		render: function() {
			this.$el.html(this.template(this.model.toJSON()));
			$(this.el).attr('data-desktop', 'span2')
				.attr('data-tablet','span4')
				.addClass('span2 responsive');
			return this;
		}
	});
	
	return OrderStatView;

});


