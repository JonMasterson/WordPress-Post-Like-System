<?php
/*
Name:  WordPress Post Like System
Description:  A simple and efficient post like system for WordPress.
Version:      0.3.3
Author:       Jon Masterson
Author URI:   http://jonmasterson.com/

License:
Copyright (C) 2014 Jon Masterson

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
 
/**
 * (1) Enqueue scripts for like system
 */
function like_scripts() {
	wp_enqueue_script( 'jm_like_post', get_template_directory_uri().'/js/post-like.js', array('jquery'), '1.0', 1 );
	wp_localize_script( 'jm_like_post', 'ajax_var', array(
		'url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ajax-nonce' )
		)
	);
}
add_action( 'init', 'like_scripts' );

/**
 * (2) Add Fontawesome Icons
 */
function enqueue_icons () {
	wp_register_style( 'icon-style', 'http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css' );
    wp_enqueue_style( 'icon-style' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_icons' );

/**
 * (3) Save like data
 */
add_action( 'wp_ajax_nopriv_jm-post-like', 'jm_post_like' );
add_action( 'wp_ajax_jm-post-like', 'jm_post_like' );
function jm_post_like() {
	$nonce = $_POST['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Nope!' );
	
	if ( isset( $_POST['jm_post_like'] ) ) {
	
		$post_id = $_POST['post_id']; // post id
		$post_like_count = get_post_meta( $post_id, "_post_like_count", true ); // post like count
		
		if ( is_user_logged_in() ) { // user is logged in
			$user_id = get_current_user_id(); // current user
			$meta_POSTS = get_user_option( "_liked_posts", $user_id  ); // post ids from user meta
			$meta_USERS = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
			$liked_POSTS = NULL; // setup array variable
			$liked_USERS = NULL; // setup array variable
			
			if ( count( $meta_POSTS ) != 0 ) { // meta exists, set up values
				$liked_POSTS = $meta_POSTS;
			}
			
			if ( !is_array( $liked_POSTS ) ) // make array just in case
				$liked_POSTS = array();
				
			if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
				$liked_USERS = $meta_USERS[0];
			}		

			if ( !is_array( $liked_USERS ) ) // make array just in case
				$liked_USERS = array();
				
			$liked_POSTS['post-'.$post_id] = $post_id; // Add post id to user meta array
			$liked_USERS['user-'.$user_id] = $user_id; // add user id to post meta array
			$user_likes = count( $liked_POSTS ); // count user likes
	
			if ( !AlreadyLiked( $post_id ) ) { // like the post
				update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Add user ID to post meta
				update_post_meta( $post_id, "_post_like_count", ++$post_like_count ); // +1 count post meta
				update_user_option( $user_id, "_liked_posts", $liked_POSTS ); // Add post ID to user meta
				update_user_option( $user_id, "_user_like_count", $user_likes ); // +1 count user meta
				echo $post_like_count; // update count on front end

			} else { // unlike the post
				$pid_key = array_search( $post_id, $liked_POSTS ); // find the key
				$uid_key = array_search( $user_id, $liked_USERS ); // find the key
				unset( $liked_POSTS[$pid_key] ); // remove from array
				unset( $liked_USERS[$uid_key] ); // remove from array
				$user_likes = count( $liked_POSTS ); // recount user likes
				update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Remove user ID from post meta
				update_post_meta($post_id, "_post_like_count", --$post_like_count ); // -1 count post meta
				update_user_option( $user_id, "_liked_posts", $liked_POSTS ); // Remove post ID from user meta			
				update_user_option( $user_id, "_user_like_count", $user_likes ); // -1 count user meta
				echo "already".$post_like_count; // update count on front end
				
			}
			
		} else { // user is not logged in (anonymous)
			$ip = $_SERVER['REMOTE_ADDR']; // user IP address
			$meta_IPS = get_post_meta( $post_id, "_user_IP" ); // stored IP addresses
			$liked_IPS = NULL; // set up array variable
			
			if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
				$liked_IPS = $meta_IPS[0];
			}
	
			if ( !is_array( $liked_IPS ) ) // make array just in case
				$liked_IPS = array();
				
			if ( !in_array( $ip, $liked_IPS ) ) // if IP not in array
				$liked_IPS['ip-'.$ip] = $ip; // add IP to array
			
			if ( !AlreadyLiked( $post_id ) ) { // like the post
				update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Add user IP to post meta
				update_post_meta( $post_id, "_post_like_count", ++$post_like_count ); // +1 count post meta
				echo $post_like_count; // update count on front end
				
			} else { // unlike the post
				$ip_key = array_search( $ip, $liked_IPS ); // find the key
				unset( $liked_IPS[$ip_key] ); // remove from array
				update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Remove user IP from post meta
				update_post_meta( $post_id, "_post_like_count", --$post_like_count ); // -1 count post meta
				echo "already".$post_like_count; // update count on front end
				
			}
		}
	}
	
	exit;
}

/**
 * (4) Test if user already liked post
 */
function AlreadyLiked( $post_id ) { // test if user liked before
	if ( is_user_logged_in() ) { // user is logged in
		$user_id = get_current_user_id(); // current user
		$meta_USERS = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
		$liked_USERS = ""; // set up array variable
		
		if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
			$liked_USERS = $meta_USERS[0];
		}
		
		if( !is_array( $liked_USERS ) ) // make array just in case
			$liked_USERS = array();
			
		if ( in_array( $user_id, $liked_USERS ) ) { // True if User ID in array
			return true;
		}
		return false;
		
	} else { // user is anonymous, use IP address for voting
	
		$meta_IPS = get_post_meta( $post_id, "_user_IP" ); // get previously voted IP address
		$ip = $_SERVER["REMOTE_ADDR"]; // Retrieve current user IP
		$liked_IPS = ""; // set up array variable
		
		if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
			$liked_IPS = $meta_IPS[0];
		}
		
		if ( !is_array( $liked_IPS ) ) // make array just in case
			$liked_IPS = array();
		
		if ( in_array( $ip, $liked_IPS ) ) { // True is IP in array
			return true;
		}
		return false;
	}
	
}

/**
 * (5) Front end button
 */
function getPostLikeLink( $post_id ) {
	$like_count = get_post_meta( $post_id, "_post_like_count", true ); // get post likes
	$count = ( empty( $like_count ) || $like_count == "0" ) ? 'Like' : esc_attr( $like_count );
	if ( AlreadyLiked( $post_id ) ) {
		$class = esc_attr( ' liked' );
		$title = esc_attr( 'Unlike' );
		$heart = '<i class="fa fa-heart"></i>';
	} else {
		$class = esc_attr( '' );
		$title = esc_attr( 'Like' );
		$heart = '<i class="fa fa-heart-o"></i>';
	}
	$output = '<a href="#" class="jm-post-like'.$class.'" data-post_id="'.$post_id.'" title="'.$title.'">'.$heart.'&nbsp;'.$count.'</a>';
	return $output;
}

/**
 * (6) Retrieve User Likes and Show on Profile
 */
add_action( 'show_user_profile', 'show_user_likes' );
add_action( 'edit_user_profile', 'show_user_likes' );
function show_user_likes( $user ) { ?>        
    <table class="form-table">
        <tr>
			<th><label for="user_likes"><?php _e( 'You Like:' ); ?></label></th>
			<td>
            <?php
			$user_likes = get_user_option( "_liked_posts", $user->ID );
			if ( !empty( $user_likes ) && count( $user_likes ) > 0 ) {
				$the_likes = $user_likes;
			} else {
				$the_likes = '';
			}
			if ( !is_array( $the_likes ) )
			$the_likes = array();
			$count = count( $the_likes ); 
			$i=0;
			if ( $count > 0 ) {
				$like_list = '';
				echo "<p>\n";
				foreach ( $the_likes as $the_like ) {
					$i++;
					$like_list .= "<a href=\"" . esc_url( get_permalink( $the_like ) ) . "\" title=\"" . esc_attr( get_the_title( $the_like ) ) . "\">" . get_the_title( $the_like ) . "</a>\n";
					if ($count != $i) $like_list .= " &middot; ";
					else $like_list .= "</p>\n";
				}
				echo $like_list;
			} else {
				echo "<p>" . _e( 'You don\'t like anything yet.' ) . "</p>\n";
			} ?>
            </td>
		</tr>
    </table>
<?php }

/**
 * (7) Add a shortcode to your posts instead
 * type [jmliker] in your post to output the button
 */
function jm_like_shortcode() {
	return getPostLikeLink( get_the_ID() );
}
add_shortcode('jmliker', 'jm_like_shortcode');

/**
 * (8) If the user is logged in, output a list of posts that the user likes
 * Markup assumes sidebar/widget usage
 */
function frontEndUserLikes() {
	if ( is_user_logged_in() ) { // user is logged in
		$like_list = '';
		$user_id = get_current_user_id(); // current user
		$user_likes = get_user_option( "_liked_posts", $user_id );
		if ( !empty( $user_likes ) && count( $user_likes ) > 0 ) {
			$the_likes = $user_likes;
		} else {
			$the_likes = '';
		}
		if ( !is_array( $the_likes ) )
			$the_likes = array();
		$count = count( $the_likes );
		if ( $count > 0 ) {
			$limited_likes = array_slice( $the_likes, 0, 5 ); // this will limit the number of posts returned to 5
			$like_list .= "<aside>\n";
			$like_list .= "<h3>" . __( 'You Like:' ) . "</h3>\n";
			$like_list .= "<ul>\n";
			foreach ( $limited_likes as $the_like ) {
				$like_list .= "<li><a href='" . esc_url( get_permalink( $the_like ) ) . "' title='" . esc_attr( get_the_title( $the_like ) ) . "'>" . get_the_title( $the_like ) . "</a></li>\n";
			}
			$like_list .= "</ul>\n";
			$like_list .= "</aside>\n";
		}
		echo $like_list;
	}
}

/**
 * (9) Outputs a list of the 5 posts with the most user likes TODAY
 * Markup assumes sidebar/widget usage
 */
function jm_most_popular_today() {
	global $post;
	$today = date('j');
  	$year = date('Y');
	$args = array(
		'year' => $year,
		'day' => $today,
		'post_type' => array( 'post', 'enter-your-comma-separated-post-types-here' ),
		'meta_key' => '_post_like_count',
		'orderby' => 'meta_value_num',
		'order' => 'DESC',
		'posts_per_page' => 5
		);
	$pop_posts = new WP_Query( $args );
	if ( $pop_posts->have_posts() ) {
		echo "<aside>\n";
		echo "<h3>" . _e( 'Today\'s Most Popular Posts' ) . "</h3>\n";
		echo "<ul>\n";
		while ( $pop_posts->have_posts() ) {
			$pop_posts->the_post();
			echo "<li><a href='" . get_permalink($post->ID) . "'>" . get_the_title() . "</a></li>\n";
		}
		echo "</ul>\n";
		echo "</aside>\n";
	}
	wp_reset_postdata();
}

/**
 * (10) Outputs a list of the 5 posts with the most user likes for THIS MONTH
 * Markup assumes sidebar/widget usage
 */
function jm_most_popular_month() {
	global $post;
	$month = date('m');
  	$year = date('Y');
	$args = array(
		'year' => $year,
		'monthnum' => $month,
		'post_type' => array( 'post', 'enter-your-comma-separated-post-types-here' ),
		'meta_key' => '_post_like_count',
		'orderby' => 'meta_value_num',
		'order' => 'DESC',
		'posts_per_page' => 5
		);
	$pop_posts = new WP_Query( $args );
	if ( $pop_posts->have_posts() ) {
		echo "<aside>\n";
		echo "<h3>" . _e( 'This Month\'s Most Popular Posts' ) . "</h3>\n";
		echo "<ul>\n";
		while ( $pop_posts->have_posts() ) {
			$pop_posts->the_post();
			echo "<li><a href='" . get_permalink($post->ID) . "'>" . get_the_title() . "</a></li>\n";
		}
		echo "</ul>\n";
		echo "</aside>\n";
	}
	wp_reset_postdata();
}

/**
 * (11) Outputs a list of the 5 posts with the most user likes for THIS WEEK
 * Markup assumes sidebar/widget usage
 */
function jm_most_popular_week() {
	global $post;
	$week = date('W');
  	$year = date('Y');
	$args = array(
		'year' => $year,
		'w' => $week,
		'post_type' => array( 'post', 'enter-your-comma-separated-post-types-here' ),
		'meta_key' => '_post_like_count',
		'orderby' => 'meta_value_num',
		'order' => 'DESC',
		'posts_per_page' => 5
		);
	$pop_posts = new WP_Query( $args );
	if ( $pop_posts->have_posts() ) {
		echo "<aside>\n";
		echo "<h3>" . _e( 'This Week\'s Most Popular Posts' ) . "</h3>\n";
		echo "<ul>\n";
		while ( $pop_posts->have_posts() ) {
			$pop_posts->the_post();
			echo "<li><a href='" . get_permalink($post->ID) . "'>" . get_the_title() . "</a></li>\n";
		}
		echo "</ul>\n";
		echo "</aside>\n";
	}
	wp_reset_postdata();
}

/**
 * (12) Outputs a list of the 5 posts with the most user likes for ALL TIME
 * Markup assumes sidebar/widget usage
 */
function jm_most_popular() {
	global $post;
	echo "<aside>\n";
	echo "<h3>" . _e( 'Most Popular Posts' ) . "</h3>\n";
	echo "<ul>\n";
	$args = array(
		 'post_type' => array( 'post', 'enter-your-comma-separated-post-types-here' ),
		 'meta_key' => '_post_like_count',
		 'orderby' => 'meta_value_num',
		 'order' => 'DESC',
		 'posts_per_page' => 5 
		 );
	$pop_posts = new WP_Query( $args );
	while ( $pop_posts->have_posts() ) {
		$pop_posts->the_post();
		echo "<li><a href='" . get_permalink($post->ID) . "'>" . get_the_title() . "</a></li>\n";
	}
	wp_reset_postdata();
	echo "</ul>\n";
	echo "</aside>\n";
}
