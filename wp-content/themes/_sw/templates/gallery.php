<?php
/* Template Name: Gallery */

get_header(); ?>

<main id="main" template="gallery-page">
  <?php
  get_template_part('template-parts/banner');
  $s = isset($_GET['search']) ? $_GET['search'] : '';
  $filters = isset($_GET['filters']) ? $_GET['filters'] : array();
  $p_type = get_post_meta(get_the_ID(), '_gallery-type', true);
  $tax = get_post_meta(get_the_ID(), '_gallery-tax', true); ?>
  <!-- <form id="gallery-filters" action="" method="GET">
    <div class="container row">
      <div class="col-lg-4 col-md-6">
        <?php
        if ($tax == 'year') : ?>
          <label for="filter">Year</label>
          <select id="filter" name="filter">
            <option value="">No Filter</option>
            <?php wp_get_archives(array('type'=>'yearly', 'format'=>'option', 'post_type'=>$p_type)); ?>
          </select>
        <?php
        elseif ($tax == 'event') : ?>
          <label for="filter">Timeline</label>
          <select id="filter" name="filter">
            <option value="upcoming">Upcoming</option>
            <option value="past">Past</option>
          </select>
        <?php
        else:
          $terms = get_terms(array('taxonomy' => $tax)); ?>
          <label for="filter"><?= get_taxonomy($tax)->label; ?></label>
          <select id="filter" name="filter">
            <option value="">No Filter</option>
            <?php
            foreach ($terms as $i=>$term) : ?>
              <option value="<?= $term->slug; ?>"><?= $term->name; ?></option>
            <?php
            endforeach; ?>
          </select>
        <?php
        endif; ?>
      </div>
      <div class="col-lg-4 col-lg-offset-4 col-md-6">
        <label for="search">Search</label>
        <input id="search" name="search" type="text" value="<?= $s; ?>" />
      </div>
    </div>
  </form> -->
  <section>
    <?php
    if ($p_type == 'event') {
      $args['orderby'] = 'meta_value_num';
      $args['meta_key'] = '_event-sortable-start';
    }
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
