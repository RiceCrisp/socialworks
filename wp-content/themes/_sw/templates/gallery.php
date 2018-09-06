<?php
/* Template Name: Gallery */

get_header(); ?>

<main id="main" template="gallery-page">
  <?php
  get_template_part('template-parts/banner');
  $s = isset($_GET['search']) ? $_GET['search'] : '';
  $filters = isset($_GET['filters']) ? $_GET['filters'] : array();
  $p_type = get_post_meta(get_the_ID(), '_gallery-type', true); ?>
  <section>
    <?php
    $args = array(
      'post_type' => $p_type,
      'post_status' => 'publish',
      'paged' => $paged,
      's' => $s
    );
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $loop = new WP_Query($args);
    if ($loop->have_posts()) : ?>
      <div class="container row <?= $loop->max_num_pages > 1 ? 'infinite-scroll-btn' : ''; ?>">
        <input class="loop-var" type="hidden" value="<?= htmlentities(json_encode($loop)); ?>" />
        <?php
        while ($loop->have_posts()) : $loop->the_post();
          get_template_part('template-parts/archive', get_post_type());
        endwhile; ?>
      </div>
    <?php
    else: ?>
      <div class="container row">
        <div class="col-xs-12 center">
          <p>No results found.</p>
        </div>
      </div>
    <?php
    endif; wp_reset_postdata(); ?>
  </section>
</main>

<?php get_footer(); ?>
