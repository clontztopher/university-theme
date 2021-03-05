<?php

/**
 * Fallback archive page for anything 
 * that doesn't have a custom template
 */

get_header();

/**
 * Get the specific archive title and description since this 
 * is kind of the fallback archive for Authors, Categories, 
 * and other taxonomies
 */
pageBanner(array(
  'title' => get_the_archive_title(),
  'subtitle' => get_the_archive_description() . ' from <code>archive.php</code>'
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
    the_post(); ?>

    <div class="post-item">
      <h2 class="headline headline--medium headline--post-title">
        <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
      </h2>
      <div class="metabox">
        <p>Posted by <?php the_author_posts_link() ?> on <?php the_time('n.j.Y') ?> in <?php echo get_the_category_list(', ') ?></p>
      </div>
      <div class="generic-content">
        <?php the_excerpt() ?>
        <p><a href="<?php the_permalink() ?>" class="btn btn--blue">Continue Reading</a></p>
      </div>
    </div>

  <?php } ?>

  <?php
  echo paginate_links();
  ?>

</div>

<?php

get_footer();

?>