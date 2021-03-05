<?php

get_header();

pageBanner();

while (have_posts()) {
  the_post(); ?>


  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <?php
        /**
         * get_post_type_archive_link retrieves the link for the post type
         * and doesn't rely on hard coding in case the slug changes later
         */
        $eventsArchiveLink = get_post_type_archive_link('event');
        ?>
        <a class="metabox__blog-home-link" href="<?= $eventsArchiveLink ?>">
          <i class="fa fa-home" aria-hidden="true"></i> Events Home
        </a>
        <span class="metabox__main"><?php the_title() ?></span>
      </p>
    </div>
    <div class="generic-content">
      <?php the_content() ?>
    </div>
    <?php
    /**
     * In this case get_field returns an array of Post objects
     */
    $relatedPrograms = get_field('related_programs');

    if ($relatedPrograms) {
    ?>
      <hr class="section-break">
      <h2 class="headline headline--medium">Related Programs</h2>
      <ul class="link-list min-list">
        <?php


        /**
         * Loop through the post objects and extract the data
         * using WordPress functions and passing in the object.
         */
        foreach ($relatedPrograms as $program) { ?>
          <li><a href="<?php echo get_the_permalink($program) ?>"><?php echo get_the_title($program) ?></a></li>
        <?php } ?>
      </ul>
    <?php } ?>
  </div>

<?php }

get_footer();

?>