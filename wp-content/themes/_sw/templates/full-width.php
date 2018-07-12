<?php
/* Template Name: Full Width */

get_header(); ?>

<main id="main" template="full-width">
  <?php
  if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class('page page-content'); ?>>
      <?php the_content(); ?>
    </div>
  <?php
  endwhile; endif; ?>
</main>

<?php get_footer(); ?>
