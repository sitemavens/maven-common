// presenters/app/views/presenter-row.js
define(['jquery', 'localization', 'notifications', 'text!templates/promotion-row.html'],
	function($, localization, notifications, PromotionRowTlt) {


		var PromotionRowView = Backbone.View.extend({
			tagName: "tr",
			template: _.template(PromotionRowTlt),
			editRoute: _.template("promotion/edit/{{ id }}"),
			events: {
				'click button[id=edit]': 'editPromotion',
				'click a[id=confirmDeleteButton]': 'showDelete',
				'click button[id=performDelete]': 'deletePromotion'
			},
			initialize: function() {
				_.bindAll(this, 'editPromotion', 'showDelete', 'deletePromotion');
				this.model.bind('change', this.render)
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
				'#section': {
					observe: 'section',
					onGet: function(value, options) {
						var label = _.find(CachedPromotionSections, function(section) {
							return section['value'] === value;
						});
						if (label)
							return label['label'];

						return value;
					}
				},
				'#code': 'code',
				'#from': {
					observe: 'from',
					onGet: function(value, options) {
						if (value)
							return value;
						return '';
					}
				},
				'#to': {
					observe: 'to',
					onGet: function(value, options) {
						if (value)
							return value;
						return '';
					}
				}

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
			editPromotion: function() {
				Backbone.history.navigate(this.editRoute(this.model.toJSON()), {
					trigger: true
				});
			},
			deletePromotion: function() {
				var self = this;

				this.model.destroy({
					success: function() {
						//Show notification
						notifications.showDelete(localization.get('PromotionDeleted'));
						self.destroy_view();
					}
				});
			}
		});

		return PromotionRowView;
	});




