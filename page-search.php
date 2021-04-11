<?php

/**
 * This template is the "Search" page (not the search results page, which is search.php)
 * The template contains the search form that users will visit if they have Javascript
 * disabled
 */

get_header();
pageBanner();
?>


<div class="container container--narrow page-section">

  <?php
  /**
   * Check to see if the current page is a parent page or a child page
   * by useing wp_get_post_parent_id and passing in the current page ID.
   */

  $the_ID = get_the_ID();
  $parent_id = wp_get_post_parent_id($the_ID);
  if ($parent_id) { ?>
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?= get_permalink($parent_id) ?>">
          <i class="fa fa-home" aria-hidden="true"></i> Back to <?= get_the_title($parent_id) ?>
        </a>
        <span class="metabox__main"><?= the_title() ?></span>
      </p>
    </div>
  <?php } ?>

  <?php
  /**
   * get_pages and wp_list_pages almost identical except wp_list_pages
   * prints a menu and get_pages just returns a list of pages.
   * 
   * get_permalink and get_the_title can accept an ID and will return
   * the link or title for the current page if ID is 0. 
   * 
   * Below, check whether current page has children or has parent. If
   * neither parent nor children there is no need to show menu.
   */

  $testArray = get_pages(array(
    'child_of' => $the_ID
  ));
  if ($parent_id or $testArray) { ?>

    <div class="page-links">
      <h2 class="page-links__title">
        <a href="<?= get_permalink($parent_id) ?>">
          <?= get_the_title($parent_id) ?>
        </a>
      </h2>
      <ul class="min-list">
        <?php

        if ($parent_id) {
          $findChildrenOf = $parent_id;
        } else {
          $findChildrenOf = $the_ID;
        }

        wp_list_pages(array(
          'title_li' => NULL,
          'child_of' => $findChildrenOf
        ));
        ?>
      </ul>
    </div>

  <?php } ?>

  <div class="generic-content">
    <?php get_search_form() ?>
  </div>

</div>

<?php

get_footer();

?>