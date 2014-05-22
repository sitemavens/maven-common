jQuery(document).ready(function($){
	
	$('.maven-user').click(function(){
		
		
		
		var link; 
		link = $(this);
		
		// Add the laoding image
		var img = link.children('img');
		img.attr('src', Maven.loadingImagePath);
				 
				 
		mvnAjax = new MavenAjax();
		//TODO: this shoud be read from the options
		mvnAjax.cache = false;

		var email; 
		email = $(this).attr('href');
		email = email.replace('#','');
		
		mvnAjax.setVar('email', email );
		mvnAjax.setVar('nonce', Users.nonce );
		mvnAjax.onCompletion = function(result) {
			
			if (result.success)
			{
				 var img = link.children('img');
				 img.attr('src', result.data);
				 link.unbind('click');

			} else {
				alert(result.data);
			}
		};
		mvnAjax.execute(Users.action);


	});
	
	
	
});


