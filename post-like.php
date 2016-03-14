<?php
/*
 *  Author: Todd Motto | @toddmotto
 *  URL: PASSIO.com | @PASSIO
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Add Support for Custom Backgrounds - Uncomment below if you're going to use
    /*add_theme_support('custom-background', array(
	'default-color' => 'FFF',
	'default-image' => get_template_directory_uri() . '/img/bg.jpg'
    ));*/

    // Add Support for Custom Header - Uncomment below if you're going to use
    /*add_theme_support('custom-header', array(
	'default-image'			=> get_template_directory_uri() . '/img/headers/default.jpg',
	'header-text'			=> false,
	'default-text-color'		=> '000',
	'width'				=> 1000,
	'height'			=> 198,
	'random-default'		=> false,
	'wp-head-callback'		=> $wphead_cb,
	'admin-head-callback'		=> $adminhead_cb,
	'admin-preview-callback'	=> $adminpreview_cb
    ));*/

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('PASSIO', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// HTML5 Blank navigation
function PASSIO_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul class="nav navbar-nav navbar-right">%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// Load HTML5 Blank scripts (header.php)
function PASSIO_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

    	wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), '4.3.0'); // Conditionizr
        wp_enqueue_script('conditionizr'); // Enqueue it!

        wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // Modernizr
        wp_enqueue_script('modernizr'); // Enqueue it!

        wp_register_script('PASSIOscripts', get_template_directory_uri() . '/js/passio.js', array('jquery'), '1.0.0'); // Custom scripts
        wp_enqueue_script('PASSIOscripts'); // Enqueue it!


    }
}

// Load HTML5 Blank conditional scripts
function PASSIO_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}

// Load HTML5 Blank styles
function PASSIO_styles()
{
    wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all');
    wp_enqueue_style('normalize'); // Enqueue it!

    wp_register_style('simple-likes-public', get_template_directory_uri() . '/simple-likes-public.css', array(), '1.0', 'all');
    wp_enqueue_style('simple-likes-public'); // Enqueue it!

    wp_register_style('PASSIO', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('PASSIO'); // Enqueue it!

    wp_register_style('icomoon', get_template_directory_uri() . '/icomoon.css', array(), '1.0', 'all');
    wp_enqueue_style('icomoon'); // Enqueue it!



}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'PASSIO'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'PASSIO'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'PASSIO') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'PASSIO'),
        'description' => __('Description for this widget-area...', 'PASSIO'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'PASSIO'),
        'description' => __('Description for this widget-area...', 'PASSIO'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'PASSIO') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function PASSIOgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function PASSIOcomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>
    <?php echo get_simple_likes_button( get_comment_ID(), 1 ); ?>
	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }
















// PASSIO function

// display publication title and tags
// requires video object
// will do all the proper echo-ing
function publication_head($query) {

    // remove excluded tags
    $display_tags = array();
    $exclude_tags = explode(',', EXCLUDE_TAGS);
    foreach ($query->tags as $tag) {
        if (!in_array($tag, $exclude_tags)) {
            $display_tags[] = $tag;
        }
    }

    // display title & tags
    $post_dat = json_decode($query->long_description);
    echo '<p>' . parse_author_dat($post_dat) . '</p>';
    echo '<p>Published: ' . get_the_time('F j, Y') . '</p>';
    echo '<h5>Tags: ';
    foreach ($display_tags as $tag) {
        echo sprintf('<code><a href="#?tag=%s">%s</a></code>', $tag, $tag);
    }
    echo '</h5>';
    $dat = json_decode($query->long_description);
    echo sprintf('<p>%s</p>', $dat->Description);

}

// takes time and returns total seconds
// http://stackoverflow.com/a/4834230/1153897
function time_code_to_s($time_code) {
    sscanf($time_code, "%d:%d:%d", $hours, $minutes, $seconds);

    return isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
}

// display table of contents for publication video
// requires object for video
// will do all the proper echo-ing
function publication_toc($query, $type) {

    echo sprintf('<div class="row"><div class="col-sm-7 toc" id="toc-%s">', $type);
    if (isset($query->text_tracks[0]->src)) {
        $toc = parse_vtt($query->text_tracks[0]->src);
        echo print_toc($toc, $type);
    }
    echo '</div></div>';
}


// generate html out of parsed vtt file
// requires output of parse_vtt()
// type {string} standard or extended
// returns valid html
function print_toc($vtt, $type) {

    $html = '<h4><u>Table of contents</u></h4>';
    foreach ($vtt as $key => $val) {
        $time = explode('.000', $key)[0];
        $html .= sprintf('<div id="toc-%s-%s"><a href="#" onclick="seek(this, %s, %s); return false;"><b>%s</b></a> - %s</div>', $type, time_code_to_s($time), time_code_to_s($time), $type, $time, $val);
    }

    return $html;

}


// parses a vtt file into a class
// keys will be time --> time
// value will be title
function parse_vtt($url) {
    $in = file($url, FILE_SKIP_EMPTY_LINES);

    $dat = array();
    $key = '';
    foreach ($in as $line) {
        if (strpos($line, 'WEBVTT') === false && trim($line) != '') { // skip header
            if (strpos($line, '-->') !== false) {
                $key = trim($line);
            } else {
                $dat[$key] = trim($line);
            }
        }
    }
    return $dat;

}

// parses author data into proper html (surrounded by <p>)
// expects json of form { "Authors" : {"1": "xxx", etc}, "Author Information": {"1": "YYY", etc} }
function parse_author_dat($dat) {

    $auths = array();
    foreach ($dat->Authors as $key => $val) {
	if (is_array($val)) {
		$clean = '';
		foreach ($val as $person) {
			$clean .= sprintf('<abbr title="%s"><b>%s</b><sup>%s</sup></abbr>', $dat->{'Author Information'}->$key, $person, $key);
		}
		$auths[] = $clean;
	} else {
	        $auths[] = sprintf('<abbr title="%s"><b>%s</b><sup>%s</sup></abbr>', $dat->{'Author Information'}->$key, $val, $key);
	}
    }
    return implode(', ', $auths);

}


// returns a string with the current logged in user role
function get_user_role() {
	global $current_user;
	return $current_user->roles[0];
}

// remove the # of posts column from the users table
add_action('manage_users_columns','remove_user_posts_column');
function remove_user_posts_column($column_headers) {
    unset($column_headers['posts']);
    return $column_headers;
}

add_filter('manage_users_columns', 'passio_admin_add_user_cols');
function passio_admin_add_user_cols($columns) {
    $columns['title'] = 'Title';
    $columns['location'] = 'Location';
    $columns['work'] = 'Work';
    $columns['specialty'] = 'Specialty';
    return $columns;
}

add_action('manage_users_custom_column',  'pippin_show_user_id_column_content', 10, 3);
function pippin_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	if ( 'title' == $column_name ) {
		return $user->title;
	} else if ( 'location' == $column_name ) {
		return $user->location;
	} else if ( 'work' == $column_name ) {
		return $user->work;
	} else if ( 'specialty' == $column_name ) {
		return $user->specialty;
	}
    return $value;
}

// add custom fields to registration form
function passio_register_form() {

    $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
    $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';
    $user_email = $_POST['user_email']; // we change this one because there are actually two "user_email" fields, the original is hidden
    $work = ( ! empty( $_POST['work'] ) ) ? trim( $_POST['work'] ) : '';
    $specialty = ( ! empty( $_POST['specialty'] ) ) ? trim( $_POST['specialty'] ) : '';

        ?>
      <p>
            <label for="title"><?php _e( 'Title*', 'mydomain' ) ?><br />
                <select id="title" name="title" style="margin-bottom:20px;">
                        <option selected disabled hidden style='display: none' value=''></option>
                        <option value="Dr">Dr</option>
                        <option value="Mr">Mr</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Ms">Ms</option>
                </select></label>
        </p>
        <p>
            <label for="first_name"><?php _e( 'First Name*', 'mydomain' ) ?><br />
                <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
        </p>
        <p>
            <label for="last_name"><?php _e( 'Last Name*', 'mydomain' ) ?><br />
                <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
        </p>
        <p>
                <label for="user_email"><?php _e('Email') ?><br />
                <input type="email" name="user_email" id="user_email" class="input" value="<?php echo esc_attr( wp_unslash( $user_email ) ); ?>" size="25" /></label>
        </p>
        <p>
            <label for="work"><?php _e( 'Institution/Company*', 'mydomain' ) ?><br />
                <input type="text" name="work" id="work" class="input" value="<?php echo esc_attr( wp_unslash( $work ) ); ?>" size="25" /></label>
        </p>
        <p>
            <label for="specialty"><?php _e( 'Surgical Specialty', 'mydomain' ) ?><br />
                <input type="text" name="specialty" id="specialty" class="input" value="<?php echo esc_attr( wp_unslash( $specialty ) ); ?>" size="25" /></label>
        </p>
        <?php
    }



// ensure all required fields have something in them
function passio_registration_errors( $errors, $sanitized_user_login, $user_email ) {

        if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
            $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: Please type your first name.', 'mydomain' ) );
        }
        if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
            $errors->add( 'last_name_error', __( '<strong>ERROR</strong>: Please type your last name.', 'mydomain' ) );
        }
        if ( empty( $_POST['work'] ) || ! empty( $_POST['work'] ) && trim( $_POST['work'] ) == '' ) {
            $errors->add( 'work_error', __( '<strong>ERROR</strong>: Please type your institution or company.', 'mydomain' ) );
        }
        if ( empty( $_POST['title'] ) || ! empty( $_POST['title'] ) && trim( $_POST['title'] ) == '' ) {
            $errors->add( 'title_error', __( '<strong>ERROR</strong>: Please type your title.', 'mydomain' ) );
        }
        return $errors;
}

// update user metadata with supplied information
function passio_user_register( $user_id ) {
	if ( ! empty( $_POST['first_name'] ) ) {
            update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
        }
	if ( ! empty( $_POST['last_name'] ) ) {
            update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
        }
	if ( ! empty( $_POST['first_name']) && ! empty( $_POST['last_name'] ) ) {

            //update_user_meta( $user_id, 'nickname', trim( $_POST['first_name'] ) . '_' . trim( $_POST['last_name'] ) );
        }
	if ( ! empty( $_POST['work'] ) ) {
            update_user_meta( $user_id, 'work', trim( $_POST['work'] ) );
        }
	if ( ! empty( $_POST['specialty'] ) ) {
            update_user_meta( $user_id, 'specialty', trim( $_POST['specialty'] ) );
        }
	if ( ! empty( $_POST['title'] ) ) {
            update_user_meta( $user_id, 'title', trim( $_POST['title'] ) );
        }

	// store IPs http://stackoverflow.com/a/3003233/1153897
	$remote = getenv('REMOTE_ADDR');
	$forwarded = getenv('HTTP_X_FORWARDED_FOR');
	update_user_meta( $user_id, 'remote_ip', $remote);
	update_user_meta( $user_id, 'forwarded_ip', $forwarded);

	if ($remote != '') {
		$url = sprintf('http://api.ipinfodb.com/v3/ip-city/?key=%s&ip=%s', IP_LOOKUP_KEY, $remote);
		$loc_arr = explode(';', file_get_contents($url));
		$loc = sprintf('%s, %s, %s', $loc_arr[6], $loc_arr[5], $loc_arr[4]);
	} else {
		$loc = 'Unknown';
	}
	update_user_meta( $user_id, 'location', $loc);

}


    /**
    * proxy for Brightcove RESTful APIs
    * gets an access token, makes the request, and returns the response
    *
    * Method: POST
    * include header: "Content-Type", "application/x-www-form-urlencoded"
    *
    * @post {string} url - the URL for the API request
    * @post {string} [requestType=GET] - HTTP method for the request
    * @post {string} [requestBody=null] - JSON data to be sent with write requests
    *
    * @returns {string} $response - JSON response received from the API
    */
    function get_token() {


        // CORS enablement
        header("Access-Control-Allow-Origin: *");

        // set up request for access token
        $data = array();
        $client_id     = BC_CLIENT;
        $client_secret = BC_SECRET;
        $auth_string   = "{$client_id}:{$client_secret}";
        $request       = "https://oauth.brightcove.com/v3/access_token?grant_type=client_credentials";
        $ch            = curl_init($request);
        curl_setopt_array($ch, array(
            CURLOPT_POST           => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_USERPWD        => $auth_string,
            CURLOPT_HTTPHEADER     => array(
                'Content-type: application/x-www-form-urlencoded',
            ),
            CURLOPT_POSTFIELDS => $data
        ));
        $response = curl_exec($ch);
        //echo $response;
        curl_close($ch);

        // Check for errors
        if ($response === FALSE) {
            die(curl_error($ch));
        }

        // Decode the response
        $responseData = json_decode($response, TRUE);
        $access_token = $responseData["access_token"];
        $_SESSION['token'] = $access_token;
        return $access_token;
    }




    /* api call to bright cove
    *
    * @post {string} $token (from call to get_token())
    * @post {string} $data for the body
    * @post {string} $method [GET, POST, etc]
    * @post {string} $request the API URL
    *
    * @return {string} response (JSON)
    */
    function bright_cove_call($token, $data=NULL, $method=NULL, $request=NULL) {

        // set up the API call
        // get data from body
        if (!$data) {
            $data = '{}';
        }

        // get request type or default to GET
        if (!$method) {
            $method = "GET";
        }

        // get the URL and authorization info from the form data
        if (!$request) {
            $request = sprintf('https://cms.api.brightcove.com/v1/accounts/%s/videos', BC_ACCOUNT);
        }

        //send the http request
        $ch = curl_init($request);
        if (strpos($request, 'edge') !== false) { // playback api: https://docs.brightcove.com/en/video-cloud/playback-api/index.html
            curl_setopt_array($ch, array(
                    CURLOPT_CUSTOMREQUEST  => $method,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_HTTPHEADER => array('Accept: application/json;pk=BCpkADawqM3U4vZQmL1o3saeYi9gQPd0GXZi5wd9-nIzUEvhAzUaw6SYU5sW2I68iHhCpDHXRkmEPU2ukorHHPTJMlf1h8VFE0wimJE7ai1nivP7iIHul60u42Me_zF7-5IzgXpEaWmgbI96'),
                    CURLOPT_POSTFIELDS => $data
                ));
        } else { // all other apis
            curl_setopt_array($ch, array(
                    CURLOPT_CUSTOMREQUEST  => $method,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_HTTPHEADER     => array(
                        'Content-type: application/json',
                        "Authorization: Bearer {$token}",
                    ), // use this for the policy api
                    CURLOPT_POSTFIELDS => $data
                ));
        }
        $response = curl_exec($ch);
        curl_close($ch);

        // Check for errors
        if ($response === FALSE) {
            $logEntry = "\nError:\n".
            "\n".date("Y-m-d H:i:s")." UTC \n"
            .$response;
            $logFileLocation = "err.txt";
            $fileHandle      = fopen($logFileLocation, 'a') or die("-1");
            fwrite($fileHandle, $logEntry);
            fclose($fileHandle);
            echo "Error: there was a problem with your API call"+
            die(curl_error($ch));
        }

        // Decode the response
        // $responseData = json_decode($response, TRUE);
        // return the response to the AJAX caller
        return json_decode($response);
    }



// single call with filtering parameters to BC to get video metadata
// will return an object with video ID as the key
function get_BC_db() {

	// adjust GET url to limit of number and posts and page offset
	$max_posts = get_option( 'posts_per_page'); // how many posts per page to show
	$page_offset = 0;
	if (isset($_GET['paged'])) {
		$page_offset = $max_posts * (get_query_var( 'paged', 1 ) - 1);
	}
	$url = sprintf("https://cms.api.brightcove.com/v1/accounts/%s/videos?limit=%s&offset=%s", BC_ACCOUNT, $max_posts, $page_offset);

	//$query = bright_cove_call(get_token(), NULL, NULL, $url);

	// reformat data so video ID is key
	if (isset($query)) {
		$dat = new stdClass();
		foreach ($query as $key => $val ){
		    $id = $val->id;
		    $dat->$id = $val;
		}
		return $dat;
	}
}

// response message for user needing to login
function must_login() {
	return sprintf('You must <a href="/wp-login.php?redirect_to=%s">login</a> to view this page.', get_permalink());
}


// PASSIO function















/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'PASSIO_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'PASSIO_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'PASSIO_styles'); // Add Theme Stylesheet
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
//add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination
add_action( 'register_form', 'passio_register_form' );
add_action( 'user_register', 'passio_user_register' );


// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'PASSIOgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images
add_filter( 'registration_errors', 'passio_registration_errors', 10, 3 );

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called HTML5-Blank
function create_post_type_html5()
{
    register_taxonomy_for_object_type('category', 'html5-blank'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'html5-blank');
    register_post_type('html5-blank', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('HTML5 Blank Custom Post', 'PASSIO'), // Rename these to suit
            'singular_name' => __('HTML5 Blank Custom Post', 'PASSIO'),
            'add_new' => __('Add New', 'PASSIO'),
            'add_new_item' => __('Add New HTML5 Blank Custom Post', 'PASSIO'),
            'edit' => __('Edit', 'PASSIO'),
            'edit_item' => __('Edit HTML5 Blank Custom Post', 'PASSIO'),
            'new_item' => __('New HTML5 Blank Custom Post', 'PASSIO'),
            'view' => __('View HTML5 Blank Custom Post', 'PASSIO'),
            'view_item' => __('View HTML5 Blank Custom Post', 'PASSIO'),
            'search_items' => __('Search HTML5 Blank Custom Post', 'PASSIO'),
            'not_found' => __('No HTML5 Blank Custom Posts found', 'PASSIO'),
            'not_found_in_trash' => __('No HTML5 Blank Custom Posts found in Trash', 'PASSIO')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}
/*
Name:  WordPress Post Like System
Description:  A simple and efficient post like system for WordPress.
Version:      0.5.2
Author:       Jon Masterson
Author URI:   http://jonmasterson.com/
License:
Copyright (C) 2015 Jon Masterson
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
 * Register the stylesheets for the public-facing side of the site.
 * @since    0.5
 */
add_action( 'wp_enqueue_scripts', 'sl_enqueue_scripts' );
function sl_enqueue_scripts() {
	wp_enqueue_script( 'simple-likes-public-js', get_template_directory_uri() . '/js/simple-likes-public.js', array( 'jquery' ), '0.5', false );
	wp_localize_script( 'simple-likes-public-js', 'simpleLikes', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'like' => __( 'Like', 'YourThemeTextDomain' ),
		'unlike' => __( 'Unlike', 'YourThemeTextDomain' )
	) );
}
/**
 * Processes like/unlike
 * @since    0.5
 */
add_action( 'wp_ajax_nopriv_process_simple_like', 'process_simple_like' );
add_action( 'wp_ajax_process_simple_like', 'process_simple_like' );
function process_simple_like() {
	// Security
	$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0;
	if ( !wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
		exit( __( 'Not permitted', 'YourThemeTextDomain' ) );
	}
	// Test if javascript is disabled
	$disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
	// Test if this is a comment
	$is_comment = ( isset( $_REQUEST['is_comment'] ) && $_REQUEST['is_comment'] == 1 ) ? 1 : 0;
	// Base variables
	$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : '';
	$result = array();
	$post_users = NULL;
	$like_count = 0;
	// Get plugin options
	if ( $post_id != '' ) {
		$count = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_comment_like_count", true ) : get_post_meta( $post_id, "_post_like_count", true ); // like count
		$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
		if ( !already_liked( $post_id, $is_comment ) ) { // Like the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = post_user_likes( $user_id, $post_id, $is_comment );
				if ( $is_comment == 1 ) {
					// Update User & Comment
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_comment_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					}
				} else {
					// Update User & Post
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_user_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = sl_get_ip();
				$post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else {
						update_post_meta( $post_id, "_user_IP", $post_users );
					}
				}
			}
			$like_count = ++$count;
			$response['status'] = "liked";
			$response['icon'] = get_liked_icon();
		} else { // Unlike the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = post_user_likes( $user_id, $post_id, $is_comment );
				// Update User
				if ( $is_comment == 1 ) {
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, "_comment_like_count", --$user_like_count );
					}
				} else {
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, '_user_like_count', --$user_like_count );
					}
				}
				// Update Post
				if ( $post_users ) {
					unset( $post_users[$user_id] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					} else {
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = sl_get_ip();
				$post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					unset( $post_users[$user_ip] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else {
						update_post_meta( $post_id, "_user_IP", $post_users );
                        wp_cache_flush();
					}
				}
			}
			$like_count = ( $count > 0 ) ? --$count : 0; // Prevent negative number
			$response['status'] = "unliked";
			$response['icon'] = get_unliked_icon();
		}
		if ( $is_comment == 1 ) {
			update_comment_meta( $post_id, "_comment_like_count", $like_count );
		} else {
			update_post_meta( $post_id, "_post_like_count", $like_count );
		}
		$response['count'] = get_like_count( $like_count );
		$response['testing'] = $is_comment;
		if ( $disabled == true ) {
			if ( $is_comment == 1 ) {
				wp_redirect( get_permalink( get_the_ID() ) );
				exit();
			} else {
				wp_redirect( get_permalink( $post_id ) );
				exit();
			}
		} else {
			wp_send_json( $response );
		}
	}
}
/**
 * Utility to test if the post is already liked
 * @since    0.5
 */
function already_liked( $post_id, $is_comment ) {
	$post_users = NULL;
	$user_id = NULL;
    global $wpdb;
	if ( is_user_logged_in() ) { // user is logged in
		$user_id = get_current_user_id();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}
	} else { // user is anonymous
		$user_id = sl_get_ip();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" );
		if ( count( $post_meta_users ) != 0 ) { // meta exists, set up values
			$post_users = $post_meta_users;
		}
	}
	if ( is_array( $post_users ) && array_key_exists( $user_id, $post_users ) ) {
		return true;
	} else {
		return false;
	}
} // already_liked()
/**
 * Output the like button
 * @since    0.5
 */
function get_simple_likes_button( $post_id, $is_comment = NULL ) {
    global $wpdb;
	$is_comment = ( NULL == $is_comment ) ? 0 : 1;
	$output = '';
	$nonce = wp_create_nonce( 'simple-likes-nonce' ); // Security
	if ( $is_comment == 1 ) {
		$post_id_class = esc_attr( ' sl-comment-button-' . $post_id );
		$comment_class = esc_attr( ' sl-comment' );
		//$like_count = $wpdb->get_results( "SELECT meta_value FROM $wpdb->commentmeta WHERE comment_id = $post_id AND meta_key = '_comment_like_count'")[0]->meta_value;
		$like_count = get_comment_meta( $post_id, "_comment_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	} else {
		$post_id_class = esc_attr( ' sl-button-' . $post_id );
		$comment_class = esc_attr( '' );
		//$like_count = $wpdb->get_results( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key = '_post_like_count'")[0]->meta_value;
		$like_count = get_post_meta( $post_id, "_post_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	}
	$count = get_like_count( $like_count );
	$icon_empty = get_unliked_icon();
	$icon_full = get_liked_icon();
	// Loader
	$loader = '<span id="sl-loader"></span>';
	// Liked/Unliked Variables
	if ( already_liked( $post_id, $is_comment ) ) {
		$class = esc_attr( ' liked' );
		$title = __( 'Unlike', 'YourThemeTextDomain' );
		$icon = $icon_full;
	} else {
		$class = '';
		$title = __( 'Like', 'YourThemeTextDomain' );
		$icon = $icon_empty;
	}
	$output = '<span class="sl-wrapper"><a href="' . admin_url( 'admin-ajax.php?action=process_simple_like' . '&nonce=' . $nonce . '&post_id=' . $post_id . '&disabled=true&is_comment=' . $is_comment ) . '" class="sl-button' . $post_id_class . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . $title . '">' . $icon . $count . '</a>' . $loader . '</span>';
	return $output;
} // get_simple_likes_button()
/**
 * Processes shortcode to manually add the button to posts
 * @since    0.5
 */
add_shortcode( 'jmliker', 'sl_shortcode' );
function sl_shortcode() {
	return get_simple_likes_button( get_the_ID(), 0 );
} // shortcode()
/**
 * Utility retrieves post meta user likes (user id array),
 * then adds new user id to retrieved array
 * @since    0.5
 */
function post_user_likes( $user_id, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !array_key_exists( $user_id, $post_users ) ) {
		$post_users[$user_id] = time(); // store as key=user_id, value=timestamp
	}
	return $post_users;
} // post_user_likes()
/**
 * Utility retrieves post meta ip likes (ip array),
 * then adds new ip to retrieved array
 * @since    0.5
 */
function post_ip_likes( $user_ip, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" );
	// Retrieve post information
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !array_key_exists( $user_ip, $post_users ) ) {
		$post_users[$user_ip] = time(); // store as key=IP, value=timestamp
	}
	return $post_users;
} // post_ip_likes()
/**
 * Utility to retrieve IP address
 * @since    0.5
 */
function sl_get_ip() {
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}
	$ip = filter_var( $ip, FILTER_VALIDATE_IP );
	$ip = ( $ip === false ) ? '0.0.0.0' : $ip;
	return $ip;
} // sl_get_ip()
/**
 * Utility returns the button icon for "like" action
 * @since    0.5
 */
function get_liked_icon() {
	/* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart"></i> */
	$icon = '<span class="sl-icon"><svg role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xml:space="preserve"><path id="heart-full" d="M124 20.4C111.5-7 73.7-4.8 64 19 54.3-4.9 16.5-7 4 20.4c-14.7 32.3 19.4 63 60 107.1C104.6 83.4 138.7 52.7 124 20.4z"/>&#9829;</svg></span>';
	return $icon;
} // get_liked_icon()
/**
 * Utility returns the button icon for "unlike" action
 * @since    0.5
 */
function get_unliked_icon() {
	/* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart-o"></i> */
	$icon = '<span class="sl-icon"><svg role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xml:space="preserve"><path id="heart" d="M64 127.5C17.1 79.9 3.9 62.3 1 44.4c-3.5-22 12.2-43.9 36.7-43.9 10.5 0 20 4.2 26.4 11.2 6.3-7 15.9-11.2 26.4-11.2 24.3 0 40.2 21.8 36.7 43.9C124.2 62 111.9 78.9 64 127.5zM37.6 13.4c-9.9 0-18.2 5.2-22.3 13.8C5 49.5 28.4 72 64 109.2c35.7-37.3 59-59.8 48.6-82 -4.1-8.7-12.4-13.8-22.3-13.8 -15.9 0-22.7 13-26.4 19.2C60.6 26.8 54.4 13.4 37.6 13.4z"/>&#9829;</svg></span>';
	return $icon;
} // get_unliked_icon()
/**
 * Utility function to format the button count,
 * appending "K" if one thousand or greater,
 * "M" if one million or greater,
 * and "B" if one billion or greater (unlikely).
 * $precision = how many decimal points to display (1.25K)
 * @since    0.5
 */
function sl_format_count( $number ) {
	$precision = 2;
	if ( $number >= 1000 && $number < 1000000 ) {
		$formatted = number_format( $number/1000, $precision ).'K';
	} else if ( $number >= 1000000 && $number < 1000000000 ) {
		$formatted = number_format( $number/1000000, $precision ).'M';
	} else if ( $number >= 1000000000 ) {
		$formatted = number_format( $number/1000000000, $precision ).'B';
	} else {
		$formatted = $number; // Number is less than 1000
	}
	$formatted = str_replace( '.00', '', $formatted );
	return $formatted;
} // sl_format_count()
/**
 * Utility retrieves count plus count options,
 * returns appropriate format based on options
 * @since    0.5
 */
function get_like_count( $like_count ) {
	$like_text = __( 'Like', 'YourThemeTextDomain' );
	if ( is_numeric( $like_count ) && $like_count > 0 ) {
		$number = sl_format_count( $like_count );
	} else {
		$number = $like_text;
	}
    $number = sl_format_count( $like_count );
	$count = '<span class="sl-count">' . $number . '</span>';
	return $count;
} // get_like_count()
// User Profile List
add_action( 'show_user_profile', 'show_user_likes' );
add_action( 'edit_user_profile', 'show_user_likes' );
function show_user_likes( $user ) { ?>
	<table class="form-table">
		<tr>
			<th><label for="user_likes"><?php _e( 'You Like:', 'YourThemeTextDomain' ); ?></label></th>
			<td>
			<?php
			$types = get_post_types( array( 'public' => true ) );
			$args = array(
			  'numberposts' => -1,
			  'post_type' => $types,
			  'meta_query' => array (
				array (
				  'key' => '_user_liked',
				  'value' => $user->ID,
				  'compare' => 'LIKE'
				)
			  ) );
			$sep = '';
			$like_query = new WP_Query( $args );
			if ( $like_query->have_posts() ) : ?>
			<p>
			<?php while ( $like_query->have_posts() ) : $like_query->the_post();
			echo $sep; ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
			<?php
			$sep = ' &middot; ';
			endwhile;
			?>
			</p>
			<?php else : ?>
			<p><?php _e( 'You do not like anything yet.', 'YourThemeTextDomain' ); ?></p>
			<?php
			endif;
			wp_reset_postdata();
			?>
			</td>
		</tr>
	</table>
<?php } // show_user_likes()
