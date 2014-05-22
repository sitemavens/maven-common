// donations/app/router.js
define(function(require){
	var $ = require('jquery'),
	Role = require('models/role'),
	RolesView = require('views/roles'),
	notifications = require('notifications'),
	spinner = require('spinner'),
	Roles = require('collections/roles'),
	RoleEditView = require('views/role-edit');
	
	return Backbone.Router.extend({
		rolesView:null,
		roles:null,
		routes: {
			'role/new':'newRole',
			'role/edit/:id':'editRole',
			'roles':'defaultRoute',
			'*path':'defaultRoute'
		},
		initialize:function(options){
			this.el=options.el;
			
			this.roles = new Roles();
			this.roles.reset(CachedRoles);
			
		},
		defaultRoute: function () {
			//spinner.stop();
		
			this.rolesView=new RolesView({
				el:this.el,
				roles:this.roles
			});
			
			this.rolesView.render();
			
		},
		newRole:function(){
	
			var role=new Role();
			this.roles.add(role);
			
			$(this.el).html(new RoleEditView({
				model:role
			}).el);
			
		},
		editRole:function(roleId){
	
			var self = this;
			
			var role = this.roles.get(roleId);
			
			$(self.el).html(new RoleEditView({
				model:role
			}).el);
 
		}
	});
});


