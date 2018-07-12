<?php get_header(); ?>

<main id="main" class="archive archive-post" template="archive-post">
  <?php
  get_template_part('template-parts/banner');
  if (have_posts()) : ?>
    <section>
      <div class="container row infinite-scroll">
        <?php
        while (have_posts()) : the_post();
          get_template_part('template-parts/archive', get_post_type());
        endwhile; ?>
      </div>
    </section>
  <?php
  endif; ?>
</main>

<?php get_footer();
