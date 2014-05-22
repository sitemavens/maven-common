// presenters/app/views/presenter-row.js
define(['jquery', 'localization', 'notifications', 'text!templates/tax-row.html'],
	function($, localization, notifications, TaxRowTlt) {


		var TaxRowView = Backbone.View.extend({
			tagName: "tr",
			template: _.template(TaxRowTlt),
			editRoute: _.template("tax/edit/{{ id }}"),
			events: {
				'click button[id=edit]': 'editTax',
				'click a[id=confirmDeleteButton]': 'showDelete',
				'click button[id=performDelete]': 'deleteTax'
			},
			bindings: {
				'#enabledImage': {
					attributes: [
						{
							name: 'src',
							observe: 'statusImageUrl',
							onGet: function(value, options) {
								if (value)
									return value;
								return '';
							}
						},
						{
							name: 'alt',
							observe: 'enabled',
							onGet: function(value, options) {
								if (value) {
									return localization.get('enabled');
								} else {
									return localization.get('disabled');
								}

								return localization.get('unknownStatus');
							}
						}
					]
				},
				'#name': 'name',
				'#country': {
					observe: 'country',
					onGet: function(value, options) {
						if (CachedCountries[value]) {
							return CachedCountries[value]['name'];
						}
						return value;
					}
				},
				'#state': 'state',
				'#value': 'value'
			},
			initialize: function() {
				_.bindAll(this, 'showDelete', 'editTax', 'deleteTax');
				this.model.bind('change', this.render)
			},
			render: function() {

				$(this.el).append(this.template(localization.toJSON()));

				this.stickit();

				return $(this.el);
			},
			showDelete: function() {
				this.$('#confirmDeleteModal').modal();
			},
			destroy_view: function() {
				//animate the destroy
				$(this.el).toggleClass('error');
				var self = this;
				$(this.el).fadeOut('slow', function() {
					//COMPLETELY UNBIND THE VIEW
					self.undelegateEvents();

					self.$el.removeData().unbind();

					//Remove view from DOM
					self.remove();
					Backbone.View.prototype.remove.call(self);
				});


			},
			editTax: function() {
				Backbone.history.navigate(this.editRoute(this.model.toJSON()), {
					trigger: true
				});
			},
			deleteTax: function() {
				var self = this;

				this.model.destroy({
					success: function() {
						//Show notification
						notifications.showDelete(localization.get('TaxDeleted'));
						self.destroy_view();
					}
				});
			}
		});

		return TaxRowView;
	});




