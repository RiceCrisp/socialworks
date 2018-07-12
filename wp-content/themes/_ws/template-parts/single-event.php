<article id="post-<?php the_ID(); ?>" <?php post_class('event-single'); ?>>
  <?php
  get_template_part('template-parts/banner');
  echo do_shortcode('[text]<h3 class="event-start-time">Start: ' . get_post_meta(get_the_ID(), '_event-start-date', true) . ' @ ' . get_post_meta(get_the_ID(), '_event-start-time', true) . '</h3><h3 class="event-end-time">End: ' . get_post_meta(get_the_ID(), '_event-end-date', true) . ' @ ' . get_post_meta(get_the_ID(), '_event-end-time', true) . '</h3>' . get_the_content() . '[/text]'); ?>
  <?= do_shortcode('[google_map location="' . get_post_meta(get_the_ID(), '_event-location', true) . '"]'); ?>
</article>
