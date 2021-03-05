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
        $eventsArchiveLink = get_post_type_archive_link('program');
        ?>
        <a class="metabox__blog-home-link" href="<?= $eventsArchiveLink ?>">
          <i class="fa fa-home" aria-hidden="true"></i> All Programs
        </a>
        <span class="metabox__main"><?php the_title() ?></span>
      </p>
    </div>
    <div class="generic-content">
      <?php the_content() ?>
    </div>


    <?php
    /**
     * Query the Professors post type to find 
     * ones related to the current program.
     */
    $relatedProfessors = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'professor',
      'orderby' => 'title',
      'order' => 'ASC',
      'meta_query' => array(
        /**
         * Only get professors that have related_programs 
         * like the current program
         */
        array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          /**
           * Related programs will be serialized array 
           * with quoted values so the target search 
           * value needs to have double quotes
           */
          'value' => '"' . get_the_ID() . '"'
        )
      )
    ));

    if ($relatedProfessors->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';
      echo '<ul class="professor-cards">';
      while ($relatedProfessors->have_posts()) {
        $relatedProfessors->the_post(); ?>

        <li class="professor-card__list-item">
          <a class="professor-card" href="<?php the_permalink() ?>">
            <img src="<?php the_post_thumbnail_url('professorLandscape') ?>" alt="" class="professor-card__image">
            <span class="professor-card__name"><?php the_title() ?></span>
          </a>
        </li>

    <?php }
      echo '</ul>';
    }

    /**
     * RESET GLOBAL POST DATA BETWEEN QUERIES
     */
    wp_reset_postdata();


    /**
     * Query the Events post type to find 
     * ones related to the current program.
     */
    $today = date('Ymd');
    $programRelatedEvents = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'event',
      'orderby' => 'meta_value_num', // Says we're ordering by a non-core value
      'meta_key' => 'event_date',   // Since orderby is meta_value, say which value
      'order' => 'ASC',              // default is DESC
      /**
         * Only get events that are in the future
         */
      'meta_query' => array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        ),
        /**
         * Only get events that have related_programs 
         * like the current program
         */
        array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          /**
           * Related programs will be serialized array 
           * with quoted values so the target search 
           * value needs to have double quotes
           */
          'value' => '"' . get_the_ID() . '"'
        )
      )
    ));

    if ($programRelatedEvents->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';
      while ($programRelatedEvents->have_posts()) {
        $programRelatedEvents->the_post();
        get_template_part('template-parts/event');
      }
    }
    /**
     * REMEMBER TO RESET DATA BETWEEN QUERIES
     */
    wp_reset_postdata(); ?>
  </div>

<?php }

get_footer();

?>