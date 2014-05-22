function MavenAjax (){
	
	var $ = jQuery.noConflict();
	this.vars = {};
	//this.vars = new Array();
	this.URLString = "";
	this.onCompletion = null;
	this.onError = null;
	this.async = true;
	this.calledFrom = 'admin';
	this.jsonObj = null;
	this.cache = true;
	this.dataType = '';
	
	_this = null;

	this.setAsync = function(value){
		if(typeof(value) == 'boolean')
			this.async = value;
	};
	this.setVar = function(key,value){
		//this.vars[key] = encodeURIComponent(value);
		this.vars[key] = value;
	};
	this.setCalledFrom = function(value){
		this.calledFrom = value;
	};
	
	this.getVarsSerialized = function(){
		return $.param(this.vars);
	}
	
	//	this.encVar = function(name, value) {
	//	    this.vars[encodeURIComponent(name)] = Array(encodeURIComponent(value), true);
	//	}
	
	this.execute = function(action){
		/*
	    urlstringtemp = new Array();
	    for (key in this.vars) {
		urlstringtemp[urlstringtemp.length] = key + "=" + this.vars[key];
	    }
	    
	    this.URLString += urlstringtemp.join("&");
	    */
	   
		this.setVar('action', Maven.handler?Maven.handler:action);
		this.setVar('calledFrom', this.calledFrom);
		this.setVar('mvnAjaxAction',action);
		this.setVar('component', Maven.component);
		_this = this;
		
		$.ajax({
			url: Maven.ajaxUrl,
			cache: this.cache,
			type: 'POST',
			dataType: 'json',
			async: this.async,
			//data:'action='+action+"&"+this.URLString,
			data: this.getVarsSerialized(),
			success: function(data, status, request){
				
				//If the response is an attachment, generate a fake form and make a post
				var disp = request.getResponseHeader('Content-Disposition');
				if (disp && disp.search('attachment') != -1) {
					var form = $('<form method="POST" action="' + Maven.ajaxUrl + '">');
					
					$.each(_this.vars, function(k, v) {
						if(typeof v === 'object'){
							$.each(v, function(key,val){
								form.append($('<input type="hidden" name="' + k + '['+ key +']" value="' + val + '">'));
							})
							
						}else{
						
							form.append($('<input type="hidden" name="' + k + '" value="' + v + '">'));
						}	
					});
					$('body').append(form);
					form.submit();
				}
				
				if (_this.onCompletion){
					_this.onCompletion(data);
					return;
				}
				 
				//var mvnAjax = new MavenAjax();
				if(undefined != data && typeof(data) == 'object' && undefined != data.is_error){
		    
					// Check if the user wants to execute another function
					if (this.onCompletion)
						this.onCompletion(data);
					else
						this.showMessage(data);
				}
					
			}

		});
	};
	
	
	this.executeJson = function(action){
		
		this.setVar('action', action);
		this.setVar('calledFrom', this.calledFrom);
		_this = this;
	
		$.ajax({
			url: MavenConstants.ajaxurl,
			type: 'POST',
			dataType: 'json',
			async: this.async,
			//data:'action='+action+"&"+this.URLString,
			data: {
				action: action, 
				obj:this.jsonObj
			},
			beforeSend: function(x) {
				if (x && x.overrideMimeType) {
					x.overrideMimeType("application/j-son;charset=UTF-8");
				}
			},
			success: function(data){

				if (_this.onCompletion){
					_this.onCompletion(data);
					return;
				}
				 
				//var mvnAjax = new MavenAjax();
				if(undefined != data && typeof(data) == 'object' && undefined != data.is_error){
		    
					// Check if the user wants to execute another function
					if (this.onCompletion)
						this.onCompletion(data);
					else
						this.showMessage(data);
				}
					
			}

		});
	};
	
	//TODO: This messages could be customized
	this.showMessage = function(result){
		if(undefined != result && result.is_error == true)
		{
			$("#errorMessageContainer").hide();
			$("#messageContainer").hide();
			if(result.message)
			{
				$("#errorMessage").text(result.message);
				$("#errorMessageContainer").show();
			} 
		}else if(undefined != result && result.is_error == false)
		{
			$("#errorMessageContainer").hide();
			$("#messageContainer").hide();
			if(result.message)
			{
				$("#succesMessage").text(result.message);
				$("#messageContainer").show();
			}
		}
	};

	this.showError = function(message){
		$("#messageContainer").hide();
		$("#errorMessage").text(message);
		$("#errorMessageContainer").show();
	};
}
	
//    mvnAjax = new MavenAjax();
//    mvnAjax.setVar("hola","mensajeeee<h1>Titulo</h1>");
//    mvnAjax.execute();
    
  
