WordPress-Post-Like-System
==========================

A simple and efficient post like system for WordPress. <a href="http://jonmasterson.com/post-like-demo/" target="_blank">View the demo.</a> 

It's a bit outdated at this point, but check out <a href="http://hofmannsven.com/2013/laboratory/wordpress-post-like-system/" target="_blank">this post</a> on <a href="https://twitter.com/hofmannsven" target="_blank">Sven Hofmann's</a> site for more information.

<h3>Four Steps to Glory</h3>
<ol>
  <li>Add the CSS to your theme's main stylesheet.</li>
  <li>Add the javascript file to your theme's <em>js</em> folder (if it exists). If there is no <em>js</em> folder at your theme's root level, create one and add <em>simple-likes-public.js</em> to it.</li>
  <li>Add the contents of post-like.php to your theme's functions.php file.</li>
  <li>Output the button by doing the following:
    <ul>
      <li>Add the button to any posts in your theme by adding the following function, <a href="https://developer.wordpress.org/themes/basics/the-loop/" target="_blank">within the loop</a> — <code>echo get_simple_likes_button( get_the_ID() );</code></li>
	  <li>Add the button to any comments in your theme by making sure the second parameter in the button function is set to "1" — <code>echo get_simple_likes_button( get_comment_ID(), 1 );</code></li>
      <li>Include the [jmliker] shortcode in your posts</li>
    </ul>
  </li>
</ol>

<h3>Upgrades</h3>
I've cleaned up a lot here, fixing a few errors, and making it easier to revise this system to suit your theme. Here are the highlights:
<ul>
  <li>You can now add the button to comments.</li>
  <li>You can now add multiple buttons for each post, and they will all update at once when you click any of them.</li>
  <li>SVG icons, so no more icon fonts. In an effort to keep things light, the SVG has been added inline, with a simple html entity as a fallback. This way, we don't have to include a javascript fallback. The trade-off is that these inline icons are not cached. They are rather small, though. If you prefer to use Font Awesome or another icon solution, swap out the svgs in the <code>get_liked_icon()</code> and <code>get_unliked_icon()</code> functions with your own solution. Since it is a popular solution, I've included commented code for Font Awesome icons.</li>
  <li>The button will now work with or without javascript. Hooray for graceful degradation!</li>
  <li>All the text is now translatable. Find and replace 'YourThemesTextDomain' with your theme's text domain.</li>
  <li>Number formatting now included. For example, a like count of "1,250" will output as "1.25K". If you would prefer a different number format, update <code>sl_format_count();</code> to your preference.</li>
  <li>Better IP address handling</li>
  <li>The time of the post/comment like is now recorded as post/comment meta. This allows us to properly perform proper meta queries to determine which posts have the most likes for a given time period.</li>
</ul>

<h3>Removed</h3>
I've removed some functions and changed the way this system works a bit. None are breaking changes. These were for efficiency.
<ul>
	<li>The functions that formerly appeared here to output most liked lists have been removed. Time-based retrieval of most liked lists (ex. "Most Popular Post This Week"), returned posts based on the time the post was published. This was inadequate — posts should have been returned based on the time the post was liked. If you should happen to need these older functions for some reason, they are still available in past versions.</li>
	<li>We no longer store an array of liked posts in user meta. As time goes by, this can lead to inconsistencies if posts are deleted. I could have built a clean-up function to find and remove deleted post ids from user meta, but it made more sense to stop adding this data to user meta. Especially since these posts can be retrieved with a meta query for all posts that have the user's ID attached (see <code>show_user_likes();</code> for this query).</li>
</ul>

<h3>Issues</h3>
This post like system is pretty simple, and does not have many moving parts. Some folks have encountered issues with AJAX-loaded content, and others have had some trouble with plugin conflicts. Feel free to open an issue if your stuck, and I will do my best to help you solve it.

<h3>Coming in <del>November</del> December</h3>
We have been working hard on many updates to this system, two new plugins, and documentation. It's taking a bit longer than expected, so please bear with us. We are extending the plugin version for use with BuddyPress and bbPress as well! Here are a couple screenshots of the settings...

<img src="http://jonmasterson.com/wp-content/uploads/general-settings.jpg" />

<img src="http://jonmasterson.com/wp-content/uploads/post-settings.jpg" />

This Post Like System will soon be offered as a free and premium plugin. The free plugin will have everything the current system does, but will be easier to manage from the WordPress admin (no more implementation errors). The premium plugin will offer AJAX widgets for Most Liked Lists that will update & respond to likes without a screen refresh, and can be managed from the WordPress admin.
