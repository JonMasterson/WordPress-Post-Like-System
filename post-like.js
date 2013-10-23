jQuery(document).ready(function() {

	jQuery(".post-like a").click(function(){
	
		heart = jQuery(this);
		post_id = heart.data("post_id");
		
		jQuery.ajax({
			type: "post",
			url: ajax_var.url,
			data: "action=post-like&nonce="+ajax_var.nonce+"&post_like=&post_id="+post_id,
			success: function(count){
				if( count.indexOf( "already" ) !== -1 )
				{
					var lecount = count.replace("already","");
					if (lecount == 0)
					{
						var lecount = "Like";
					}
					heart.children(".like").removeClass("pastliked").addClass("disliked").html("<i class='fa fa-heart'></i>");
					heart.children(".unliker").text("");
					heart.children(".count").removeClass("liked").addClass("disliked").text(lecount);
				}
				else
				{
					heart.children(".like").addClass("pastliked").removeClass("disliked").html("<i class='fa fa-heart'></i>");
					heart.children(".unliker").html("<i class='fa fa-times-circle'></i>");
					heart.children(".count").addClass("liked").removeClass("disliked").text(count);
				}
			}
		});
		
		return false;
	})
	
})
