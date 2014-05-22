define(['jquery', 'dataTables', 'spinner', 'localization', 'text!templates/attributes.html',
	'collections/attributes', 'views/attribute-row', 'text!templates/empty.html', 'backgridPaginator', 'backgridFilter', 'backgrid']
	, function($, dataTables, spinner, localization, AttributesTlt, Attributes, AttributeRowView, EmptyTlt) {

		var AttributeCell = Backgrid.ActionCell.extend({
			editRoute: _.template("attribute/edit/{{ id }}")
		});

		var LinkCell = Backgrid.Cell.extend({
			template: _.template('<a id="slug" href="" target="_blank">{{view}}</a>'),
			render: function() {
				this.$el.html(this.template(localization.toJSON()));

				this.stickit();
				return this;
			}
		});

		var AttributesView = Backbone.View.extend({
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			//el: '#mainContainer',
			//forms:null,
			template: _.template(AttributesTlt),
			emptyTemplate: _.template(EmptyTlt),
			name: null,
			//generalTabView:null,
			events: {
				'click #add': 'addNew',
				'click #search': 'search',
				'click #cleanSearch': 'removeSearch'
			},
			// Constructor
			initialize: function(options) {
				_.bindAll(this, 'addNew', 'search', 'removeSearch');

				this.name = options.name;

				var self = this;
				this.collection.fetch({
					success: function(collection, response) {
						//console.log('success');
						//console.log(collection.state);
						self.render();
					},
					error: function(collection, response) {
						// called if server request fail
						//console.log('fail :(');
					}
				});
			},
			addNew: function() {
				Backbone.history.navigate('attribute/new', {
					trigger: true
				});
			},
			loadFilter: function() {
				if (this.name)
					this.$('#fieldName').val(this.name);
			},
			removeSearch: function() {
				Backbone.history.navigate('attributes', {
					trigger: true
				});
			},
			search: function() {
				var query = '';
				var name = this.$('#fieldName').val();
				if (name)
					query += '/name/' + name;

				Backbone.history.navigate('attributes' + query, {
					trigger: true
				});
			},
			render: function() {
				this.$el.html(this.template(localization.toJSON()));
				var columns = [
					{
						name: '',
						cell: AttributeCell
					},
					{
						name: 'name',
						label: 'Attribute',
						editable: false,
						cell: 'string'
					},
					{
						name: 'defaultAmount',
						label: 'Default Price',
						editable: false,
						cell: 'string'
					},
					{
						name: 'defaultWholesaleAmount',
						label: 'Default Wholesale Price',
						editable: false,
						cell: 'string'
					}];

				// Set up a grid to use the pageable collection
				var pageableGrid = new Backgrid.Grid({
					className: 'backgrid table table-striped table-condensed table-hover',
					columns: columns,
					collection: this.collection,
					emptyText: localization.get('emptyResult')
				});

				//pageableGrid.header.row.cells[0].sort("email", "ascending");

				this.$('#attributesContainer').html(pageableGrid.render().$el);

				// Initialize the paginator
				var paginator = new Backgrid.Extension.Paginator({
					collection: this.collection
				});

				this.$('#attributesContainer').append(paginator.render().$el);

				this.loadFilter();

				spinner.stop();
				return this;
			}

		});
		return AttributesView;
	});






