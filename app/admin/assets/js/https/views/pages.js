define(['jquery', 'spinner', 'localization', 'notifications', 'collections/pages', 'views/page', 'text!templates/pages.html']
	, function($, spinner, localization, notifications, PagesCollection, PageView, PagesTlt) {

		var MainView = Backbone.View.extend({
			template: _.template(PagesTlt),
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			// Constructor
			collection: null,
			pages: null,
			initialize: function() {
				_.bindAll(this, 'save', 'addOne', 'addAll', 'render');
				this.pages = this.model.get('pages').split(",");

				this.collection = new PagesCollection();
				this.collection.on("reset", this.render);
				this.collection.on("add", this.addOne);
				this.collection.reset(Pages);

			},
			save: function(e) {
				e.preventDefault();

				var selected = this.collection.filter(function(m) {
					return m.get('status')
				});
				var pages = null;
				for (var i = 0; i < selected.length; i += 1) {
					if (pages == null) {
						pages = selected[i].get('name');
					} else {
						pages += "," + selected[i].get('name');
					}
				}
				this.model.set("pages", pages);

				this.model.save();
			},
			events: {
				'click #save': 'save',
			},
			bindings: {
				//'#pages': 'pages'
			},
			addOne: function(model) {
				var postName = model.get('name');

				if ($.inArray(postName, this.pages) >= 0) {
					model.set('status', true);
				}

				var pageView = new PageView({
					model: model
				});
				$("#wpPages", this.el).append(pageView.render());
			},
			addAll: function() {
				this.collection.each(this.addOne);
			},
			render: function() {

				$(this.el).html(this.template(localization.toJSON()));
				this.addAll();

				/*Bind model to view*/
				//this.stickit();

				spinner.stop();

				return this;
			}

		});
		return MainView;
	})
