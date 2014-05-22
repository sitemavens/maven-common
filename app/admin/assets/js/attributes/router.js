// donations/app/router.js
define(function(require) {
	var $ = require('jquery'),
		Attribute = require('models/attribute'),
		CategoriesView = require('views/attributes'),
		notifications = require('notifications'),
		spinner = require('spinner'),
		Categories = require('collections/attributes'),
		AttributeEditView = require('views/attribute-edit');

	//this keep the last state
	var gridState = {
		filter: {
			name: null
		}
	};

	return Backbone.Router.extend({
		attributesView: null,
		attributes: null,
		routes: {
			'attribute/new': 'newAttribute',
			'attribute/edit/:id': 'editAttribute',
			'attributes/:field/:value': 'filterSingle',
			'attributes': 'noFilter',
			'*path': 'defaultRoute'
		},
		initialize: function(options) {
			this.el = options.el;
			this.attributes = new Categories();
		},
		noFilter: function() {
			this.setParams(null, null, null);
			this.defaultRoute();
		},
		filterSingle: function(field, value) {
			switch (field) {
				case 'name':
					this.setParams(value);
					break;
				default:
					this.setParams(null);
					break;
			}
			this.defaultRoute();
		},
		setParams: function(name) {
			this.deleteParams();
			gridState.filter.name = name;
		},
		deleteParams: function() {
			delete this.attributes.queryParams['name'];
		},
		defaultRoute: function() {
			if (gridState.filter.name)
				this.attributes.queryParams['name'] = gridState.filter.name;
			//spinner.stop();

			this.attributesView = new CategoriesView({
				el: this.el,
				collection: this.attributes,
				name: gridState.filter.name
			});
		},
		newAttribute: function() {
			var attribute = new Attribute();
			$(this.el).html(new AttributeEditView({
				model: attribute
			}).el);

		},
		editAttribute: function(attributeId) {
			var self = this;
			var attribute = new Attribute({
				id: attributeId
			});
			//Fetch the data from the server
			attribute.fetch({
				success: function(model) {
					$(self.el).html(new AttributeEditView({
						model: model
					}).el);
				},
				failure: function(ex) {
					notifications.showError(ex);
					spinner.stop();
				}
			});
		}
	});
});


