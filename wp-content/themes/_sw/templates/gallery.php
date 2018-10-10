<?php
/* Template Name: Gallery */

get_header(); ?>

<main id="main" template="gallery-page">
  <?php
  get_template_part('template-parts/banner');
  $s = isset($_GET['search']) ? $_GET['search'] : '';
  $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
  $p_type = get_post_meta(get_the_ID(), '_gallery-type', true);
  $tax = get_post_meta(get_the_ID(), '_gallery-tax', true); ?>
  <form id="gallery-filters" action="#filtered" method="GET">
    <div id="filtered"></div>
    <div class="container row">
      <div class="col-lg-4 col-md-6">
        <?php
        if ($tax == 'year') : ?>
          <label for="filter">Year</label>
          <select id="filter" name="filter">
            <option value="" <?= !$filter ? 'selected' : ''; ?>>All</option>
            <?php
            $years = wp_get_archives(array('type'=>'yearly', 'format'=>'custom', 'echo'=>0, 'post_type'=>$p_type));
            $years = explode('</a>', $years);
            array_pop($years);
            foreach ($years as $i=>$year) :
              $year = substr($year, -4); ?>
              <option value="<?= $year; ?>" <?= $filter==$year ? 'selected' : ''; ?>><?= $year; ?></option>
            <?php
            endforeach; ?>
          </select>
        <?php
        elseif ($tax == 'event') : ?>
          <label for="filter">Timeline</label>
          <select id="filter" name="filter">
            <option value="upcoming" <?= !$filter || $filter=='upcoming' ? 'selected' : ''; ?>>Upcoming</option>
            <option value="past" <?= $filter=='past' ? 'selected' : ''; ?>>Past</option>
          </select>
        <?php
        else:
          $terms = get_terms(array('taxonomy' => $tax)); ?>
          <label for="filter"><?= get_taxonomy($tax)->label; ?></label>
          <select id="filter" name="filter">
            <option value="" <?= !$filter ? 'selected' : ''; ?>>No Filter</option>
            <?php
            foreach ($terms as $i=>$term) : ?>
              <option value="<?= $term->slug; ?>" <?= $filter==$term->slug ? 'selected' : ''; ?>><?= $term->name; ?></option>
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
  </form>
  <section>
    <?php
    $args = array(
      'post_type' => $p_type,
      'post_status' => 'publish',
      'paged' => $paged,
      's' => $s
    );
    if ($p_type == 'event') {
      $args['orderby'] = 'meta_value_num';
      $args['meta_key'] = '_event-sortable-start';
    }
    if ($tax == 'year') {
      $args['year'] = $filter;
    }
    else if ($tax == 'event') {
      $args['meta_query'] = array(
        'relation' => 'OR'
      );
      if (!$filter || $filter=='upcoming') {
        array_push($args['meta_query'],
          array(
            'key' => '_event-sortable-start',
            'value' => date('YmdHi'),
            'compare' => '>='
          )
        );
        $args['order'] = 'ASC';
      }
      if ($filter=='past') {
        array_push($args['meta_query'],
          array(
            'key' => '_event-sortable-start',
            'value' => date('YmdHi'),
            'compare' => '<'
          )
        );
      }
    }
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
