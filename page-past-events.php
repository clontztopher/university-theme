<?php

/**
     * This page will be used as the /blog list page because a 
     * 'front-page.php' template has been added to the directory.
     */

get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'Events that have come and gone.'
));
?>

<div class="container container--narrow page-section">

  <?php

  /**
   * Custome query that selects custom post type, 'event',
   * and orders by this event using the special query
   * parameters
   */
  $today = date('Ymd');
  $pastEvents = new WP_Query(array(
    'posts_per_page' => 1,
    /**
     * Signifies that this query is paged and 
     * says which page we're on currently.
     * 
     * get_query_var returns specified URL part
     */
    'paged' => get_query_var('page', get_query_var('paged')),
    'post_type' => 'event',
    'orderby' => 'meta_value_num', // Says we're ordering by a non-core value
    'meta_key' => 'event_date',   // Since orderby is meta_value, say which value
    'order' => 'DESC',
    /**
     * Only get events that are in the past
     */
    'meta_query' => array(
      array(
        'key' => 'event_date',
        'compare' => '<=',
        'value' => $today,
        'type' => 'numeric'
      )
    )
  ));

  while ($pastEvents->have_posts()) {
    /**
     * the_post prepares all of the variables needed to 
     * supply the other template functions with the correct
     * data.
     */
    $pastEvents->the_post();

    get_template_part('template-parts/event');
  }

  /**
   * Paginated links
   */
  echo paginate_links(array(
    'total' => $pastEvents->max_num_pages
  ));
  ?>

</div>

<?php

get_footer();

?>