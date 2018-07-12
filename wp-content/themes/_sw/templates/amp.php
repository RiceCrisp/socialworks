<?php
get_header(); ?>

  <main id="main" class="single">
    <?php
    if (have_posts()) : while (have_posts()) : the_post();
      get_template_part('template-parts/amp', get_post_type()); ?>
      <?php
      // If comments are open or we have at least one comment, load up the comment template.
      if (comments_open() || get_comments_number()) :
        comments_template();
      endif;
    endwhile; endif; ?>
  </main>

<?php
get_footer(); ?>
