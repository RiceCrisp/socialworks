<?php
/* Template Name: Initiative Overview */

get_header(); ?>

<main id="main" template="initiative-overview">
  <?php
  get_template_part('template-parts/banner'); ?>
  <section>
    <div class="container row">
      <?php
      $inits = get_posts(array(
        'post_type' => 'page',
        'post_parent' => get_page_by_title('Initiatives')->ID,
        'post_status' => 'publish',
        'posts_per_page' => -1
      ));
      foreach ($inits as $init) : ?>
        <div class="col-md-6 card-container">
          <?php
          echo do_shortcode('[card img="' . $init->ID . '"]
            <h3>' . $init->post_title . '</h3>
            <p>' . _sw_excerpt($init->ID) . '</p>
            <div>
              <a href="' . get_permalink($init->ID) . '" class="btn">Learn More</a>
            </div>
          [/card]'); ?>
        </div>
      <?php
      endforeach; ?>
    </div>
  </section>
  <?php
  if (have_posts()) : while (have_posts()) : the_post(); if (get_the_content()) : ?>
    <section>
      <div class="container row">
        <div class="col-xl-8 col-xl-offset-2 col-lg-10 col-lg-offset-1 center">
          <?php the_content(); ?>
        </div>
      </div>
    </section>
  <?php
  endif; endwhile; endif; ?>
</main>

<?php get_footer(); ?>
