// donations/app/views/donation.js
define(['jquery', 'localization', 'text!templates/order-status-row.html'],
	function($, localization, OrderStatusRowTlt) {

		var OrderStatusRowView = Backbone.View.extend({
			tagName: "tr",
			template: _.template(OrderStatusRowTlt),
			bindings: {
				'#name': {
					observe: 'name',
					updateMethod: 'html'
				},
				'#timestamp': {
					observe: 'timestamp',
					onGet: function(value, options) {
						return value;
					}
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
				},
				'#statusDescription': 'statusDescription'
			},
			initialize: function(options) {
				//_.bindAll(this);
			},
			render: function() {

				$(this.el).append(this.template(localization.toJSON()));

				this.stickit();

				return $(this.el);
			}
		});

		return OrderStatusRowView;
	});
