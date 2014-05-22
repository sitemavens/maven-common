// presenters/app/router.js
define(function(require) {
	var $ = require('jquery'),
		Order = require('models/order'),
		Orders = require('collections/orders'),
		OrdersView = require('views/orders'),
		notifications = require('notifications'),
		spinner = require('spinner'),
		OrderEditView = require('views/order-edit');

	//this keep the last state
	var gridState = {
		filter: {
			start: null, //Date.today().add({days:-6}).toString('yyyy-MM-dd'), 
			end: null, //Date.today().toString('yyyy-MM-dd'),
			newest: null,
			status: CompletedStatusId,
			number: null
		}
	};

	return Backbone.Router.extend({
		ordersView: null,
		routes: {
			//'order/new': 'newOrder',
			'order/edit/:id': 'editOrder',
			'orders/newest': 'newestOrders',
			'orders/number/:number': 'ordersByNumber',
			'orders/status/:status': 'ordersByStatus',
			'orders/status/:status/from/:start/to/:end': 'ordersByStatusRange',
			'orders/from/:start/to/:end': 'defaultRoute',
			'*path': 'defaultRoute'
		},
		initialize: function(options) {
			this.el = options.el;

			this.orders = new Orders();
		},
		setTitle: function(orderNumber) {
			if (orderNumber) {
				$('.page-title').html('Order #' + orderNumber);
			} else {
				$('.page-title').html('Orders');
			}
		},
		deleteParams: function() {
			delete this.orders.queryParams['newest'];
			delete this.orders.queryParams['status'];
			delete this.orders.queryParams['number'];
			delete this.orders.queryParams['start'];
			delete this.orders.queryParams['end'];
		},
		newestOrders: function() {
			this.setTitle();
			//clear the filter
			gridState.filter.start = null;
			gridState.filter.end = null;
			gridState.filter.newest = true;
			gridState.filter.status = null;
			gridState.filter.number = null;

			var self = this;
			this.deleteParams();
			this.orders.queryParams['newest'] = true;
			this.orders.fetch({
				success: function(orders) {
					$(self.el).html(new OrdersView({
						collection: orders,
						startDate: null,
						endDate: null
					}).el);
				},
				error: function(ex) {
					notifications.showError(ex);
					spinner.stop();
				}
			});
		},
		ordersByStatus: function(status) {
			this.setTitle();
			gridState.filter.start = null;
			gridState.filter.end = null;
			gridState.filter.newest = false;
			gridState.filter.status = status;
			gridState.filter.number = null;

			var self = this;
			this.deleteParams();
			this.orders.queryParams['status'] = status;
			this.orders.fetch({
				success: function(orders) {
					$(self.el).html(new OrdersView({
						collection: orders,
						startDate: null,
						endDate: null,
						number: null,
						orderStatus: gridState.filter.status
					}).el);
				},
				error: function(ex) {
					notifications.showError(ex);
					spinner.stop();
				}
			});
		},
		ordersByNumber: function(number) {
			this.setTitle();
			gridState.filter.start = null;
			gridState.filter.end = null;
			gridState.filter.newest = false;
			gridState.filter.status = null;
			gridState.filter.number = number;

			var self = this;
			this.deleteParams();
			this.orders.queryParams['number'] = number;
			this.orders.fetch({
				success: function(orders) {
					$(self.el).html(new OrdersView({
						collection: orders,
						startDate: null,
						endDate: null,
						number: gridState.filter.number,
						orderStatus: null
					}).el);
				},
				error: function(ex) {
					notifications.showError(ex);
					spinner.stop();
				}
			});
		},
		ordersByStatusRange: function(status, start, end) {
			this.setTitle();
			gridState.filter.start = start;
			gridState.filter.end = end;
			gridState.filter.newest = false;
			gridState.filter.status = status;
			gridState.filter.number = null;

			var self = this;
			this.deleteParams();
			this.orders.queryParams['status'] = status;
			this.orders.queryParams['start'] = start;
			this.orders.queryParams['end'] = end;

			this.orders.fetch({
				success: function(orders) {
					$(self.el).html(new OrdersView({
						collection: orders,
						startDate: Date.parse(gridState.filter.start),
						endDate: Date.parse(gridState.filter.end),
						number: null,
						orderStatus: gridState.filter.status
					}).el);
				},
				error: function(ex) {
					notifications.showError(ex);
					spinner.stop();
				}
			});
		},
		defaultRoute: function(start, end) {
			this.setTitle();
			if (!start || !end) {
				if (gridState.filter.newest) {
					Backbone.history.navigate('orders/newest', {
						trigger: true
					});
					return;
				} else if (gridState.filter.start && gridState.filter.end) {
					if (gridState.filter.status != undefined) {
						Backbone.history.navigate('orders/status/' + gridState.filter.status + '/from/' + gridState.filter.start + '/to/' + gridState.filter.end, {
							trigger: true
						});
					} else {
						Backbone.history.navigate('orders/from/' + gridState.filter.start + '/to/' + gridState.filter.end, {
							trigger: true
						});
					}
					return;
				} else if (gridState.filter.status != undefined) {
					Backbone.history.navigate('orders/status/' + gridState.filter.status, {
						trigger: true
					});
					return;
				} else if (gridState.filter.number != undefined) {
					Backbone.history.navigate('orders/number/' + gridState.filter.number, {
						trigger: true
					});
					return;
				}
			}
			//save the filter
			gridState.filter.start = start;
			gridState.filter.end = end;
			gridState.filter.newest = false;
			gridState.filter.status = null;
			gridState.filter.number = null;

			var self = this;
			this.deleteParams();
			this.orders.queryParams['start'] = gridState.filter.start;
			this.orders.queryParams['end'] = gridState.filter.end;

			this.orders.fetch({
				success: function(orders) {
					$(self.el).html(new OrdersView({
						collection: orders,
						startDate: Date.parse(gridState.filter.start),
						endDate: Date.parse(gridState.filter.end),
						number: null,
						orderStatus: null
					}).el);
				},
				failure: function(ex) {
					notifications.showError(ex);
					spinner.stop();
				}
			});
		},
		/*newOrder:function(){
		 this.setTitle();
		 var order=new Order();
		 var orderEditView = new new OrderEditView({
		 model:order
		 });
		 
		 $(this.el).html(orderEditView.render().el);			
		 },*/
		editOrder: function(orderId) {
			var self = this;
			var order = new Order({
				id: orderId
			});

			//Fetch the data from the server
			order.fetch({
				success: function(model) {
					self.setTitle(model.get('number'));

					var orderEditView = new OrderEditView({
						model: model
					});

					$(self.el).html(orderEditView.render().el);
				},
				failure: function(ex) {
					notifications.showError(ex);
					spinner.stop();
				}
			});
		}
	});
});


