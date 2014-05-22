define(['jquery', 'dataTables', 'spinner', 'localization', 'text!templates/promotions.html',
	'collections/promotions', 'views/promotion-row', 'text!templates/empty.html', 'models/export', 'backgridPaginator', 'backgridFilter', 'backgrid']
	, function($, dataTables, spinner, localization, PromotionsTlt,
		Promotions, PromotionRowView, EmptyTlt, Export) {

		var PromotionCell = Backgrid.ActionCell.extend({
			editRoute: _.template("promotion/edit/{{ id }}")
		});

		var StatusCell = Backgrid.Cell.extend({
			template: _.template("<img id='statusImage' src='{{statusImageUrl}}' />"),
			render: function() {
				this.$el.html(this.template(this.model.toJSON()));
				return this;
			}
		});

		var UsesCell = Backgrid.Cell.extend({
			template: _.template("{{uses}} / {{limitOfUse}}"),
			render: function() {
				var temp = {};
				temp.uses = this.model.get('uses');
				var limit = this.model.get('limitOfUse');
				temp.limitOfUse = limit == '0' ? localization.get('unlimited') : limit;
				this.$el.html(this.template(temp));
				return this;
			}
		});

		var PromotionsView = Backbone.View.extend({
			template: _.template(PromotionsTlt),
			emptyTemplate: _.template(EmptyTlt),
			events: {
				'click #add': 'addNew',
				'click #multi': 'addMulti',
				'click #export': 'exportGrid'
			},
			// Constructor
			initialize: function() {
				_.bindAll(this, 'addNew', 'addMulti', 'exportGrid');

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
				var promotionRowView = new PromotionRowView({
					model: model
				});
				$("table", this.el).append(promotionRowView.render());
			},
			addAll: function() {
				this.collection.each(this.addOne);
			},
			addNew: function() {
				Backbone.history.navigate('promotion/new', {
					trigger: true
				});
			},
			addMulti: function() {
				Backbone.history.navigate('promotion/multi', {
					trigger: true
				});
			},
			exportGrid: function() {
				var exportGrid = new Export();

				/*var event=this.$('#events').val();
				 var status=this.$('#statuses').val();
				 if(event){
				 exportGrid.set('event',event);					
				 }
				 if(status){
				 exportGrid.set('status',status);
				 }*/
				if (this.collection.state.sortKey) {
					exportGrid.set('sort_by', this.collection.state.sortKey);
				}
				if (this.collection.state.order) {
					if (this.collection.state.order == 1) {
						exportGrid.set('order', 'desc');
					}
					if (this.collection.state.order == -1) {
						exportGrid.set('order', 'asc');
					}
				}

				exportGrid.export();
			},
			render: function() {
				this.$el.html(this.template(localization.toJSON()));
				//this.addAll();
				var columns = [
					{
						name: '',
						editable: false,
						cell: PromotionCell
					},
					{
						name: 'enabled',
						label: 'Status',
						editable: false,
						cell: StatusCell
					},
					{
						name: 'name',
						label: 'Name',
						editable: false,
						cell: 'string'
					},
					{
						name: "code", // The key of the model attribute
						label: "Code", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'string'
					},
					{
						name: "section", // The key of the model attribute
						label: "Section", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'string'
					},
					{
						name: "uses", // The key of the model attribute
						label: "Uses", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: UsesCell
					},
					{
						name: "from", // The key of the model attribute
						label: "From", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'date'
					},
					{
						name: "to", // The key of the model attribute
						label: "To", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'date'
					}];

				// Set up a grid to use the pageable collection
				var pageableGrid = new Backgrid.Grid({
					className: 'backgrid table table-striped table-condensed table-hover',
					columns: columns,
					collection: this.collection,
					emptyText: localization.get('emptyResult')
				});

				//pageableGrid.header.row.cells[0].sort("email", "ascending");

				this.$('#promotions').html(pageableGrid.render().$el);

				// Initialize the paginator
				var paginator = new Backgrid.Extension.Paginator({
					collection: this.collection
				});

				this.$('#promotions').append(paginator.render().$el);

				spinner.stop();
				return this;
			}

		});
		return PromotionsView;
	});






