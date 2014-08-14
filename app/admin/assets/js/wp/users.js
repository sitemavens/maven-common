jQuery(document).ready(function($) {

	$('.maven-user').click(function() {



		var link;
		link = $(this);

		// Add the laoding image
		var img = link.children('img');
		img.attr('src', Maven.loadingImagePath);

		var email;
		email = $(this).attr('href');
		email = email.replace('#', '');

		$.ajax({
			url: '/wp-json/maven/v2/profile/convert-from-user',
			type: "POST",
			headers: {
				"Accept": "application/json; charset=utf-8",
				'X-WP-Nonce':Users.nonce
			},
//			contentType: "application/json; charset=utf-8",
			data:{data:{email: email}},
			dataType: "json",
			success:function( result ){

			if (result.successful)
			{
				var img = link.children('img');
				img.attr('src', Maven.imagesPathUrl+"/logo-on.png");
				link.unbind('click');

			} else {
				alert(result.description);
			}
			}
		});
 

	});



});


