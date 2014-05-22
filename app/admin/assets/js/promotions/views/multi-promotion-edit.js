// donations/app/views/donation_edit.js
define(['jquery', 'localization', 'notifications', 'spinner', 'text!templates/multi-promotion-edit.html', 'toggleButtons', 'wysihtml5', 'datePicker'],
	function($, localization, notifications, spinner, MultiPromotionTemplate) {

		return  Backbone.View.extend({
			template: _.template(MultiPromotionTemplate),
			//countries:null,
			initialize: function(options) {
				_.bindAll(this, 'savePromotion', 'cancelPromotion', 'typeChanged', 'sectionChanged');

				if (!this.model.id) {
					spinner.stop();
				}
				this.model.on('change:section', this.sectionChanged);
				this.model.on('change:type', this.typeChanged);
				this.render();
			},
			events: {
				'click #save': 'savePromotion',
				'click #cancel': 'cancelPromotion'
			},
			sectionChanged: function(model, value) {
				if (value === '') {
					this.$('#section-help').html('');
				} else {
					this.$('#section-help').html(localization.get(value));
				}
			},
			typeChanged: function(model, value) {
				var type = _.find(CachedPromotionTypes, function(item) {
					return item['value'] == value;
				})
				this.$('#typeSymbol').text(type['symbol']);
			},
			bindings: {
				'#quantity': 'quantity',
				'#enabled': 'enabled',
				'#section': {
					observe: 'section',
					selectOptions: {
						collection: function() {
							return CachedPromotionSections;
						}
					},
					labelPath: 'name',
					valuePath: 'value'
				},
				'#name': 'name',
				'#type': {
					observe: 'type',
					selectOptions: {
						collection: function() {
							return CachedPromotionTypes;
						}
					},
					labelPath: 'name',
					valuePath: 'value'
				},
				'#value': 'value',
				'#limitOfUse': 'limitOfUse',
				'#from': {
					observe: 'from',
					onGet: function(data) {
						if (data) {
							//show the date accoding to DateJS CultureInfo
							return Date.parse(data).toString(Date.CultureInfo.formatPatterns.shortDate);
						} else {
							return '';
						}
					},
					onSet: function(data) {
						if (data) {
							//Save the date as valid format for php/mysql
							return Date.parse(data).toString('yyyy-MM-dd');
						} else {
							return '';
						}
					}
				},
				'#to': {
					observe: 'to',
					onGet: function(data) {
						if (data) {
							//show the date accoding to DateJS CultureInfo
							return Date.parse(data).toString(Date.CultureInfo.formatPatterns.shortDate);
						} else {
							return '';
						}
					},
					onSet: function(data) {
						if (data) {
							//Save the date as valid format for php/mysql
							return Date.parse(data).toString('yyyy-MM-dd');
						} else {
							return '';
						}
					}
				},
				'#exclusive': 'exclusive',
				'#description': 'description'
			},
			render: function() {
				var self = this;
				$(this.el).html(this.template(localization.toJSON()));

				this.$('#nav a').click(function(e) {
					e.preventDefault();
					$(this).tab('show');
				});

				//TODO: datepicker dont use the same format as DateJs.
				this.$('#from').datepicker({
					format: 'm/d/yyyy',
					autoclose: true
				});
				//set initial value on popup calendar
				if (this.model.get('from')) {
					this.$('#from').val(
						Date.parse(this.model.get('from'))
						.toString(Date.CultureInfo.formatPatterns.shortDate))
						.datepicker('update');
				}

				//set initial symbol value
				if (this.model.get('type')) {
					var type = _.find(CachedPromotionTypes, function(item) {
						return item['value'] == self.model.get('type');
					})
					this.$('#typeSymbol').text(type['symbol']);
				} else {
					this.$('#typeSymbol').text(' ');
				}

				//TODO: datepicker dont use the same format as DateJs.
				this.$('#to').datepicker({
					format: 'm/d/yyyy',
					autoclose: true
				});
				//set initial value on popup calendar
				if (this.model.get('to')) {
					this.$('#to').val(
						Date.parse(this.model.get('to'))
						.toString(Date.CultureInfo.formatPatterns.shortDate))
						.datepicker('update');
				}

				/*Bind model to view*/
				this.stickit();
				/*Bind Validation*/
				Backbone.Validation.bind(this, {
					//Important! this allow models to be updated with invalid values.
					//This way the validation behave correctly when the form fields 
					//are invalid
					forceUpdate: true
				});

				//apply wysishtml5
				this.$('.wysihtml5').wysihtml5({
					'html': true,
					'stylesheets': false
				});

				/*Important: First bind stickit, then apply toggleButton*/
				this.$('.toggle-button').toggleButtons({
					width: 100,
					label: {
						enabled: localization.get('yes'),
						disabled: localization.get('no')
					}
				});

				return this;
			},
			savePromotion: function(e) {
				e.preventDefault();
				//this.model.validate();

				// Stickit doesn't work with wisyhtml5, we have to do it manually
				var content = this.$('#description').val();
				this.model.set('description', content);

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
			cancelPromotion: function() {
				//return to default
				Backbone.history.navigate('', {
					trigger: true
				});
			}
		});

	});










