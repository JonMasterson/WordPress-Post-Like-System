WordPress-Post-Like-System
==========================

A simple and efficient post like system for WordPress. <a href="http://jonmasterson.com/post-like-demo/" target="_blank">View the demo.</a> 

Check out <a href="http://hofmannsven.com/2013/laboratory/wordpress-post-like-system/" target="_blank">this post</a> on <a href="https://twitter.com/hofmannsven" target="_blank">Sven Hofmann's</a> site for more information.

Originally, this system utilized <a href="http://fontawesome.io/" target="_blank">Font Awesome</a> for the heart and gear icons. The Font Awesome version is still available in the <em>vendor</em> folder for those who are already using Font Awesome in their theme.

<h3>Five Steps to Glory</h3>
<ol>
  <li>Add the CSS to your theme's main stylesheet.</li>
  <li>Add <em>post-like.min.js</em> to your theme's <em>js</em> folder (if it exists). If there is no <em>js</em> folder at your theme's root level, create one and add <em>post-like.min.js</em> to it.</li>
  <li>Add all the fonts to your theme's <em>fonts</em> folder (if it exists). If there is no <em>fonts</em> folder at your theme's root level, create one and add all the fonts to it.</li>
  <li>Add the contents of post-like.php to your theme's functions.php file.</li>
  <li>Implement the button by doing one of the following:
    <ol>
      <li>Add the button function to your theme's single page template (typically content-single.php) — <em>echo getPostLikeLink( get_the_ID() );</em></li>
      <li>Include the [jmliker] shortcode in your posts</li>
    </ol>
  </li>
</ol>

<h3>Most Liked Lists</h3>
This system does not currently offer AJAX updates for functions 7-11. Originally, these functions were offered to show what could be done with this system, and were an afterthought. PHP functions are provided, but they will not update without a screen refresh.

<h3>Issues</h3>
<ul>
<li>This post like system is pretty simple, and does not have many moving parts. Some folks have encountered issues with AJAX-loaded content, and others have had some trouble with plugin conflicts. Feel free to open an issue if your stuck, and I will do my best to help you solve it.</li>
<li>Time-based retrieval of most liked lists (ex. "Most Popular Post This Week"), returns posts based on the time the post was published. This is inadequate — posts should be returned based on the time the post was liked.</li>
</ul>

<h3>Coming Soon</h3>
This Post Like System will soon be offered as a free and premium plugin. The free plugin will have everything the current system does, but will be easier to manage from the WordPress admin (no more implementation errors). The premium plugin will offer AJAX widgets for functions 7-11 (and beyond) that will update & respond to likes without a screen refresh, and can be managed from the WordPress admin.
