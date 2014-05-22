define(function(require) {
	var Router = require('router');

	return function(root_el) {
		new Router({
			el:root_el
		});
		Backbone.history.start();
	};
});


