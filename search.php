<?php

/**
 * The index.php page is the last fallback for WordPress
 * and is usually only used for the blog archive.
 */

get_header();

pageBanner(array(
  'title' => 'Search Results',
  'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query(false)) . '&rdquo;'
));
?>

<div class="container container--narrow page-section">

  <?php
  if (have_posts()) {
    while (have_posts()) {
      /**
       * the_post prepares all of the variables needed to 
       * supply the other template functions with the correct
       * data.
       */
      the_post();
      get_template_part('template-parts/content', get_post_type());
    }
  } else {
    echo '<h2 class="headline headline--small-plus">No results match.</h2>';
  }

  get_search_form();

  echo paginate_links();
  ?>

</div>

<?php

get_footer();

?>