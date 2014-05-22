define(['jquery','collections/Options','views/tabs','spinner' ]
	,function( $, Options,TabsView, spinner){
		
		var MainView = Backbone.View.extend({

			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			el: '#mainContainer',
			settings:null,
			translation: null,
			// Constructor
			initialize: function() {
			
				this.settings = new Options();
				this.settings.on('reset',this.render,this);

				this.settings.reset(MavenSettings);


			//Setting the view's model property.  This assumes you have created a model class and stored it in the Model variable
			//this.model = new User();
				
			//console.log(this.model.get('example'));
			
			//Event handler that calls the initHandler method when the init Model Event is triggered
			//this.model.on("test", this.test);
			//this.render();

			},
			
			
			render: function(){
				
				var tabs = new TabsView({
					settings: this.settings,
					translation: this.translation
				});

				tabs.setElement(this.$el).render();
			  
				spinner.stop();
				
				return this;
			}

		});
		return MainView;
	});
