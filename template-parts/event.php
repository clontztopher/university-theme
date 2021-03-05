<div class="event-summary">
  <a class="event-summary__date t-center" href="#">
    <?php
    /**
     * Advanced Custom Fields has functions, 'the_field' and 'get_field'. 
     * Use get_field to retrieve date which was manually specified in ACF
     * plugin to return YYYYMMDD format to use for DateTime instance.
     */
    $eventDate = new DateTime(get_field('event_date'));
    $eventMonth = $eventDate->format('M');
    $eventDay = $eventDate->format('d');
    ?>
    <span class="event-summary__month"><?php echo $eventMonth ?></span>
    <span class="event-summary__day"><?php echo $eventDay ?></span>
  </a>
  <div class="event-summary__content">
    <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h5>
    <?php
    /**
     * If there is an excerpt for the event, print that
     * as a summary. Else, get the trimmed content.
     */
    if (has_excerpt()) {
      $excerpt = get_the_excerpt();
    } else {
      $excerpt = wp_trim_words(get_the_content(), 18);
    }
    ?>
    <p><?php echo $excerpt ?> <a href="<?php the_permalink() ?>" class="nu gray">Learn more</a></p>
  </div>
</div>