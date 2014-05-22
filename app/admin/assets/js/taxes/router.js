// presenters/app/router.js
define(function(require){
	var $ = require('jquery'),
	Tax = require('models/tax'),
	TaxesView = require('views/taxes'),
	Taxes = require('collections/taxes'),
	notifications = require('notifications'),
	spinner = require('spinner'),
	TaxEditView = require('views/tax-edit');
	
	return Backbone.Router.extend({
		taxesView:null,
		routes: {
			'tax/new':'newTax',
			'tax/edit/:id':'editTax',
			'taxes':'defaultRoute',
			'*path':'defaultRoute'
		},
		initialize:function(options){
			this.el=options.el;
			
			this.collection = new Taxes();
		},
		defaultRoute: function () {
			//spinner.stop();
		
			this.taxesView=new TaxesView({
				el:this.el,
				collection:this.collection
			});
		},
		newTax:function(){
			var tax=new Tax();
			$(this.el).html(new TaxEditView({
				model:tax
			}).el);
			
		},
		editTax:function(taxId){
			var self = this;
			var tax=new Tax({
				id:taxId
			});
			//Fetch the data from the server
			tax.fetch({
				success:function(model){
					$(self.el).html(new TaxEditView({
						model:model
					}).el);
				},
				failure:function(ex){
					notifications.showError(ex);
					spinner.stop();
				}
			});
		}
	});
});


