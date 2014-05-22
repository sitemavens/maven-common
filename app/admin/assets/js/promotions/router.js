// presenters/app/router.js
define(function(require){
	var $ = require('jquery'),
	Promotion = require('models/promotion'),
	MultiPromotion = require('models/multi-promotion'),
	PromotionsView = require('views/promotions'),
	notifications = require('notifications'),
	Promotions=require('collections/promotions'),
	spinner = require('spinner'),
	PromotionEditView = require('views/promotion-edit'),
	MultiPromotionEditView = require('views/multi-promotion-edit');
	
	return Backbone.Router.extend({
		promotionsView:null,
		routes: {
			'promotion/new':'newPromotion',
			'promotion/multi':'newMultiPromotion',
			'promotion/edit/:id':'editPromotion',
			'promotions':'defaultRoute',
			'*path':'defaultRoute'
		},
		initialize:function(options){
			this.el=options.el;
			this.collection=new Promotions();
		},
		defaultRoute: function () {
			//spinner.stop();
		
			this.promotionsView=new PromotionsView({
				el:this.el,
				collection:this.collection
			});
		},
		newPromotion:function(){
			var promotion=new Promotion();
			$(this.el).html(new PromotionEditView({
				model:promotion
			}).el);
			
		},
		newMultiPromotion:function(){
			var multiPromotion=new MultiPromotion();
			$(this.el).html(new MultiPromotionEditView({
				model:multiPromotion
			}).el);
			
		},
		editPromotion:function(promotionId){
			var self = this;
			var promotion=new Promotion({
				id:promotionId
			});
			//Fetch the data from the server
			promotion.fetch({
				success:function(model){
					$(self.el).html(new PromotionEditView({
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


