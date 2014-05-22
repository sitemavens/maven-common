// donations/app/views/donation_edit.js
define(['jquery', 'localization', 'notifications', 'spinner', 'text!templates/address-edit.html', 'toggleButtons', 'wysihtml5', 'select2'],
	function($, localization, notifications, spinner, AddressEditTemplate) {

		return  Backbone.View.extend({
			template: _.template(AddressEditTemplate),
			tagName: 'div',
			className: 'accordion-group',
			//countries:null,
			collection: null,
			tempId: null,
			optionsTemplate: _.template("<option value={{value}}>{{name}}</option>"),
			initialize: function(options) {
				if (!this.model.id) {
					spinner.stop();
				}
				this.collection = options.collection;
				//console.log(this.model);
				//this.render();
			},
			renderOptions: function() {
				var self = this;
				_.each(CachedCountries, function(country) {
					self.$('#country').append(self.optionsTemplate(country));
				}, self)
			},
			events: {
				'change #country': 'setCountry',
				'click button[id=delete]': 'deleteAddress'
			},
			deleteAddress: function() {
				this.collection.remove(this.model);
				this.destroy_view();
			},
			destroy_view: function() {
				//animate the destroy
				//$(this.el).toggleClass('error');
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
			bindings: {
				'.accordion-toggle': {
					attributes: [{
							name: 'href',
							observe: 'id',
							onGet: function(value, options) {
								if (value)
									return '#acc_' + value;
								return '#acc_' + this.cid;
							}
						}]
				},
				'.accordion-body': {
					attributes: [{
							name: 'id',
							observe: 'id',
							onGet: function(value, options) {
								if (value) {
									return 'acc_' + value;
								}
								return 'acc_' + this.cid;
							}
						}]
				},
				'#title': {
					observe: 'type',
					onGet: function(value) {
						return AddressesTypes[value];
					}
				},
				'#title-first-line': {
					observe: 'firstLine',
					onGet: function(value) {
						if (value)
							return ' - ' + value;
						return '';
					}
				},
				'#title-city': {
					observe: 'city',
					onGet: function(value) {
						if (value)
							return ' - ' + value;
						return '';
					}
				},
				'#title-state': {
					observe: 'state',
					onGet: function(value) {
						if (value)
							return ' - ' + value;
						return '';
					}
				},
				'#title-country': {
					observe: 'country',
					onGet: function(value) {
						if (value)
							return ' - ' + value;
						return '';
					}
				},
				'#title-primary': {
					observe: 'primary',
					onGet: function(value) {
						if (value)
							return ' ('+ localization.get('primary') +')';
						return '';
					}
				},
				'#type': {
					observe: 'type',
					selectOptions: {
						collection: function() {
							var temp = [];
							for (var key in AddressesTypes) {
								temp.push({
									id: key,
									data: {
										name: AddressesTypes[key]
									}
								});
							}

							return temp;
						},
						labelPath: 'data.name',
						valuePath: 'id'

					}
				},
				'#name': 'name',
				'#primary': 'primary',
				'#description': 'description',
				'#firstLine': 'firstLine',
				'#secondLine': 'secondLine',
				'#neighborhood': 'neighborhood',
				'#city': 'city',
				'#state': 'state',
				//'#country'		: 'country',
				'#zipcode': 'zipcode',
				'#notes': 'notes',
				'#addressPhone': 'phone',
				'#phoneAlternative': 'phoneAlternative',
				'#adminNotes': 'adminNotes',
				'#primary': 'primary'
			},
			setCountry: function() {
				this.model.set('country', this.$('#country').val());
			},
			getSelectedCountry: function() {
				//set country
				return this.$('#country').val();
			},
			format: function(state) {
				if (!state.id)
					return state.text; // optgroup
				var image = state.id.toLowerCase();
				if (state.id == '*')
					image = 'all';
				return "<img class='flag' src='" + Maven.imagesPath + "flags_small/" + image + ".png'/>&nbsp;&nbsp;" + state.text;
			},
			render: function() {
				var self = this;
				this.$el.html(this.template(localization.toJSON()));

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
				this.$('.toggle-button.address-toggle-button').toggleButtons({
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
			}
		});

	});

