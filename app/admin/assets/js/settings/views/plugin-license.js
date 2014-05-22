define(['text!templates/plugin-license.html', 'localization', 'jquery', 'models/license']
		, function(PluginLicenseTlt, localization, $, License) {

	var PluginLicenseView = Backbone.View.extend({
		
		template: _.template(PluginLicenseTlt),
		/* Bind controls to model attributes */
		bindings: {
			'#value': 'value',
			'#name': {
				observe: 'name',
				updateMethod: 'html'
			}
		},
		events: {
			"click #activate-license": 'activateLicense',
			"click #deactivate-license": 'deactivateLicense'			
		},
		activateLicense: function() {
			var that = this;
			
			lic = new License();
			lic.set('id', this.model.get('id'));
			lic.set('value', this.model.get('value'));
			
			lic.save(null, {
				success: function() {
					that.validLicenseBox( true );
				}
			});

		},
		deactivateLicense: function() {
			var that = this;
			
			lic = new License();
			lic.set('id', this.model.get('id'));
			lic.set('value', '');
			
			lic.save(null, {
				success: function() {
					that.validLicenseBox( false );
				}
			});

		},
		
		validLicenseBox: function( valid ) {
		
			if ( valid ) {

				this.$('#license-box').removeClass('alert-warning');
				this.$('#license-box').addClass('alert-success');
				this.$('#activate-license').hide();
				this.$('#deactivate-license').show();
				this.$('#value').attr('readonly', true);
				this.$('#license-box').show('fast');
			}
			else {
				this.$('#license-box').removeClass('alert-success');
				this.$('#license-box').addClass('alert-warning');
				this.$('#activate-license').show();
				this.$('#deactivate-license').hide();
				this.$('#value').attr('readonly', false);
				this.$('#license-box').show('fast');
			}
		},
		render: function() {


			this.$el.html(this.template(localization.toJSON()));

			// We need to set the plugin image
			this.$el.find('#avatar').attr('src', this.model.get('img'));


			this.stickit();
			
			this.validLicenseBox( this.model.get('value')!=="" );

			return this;

		}


	});
	return PluginLicenseView;
});
