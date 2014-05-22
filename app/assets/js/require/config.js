
requirejs.config({
	baseUrl: Maven.requireBaseUrl,
	paths: {
		text: Maven.requireTextPluginPath,
		stickit: Maven.requireStickItPluginPath,
		i18n: Maven.requireil8nPluginPath,
		validation: Maven.requireBackboneValidationPluginPath,
		backgridCore: Maven.requireBackgridPluginPath,
		backgridPaginator: Maven.requireBackgridPaginatorPluginPath,
		backgridFilter: Maven.requireBackgridFilterPluginPath,
		lunr: Maven.requireBackgridFilterLunrPluginPath,
		pageableCore: Maven.requireBackbonePageablePluginPath,
		dataTables: Maven.requireDataTablesPluginPath,
		toggleButtons: Maven.requireToggleButtonsPluginPath,
		dateJS: Maven.requireDateJSPluginPath,
		datePicker: Maven.requireDatePickerPluginPath,
		timePicker: Maven.requireTimePickerPluginPath,
		dateRangePicker: Maven.requireDateRangePickerPluginPath,
		gritter: Maven.requireGritterPluginPath,
		//tinyMCE: Maven.requireTinyMce,
		wysi: Maven.requireWysiPluginPath,
		wysihtml5: Maven.requireWysihtml5PluginPath,
		jqueryForm: Maven.requireForm,
		tagsInput: Maven.requireTagsInput,
		domReady: Maven.requireDomReady,
		clockface: Maven.requireClockfacePluginPath,
		select2: Maven.requireSelect2PluginPath,
		googleMaps: Maven.requireGoogleMaps

	},
	shim: {
		dateRangePicker: {
			deps: ['dateJS']
		},
		datePicker: {
			deps: ['dateJS']
		},
		wysihtml5: {
			deps: ['wysi']
		},
		backgridCore: {
			exports: 'Backgrid'
		},
		backgridPaginator: {
			deps: ['backgridCore']
		},
		backgridFilter: {
			deps: ['backgridCore', 'lunr']
		}
	}
});

define('jquery', [], function() {
	return jQuery;
})

define('backbone', [], function() {
	return Backbone;
})

define('underscore', [], function() {
	return _;
})

define('pageable', ['pageableCore'], function() {
	Backbone.MavenCollection = Backbone.PageableCollection.extend({
		state: {
			pageSize: Maven.gridRows
		}
	});

//return Backbone.MavenCollection;
});

define('wpuploader', ['jquery'], function($) {

	var wpUploader = ({
		show: function(selectedImageHandler) {
			var that = this;

			// If the media frame already exists, reopen it.
			if (this.file_frame) {

				// We need to remove the select event so we can change the handler. 
				// This is useful when you call the uploader from different inputs.
				this.file_frame.off('select');

				// When an image is selected, run a callback.
				this.file_frame.on('select', function() {

					// We set multiple to false so only get one image from the uploader
					attachment = that.file_frame.state().get('selection').first().toJSON();

					if (selectedImageHandler)
						selectedImageHandler(attachment);


				});

				this.file_frame.open();



				return false;
			}

			// Create the media frame.
			this.file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery(this).data('uploader_title'),
				button: {
					text: jQuery(this).data('uploader_button_text')
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected, run a callback.
			this.file_frame.on('select', function() {

				// We set multiple to false so only get one image from the uploader
				attachment = that.file_frame.state().get('selection').first().toJSON();

				if (selectedImageHandler)
					selectedImageHandler(attachment);


			});

			// Finally, open the modal
			this.file_frame.open();

			return false;
		}

	});

	return wpUploader;

});

define('spinner', ['jquery'], function($) {
	var Spinner = ({
		initialize: function() {
			$(document).ajaxStart(function() {
				$('#loader').fadeIn();
			});
			$(document).ajaxComplete(function() {
				$('#loader').fadeOut();
			});
		},
		start: function() {
			$('#loader').fadeIn();
		},
		stop: function() {
			$('#loader').fadeOut();
		}
	});

	Spinner.initialize();

	return Spinner;
})

define('notifications', ['jquery'], function($) {
	var Notifications = {
		messages: [],
		initialize: function() {
			//this.messages=[];
		},
		showSuccess: function(message) {

			if (!message)
				message = 'Your data was sucessfuly saved';

			var unique_id = $.gritter.add({
				// (string | mandatory) the heading of the notification
				title: 'Data updated!',
				// (string | mandatory) the text inside the notification
				text: message,
				// (string | optional) the image to display on the left
				image: Maven.adminImagesPath + 'ok.png',
				// (bool | optional) if you want it to fade out on its own or just sit there
				sticky: false,
				// (int | optional) the time you want it to be alive for before fading out
				time: '4000',
				// (string | optional) the class name you want to apply to that specific message
				class_name: 'gritter-success'

			});

			//this.messages.push(unique_id);

		},
		showDelete: function(message) {

			if (!message)
				message = 'Your data was sucessfuly deleted';

			var unique_id = $.gritter.add({
				// (string | mandatory) the heading of the notification
				title: 'Data deleted!',
				// (string | mandatory) the text inside the notification
				text: message,
				// (string | optional) the image to display on the left
				image: Maven.adminImagesPath + 'ok.png',
				// (bool | optional) if you want it to fade out on its own or just sit there
				sticky: false,
				// (int | optional) the time you want it to be alive for before fading out
				time: '4000',
				// (string | optional) the class name you want to apply to that specific message
				class_name: 'gritter-success'

			});

			//this.messages.push(unique_id);

		},
		showError: function(message) {

			if (!message)
				message = 'Your data was not saved.';

			var unique_id = $.gritter.add({
				// (string | mandatory) the heading of the notification
				title: 'Error!',
				// (string | mandatory) the text inside the notification
				text: message,
				// (string | optional) the image to display on the left
				image: Maven.adminImagesPath + 'error.png',
				// (bool | optional) if you want it to fade out on its own or just sit there
				sticky: true,
				// (int | optional) the time you want it to be alive for before fading out
				time: '',
				// (string | optional) the class name you want to apply to that specific message
				class_name: 'gritter-error'

			});

			//only put sticky messages in the array
			this.messages.push(unique_id);

		},
		removeAll: function(params) {
			var after_close = ($.isFunction(params.after_close)) ? params.after_close : function() {
			};

			//delete all messages in the array
			for (i in this.messages) {
				$.gritter.remove(this.messages[i]);
			}
			;
			//clear the array
			this.messages = [];

			after_close();

		}
	};

	return Notifications;

})

define('localization', [], function() {

	var Localization = Backbone.Model.extend({
		defaults: {
		},
		// Constructor
		initialize: function() {
			this.clear().set(Maven.translations)

		},
		// Any time a Model attribute is set, this method is called
		validate: function(attrs) {

		}

	});

	return new Localization();
});


define('backgrid', ['jquery', 'localization', 'notifications', 'backgridCore'], function($, localization, notifications) {
	Backgrid.ActionCell = Backgrid.Cell.extend({
		template: _.template(
			"<button class='btn btn-primary btn-mini' id='edit'><i class='icon-edit'></i>&nbsp;{{ buttonEdit }}</button>&nbsp;&nbsp;" +
			"<button id='confirmDeleteButton' class='btn btn-mini' role='button'><i class='icon-trash'></i>&nbsp;{{ buttonDelete }}</button>&nbsp;&nbsp;" +
			"<a class='btn btn-primary btn-mini' id='print' target='_blank'><i class='icon-print'></i>&nbsp;{{ buttonPrint }}</a>" +
			"<div aria-hidden='true' aria-labelledby='myModalLabel3' role='dialog' tabindex='-1' class='modal fade' id='confirmDeleteModal' style='display: none;'>" +
			"<div class='modal-header'><button aria-hidden='true' data-dismiss='modal' class='close' type='button'>×</button><h3 id='myModalLabel3'>{{ deleteConfirmationMessage }}</h3></div>" +
			"<div class='modal-body'>" +
			"<div class='alert alert-block alert-error fade in'>" +
			"<h4 class='alert-heading'>{{deleteWarning}}</h4><p>{{deleteWarningMessage}}</p></div></div>" +
			"<div class='modal-footer'>" +
			"<button aria-hidden='true' data-dismiss='modal' class='btn'>{{ buttonCloseModal }}</button>" +
			"<button class='btn btn-danger' id='performDelete' data-dismiss='modal'>{{ buttonConfirmDelete }}</button>" +
			"</div></div>"
			),
		events: {
			"click #edit": "editRow",
			"click #confirmDeleteButton": "showDelete",
			'click #performDelete': 'del'
		},
		printObject: null,
		bindings: {
			'#print': {
				attributes: [
					{
						name: 'href',
						observe: 'id',
						onGet: function(value, options) {
							if (value && this.printObject)
								return Maven.printUrl + this.printObject + '/' + value;
							return '';
						}
					}]
			}
		},
		editRoute: _.template("edit/{{ id }}"),
		editRow: function() {
			Backbone.history.navigate(this.editRoute(this.model.toJSON()), {
				trigger: true
			});
		},
		render: function() {
			this.$el.html(this.template(localization.toJSON()));
			this.delegateEvents();

			this.stickit();

			if (this.printObject == null)
				this.$('#print').remove();

			return this;
		},
		showDelete: function() {
			this.$('#confirmDeleteModal').modal();
		},
		del: function() {
			var self = this;

			this.model.destroy({
				success: function() {
					//Show notification
					notifications.showDelete(localization.get('deleted'));
					self.destroy_view();
				}
			});
		},
		destroy_view: function() {
			//animate the destroy
			$(this.el).toggleClass('error');
			var self = this;
			$(this.el).fadeOut('slow', function() {
				//COMPLETELY UNBIND THE VIEW
				self.undelegateEvents();

				self.$el.removeData().unbind();

				//Remove view from DOM
				//self.remove();  
				Backbone.View.prototype.remove.call(self);
			});


		}
	});

	Backgrid.DeleteCell = Backgrid.Cell.extend({
		template: _.template('<button>Delete</button>'),
		events: {
			"click": "deleteRow"
		},
		deleteRow: function(e) {
			e.preventDefault();
			this.model.collection.remove(this.model);
		},
		render: function() {
			this.$el.html(this.template());
			this.delegateEvents();
			return this;
		}
	});

	Backgrid.MavenDateTimeCell = Backgrid.Cell.extend({
		//template: _.template('<span></span>'),
		events: {
		},
		render: function() {
			this.$el.html(this.model.get(this.column.get('name')));
			this.delegateEvents();
			return this;
		}
	});

	return Backgrid;
});
//Load common code that includes config, then load the app
//logic for this page. Do the require calls here instead of
//a separate file so after a build there are only 2 HTTP
//requests instead of three.

require(['jquery', 'notifications', 'spinner', 'stickit', 'validation', 'gritter'], function($, notifications, spinner) {

	Backbone.Collection.prototype.update = function( ) {

		Backbone.sync('updateCollection', this);
	}

	Backbone.Model.prototype.clone = function(options) {

		Backbone.sync('clone', this, options);
	}

	Backbone.Model.prototype.export = function(options) {

		Backbone.sync('export', this, options);
	}

	Backbone.sync = function(method, model, options) {

		switch (method) {
			case 'read':
				mvnAjax = new MavenAjax();
				//TODO: this shoud be read from the options
				mvnAjax.cache = false;

				mvnAjax.setVar('event', 'read');
				//add options data to fetch
				if (options.data)
					mvnAjax.setVar('data', options.data);
				//If is set model.id, it load a single element
				if (model.id)
					mvnAjax.setVar('id', model.id);

				mvnAjax.onCompletion = function(data) {

					spinner.stop();
					if (typeof (data) == 'object')
					{
						if (!data.success)
						{
							options.error(data.data);
							notifications.showError(data.data);
						} else {
							options.success(data.data);
						}
					}
				};
				mvnAjax.execute(model.action);
				break;
			case 'create':
				mvnAjax = new MavenAjax();
				mvnAjax.setVar('event', 'create');
				mvnAjax.setVar('data', model.toJSON());
				mvnAjax.onCompletion = function(data) {

					spinner.stop();
					if (typeof (data) == 'object')
					{
						if (!data.success)
						{
							options.error(data.data);
							notifications.showError(data.data);
						}
						else {

							options.success(data.data);
							//In case of success, 
							//remove all previous messages
							notifications.removeAll({
								after_close: function() {
									notifications.showSuccess();
								}
							});
						}
					}
				};
				mvnAjax.execute(model.action);
				break;
			case 'clone':
				mvnAjax = new MavenAjax();
				mvnAjax.setVar('event', 'clone');
				if (model.id) {
					mvnAjax.setVar('id', model.id);
					mvnAjax.onCompletion = function(data) {
						if (typeof (data) == 'object')
						{
							if (!data.success)
							{
								options.error(data.data);
								notifications.showError(data.data);
							} else {
								options.success(data.data);
								//remove all previous messages
								notifications.removeAll({
									after_close: function() {
										notifications.showSuccess();
									}
								});

							}
						}
					};
					mvnAjax.execute(model.action);
				}
				else {
					//Error
					options.error('missing id');
				}
				break;
			case 'export':
				var params = {};
				params.calledFrom = 'admin';
				params.component = Maven.component;
				params.action = Maven.handler;
				params.mvnAjaxAction = model.action;
				params.event = 'create';
				params.data = model.attributes;
				var form = $('<form method="POST" action="' + Maven.ajaxUrl + '">');
				$.each(params, function(k, v) {
					if (typeof v === 'object') {
						$.each(v, function(key, val) {
							form.append($('<input type="hidden" name="' + k + '[' + key + ']" value="' + val + '">'));
						})

					} else {
						form.append($('<input type="hidden" name="' + k + '" value="' + v + '">'));
					}
				});
				$('body').append(form);
				form.submit();
				break;
			case 'updateCollection':
				mvnAjax = new MavenAjax();
				mvnAjax.setVar('event', 'updateCollection');
				mvnAjax.setVar('data', model.toJSON());
				mvnAjax.onCompletion = function(data) {

					spinner.stop();
					if (typeof (data) == 'object')
					{
						if (!data.success)
						{
							options.error(data.data);
							notifications.showError(data.data);

						} else {
							options.success(data.data);
							//In case of success, 
							//remove all previous messages
							notifications.removeAll({
								after_close: function() {
									notifications.showSuccess();
								}
							});
						}
					}
				};
				mvnAjax.execute(model.action);
				break;
			case 'update':
				mvnAjax = new MavenAjax();
				mvnAjax.setVar('event', 'update');
				mvnAjax.setVar('data', model.toJSON());
				mvnAjax.onCompletion = function(data) {

					spinner.stop();
					if (typeof (data) == 'object')
					{
						if (!data.success)
						{
							options.error(data.data);
							notifications.showError(data.data);

						} else {
							options.success(data.data);
							//In case of success, 
							//remove all previous messages
							notifications.removeAll({
								after_close: function() {
									notifications.showSuccess();
								}
							});
						}
					}
				};
				mvnAjax.execute(model.action);
				break;
			case 'delete':
				mvnAjax = new MavenAjax();
				mvnAjax.setVar('event', 'delete');
				if (model.id) {
					mvnAjax.setVar('id', model.id);
					mvnAjax.onCompletion = function(data) {
						if (typeof (data) == 'object')
						{
							if (!data.success)
							{
								options.error(data.data);
								notifications.showError(data.data);
							} else {
								options.success(data.data);

							}
						}
					};
					mvnAjax.execute(model.action);
				}
				else {
					//Error
					options.error('missing id');
				}
				break;
		}
	};




	//get the error messages from maven
	_.extend(Backbone.Validation.messages, Maven.errorMessages);
	//TODO: Donde ponemos esto!?

	_.templateSettings = {
		interpolate: /\{\{(.+?)\}\}/g
	};

	_.extend(Backbone.Validation.callbacks, {
		valid: function(view, attr, selector) {
			var control = view.$('[' + selector + '=' + attr + ']');
			var group = control.parents(".control-group");
			group.removeClass("error");

			if (control.data("error-style") === "tooltip") {
				// CAUTION: calling tooltip("hide") on an uninitialized tooltip
				// causes bootstraps tooltips to crash somehow...
				if (control.data("tooltip"))
					control.tooltip("hide");
			}
			else if (control.data("error-style") === "inline") {
				group.find(".help-inline.error-message").remove();
			}
			else {
				group.find(".help-block.error-message").remove();
			}
		},
		invalid: function(view, attr, error, selector) {
			var control = view.$('[' + selector + '=' + attr + ']');
			var group = control.parents(".control-group");
			group.addClass("error");

			if (control.data("error-style") === "tooltip") {
				var position = control.data("tooltip-position") || "right";
				control.tooltip({
					placement: position,
					trigger: "manual",
					title: error
				});
				control.tooltip("show");
			}
			else if (control.data("error-style") === "inline") {
				if (group.find(".help-inline").length === 0) {
					group.find(".controls").append("<span class=\"help-inline error-message\"></span>");
				}
				var target = group.find(".help-inline");
				target.text(error);
			}
			else {
				if (group.find(".help-block").length === 0) {
					group.find(".controls").append("<p class=\"help-block error-message\"></p>");
				}
				var target = group.find(".help-block");
				target.text(error);
			}
		}
	});

	// We need to convert the prop from string to boolean
	Maven.recurringEnabled = Maven.recurringEnabled === "false" ? false : true;

	//We need to parse the gridRow value to Int
	Maven.gridRows = parseInt(Maven.gridRows, 10);
	if (isNaN(Maven.gridRows))
		Maven.gridRows = 10; //if error on parse, set default


	require([Maven.main], function() {
	});
});

