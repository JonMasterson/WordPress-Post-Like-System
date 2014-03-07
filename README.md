WordPress-Post-Like-System
==========================

A simple and efficient post like system for WordPress. <a href="http://jonmasterson.com/post-like-demo/" target="_blank">View the demo.</a> 

Check out <a href="http://hofmannsven.com/2013/laboratory/wordpress-post-like-system/" target="_blank">this post</a> on <a href="https://twitter.com/hofmannsven" target="_blank">Sven Hofmann's</a> site for more information.

<h3>Four Steps to Glory</h3>
<ol>
  <li>Add the styles from <em>like-styles.css</em> to your theme's main stylesheet.</li>
  <li>Add <em>post-like.js</em> to your theme's <em>js</em> folder (if it exists). If there is no <em>js</em> folder, create one and add <em>post-like.js</em> to it.</li>
  <li>Add the contents of post-like.php to your theme's functions.php file.</li>
  <li>Implement the button by doing one of the following:
    <ol>
      <li>Add the button function ( echo getPostLikeLink( get_the_ID() ); ) to your theme's single page template (typically content-single.php)</li>
      <li>Include the [jmliker] shortcode in your posts</li>
    </ol>
  </li>
</ol>

<h3>Troubleshooting</h3>
This plugin is pretty simple, and does not have many moving parts. Some folks have encountered issues with AJAX-loaded content, and others have had some trouble with plugin conflicts. Feel free to open an issue if your stuck, and I will do my best to help you solve it.
