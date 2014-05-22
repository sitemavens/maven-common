define(['jquery', 'spinner', 'localization', 'notifications', 'text!templates/page.html']
	, function($, spinner, localization, notifications, PageTlt) {

		var PageView = Backbone.View.extend({
			tagName: "li",
			template: _.template(PageTlt),
			bindings: {
				'#status': 'status',
				'#title': 'title',
				'#url': 'url'
			},
			initialize: function() {
				_.bindAll(this, 'render');
			},
			render: function() {

				$(this.el).append(this.template(localization.toJSON()));

				this.stickit();

				return $(this.el);

				return this;
			}
		});

		return PageView;
	});

