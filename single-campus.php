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
        $eventsArchiveLink = get_post_type_archive_link('campus');
        ?>
        <a class="metabox__blog-home-link" href="<?= $eventsArchiveLink ?>">
          <i class="fa fa-home" aria-hidden="true"></i> All Campuses
        </a>
        <span class="metabox__main"><?php the_title() ?></span>
      </p>
    </div>
    <div class="generic-content">
      <?php the_content() ?>
    </div>

    <div class="acf-map">
      <?php $map_location = get_field('map_location'); ?>
      <div class="marker" data-lat="<?php echo $map_location['lat'] ?>" data-lng="<?php echo $map_location['lng'] ?>">
        <h3><?php the_title() ?></h3>
        <?php echo $map_location['address'] ?>
      </div>
    </div>


    <?php
    /**
     * Query the programs post type to find 
     * ones related to the current program.
     */
    $relatedPrograms = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'program',
      'orderby' => 'title',
      'order' => 'ASC',
      'meta_query' => array(
        /**
         * Only get programs that have related_campuses 
         * like the current program
         */
        array(
          'key' => 'related_campuses',
          'compare' => 'LIKE',
          /**
           * Related campuses will be serialized array 
           * with quoted values so the target search 
           * value needs to have double quotes
           */
          'value' => '"' . get_the_ID() . '"'
        )
      )
    ));

    if ($relatedPrograms->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Programs Available at this Campus</h2>';
      echo '<ul class="professor-cards">';
      while ($relatedPrograms->have_posts()) {
        $relatedPrograms->the_post(); ?>

        <li>
          <a href="<?php the_permalink() ?>">
            <?php the_title() ?>
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
     * ones related to the current campus.
     */
    $today = date('Ymd');
    $campusRelatedEvents = new WP_Query(array(
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
          'key' => 'related_campuses',
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

    if ($campusRelatedEvents->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Upcoming Events at ' . get_the_title() . '</h2>';
      while ($campusRelatedEvents->have_posts()) {
        $campusRelatedEvents->the_post();
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