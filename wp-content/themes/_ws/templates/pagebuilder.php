<?php
/* Template Name: Page Builder */

get_header(); ?>

<main id="main" template="pagebuilder">
  <?php
  get_template_part('template-parts/banner');
  if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class('page page-content'); ?>>
      <?= do_shortcode(preg_replace(array('/\[\[/', '/\]\]/'), array('[', ']'), get_the_content())); ?>
    </div>
  <?php
  endwhile; endif; ?>
</main>

<?php get_footer(); ?>
