define(['jquery', 'spinner', 'localization', 'text!templates/general.html', 'collections/settings', 'toggleButtons']
	, function($, spinner, localization, GeneralTlt, Settings) {

		var GeneralTabView = Backbone.View.extend({
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)

			settings: null,
			template: _.template(GeneralTlt),
			events: {
				'click #save': 'save',
				'click .control-group .btn.custom-action': 'addVariable'
			},
			save: function() {

				//Since stickit doesn't work with tagsInput, we have to refresh the value manually
				this.model.set('emailNotificationsTo', $('#emailNotificationsTo').val());

				// Stickit doesn't work with tinyMCE, we have to do it manually
				var content = this.$('#thankYouMailContent').val();

				this.model.set('thankYouMailContent', content);

				this.model.save();
			},
			bindings: {
				'#sendReportTo': 'sendReportTo',
				'#enabled': {
					observe: 'enabled',
					onSet: function(value, options) {
						var val = this.$('#enabled').is(':checked');
						return val;
					}
				},
				'#daysOfTheWeek': {
					observe: 'daysOfTheWeek',
					selectOptions: {
						collection: function() {

							return	[
								{id: 'monday', name: 'Monday'},
								{id: 'tuesday', name: 'Tuesday'},
								{id: 'wednesday', name: 'Wednesday'},
								{id: 'thursday', name: 'Thursday'},
								{id: 'friday', name: 'Friday'},
								{id: 'saturday', name: 'Saturday'},
								{id: 'sunday', name: 'Sunday'}
							];

						},
						labelPath: 'name',
						valuePath: 'id'
					}
				}
			},
			// Constructor
			initialize: function(options) {

				_.bindAll(this, 'save', 'render');

			},
			render: function() {


				this.$el.html(this.template(localization.toJSON()));

				this.stickit();

				$('#emailNotificationsTo').tagsInput({
					width: 'auto',
					defaultText: localization.get('addEmail')
				});

				this.$('.toggle-button').toggleButtons({
					width: 100
				});

				return this;
			}
		});
		return GeneralTabView;
	});
