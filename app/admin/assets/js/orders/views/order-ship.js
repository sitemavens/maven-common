// orders/app/views/order-edit.js
define(['jquery', 'localization', 'notifications', 'spinner', 'text!templates/order-ship.html'],
	function($, localization, notifications, spinner, OrderShipTemplate) {

		return  Backbone.View.extend({
			template: _.template(OrderShipTemplate),
			//title:null,
			className: 'col-3',
			position: 0,
			parentView: null,
			prevCarrier: '',
			prevCode: '',
			prevUrl: '',
			initialize: function(options) {
				_.bindAll(this, 'showFields', 'cancelSend', 'sendNotice');

				this.position = options.position;
				this.parentView = options.parentView;
				//this.render();	

				this.prevCarrier = this.model.get('shippingCarrier');
				this.prevCode = this.model.get('shippingTrackingCode');
				this.prevUrl = this.model.get('shippingTrackingUrl');

			},
			events: {
				'click #btnAddShipmentNotice': 'showFields',
				'click #btnCancelShipmentNotice': 'cancelSend',
				'click #btnSendShipmentNotice': 'sendNotice'
			},
			bindings: {
				'#shippingCarrier': 'shippingCarrier',
				'#shippingTrackingCode': 'shippingTrackingCode',
				'#shippingTrackingUrl': 'shippingTrackingUrl',
				'#shippingCarrierLabel': 'shippingCarrier',
				'#shippingTrackingCodeLabel': 'shippingTrackingCode',
				'#shippingTrackingUrlLink': {
					attributes: [{
							name: 'href',
							observe: 'shippingTrackingUrl'
						}]
				}
			},
			manageView: function(edit) {
				if (edit) {
					this.$('#info').hide();
					this.$('#action').hide();
					this.$('#fields').show();
				} else {
					if (this.model.get('statusId') == ShippedStatusId) {
						this.$('#info').show();
					} else {
						this.$('#info').hide();
					}

					this.$('#action').show();
					this.$('#fields').hide();
				}

			},
			restoreFields: function() {
				this.model.set('shippingCarrier', this.prevCarrier);
				this.model.set('shippingTrackingCode', this.prevCode);
				this.model.set('shippingTrackingUrl', this.prevUrl);
			},
			showFields: function() {

				this.model.set('validateShipping', true);

				this.manageView(true);
			},
			cancelSend: function() {
				//restoreFields
				this.restoreFields();
				this.model.set('validateShipping', false);
				this.manageView(false);
			},
			sendNotice: function() {
				this.model.set('sendNotice', true);
				this.parentView.saveOrder();
			},
			render: function() {
				$(this.el).html(this.template(localization.toJSON()));

				if ((this.position % 3) == 0) {
					$(this.el).addClass('shipto');
				}

				this.stickit();

				this.manageView(false);

				return $(this.el)
			}
		});
	});



