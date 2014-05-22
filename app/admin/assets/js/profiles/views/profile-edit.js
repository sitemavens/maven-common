// donations/app/views/donation_edit.js
define(['jquery', 'localization', 'notifications', 'spinner',
	'text!templates/profile-edit.html', 'models/address', 'collections/addresses', 'views/address-edit',
	'collections/roles', 'views/roles',
	'wpuploader', 'toggleButtons', 'wysihtml5'],
	function($, localization, notifications, spinner,
		ProfileEditTemplate, Address, Addresses, AddressEditView,
		Roles, RoleView,
		wpuploader) {

		return  Backbone.View.extend({
			template: _.template(ProfileEditTemplate),
			//countries:null,
			addresses: null,
			roles: null,
			userRoles: null,
			useOneAddress: false,
			initialize: function(options) {
				_.bindAll(this, 'save', 'cancel', 'showUploader', 'addOne', 'addNew', 'addRole', 'changeRegistered');
				if (!this.model.id) {
					spinner.stop();
				}
				this.addresses = new Addresses();
				this.addresses.on("add", this.addOne);

				this.addresses.reset(this.model.get('addresses'));

				this.roles = new Roles();
				this.roles.on("add", this.addRole);
				this.roles.reset(CachedRoles);

				this.userRoles = new Roles();
				this.userRoles.reset(this.model.get('roles'));

				this.render();
			},
			events: {
				'click #save': 'save',
				'click #cancel': 'cancel',
				'click .upload_image_button': 'showUploader',
				'click #addAddress': 'addNew',
				'change #registered': 'changeRegistered'
			},
			addTypes: function() {
				for (var key in AddressesTypes) {
					this.$('#typeAddressAdd').append('<option value=' + key + '>' + AddressesTypes[key] + '</option>');

				}
			},
			addOne: function(model) {
				var addressEditView = new AddressEditView({
					model: model,
					collection: this.addresses
				});
				this.$('#attendee_address').append(addressEditView.render().el);
			},
			addAll: function() {
				this.addresses.each(this.addOne);
			},
			addRoles: function() {
				this.roles.each(this.addRole);
			},
			addRole: function(model) {
				var roleView = new RoleView({
					model: model,
					collection: this.userRoles
				});
				this.$('#roles_container').append(roleView.render().el);
			},
			addNew: function() {
				//set type
				var addressType = this.$('#typeAddressAdd').val();
				var exist = this.addresses.findWhere({type: addressType});
				if (exist) {
					notifications.showError("There is already a " + AddressesTypes[addressType] + " address.");

				} else {
					var address = new Address();
					address.set('type', addressType);

					this.addresses.add(address);
				}
			},
			changeRegistered: function() {
				if (this.$('#registered').is(':checked')) {
					this.$('#roles_message').hide();
					this.$('#roles_container').show();
				} else {
					this.$('#roles_message').show();
					this.$('#roles_container').hide();
				}
			},
			bindings: {
				'#salutation': {
					observe: 'salutation',
					selectOptions: {
						collection: function() {
							// Prepend null or undefined for an empty select option and value.
							return [null, {
									id: 1,
									data: {
										name: 'Dr.'
									}
								}, {
									id: 2,
									data: {
										name: 'Mr.'
									}
								}, {
									id: 3,
									data: {
										name: 'Mrs.'
									}
								}, {
									id: 4,
									data: {
										name: 'Ms.'
									}
								}];
						},
						labelPath: 'data.name',
						valuePath: 'data.name'
					}
				},
				'#firstName': 'firstName',
				'#lastName': 'lastName',
				'#email': 'email',
				'#phone': 'phone',
				'#company': 'company',
				'#notes': 'notes',
				'#wholesale': 'wholesale',
				'#adminNotes': 'adminNotes',
				'#createdOn': 'createdOn',
				'#lastUpdate':'lastUpdate',
				'#registeredControl': {
					observe: 'userId',
					updateView: false,
					visible: function(value) {
						if (value)
							return !(value > 0);

						return true;
					}
				},
				'#registeredMessage': {
					observe: 'userId',
					updateView: false,
					visible: function(value) {
						if (value)
							return value > 0;

						return false;
					}
				},
				'#username': {
					attributes: [{
							name: 'value',
							observe: 'userName',
							onGet: function(value) {
								if (value)
									return value;

								return this.model.get('email');
							}
						}, {
							name: 'readonly',
							observe: 'userId',
							onGet: function(value) {
								if (value && value > 0)
									return 'readonly';

								return '';
							}
						}
					]
				},
				'#roles_message': {
					observe: 'userId',
					updateView: false,
					visible: function(value) {
						if (value)
							return value == 0;

						return false;
					}
				},
				'#roles_container': {
					observe: 'userId',
					updateView: false,
					visible: function(value) {
						if (value)
							return value > 0;

						return false;
					}
				}
			},
			render: function() {
				//var self=this;
				$(this.el).html(this.template(localization.toJSON()));
				this.addTypes();
				this.addAll();
				this.addRoles();

				this.$('#nav a').click(function(e) {
					e.preventDefault();
					$(this).tab('show');
				});
				this.$('#address_nav a').click(function(e) {
					e.preventDefault();
					$(this).tab('show');
				});

				//Set name of default role
				this.$('#defaultRole').text(DefaultRole);

				/*Bind model to view*/
				this.stickit();
				/*Bind Validation*/
				Backbone.Validation.bind(this, {
					//Important! this allow models to be updated with invalid values.
					//This way the validation behave correctly when the form fields 
					//are invalid
					forceUpdate: true
				});

				/*Important: First bind stickit, then apply toggleButton*/
				this.$('.toggle-button.profile-toggle-button').toggleButtons({
					width: 100,
					label: {
						enabled: localization.get('yes'),
						disabled: localization.get('no')
					}
				});

				if (this.model.get('profileImageUrl'))
					this.replaceImage(this.model.get('profileImageUrl'));
				else
					this.replaceImage(Maven.noPhotoUrl);

				return this;
			},
			replaceImage: function(url) {
				this.$('.preview').attr('src', url);
			},
			showUploader: function() {
				var that = this;

				wpuploader.show(
					function(attachment) {
						that.model.set('profileImage', attachment.id);
						that.replaceImage(attachment.url);
					});

			},
			save: function(e) {
				e.preventDefault();
				//this.model.validate();

				var password = this.$('#password').val();
				var confirm = this.$('#confirm').val();
				var validPass = (password === confirm);
				this.model.set('password', password);
				this.model.set('confirm', confirm);
				this.model.set('username', this.$('#username').val());
				this.model.set('registered', (this.$('#registered').is(':checked')));
				//Set the addresses collection
				this.model.set('addresses', this.addresses.toJSON());

				//Set roles collection
				this.model.set('roles', this.userRoles.toJSON());

				var validProfile = this.model.isValid(true);
				var validAddress = true;
				this.addresses.each(function(model) {
					var validModel = model.isValid(true);
					validAddress = validAddress && validModel;
					return true;
				});

				if (validProfile && validAddress && validPass) {
					this.model.save(null, {
						success: function() {
							Backbone.history.navigate('', {
								trigger: true
							});
						},
						failure: function(ex) {
							notifications.showError(localization.get('saveError'));
						}
					});
				} else {
					//TODO: This should show a message in the page
					if (!validPass) {
						notifications.showError("Passwords doesn't match.");
					} else if (!validAddress) {
						notifications.showError("There was an error in one of the address.");
					}
					else {
						notifications.showError(localization.get('saveError'));
					}

				}
			},
			cancel: function() {
				//return to default
				Backbone.history.navigate('', {
					trigger: true
				});
			}
		});

	});










