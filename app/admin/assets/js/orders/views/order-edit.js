// orders/app/views/order-edit.js
define(['jquery', 'localization', 'notifications', 'spinner',
	'text!templates/order-edit.html', 'views/address',
	'collections/extra-fields', 'views/extra-field-row',
	'collections/order-items', 'views/order-item-row',
	'collections/order-statuses', 'views/order-status-row',
	'collections/promotions', 'views/contact', 'views/credit-card',
	'views/extra-field-group', 'views/order-status-title',
	'views/order-ship',
	'wysihtml5', 'datePicker'],
	function($, localization, notifications, spinner,
		OrderTemplate, AddressView,
		ExtraFieldsCollection, ExtraFieldRowView,
		OrderItemsCollection, OrderItemRowView,
		OrderStatusesCollection, OrderStatusRowView,
		Promotions, ContactView, CreditCardView,
		ExtraFieldGroupView, OrderStatusTitleView,
		OrderShipView) {

		return  Backbone.View.extend({
			template: _.template(OrderTemplate),
			//countries:null,
			extraFieldsCollection: null,
			itemsCollection: null,
			statusesCollection: null,
			contactView: null,
			shippingContactView: null,
			billingContactView: null,
			creditCardView: null,
			orderStatusTitleView: null,
			orderShipView: null,
			extraFieldsView: null,
			groupCount: 0,
			addStatus: false,
			initialize: function(options) {

				_.bindAll(this, 'saveOrder', 'cancelOrder', 'addExtraFields', 'addItems', 'addStatuses');

				//add extra fields
				this.extraFieldsCollection = new ExtraFieldsCollection();
				this.extraFieldsCollection.on('reset', this.addExtraFields);

				//add order items
				this.itemsCollection = new OrderItemsCollection();
				this.itemsCollection.on('reset', this.addItems);

				//add order status history
				this.statusesCollection = new OrderStatusesCollection();
				this.statusesCollection.on('reset', this.addStatuses);



			},
			events: {
				'click #save': 'saveOrder',
				'click #cancel': 'cancelOrder'
			},
			bindings: {
				'#number': {
					observe: 'number',
					onGet: function(data) {
						if (data) {
							return ' # ' + data;
						} else {
							return '';
						}
					}
				},
				'#description': 'description',
				'#orderDate': {
					observe: 'orderDate',
					onGet: function(data) {
						//show the date accoding to DateJS CultureInfo
						return Date.parse(data).toString(Date.CultureInfo.formatPatterns.shortDate);
					},
					onSet: function(data) {
						//Save the date as valid format for php/mysql
						return Date.parse(data).toString('yyyy-MM-dd');
					}
				},
				'#subtotal': {
					observe: 'subtotal',
					updateMethod: 'html',
					onGet: function(value, options) {
						return "$&nbsp;" + parseFloat(value).toFixed(2)
					}
				},
				'#total': {
					observe: 'total',
					updateMethod: 'html',
					onGet: function(value, options) {
						return "$&nbsp;" + parseFloat(value).toFixed(2)
					}
				},
				'#shippingAmount': {
					observe: ['shippingAmount', 'shippingMethod'],
					updateMethod: 'html',
					onGet: function(values, options) {
						var shippingText = "$&nbsp;" + parseFloat(values[0]).toFixed(2);
						if (values[1] && values[1].name) {
							shippingText += '<br><em>' + values[1].name + '</em>';
						}
						return  shippingText;
					}
				},
				'#discountAmount': {
					observe: ['discountAmount'],
					updateMethod: 'html',
					onGet: function(values, options) {
						var promotions = new Promotions();
						promotions.reset(this.model.get('promotions'));
						var text = "";
						promotions.each(function(model) {
							if (text.length == 0) {
								text += model.get('name');
							} else {
								text += "<br/>" + model.get('name');
							}
						});

						return "$&nbsp;" + parseFloat(-values[0]).toFixed(2) + '<br><em>' + text + '</em>';
					}
				},
				'#contactName': {
					observe: ['contactSalutation', 'contactFirstName', 'contactLastName'],
					updateMethod: 'html',
					onGet: function(values, options) {
						var result = '';
						if (values[0])
							result += values[0] + '&nbsp;';

						return result + values[1] + '&nbsp;' + values[2];
					}
				},
				'#contactEmail': 'contactEmail',
				'#contactCompany': 'contactCompany',
				'#contactPhone': 'contactPhone',
				'#print': {
					attributes: [
						{
							name: 'href',
							observe: 'id',
							onGet: function(value, options) {
								if (value)
									return Maven.printUrl + 'order/' + value;
								return '';
							}
						}]
				}
			},
			render: function() {
				//var self=this;
				$(this.el).html(this.template(localization.toJSON()));

				this.$('#nav a').click(function(e) {
					e.preventDefault();
					$(this).tab('show');
				});

				//setup datepicker
				//this.setupDatepicker('orderDate');

				/*Bind model to view*/
				this.stickit();
				/*Bind Validation*/
				Backbone.Validation.bind(this, {
					//Important! this allow models to be updated with invalid values.
					//This way the validation behave correctly when the form fields 
					//are invalid
					forceUpdate: true
				});


				this.contactView = new ContactView({
					title: localization.get('contact'),
					data: this.model.get('contact')
				});

				this.contactView.setElement(this.$('#contactContainer')).render();


				this.billingContactView = new ContactView({
					title: localization.get('billingContact'),
					data: this.model.get('billingContact')
				});
				this.billingContactView.setElement(this.$('#billingContainer')).render();

				this.shippingContactView = new ContactView({
					title: localization.get('shippingContact'),
					data: this.model.get('shippingContact')
				});
				this.shippingContactView.setElement(this.$('#shippingContainer')).render();

				this.creditCardView = new CreditCardView({
					title: localization.get('creditCard'),
					data: this.model.get('creditCard')
				});
				this.creditCardView.setElement(this.$('#creditCardContainer')).render();

				this.orderStatusTitleView = new OrderStatusTitleView({
					data: this.model.get('status'),
					transaction: this.model.get('transactionId')
				});
				this.orderStatusTitleView.setElement(this.$('#statusContainer')).render();

				this.orderShipView = new OrderShipView({
					model: this.model,
					position: this.groupCount,
					parentView: this
				});
				this.$("#extraGroupsContainer").append(this.orderShipView.render());
				this.groupCount += 1;

				this.extraFieldsCollection.reset(this.model.get('extraFields'));
				var grouped = this.extraFieldsCollection.groupBy(function(model) {
					return model.get('group');
				});

				this.addExtraFieldGroups(grouped);
				this.$('#extraGroupsContainer').append('<div style="clear:both">');

				this.itemsCollection.reset(this.model.get('items'));
				this.statusesCollection.reset(this.model.get('statusHistory'));

				if (!this.model.id) {
					spinner.stop();
				}

				return this;
			},
			/*setupDatepicker: function(id) {
			 //TODO: datepicker dont use the same format as DateJs.
			 this.$('#' + id).datepicker({
			 format: 'm/d/yyyy',
			 autoclose: true
			 });
			 //set initial value on popup calendar
			 this.$('#' + id).val(
			 Date.parse(this.model.get(id))
			 .toString(Date.CultureInfo.formatPatterns.shortDate))
			 .datepicker('update');
			 },*/
			addExtraField: function(model) {
				var extraFieldRowView = new ExtraFieldRowView({
					model: model
				});
				this.$("#extraFieldsContainer").append(extraFieldRowView.render());
			},
			addExtraFields: function() {
				if (this.extraFieldsCollection.length > 0) {
					this.extraFieldsCollection.each(this.addExtraField, this);
				} else {
					this.$('#emptyExtraInformation').html(localization.get('emptyExtraInformation'));
				}
			},
			addExtraFieldGroup: function(value, key) {
				var extraFieldGroupView = new ExtraFieldGroupView({
					collection: value,
					title: key,
					position: this.groupCount
				});
				this.$("#extraGroupsContainer").append(extraFieldGroupView.render());
				this.groupCount += 1;
			},
			addExtraFieldGroups: function(groups) {
				//this.groupCount = 0;
				_.each(groups, this.addExtraFieldGroup, this);
			},
			addItem: function(model) {
				var orderItemRowView = new OrderItemRowView({
					model: model
				});
				this.$("#orderItems > tbody").append(orderItemRowView.render());
			},
			addItems: function() {
				this.itemsCollection.each(this.addItem, this);
			},
			addStatusFn: function(model) {
				var orderStatusRowView = new OrderStatusRowView({
					model: model
				});
				this.$("#orderStatuses > tbody").append(orderStatusRowView.render());
			},
			addStatuses: function() {
				this.statusesCollection.each(this.addStatusFn, this);
			},
			saveOrder: function() {
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
			cancelOrder: function() {
				//return to default
				Backbone.history.navigate('', {
					trigger: true
				});
			}
		});

	});










