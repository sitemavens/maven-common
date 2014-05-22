define(['jquery','spinner', 'views/pages', 'models/setting']
	,function( $, spinner, PagesView, Setting){
		
		var MainView = Backbone.View.extend({

			// Represents the actual DOM element that corresponds to your View (There is a one to one relationship between View Objects and DOM elements)
			el: '#mainContainer',
			settings:null,
			translation: null,
			collection:null,
			// Constructor
			initialize: function() {
			
				this.render();
			},
			
			
			render: function(){
				
				var pages = new Setting( HTTPPages );
				pages.set('id',1);
				var pagesView = new PagesView({
					el:this.$el,
					 model:pages					 
				});

				spinner.stop();
				
				return this;
			}

		});
		return MainView;
	});
