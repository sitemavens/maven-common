// donations/app/views/donation.js
define(['jquery','localization','notifications','text!templates/attribute-row.html'],
	function($, localization, notifications ,AttributeRowTlt){


		var AttributeRowView=  Backbone.View.extend({
			tagName: "tr",
			template:  _.template(AttributeRowTlt),
			editRoute:_.template("attribute/edit/{{ id }}"),
			events:{
				'click button[id=edit]'				: 'editAttribute',
				'click a[id=confirmDeleteButton]'	: 'showDelete',
				'click button[id=performDelete]'	: 'deleteAttribute'
			},
			bindings: {
				'#name'		:'name'
				
			},
			initialize: function () {
				_.bindAll(this, 'editAttribute','showDelete','deleteAttribute');
				this.model.bind('change',this.render);
			},
			render: function () {
			
				$(this.el).append(this.template(localization.toJSON())) ;
			
				this.stickit();
			
				return $(this.el);
			},
			showDelete:function(){
				this.$('#confirmDeleteModal').modal();
			},
			destroy_view: function() {
				//animate the destroy
				$(this.el).toggleClass('error'); 
				var self=this;
				$(this.el).fadeOut('slow',function(){
					//COMPLETELY UNBIND THE VIEW
					self.undelegateEvents();

					self.$el.removeData().unbind(); 

					//Remove view from DOM
					self.remove();  
					Backbone.View.prototype.remove.call(self);
				});
			

			},
			editAttribute:function(){
				Backbone.history.navigate(this.editRoute(this.model.toJSON()),{
					trigger:true
				});
			},
			deleteAttribute:function(){
				var self=this;
			
				this.model.destroy({
					success: function () {
						//Show notification
						notifications.showDelete(localization.get('AttributeDeleted'));
						self.destroy_view();					
					}
				});
			}
		});
	
		return AttributeRowView;
	});




