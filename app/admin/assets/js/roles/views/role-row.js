
define(['jquery', 'localization', 'notifications', 'text!templates/role-row.html'],
	function($, localization, notifications, RoleRowTlt) {


		var RoleRowView = Backbone.View.extend({
			tagName: "tr",
			template: _.template(RoleRowTlt),
			editRoute: _.template("role/edit/{{ id }}"),
			events: {
				'click button[id=edit]': 'editRole',
				'click a[id=confirmDeleteButton]': 'showDelete',
				'click button[id=performDelete]': 'deleteRole'
			},
			bindings: {
				'#name': 'name'
			},
			initialize: function() {
				_.bindAll(this, 'render', 'editRole', 'showDelete', 'deleteRole');
				this.model.bind('change', this.render)
			},
			render: function() {

				$(this.el).append(this.template(localization.toJSON()));

				if (this.model.get('systemRole'))
					this.$('#confirmDeleteButton').hide();

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
			editRole: function() {
				Backbone.history.navigate(this.editRoute(this.model.toJSON()), {
					trigger: true
				});
			},
			deleteRole: function() {
				var self = this;

				this.model.destroy({
					success: function() {
						//Show notification
						notifications.showDelete(localization.get('RoleDeleted'));
						self.destroy_view();
					}
				});
			}
		});

		return RoleRowView;
	});




