// presenters/app/views/presenter-row.js
define(['jquery', 'localization', 'notifications', 'text!templates/profile-row.html'],
	function($, localization, notifications, ProfileRowTlt) {


		var ProfileRowView = Backbone.View.extend({
			//tagName: "tr",
			template: _.template(ProfileRowTlt),
			editRoute: _.template("profile/edit/{{ id }}"),
			events: {
				'click button[id=edit]': 'edit',
				'click a[id=confirmDeleteButton]': 'showDelete',
				'click button[id=performDelete]': 'del'
			},
			bindings: {
				'#email': 'email',
				'#firstName': 'firstName',
				'#lastName': 'lastName',
				'#statusImage': {
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
							observe: 'userId',
							onGet: function(value, options) {
								if (value)
									return 'Has wordpress user!';

								return 'Dont have wordpress user';
							}
						}
					]
				}

			},
			render: function() {

				this.$el.html(this.template(localization.toJSON()));
				this.delegateEvents();
				this.stickit();

				return this;
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
			edit: function() {
				Backbone.history.navigate(this.editRoute(this.model.toJSON()), {
					trigger: true
				});
			},
			del: function() {
				var self = this;

				this.model.destroy({
					success: function() {
						//Show notification
						notifications.showDelete(localization.get('deleted'));
						self.destroy_view();
					}
				});
			}
		});

		return ProfileRowView;
	});




