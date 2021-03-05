<?php

/**
 * This page will be used as the /blog list page because a 
 * 'front-page.php' template has been added to the directory.
 */

get_header();
pageBanner(array(
  'title' => 'All Events',
  'subtitle' => 'The latest happenings at the university.'
));
?>

<div class="container container--narrow page-section">

  <?php
  while (have_posts()) {
    /**
     * the_post prepares all of the variables needed to 
     * supply the other template functions with the correct
     * data.
     */
    the_post();

    /**
     * Output the template for events list
     */
    get_template_part('template-parts/event');
  }
  echo paginate_links();
  ?>
  <hr class="section-break">
  <p><a href="<?php site_url('/past-events') ?>">See Past Events</a></p>
</div>

<?php

get_footer();

?>