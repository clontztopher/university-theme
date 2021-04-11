<!DOCTYPE html>
<html <?php language_attributes() ?>>

<head>
  <meta charset="<?= bloginfo('charset') ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  /**
   * Allow WordPress to control the head section
   */
  wp_head();
  ?>
</head>

<!-- body_class adds contextual classes to the body of  -->
<!-- each page that help with identification of different  -->
<!-- attributes in JS code. -->

<body <?= body_class() ?>>
  <header class="site-header">
    <div class="container">
      <h1 class="school-logo-text float-left">
        <a href="<?= site_url() ?>"><strong>Fictional</strong> University</a>
      </h1>
      <a href="<?php echo site_url('search') ?>" class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
      <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
      <div class="site-header__menu group">
        <nav class="main-navigation">

          <!-- Add menu location registered in functions.php -->
          <?php wp_nav_menu(array(
            'theme_location' => 'headerMenuLocation'
          )) ?>

        </nav>
        <div class="site-header__util">
          <a href="<?= site_url() ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
          <a href="<?= site_url() ?>" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
          <a href="<?php echo site_url('search') ?>" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
        </div>
      </div>
    </div>
  </header>