define(['jquery', 'dataTables', 'spinner', 'localization', 'text!templates/orders.html',
	'collections/orders', 'views/order-row', 'text!templates/empty.html', 'collections/order-statuses',
	'notifications', 'views/order-stats', 'collections/order-stats',
	'views/order-total', 'backgridPaginator', 'backgridFilter', 'backgrid',
	'dateRangePicker']
	, function($, dataTables, spinner, localization, OrdersTlt,
		Orders, OrderRowView, EmptyTlt, OrderStatuses, notifications,
		OrderStatsView, OrderStatsCollection, OrderTotalView) {

		var OrderCell = Backgrid.ActionCell.extend({
			editRoute: _.template("order/edit/{{ id }}"),
			printObject: 'order'
		});

		var StatusCell = Backgrid.Cell.extend({
			template: _.template("<img id='statusImage' src='{{imageUrl}}' />"),
			render: function() {
				this.$el.html(this.template(this.model.get('status')));
				return this;
			}
		});

		var CustomerCell = Backgrid.Cell.extend({
			template: _.template("{{lastName}}, {{firstName}}"),
			render: function() {
				this.$el.html(this.template(this.model.get('contact')));
				return this;
			}
		});

		var OrdersView = Backbone.View.extend({
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			//el: '#mainContainer',
			//forms:null,
			template: _.template(OrdersTlt),
			emptyTemplate: _.template(EmptyTlt),
			//generalTabView:null,
			startDate: null,
			endDate: null,
			cachedOrderStatuses: null,
			orderStatus: null,
			number: null,
			filterRoute: _.template("orders/from/{{ start }}/to/{{ end }}"),
			filterStatusRange: _.template("orders/status/{{ status }}/from/{{ start }}/to/{{ end }}"),
			// Constructor
			initialize: function(options) {
				_.bindAll(this, 'rangeChanged', 'searchNumber', 'changeStatus','addStatus');
				if (options.startDate)
					this.startDate = options.startDate;

				if (options.endDate)
					this.endDate = options.endDate;

				if (options.orderStatus)
					this.orderStatus = options.orderStatus;

				if (options.number)
					this.number = options.number;

				this.cachedOrderStatuses = new OrderStatuses();
				this.cachedOrderStatuses.reset(CachedStatuses);

				this.on('change:range', this.rangeChanged);

				this.render();
			},
			events: {
				//'click #add': 'addNew',
				'click #btnLastOrders': 'showLastOrders',
				'click #searchNumber': 'searchNumber',
				'change #searchStatus': 'changeStatus'
			},
			rangeChanged: function(range) {
				if (range.start === null && range.end === null) {
					Backbone.history.navigate('orders/newest', {
						trigger: true
					});
				}
				var status = this.$('#searchStatus').val();
				if (status) {
					range.status = status;
					Backbone.history.navigate(this.filterStatusRange(range), {
						trigger: true
					});
				} else {
					Backbone.history.navigate(this.filterRoute(range), {
						trigger: true
					});
				}
			},
			showLastOrders: function() {
				Backbone.history.navigate('orders/newest', {
					trigger: true
				});
			},
			searchNumber: function() {
				var number = this.$('#fieldNumber').val();
				if (number) {
					Backbone.history.navigate('orders/number/' + number, {
						trigger: true
					});
				}
			},
			changeStatus: function() {
				var status = this.$('#searchStatus').val();
				if (status) {
					if (this.startDate && this.endDate) {
						var params = {};
						params.start = this.startDate.toString('yyyy-MM-dd');
						params.end = this.endDate.toString('yyyy-MM-dd');
						params.status = status;
						Backbone.history.navigate(this.filterStatusRange(params), {
							trigger: true
						});
					} else {
						Backbone.history.navigate('orders/status/' + status, {
							trigger: true
						});
					}
				} else {
					if (this.startDate && this.endDate) {
						var params = {};
						params.start = this.startDate.toString('yyyy-MM-dd');
						params.end = this.endDate.toString('yyyy-MM-dd');
						Backbone.history.navigate(this.filterRoute(params), {
							trigger: true
						});
					} else {
						Backbone.history.navigate('orders/newest', {
							trigger: true
						});
					}
				}
			},
			addStatuses: function() {
				this.cachedOrderStatuses.each(this.addStatus);
			},
			addStatus: function(model) {
				var label = model.get('label') == '' ? localization.get('selectOrderStatus') : model.get('label');
				if (model.get('value') == this.orderStatus) {
					this.$('#searchStatus').append("<option selected='selected' value='" + model.get('value') + "'>" + label + "</option>");

				} else {
					this.$('#searchStatus').append("<option value='" + model.get('value') + "'>" + label + "</option>");
				}
			},
			render: function() {
				this.$el.html(this.template(localization.toJSON()));

				this.addStatuses();

				//Add stats
				new OrderStatsView({
					collection: new OrderStatsCollection(null, {
						collection: this.collection
					}),
					el: this.$('#ordersStats')
				})

				//Add Totalstats
				new OrderTotalView({
					el: this.$('#ordersStatsTotal')
				})

				var columns = [
					{
						name: '',
						cell: OrderCell,
						editable: false
					},
					{
						name: 'statusId',
						label: 'Status',
						editable: false,
						cell: StatusCell
					},
					{
						name: "number", // The key of the model attribute
						label: "Number", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: Backgrid.IntegerCell.extend({className: ''}),
					},
					{
						name: "contact", // The key of the model attribute
						label: "Customer", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						sortable: false,
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: CustomerCell
					},
					{
						name: "orderDate", // The key of the model attribute
						label: "Date", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'string'
					},
					{
						name: "total",
						label: "Total",
						editable: false,
						//cell: 'number',
						cell: Backgrid.NumberCell.extend({className: ''}),
						decimals: 2
					}
				];

				// Set up a grid to use the pageable collection
				var pageableGrid = new Backgrid.Grid({
					className: 'backgrid table table-striped table-condensed table-hover',
					columns: columns,
					collection: this.collection,
					emptyText: localization.get('emptyResult')
				});

				//set the correct order direction on the header
				pageableGrid.header.row.cells[4].direction('descending');

				this.$('#orders').html(pageableGrid.render().$el);

				// Initialize the paginator
				var paginator = new Backgrid.Extension.Paginator({
					collection: this.collection
				});

				this.$('#orders').append(paginator.render().$el);

				var self = this;
				//prepare datepicker
				var myRanges = {};
				//myRanges[localization.get('lastDonations')]=[null,null];
				myRanges[localization.get('today')] = ['today', 'today'];
				myRanges[localization.get('yesterday')] = ['yesterday', 'yesterday'];
				myRanges[localization.get('lastSevenDays')] = [Date.today().add({
						days: -6
					}), 'today'];
				myRanges[localization.get('lastThirtyDays')] = [Date.today().add({
						days: -29
					}), 'today'];
				myRanges[localization.get('thisMonth')] = [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()];
				myRanges[localization.get('lastMonth')] = [Date.today().moveToFirstDayOfMonth().add({
						months: -1
					}), Date.today().moveToFirstDayOfMonth().add({
						days: -1
					})];

				this.$('#form-date-range').daterangepicker({
					ranges: myRanges,
					opens: 'left',
					format: Date.CultureInfo.formatPatterns.shortDate, // 'MM/dd/yyyy',
					separator: ' to ',
					startDate: Date.today().add({
						days: -29
					}),
					endDate: Date.today(),
					maxDate: Date.today(),
					locale: {
						applyLabel: localization.get('rangeApplyLabel'),
						fromLabel: localization.get('rangeFromLabel'),
						toLabel: localization.get('rangeToLabel'),
						customRangeLabel: localization.get('rangeLabel'),
						//daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
						//monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
						firstDay: 1
					},
					showWeekNumbers: true,
					buttonClasses: ['btn-danger']
				}, function(start, end) {
					self.startDate = (start == null ? null : start.toString('yyyy-MM-dd'));
					self.endDate = (end == null ? end : end.toString('yyyy-MM-dd'));

					self.trigger('change:range', {
						start: self.startDate,
						end: self.endDate
					});
				});

				//Set initial range
				if (this.startDate == null || this.endDate == null) {
					this.$('#form-date-range span').html(localization.get('noRange'));
				} else {
					this.$('#form-date-range span').html(this.startDate.toString(Date.CultureInfo.formatPatterns.shortDate) + ' - ' + this.endDate.toString(Date.CultureInfo.formatPatterns.shortDate));
				}

				//set initial number
				if (this.number == null) {
					this.$('#fieldNumber').val('');
				} else {
					this.$('#fieldNumber').val(this.number);
				}

				return this;
			}

		});
		return OrdersView;
	});






