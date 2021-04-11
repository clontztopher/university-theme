<?php

/**
 * Callback for search endpoint.
 * 
 * @param Array $data - array of query parameters passed to the route.
 * @return Array - returned array is converted to JSON by WordPress.
 */
function universitySearchResults($data)
{
  $result = array(
    'generalInfo' => array(),
    'professors' => array(),
    'events' => array(),
    'campuses' => array(),
    'programs' => array()
  );

  $query = new WP_Query(array(
    'post_type' => array('post', 'page', 'professor', 'event', 'campus', 'program'),
    's' => sanitize_text_field($data['term'])
  ));

  while ($query->have_posts()) {
    $query->the_post();

    $post_type = get_post_type();

    /**
     * Start with basic data for any match and 
     * add post type-specific data to each
     */
    $post_data = array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
      'post_type' => get_post_type()
    );

    if ($post_type == 'post' || $post_type == 'page') {
      $post_data['author_name'] = get_the_author();
      array_push($result['generalInfo'], $post_data);
    }

    if ($post_type == 'professor') {
      $post_data['image'] = get_the_post_thumbnail_url(0, 'professorLandscape');
      array_push($result['professors'], $post_data);
    }

    if ($post_type == 'event') {
      $event_date = new DateTime(get_field('event_date'));
      $post_data['month'] = $event_date->format('M');
      $post_data['day'] = $event_date->format('d');
      if (has_excerpt()) {
        $excerpt = get_the_excerpt();
      } else {
        $excerpt = wp_trim_words(get_the_content(), 18);
      }
      $post_data['excerpt'] = $excerpt;
      array_push($result['events'], $post_data);
    }

    if ($post_type == 'campus') {
      array_push($result['campuses'], $post_data);
    }

    if ($post_type == 'program') {

      /**
       * For programs, we will also want the related
       * campuses. Each program has one or more campuses.
       */
      $relatedCampuses = get_field('related_campuses');

      $result['test'] = $relatedCampuses;

      if ($relatedCampuses) {
        foreach ($relatedCampuses as $campus) {
          array_push($result['campuses'], array(
            'title' => get_the_title($campus),
            'permalink' => get_the_permalink($campus)
          ));
        }
      }

      $post_data['id'] = get_the_ID();
      array_push($result['programs'], $post_data);
    }
  }

  /**
   * For professors of related programs, create a meta query that 
   * contains all of the program IDs that were found in the search.
   * Only run this query if programs exist or query will return all
   * professors.
   */

  if ($result['programs']) {

    $relatedProgramMetaQuery = array_map(function ($program) {
      return array(
        'key' => 'related_programs',
        'compare' => 'LIKE',
        'value' => '"' . $program['id'] . '"'
      );
    }, $result['programs']);

    $relatedProgramMetaQuery['relation'] = 'OR';

    $programRelationshipQuery = new WP_Query(array(
      'post_type' => array('professor', 'event'),
      'meta_query' => $relatedProgramMetaQuery
    ));

    while ($programRelationshipQuery->have_posts()) {
      $programRelationshipQuery->the_post();
      $post_type = get_post_type();
      $post_data = array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'post_type' => get_post_type()
      );
      if ($post_type == 'professor') {
        $post_data['image'] = get_the_post_thumbnail_url(0, 'professorLandscape');
        array_push($result['professors'], $post_data);
      }
      if ($post_type == 'event') {
        $event_date = new DateTime(get_field('event_date'));
        $post_data['month'] = $event_date->format('M');
        $post_data['day'] = $event_date->format('d');
        if (has_excerpt()) {
          $excerpt = get_the_excerpt();
        } else {
          $excerpt = wp_trim_words(get_the_content(), 18);
        }
        $post_data['excerpt'] = $excerpt;
        array_push($result['events'], $post_data);
      }
    }

    // de-dupe professors array
    // array_unique adds associative index so array_values flattens it back out
    $result['professors'] = array_values(array_unique($result['professors'], SORT_REGULAR));
    $result['events'] = array_values(array_unique($result['events'], SORT_REGULAR));
  }

  /**
   * WordPress automatically converts PHP arrays to JSON
   */
  return $result;
}

/**
 * Register the endpoint with WordPress REST API
 */
function university_register_search()
{
  /**
   * Arguments
   * 1. Namespace for route
   * 2. Name for route
   * 3. Options array
   */
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE, // Basically 'GET'
    'callback' => 'universitySearchResults'
  ));
}
add_action('rest_api_init', 'university_register_search');
