(function( $ ) {
	'use strict';
	
	function process_simple_likes () {
		var button = $('#sl-button');
		var post_id = button.attr('data-post-id');
		var security = button.attr('data-nonce');
		var iscomment = button.attr('data-iscomment');
		var isrefresh = button.attr('data-isrefresh');
		var allbuttons;
		if ( iscomment === '1' ) { /* Comments can have same id */
			allbuttons = $('.sl-comment-button-'+post_id);
		} else {
			allbuttons = $('.sl-button-'+post_id);
		}
		var loader = allbuttons.next('#sl-loader');
		if (post_id !== '') {
			$.ajax({
				type: 'POST',
				url: simpleLikes.ajaxurl,
				data : {
					action : 'process_simple_like',
					post_id : post_id,
					nonce : security,
					is_comment : iscomment,
				},
				beforeSend:function(){
					loader.html('&nbsp;<div class="loader">Loading...</div>');
				},	
				success: function(response){
					var icon = response.icon;
					var count = response.count;
					allbuttons.html(icon+count);
					if ( isrefresh ){
						button.attr('data-isrefresh', 'false');
					}
					else {
						if(response.status === 'unliked') {
							var like_text = simpleLikes.like;
							allbuttons.prop('title', like_text);
							allbuttons.removeClass('liked');
						} else {
							var unlike_text = simpleLikes.unlike;
							allbuttons.prop('title', unlike_text);
							allbuttons.addClass('liked');
						}
					}
					loader.empty();					
				}
			});
		}
		return false;
	}
	
	$(document).on('click', '#sl-button', function(){
		process_simple_likes();
		return false;
	});
	
	$(document).ready(function(){
		process_simple_likes();
	});
	
})( jQuery );
