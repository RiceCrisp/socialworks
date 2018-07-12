<?php get_header(); ?>

<main id="main" class="search">
  <?php
  if (have_posts()) :
    get_template_part('template-parts/banner');
    global $wp_query; ?>
    <section>
      <div class="container row <?= $wp_query->max_num_pages > 1 ? 'infinite-scroll-btn' : ''; ?>" type="search">
        <?php
        while (have_posts()) : the_post();
          get_template_part('template-parts/archive', 'search');
        endwhile; ?>
      </div>
    </section>
  <?php
  else : ?>
    <div class="no-results">
      <?php
      get_template_part('template-parts/banner');
      echo do_shortcode('[text]<p>Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>' . get_search_form(false) . '[/text]'); ?>
    </div>
  <?php
  endif; ?>
</main>

<?php get_footer(); ?>
