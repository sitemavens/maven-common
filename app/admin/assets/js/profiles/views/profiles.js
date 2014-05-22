define(['jquery', 'dataTables', 'spinner', 'localization', 'text!templates/profiles.html',
	'collections/profiles', 'views/profile-row', 'text!templates/empty.html', 'backgridPaginator', 'backgridFilter', 'backgrid']
	, function($, dataTables, spinner, localization, ProfilesTlt, Profiles, ProfileRowView, EmptyTlt) {

		var ProfileCell = Backgrid.ActionCell.extend({
			editRoute: _.template("profile/edit/{{ id }}")
		});

		var StatusCell = Backgrid.Cell.extend({
			template: _.template("<img id='statusImage' src='{{statusImageUrl}}' />"),
			render: function() {
				this.$el.html(this.template(this.model.toJSON()));
				return this;
			}
		});

		var ProfilesView = Backbone.View.extend({
			template: _.template(ProfilesTlt),
			paginator: null,
			emptyTemplate: _.template(EmptyTlt),
			email: null,
			firstName: null,
			lastName: null,
			events: {
				'click #add': 'addNew',
				'click #search': 'search',
				'click #cleanSearch': 'removeSearch'
			},
			// Constructor
			initialize: function(options) {
				_.bindAll(this, 'addNew', 'search', 'removeSearch');

				this.collection = options.profiles;
				var self = this;

				this.email = options.email;
				this.firstName = options.firstName;
				this.lastName = options.lastName;
				this.collection.fetch({
					success: function(collection, response) {
						self.render();
					},
					error: function(collection, response) {
					}
				});

				//this.render();
			},
			addNew: function() {
				Backbone.history.navigate('profile/new', {
					trigger: true
				});
			},
			loadFilter: function() {
				if (this.email)
					this.$('#fieldEmail').val(this.email);

				if (this.firstName)
					this.$('#fieldFirstName').val(this.firstName);

				if (this.lastName)
					this.$('#fieldLastName').val(this.lastName);
			},
			removeSearch: function() {
				Backbone.history.navigate('profiles', {
					trigger: true
				});
			},
			search: function() {
				var query = '';
				var email = this.$('#fieldEmail').val();
				if (email)
					query += '/email/' + email;

				var firstName = this.$('#fieldFirstName').val();
				if (firstName)
					query += '/fname/' + firstName;

				var lastName = this.$('#fieldLastName').val();
				if (lastName)
					query += '/lname/' + lastName;

				Backbone.history.navigate('profiles' + query, {
					trigger: true
				});
			},
			render: function() {
				this.$el.html(this.template(localization.toJSON()));

				var columns = [
					{
						name: '',
						cell: ProfileCell
					},
					{
						name: 'user_id',
						label: 'Status',
						editable:false,
						cell: StatusCell
					},
					{
						name: "email", // The key of the model attribute
						label: "Email", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'string'
					},
					{
						name: "firstName", // The key of the model attribute
						label: "First Name", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'string'
					},
					{
						name: "lastName", // The key of the model attribute
						label: "Last Name", // The name to display in the header
						editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
						// Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
						cell: 'string'
					}, 
					{
						name: "createdOn",
						label: "Creation Date",
						editable: false, 
						cell: Backgrid.MavenDateTimeCell
					},
					{
						name: "lastUpdate",
						label: "Last Update",
						editable: false, 
						cell: Backgrid.MavenDateTimeCell
					},];

				// Set up a grid to use the pageable collection
				var pageableGrid = new Backgrid.Grid({
					className: 'backgrid table table-striped table-condensed table-hover',
					columns: columns,
					collection: this.collection,
					emptyText: localization.get('emptyResult')
				});

				//pageableGrid.header.row.cells[0].sort("email", "ascending");

				this.$('#profiles-container').html(pageableGrid.render().$el);

				// Initialize the paginator
				var paginator = new Backgrid.Extension.Paginator({
					collection: this.collection
				});

				this.$('#profiles-container').append(paginator.render().$el);

				this.loadFilter();

				spinner.stop();
				return this;
			}

		});
		return ProfilesView;
	});






