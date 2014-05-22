// donations/app/views/donation_edit.js
define(['jquery', 'localization', 'notifications', 'spinner', 'text!templates/role.html', 'toggleButtons', 'wysihtml5', 'select2'],
	function($, localization, notifications, spinner, RoleTemplate) {

		return  Backbone.View.extend({
			template: _.template(RoleTemplate),
			tagName: 'div',
			className: 'control-group',
			userRoles: null,
			initialize: function(options) {
				if (!this.model.id) {
					spinner.stop();
				}
				this.userRoles = options.collection;
				var found = this.userRoles.find(function(model) {
					return model.get('id') == this.get('id');
				}, this.model);
				//console.log(found);
				if (found)
					this.model.set('selected', true);
				//console.log(this.model);
				//this.render();
			},
			events: {
				'change #status': 'changeStatus'
			},
			bindings: {
				'#rolename': 'name',
				'#status': 'selected'
			},
			changeStatus: function() {
				if (this.$('#status').is(':checked')) {
					this.userRoles.add(this.model);
				} else {
					this.userRoles.remove(this.model);
				}
			},
			render: function() {
				//var self = this;
				this.$el.html(this.template(localization.toJSON()));
				/*Bind model to view*/
				this.stickit();
				/*Bind Validation*/
				Backbone.Validation.bind(this, {
					//Important! this allow models to be updated with invalid values.
					//This way the validation behave correctly when the form fields 
					//are invalid
					forceUpdate: true
				});
				
				/*Important: First bind stickit, then apply toggleButton*/
				this.$('.toggle-button.role-toggle-button').toggleButtons({
					width: 100,
					label: {
						enabled: localization.get('yes'),
						disabled: localization.get('no')
					}
				});
				
				return this;
			}
		});
	});

