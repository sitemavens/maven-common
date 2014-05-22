define(['jquery', 'localization', 'models/order-status', 'text!templates/order-status-title.html'],
	function($, localization, OrderStatus, OrderStatusTitleTlt) {

		var OrderStatusTitleView = Backbone.View.extend({
			tagName: "span",
			className: 'tools',
			template: _.template(OrderStatusTitleTlt),
			transaction: null,
			bindings: {
				'#name': {
					observe: 'name',
					updateMethod: 'html'
				},
				'#statusImage': {
					attributes: [
						{
							name: 'src',
							observe: 'imageUrl',
							onGet: function(value, options) {
								if (value)
									return value;
								return '';
							}
						},
						{
							name: 'alt',
							observe: 'name',
							onGet: function(value, options) {
								if (value)
									return value;
								return localization.get('unknownStatus');
							}
						}
					]
				}
			},
			initialize: function(options) {
				//_.bindAll(this);

				this.model = new OrderStatus();
				this.model.set(options.data);

				this.transaction = options.transaction;

			},
			render: function() {

				$(this.el).append(this.template(localization.toJSON()));

				this.stickit();

				if (this.transaction) {
					this.$('#transactionId').append(this.transaction);

				} else {
					this.$('#transaction').remove();
				}

				return $(this.el);
			}
		});

		return OrderStatusTitleView;
	});



