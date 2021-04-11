<?php

require get_theme_file_path('inc/search-route.php');

define('GOOGLE_MAPS_KEY', 'AIzaSyABW3SmLOMqk_vIpi7D0kBlfLoi5ubIZH4');

/**
 * Use 'wp_enqueue_scripts' to add CSS and JS files to theme.
 * 
 * wp_enqueue_script function has an argument for whether 
 * to include in header or end of body tag.
 */
function university_files()
{
  wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_script('google-map', '//maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_KEY, NULL, '1.0', true);

  if (strstr($_SERVER['SERVER_NAME'], 'localhost')) {
    wp_enqueue_script('university-javascript', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
  } else {
    wp_enqueue_script('vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.8c97d901916ad616a264.js'), NULL, '1.0', true);
    wp_enqueue_script('university-javascript', get_theme_file_uri('/bundled-assets/scripts.bc49dbb23afb98cfc0f7.js'), NULL, '1.0', true);
    wp_enqueue_style('our_main_styles', get_theme_file_uri('/bundled-assets/styles.bc49dbb23afb98cfc0f7.css'));
  }

  /**
   * Adds Javascript object to the page with specified 
   * script and contains any information entered.
   */
  wp_localize_script('university-javascript', 'universityData', array(
    'root_url' => get_site_url()
  ));
}
add_action('wp_enqueue_scripts', 'university_files');

/**
 * Use 'after_setup_theme' hook to add theme support and register menus
 */
function university_features()
{
  add_theme_support('title-tag');
  // Enable featured images for blog post types
  add_theme_support('post-thumbnails');
  // Add image sizes for creation on upload
  add_image_size('professorLandscape', 400, 260, true);
  add_image_size('professorPortrait', 480, 650, true);
  add_image_size('pageBanner', 1500, 350, true);

  register_nav_menu('headerMenuLocation', 'Header Menu Location');
  register_nav_menu('footerLocationOne', 'Footer Location 1');
  register_nav_menu('footerLocationTwo', 'Footer Location 2');
}
add_action('after_setup_theme', 'university_features');

/**
 * This code customizes the query for the Events Archive page only by hooking
 * into the pre_get_posts event and modifying the query object if the current
 * page is the Events archive page. Orders by event date, filters for only 
 * future dates, and orders ascending.
 */
function university_adjust_queries($query)
{
  /**
   * Manipulate Event Archive Query
   */
  if (
    !is_admin() and // Not admin
    is_post_type_archive('event') and // Event post type
    $query->is_main_query() // Is main page default query and not custom query
  ) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      'key' => 'event_date',
      'compare' => '>=',
      'value' => $today,
      'type' => 'numeric'
    ));
  }

  /**
   * Manipulate Program Archive Query
   */
  if (
    !is_admin() and // Not admin
    is_post_type_archive('program') and // Program post type
    $query->is_main_query() // Is main page default query and not custom query
  ) {
    $query->set('posts_per_page', -1);
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
  }

  /**
   * Manipulate Campus Archive query to include all campuses
   */
  if (
    !is_admin() and
    is_post_type_archive('campuse') and
    $query->is_main_query()
  ) {
    $query->set('posts_per_page', -1);
  }
}
add_action('pre_get_posts', 'university_adjust_queries');


function pageBanner($args = NULL)
{
  if (!$args['title']) {
    $args['title'] = get_the_title();
  }

  if (!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!$args['photo'] and !is_archive() and !is_home()) {
    if (get_field('page_banner_background_image')) {
      /**
       * Get custom field data for page banner and retrieve 
       * the cropped 'pageBanner' version of the image
       */
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  } else {
    $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
  }

?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?= $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle'] ?></p>
      </div>
    </div>
  </div>
<?php } // end of banner function 

/**
 * Attach the Google API key from GCP to they 'key' 
 * field of the $args array and return the updated array.
 */
function universityMapKey($api)
{
  $api['key'] = GOOGLE_MAPS_KEY;
  return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');

/**
 * Register custom REST API fields.
 */
function university_custom_rest()
{
  register_rest_field('post', 'authorName', array(
    'get_callback' => function () {
      return get_the_author();
    }
  ));
}
add_action('rest_api_init', 'university_custom_rest');

?>