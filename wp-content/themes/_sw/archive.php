<?php get_header(); ?>

<main id="main" class="archive archive-<?= get_post_type(); ?>">
  <?php
  get_template_part('template-parts/banner');
  if (have_posts()) : ?>
    <section>
      <div class="container row">
        <?php
        while (have_posts()) : the_post();
          get_template_part('template-parts/archive');
          if (comments_open() || get_comments_number()) {
            comments_template();
          }
        endwhile; ?>
      </div>
    </section>
    <div id="post-navigation">
      <div class="container row">
        <div class="col-xs-12">
          <?php the_posts_navigation(); ?>
        </div>
      </div>
    </div>
  <?php
  endif; ?>
</main>

<?php get_footer(); ?>
