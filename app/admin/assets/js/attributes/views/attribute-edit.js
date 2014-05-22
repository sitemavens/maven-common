// donations/app/views/donation_edit.js
define(['jquery','localization','notifications','spinner','text!templates/attribute-edit.html', 'toggleButtons', 'wysihtml5'],
function($,localization,notifications,spinner, AttributeTemplate){
	
	return  Backbone.View.extend({
		template: _.template(AttributeTemplate),
		//countries:null,
		initialize: function (options) {
			if(!this.model.id){
				spinner.stop();	
			}
			this.render();			
		},
		events:{
			'click #save'	: 'saveAttribute',
			'click #cancel'	: 'cancelAttribute'
		},
		bindings: {
			'#name'			: 'name',
			'#defaultAmount'			: 'defaultAmount',
			'#defaultWholesaleAmount'	: 'defaultWholesaleAmount',
			'#description'	: 'description'
		},
		render: function () {
			//var self=this;
			$(this.el).html(this.template(localization.toJSON()));
		
			this.$('#nav a').click(function (e) {
				e.preventDefault();
				$(this).tab('show');
			});
			
			//this.$('.tooltips').tooltip();
			
			/*Bind model to view*/
			this.stickit();
			/*Bind Validation*/
			Backbone.Validation.bind(this,{
				//Important! this allow models to be updated with invalid values.
				//This way the validation behave correctly when the form fields 
				//are invalid
				forceUpdate:true
			});
	
			//apply wysishtml5
			this.$('.wysihtml5').wysihtml5({
				'html':true,
				'stylesheets':false
			});
			
			return this;
		},
		saveAttribute:function(e){
			e.preventDefault();
			//this.model.validate();
			
			// Stickit doesn't work with wisyhtml5, we have to do it manually
			var content = this.$('#description').val();
			this.model.set('description', content);
			
			if(this.model.isValid(true)){
				this.model.save(null,{
					success:function(){
						Backbone.history.navigate('',{
							trigger:true
						});
					},
					failure:function(ex){
						notifications.showError(localization.get('saveError'));
					}
				});
			}else{
				//TODO: This should show a message in the page
				notifications.showError(localization.get('saveError'));
			}
		},
		cancelAttribute:function(){
			//return to default
			Backbone.history.navigate('',{
				trigger:true
			});		
		}
	});

});










