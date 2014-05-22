define(['models/order-stat','localization', 'collections/orders'],function( OrderStat, localization, Orders ){
	
	var OrderStats = Backbone.Collection.extend({
		model: OrderStat,
		initialize: function(models, options) {
			this.collection = options.collection;
			
			this.collection.on('all', this.recompute, this);
			this.recompute();
		},
		recompute: function() {
			//var self=this;
			var sum=this.collection.reduce(function(acum, obj){
				if(obj.get('statusId')===CompletedStatusId){
					return acum + parseFloat(obj.get('total'))
				}else{
					return acum + 0;
				}
			},0);
			var count=this.collection.length;
			var completedCount=this.collection.filter(function(order){
				return order.get('statusId')===CompletedStatusId;
			}).length;
			this.reset(_.map(['count','countCompleted','sum', 'avg'], function(option) {
				switch(option){
					case 'count':
						return {
							name:localization.get('orders'),
							value:count
						};
						break;
					case 'countCompleted':
						return {
							name:localization.get('completedOrders'),
							value:completedCount
						};
						break;
					case 'sum':
						return {
							name:localization.get('sales'), 
							value:'$&nbsp;'+sum.toFixed(2)
						}
						break;
					case 'avg':
						if(sum>0 || completedCount>0){
							return {
								name:localization.get('averageSale'),
								value:'$&nbsp;' +  (sum / completedCount).toFixed(2)
							};
						}else{
							return {
								name:localization.get('averageSale'),
								value:'$&nbsp;' +  (0).toFixed(2)
							};
						}
						break;	
				}
			}, this));
		}
	});
	
	return OrderStats;
	
});


