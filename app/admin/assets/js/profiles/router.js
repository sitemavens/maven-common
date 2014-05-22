// presenters/app/router.js
define(function(require) {
	var $ = require('jquery'),
		Profile = require('models/profile'),
		ProfilesView = require('views/profiles'),
		notifications = require('notifications'),
		spinner = require('spinner'),
		Profiles = require('collections/profiles'),
		ProfileEditView = require('views/profile-edit');

	//this keep the last state
	var gridState = {
		filter: {
			email: null,
			firstName: null,
			lastName: null
		}
	};

	return Backbone.Router.extend({
		attendeesView: null,
		routes: {
			'profile/new': 'newObject',
			'profile/edit/:id': 'editObject',
			'profiles/:field/:value': 'filterSingle',
			'profiles/:field1/:value1/:field2/:value2': 'filterDouble',
			'profiles/:field1/:value1/:field2/:value2/:field3/:value3': 'filterTriple',
			'profiles': 'noFilter',
			'*path': 'defaultRoute'
		},
		initialize: function(options) {
			this.el = options.el;

			this.profiles = new Profiles();
			//this.profiles.setSorting('email');
			//this.profiles.reset(CachedProfiles);
		},
		noFilter:function(){
			this.setParams(null,null,null);
			this.defaultRoute();
		},
		filterSingle: function(field, value) {
			switch (field) {
				case 'email':
					this.setParams(value, null, null);
					break;
				case 'fname':
					this.setParams(null, value, null);
					break;
				case 'lname':
					this.setParams(null, null, value);
					break;
				default:
					this.setParams(null, null, null);
					break;
			}
			this.defaultRoute();
		},
		filterDouble: function(field1, value1, field2, value2) {
			var email, fname, lname;
			switch (field1) {
				case 'email':
					email = value1;
					break;
				case 'fname':
					fname = value1;
					break;
				case 'lname':
					lname = value1;
					break;
			}
			switch (field2) {
				case 'email':
					email = value2;
					break;
				case 'fname':
					fname = value2;
					break;
				case 'lname':
					lname = value2;
					break;
			}

			this.setParams(email, fname, lname);
			this.defaultRoute();
		},
		filterTriple: function(field1, value1, field2, value2, field3, value3) {
			var email, fname, lname;
			switch (field1) {
				case 'email':
					email = value1;
					break;
				case 'fname':
					fname = value1;
					break;
				case 'lname':
					lname = value1;
					break;
			}
			switch (field2) {
				case 'email':
					email = value2;
					break;
				case 'fname':
					fname = value2;
					break;
				case 'lname':
					lname = value2;
					break;
			}
			switch (field3) {
				case 'email':
					email = value3;
					break;
				case 'fname':
					fname = value3;
					break;
				case 'lname':
					lname = value3;
					break;
			}

			this.setParams(email, fname, lname);
			this.defaultRoute();
		},		
		setParams: function(email, firstName, lastName) {
			this.deleteParams();
			gridState.filter.email = email;
			gridState.filter.firstName = firstName;
			gridState.filter.lastName = lastName;
		},
		deleteParams: function() {
			delete this.profiles.queryParams['email'];
			delete this.profiles.queryParams['firstName'];
			delete this.profiles.queryParams['lastName'];
		},
		defaultRoute: function() {
			//spinner.stop();
			if (gridState.filter.email)
				this.profiles.queryParams['email'] = gridState.filter.email;
			if (gridState.filter.firstName)
				this.profiles.queryParams['firstName'] = gridState.filter.firstName;
			if (gridState.filter.lastName)
				this.profiles.queryParams['lastName'] = gridState.filter.lastName;

			this.profilesView = new ProfilesView({
				el: this.el,
				profiles: this.profiles,
				email:gridState.filter.email,
				firstName:gridState.filter.firstName,
				lastName:gridState.filter.lastName
			});

			//this.profilesView.render();
		},
		newObject: function() {
			var profile = new Profile();

			this.profiles.add(profile);

			$(this.el).html(new ProfileEditView({
				model: profile
			}).el);

		},
		editObject: function(id) {
			var self = this;

			var profile = this.profiles.get(id);
			if (profile) {
				$(self.el).html(new ProfileEditView({
					model: profile
				}).el);
			} else {
				//There is no profile, its a direct access to edit page
				//get the profile from the server

				profile = new Profile({
					id: id
				});
				//Fetch the data from the server
				profile.fetch({
					success: function(model) {
						$(self.el).html(new ProfileEditView({
							model: model
						}).el);
					},
					failure: function(ex) {
						notifications.showError(ex);
						spinner.stop();
					}
				});
			}
		}
	});
});


