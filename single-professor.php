<?php

get_header();

while (have_posts()) {
  the_post();
  pageBanner();
?>


  <div class="container container--narrow page-section">

    <div class="generic-content">
      <div class="row group">
        <div class="one-third"><?php the_post_thumbnail('professorPortrait') ?></div>
        <div class="two-thirds"><?php the_content() ?></div>
      </div>
    </div>
    <?php
    /**
     * Get a list of programs taught by this professor by fetching
     * the professor's related_programs list.
     * 
     * In this case get_field returns an array of Post objects
     */
    $relatedPrograms = get_field('related_programs');

    if ($relatedPrograms) {
    ?>
      <hr class="section-break">
      <h2 class="headline headline--medium">Programs Taught</h2>
      <ul class="link-list min-list">
        <?php
        /**
         * Loop through the post objects and extract the data
         * using WordPress functions and passing in the object.
         */
        foreach ($relatedPrograms as $program) { ?>
          <li>
            <a href="<?php echo get_the_permalink($program) ?>">
              <?php echo get_the_title($program) ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    <?php } ?>
  </div>

<?php }

get_footer();

?>