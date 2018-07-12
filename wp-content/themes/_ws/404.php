<?php get_header(); ?>

<main class="page error">
  <?php
  get_template_part('template-parts/banner');
  echo do_shortcode('[text]<p>It seems we can\'t find what you\'re looking for. Perhaps searching can help.</p>' . get_search_form(false) . '[/text]'); ?>
</main>

<?php get_footer(); ?>
