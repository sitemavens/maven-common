define(['jquery', 'dataTables', 'spinner', 'localization', 'text!templates/taxes.html',
	'collections/taxes', 'views/tax-row', 'text!templates/empty.html', 'backgridPaginator', 'backgridFilter', 'backgrid']
	, function($, dataTables, spinner, localization, TaxesTlt,
		Taxes, TaxRowView, EmptyTlt) {

		var TaxCell = Backgrid.ActionCell.extend({
			editRoute: _.template("tax/edit/{{ id }}")
		});

		var StatusCell = Backgrid.Cell.extend({
			template: _.template("<img id='statusImage' src='{{statusImageUrl}}' />"),
			render: function() {
				this.$el.html(this.template(this.model.toJSON()));
				return this;
			}
		});

		var TaxesView = Backbone.View.extend({
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			//el: '#mainContainer',
			//forms:null,
			template: _.template(TaxesTlt),
			emptyTemplate: _.template(EmptyTlt),
			//generalTabView:null,
			events: {
				'click #add': 'addNew'
			},
			// Constructor
			initialize: function() {
				_.bindAll(this, 'addNew');

				var self = this;
				this.collection.fetch({
					success: function(collection, response) {
						self.render();
					},
					error: function(collection, response) {
					}
				});
			},
			addOne: function(model) {
				var taxRowView = new TaxRowView({
					model: model
				});
				$("table", this.el).append(taxRowView.render());
			},
			addAll: function() {
				this.collection.each(this.addOne);
			},
			addNew: function() {
				Backbone.history.navigate('tax/new', {
					trigger: true
				});
			},
			render: function() {
				this.$el.html(this.template(localization.toJSON()));
				//this.addAll();

				var columns = [
					{
						name: '',
						cell: TaxCell
					},
					{
						name: 'enabled',
						label: 'Status',
						cell: StatusCell
					},
					{
						name: 'name',
						label: 'Name',
						editable: false,
						cell: 'string'
					},
					{
						name: "country", // The key of the model attribute
						label: "Country", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'string'
					},
					{
						name: "state", // The key of the model attribute
						label: "State", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'string'
					},
					{
						name: "value", // The key of the model attribute
						label: "Value", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'string'
					}, ];

				// Set up a grid to use the pageable collection
				var pageableGrid = new Backgrid.Grid({
					className: 'backgrid table table-striped table-condensed table-hover',
					columns: columns,
					collection: this.collection,
					emptyText: localization.get('emptyResult')
				});


				this.$('#taxes').html(pageableGrid.render().$el);

				// Initialize the paginator
				var paginator = new Backgrid.Extension.Paginator({
					collection: this.collection
				});

				this.$('#taxes').append(paginator.render().$el);

				spinner.stop();
				return this;
			}

		});
		return TaxesView;
	});






