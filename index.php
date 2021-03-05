<?php

/**
 * The index.php page is the last fallback for WordPress
 * and is usually only used for the blog archive.
 */

get_header();

pageBanner(array(
  'title' => get_the_archive_title(),
  'subtitle' => 'This page is brought to you by <code>index.php</code>'
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