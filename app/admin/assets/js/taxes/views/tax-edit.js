// donations/app/views/donation_edit.js
define(['jquery', 'localization', 'notifications', 'spinner', 'text!templates/tax-edit.html', 'toggleButtons', 'select2'],
	function($, localization, notifications, spinner, TaxTemplate) {

		return  Backbone.View.extend({
			template: _.template(TaxTemplate),
			//countries:null,
			optionsTemplate: _.template("<option value={{value}}>{{name}}</option>"),
			initialize: function(options) {
				_.bindAll(this, 'renderOptions', 'render', 'saveTax');

				if (!this.model.id) {
					spinner.stop();
				}
				this.render();
			},
			renderOptions: function() {
				var self = this;
				_.each(CachedCountries, function(country) {
					self.$('#country').append(self.optionsTemplate(country));
				}, self)
			},
			events: {
				'click #save': 'saveTax',
				'click #cancel': 'cancelTax'
			},
			bindings: {
				'#enabled': 'enabled',
				'#name': 'name',
				'#state': 'state',
				'#value': 'value',
				'#forShipping': 'forShipping',
				'#compound': 'compound'
			},
			render: function() {
				var self = this;
				$(this.el).html(this.template(localization.toJSON()));

				this.$('#nav a').click(function(e) {
					e.preventDefault();
					$(this).tab('show');
				});

				//add countries options
				this.renderOptions();

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
				this.$('.toggle-button').toggleButtons({
					width: 100,
					label: {
						enabled: localization.get('yes'),
						disabled: localization.get('no')
					}
				});

				this.$('#country').select2({
					allowClear: true,
					formatResult: self.format,
					formatSelection: self.format,
					escapeMarkup: function(m) {
						return m;
					}
				});

				//Manually bind country
				var country = this.model.get('country');
				if (CachedCountries[country]) {
					//Seteo el pais, solo si es una opcion del combo
					this.$('#country').select2('val', country);
				}

				return this;
			},
			format: function(state) {
				if (!state.id)
					return state.text; // optgroup
				var image = state.id.toLowerCase();
				if (state.id == '*')
					image = 'all';
				return "<img class='flag' src='" + Maven.imagesPath + "flags_small/" + image + ".png'/>&nbsp;&nbsp;" + state.text;
			},
			saveTax: function(e) {
				e.preventDefault();
				//this.model.validate();

				//set country
				this.model.set('country', this.$('#country').val());

				if (this.model.isValid(true)) {
					this.model.save(null, {
						success: function() {
							Backbone.history.navigate('', {
								trigger: true
							});
						},
						failure: function(ex) {
							notifications.showError(localization.get('saveError'));
						}
					});
				} else {
					//TODO: This should show a message in the page
					notifications.showError(localization.get('saveError'));
				}
			},
			cancelTax: function() {
				//return to default
				Backbone.history.navigate('', {
					trigger: true
				});
			}
		});

	});










