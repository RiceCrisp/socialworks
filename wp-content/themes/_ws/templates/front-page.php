<?php
/* Template Name: Front Page */

get_header(); ?>

<main id="main" template="front-page">
  <?php
  get_template_part('template-parts/banner');
  if (have_posts()) : while (have_posts()) : the_post();
    get_template_part('template-parts/content', get_post_type());
  endwhile; endif; ?>
</main>

<?php get_footer(); ?>
