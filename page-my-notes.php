<?php

if (!is_user_logged_in()) {
  wp_redirect(site_url('/'));
  exit;
}

get_header();
pageBanner();
?>


<div class="container container--narrow page-section">

  <div class="create-note">
    <h2 class="headline headline--medium">Create New Note</h2>
    <input type="text" class="new-note-title" placeholder="Title">
    <textarea name="" id="" cols="30" rows="10" class="new-note-body" placeholder="Your note here..."></textarea>
    <span class="submit-note">Create Note</span>
  </div>

  <ul class="min-list link-list" id="my-notes">
    <?php
    $myNotes = new WP_Query(array(
      'post_type' => 'note',
      'posts_per_page' => -1,
      /**
       * Make sure the user can only see their own notes
       */
      'author' => get_current_user_id()
    ));

    while ($myNotes->have_posts()) {
      $myNotes->the_post();
    ?>
      <li data-id="<?php the_ID() ?>">
        <input readonly class="note-title-field" type="text" value="<?php echo str_replace("Private: ", "", esc_attr(get_the_title())) ?>">
        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
        <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
        <textarea readonly class="note-body-field" name="" id="" cols="30" rows="10"><?php echo esc_attr(wp_strip_all_tags(get_the_content())) ?></textarea>
        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
      </li>
    <?php
    }
    ?>
  </ul>

</div>

<?php

get_footer();

?>