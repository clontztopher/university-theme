<?php

/**
 * Campus Archive Pages
 */

get_header();

pageBanner(array(
  'title' => 'Our Campuses',
  'subtitle' => 'We have several convenient locations.'
));
?>

<div class="container container--narrow page-section">
  <div class="acf-map">
    <?php
    while (have_posts()) {
      /**
       * the_post prepares all of the variables needed to 
       * supply the other template functions with the correct
       * data.
       */
      the_post();

      $map_location = get_field('map_location');
    ?>
      <div class="marker" data-lat="<?php echo $map_location['lat'] ?>" data-lng="<?php echo $map_location['lng'] ?>">
        <h3><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
        <?php echo $map_location['address'] ?>
      </div>
    <?php } ?>
  </div>
</div>

<?php

get_footer();

?>