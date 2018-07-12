<article id="post-<?php the_ID(); ?>" <?php post_class('post-single'); ?>>
  <?php
  get_template_part('template-parts/banner');
  echo do_shortcode('[text]' . get_the_content() . '[/text]'); ?>
</article>
