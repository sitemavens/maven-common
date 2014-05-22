define(['jquery', 'dataTables', 'spinner', 'localization', 'text!templates/roles.html',
	'collections/roles', 'views/role-row', 'text!templates/empty.html']
	, function($, dataTables, spinner, localization, RolesTlt, Roles, RoleRowView, EmptyTlt) {

		var RolesView = Backbone.View.extend({
			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			//el: '#mainContainer',
			//forms:null,
			template: _.template(RolesTlt),
			emptyTemplate: _.template(EmptyTlt),
			//generalTabView:null,
			events: {
				'click #add': 'addNew'
			},
			// Constructor
			initialize: function(options) {
				_.bindAll(this, 'addNew');

				this.collection = options.roles;
				this.collection.on("add", this.addOne);

			},
			addOne: function(model) {
				var roleRowView = new RoleRowView({
					model: model
				});
				$("table", this.el).append(roleRowView.render());
			},
			addAll: function() {
				this.collection.each(this.addOne);
			},
			addNew: function() {
				Backbone.history.navigate('role/new', {
					trigger: true
				});
			},
			render: function() {
				this.$el.html(this.template(localization.toJSON()));
				this.addAll();

				this.$('#roles').dataTable({
					"bPaginate": false,
					"bLengthChange": false,
					"bFilter": true,
					"bSort": true,
					"bInfo": false,
					"bAutoWidth": false,
					"oLanguage": {
						"sEmptyTable": this.emptyTemplate({
							message: localization.get('emptyResult')
						}),
						"sZeroRecords": this.emptyTemplate({
							message: localization.get('emptySearch')
						})
					},
					"aoColumnDefs": [
						{
							"aTargets": ['nameColumn'],
							'sWidth': '500'
						},
						{
							"aTargets": ['actions'],
							'sWidth': '350',
							'bSortable': false,
							"bSearchable": false
						}]
				});
				spinner.stop();
				return this;
			}

		});
		return RolesView;
	});






