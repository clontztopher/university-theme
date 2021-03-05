<?php

/**
 * This page will be used as the /blog list page because a 
 * 'front-page.php' template has been added to the directory.
 */

get_header();

pageBanner(array(
  'title' => 'All Programs',
  'subtitle' => 'There is something for everyone.'
));
?>

<div class="container container--narrow page-section">
  <ul class="link-list min-list">
    <?php
    while (have_posts()) {
      /**
       * the_post prepares all of the variables needed to 
       * supply the other template functions with the correct
       * data.
       */
      the_post(); ?>

      <li><a href="<?php the_permalink() ?>"><?php the_title() ?></a></li>

    <?php } ?>
  </ul>
  <?php echo paginate_links(); ?>
</div>

<?php

get_footer();

?>