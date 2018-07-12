<?php get_header(); ?>

<main id="main" class="page">
  <?php
  get_template_part('template-parts/banner');
  if (have_posts()) : while (have_posts()) : the_post();
    get_template_part('template-parts/page');
  endwhile; endif; ?>
</main>

<?php get_footer(); ?>
