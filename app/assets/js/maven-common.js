var MavenJS = MavenJS || {};

MavenJS.Message = function() {
	this.successful = false;
	this.error = false;
	this.warning = false;
	this.none = true;
	this.description = "";
};

MavenJS.Ajax = function() {

	var $ = jQuery.noConflict();

	this.vars = {};
	this.URLString = "";
	this.onCompletion = null;
	this.onError = null;
	this.async = true;
	this.calledFrom = 'admin';
	this.jsonObj = null;
	this.cache = true;

	_this = null;

	this.setAsync = function(value) {
		if (typeof (value) == 'boolean')
			this.async = value;
	};
	this.setVar = function(key, value) {
		//this.vars[key] = encodeURIComponent(value);
		this.vars[key] = value;
	};
	this.setCalledFrom = function(value) {
		this.calledFrom = value;
	};

	this.getVarsSerialized = function() {
		return $.param(this.vars);
	};


	this.execute = function(action) {

		this.setVar('action', action);
		this.setVar('calledFrom', this.calledFrom);
		_this = this;

		$.ajax({
			url: Maven.ajaxUrl,
			cache: this.cache,
			type: 'POST',
			dataType: 'json',
			async: this.async,
			data: this.getVarsSerialized(),
			success: function(data) {

				if (undefined != data && typeof (data) == 'object') {

					// Check if the user wants to execute another function
					if (_this.onCompletion)
						_this.onCompletion(data);
					else {
						console.log("No onCompletition defined!!!");
						console.log(data);
					}
				}
			},
			error: function(data) {
				if (_this.onError)
					_this.onError(data);
				else {
					console.log("No onError defined!!!");
					console.log(data);
				}
			}

		});
	};
};

MavenJS.Common = {};

MavenJS.Common.PluginKey = function() {
	return 'mavencommon';
};

MavenJS.Common.onSuccess = {};
MavenJS.Common.onError = {};
MavenJS.Common.SaveProfile = function(profile, manageResultFnc) {

	var that = this;
	var result = new MavenJS.Message();
	
	if (!profile.id) {
		result.error = true;
		result.description = "Missing Profile ID";
		this.onError(result);
	}

	profile.step = {};
	profile.step.action = 'saveProfile';


	var ajaxCall = new MavenJS.Ajax();
	ajaxCall.onCompletion = function(result) {
		that.onSuccess(result);
	};
	ajaxCall.onError = function(result) {
		that.onError(result);
	};
	ajaxCall.setVar('mvn', profile);
	ajaxCall.setVar('mavenTransactionKey', Maven.transactionNonce);
	ajaxCall.execute('mavenAjaxCartHandler');

};

MavenJS.Common.GetCartInfo = function(manageResultFnc){
	var that = this;
	var params={};
	params.step = {};
	params.step.action = 'getCartInfo';
	
	var ajaxCall = new MavenJS.Ajax();
	ajaxCall.onCompletion = function(result) {
		that.onSuccess(result);
	};
	ajaxCall.onError = function(result) {
		that.onError(result);
	};
	ajaxCall.setVar('mvn', params);
	ajaxCall.setVar('mavenTransactionKey', Maven.transactionNonce);
	ajaxCall.execute('mavenAjaxCartHandler');
}

 
MavenJS.Country = {};
MavenJS.Country.onSuccess = {};
MavenJS.Country.onError = {};
MavenJS.Country.getStates = function( country ) {
	var that = this;
	var ajaxCall = new MavenJS.Ajax();
	ajaxCall.onCompletion = function(result) {
		that.onSuccess(result);
	};
	ajaxCall.onError = function(result) {
		that.onError(result);
	};
	ajaxCall.setVar('country', country);
	ajaxCall.setVar('method', 'getStates');
	ajaxCall.setVar('mavenTransactionKey', Maven.transactionNonce);
	ajaxCall.execute('mavenAjaxCountry');
	
};
 