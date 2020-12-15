<?php
function _ws_block_archive($a = []) {
  $a['className'] = 'wp-block-ws-archive ' . ($a['className'] ?? '');
  $filters = $a['filters'] ?? [];
  $postTypes = $a['postTypes'] ?? [];
  $numPosts = $a['numPosts'] ?? get_option('posts_per_page');
  $numPosts = isset($a['allPosts']) && $a['allPosts'] ? -1 : $numPosts;
  ob_start();
    _ws_archive_build_filters($filters, $postTypes, $numPosts); ?>
    <div class="archive-results row <?= count($postTypes) > 1 ? 'default-row' : $postTypes[0] . '-row'; ?>"></div>
  <?php
  return _ws_block_wrapping($a, ob_get_clean(), 'div');
}

function _ws_archive_build_filters($filters, $postTypes, $numPosts) {
  $years = '';
  foreach ($postTypes as $postType) {
    $years .= wp_get_archives([
      'type' => 'yearly',
      'format' => 'custom',
      'echo' => 0,
      'post_type' => $postType
    ]);
  }
  $years = explode('</a>', $years);
  array_pop($years);
  $years = array_map(function($year) {
    return substr($year, -4);
  }, $years);
  $years = array_unique($years);
  ?>
  <form class="archive-filters <?= 'archive-filters-' . count($filters); ?>" action="#filtered" method="GET">
    <input type="hidden" name="post_type" value="<?= implode(',', $postTypes); ?>" />
    <input type="hidden" name="posts_per_page" value="<?= $numPosts; ?>" />
    <div class="row">
      <?php
      foreach ($filters as $tax) :
        if ($tax === 'year') : ?>
          <div class="col-xs-12 filter">
            <label for="archive-filter-year"><?= __('Filter by Year', '_ws'); ?></label>
            <select id="archive-filter-year" name="year">
              <option value="">All Years</option>
              <?php
              $years = '';
              foreach ($postTypes as $postType) {
                $years .= wp_get_archives([
                  'type' => 'yearly',
                  'format' => 'custom',
                  'echo' => 0,
                  'post_type' => $postType
                ]);
              }
              $years = explode('</a>', $years);
              array_pop($years);
              $years = array_map(function($year) {
                return substr($year, -4);
              }, $years);
              $years = array_unique($years);
              foreach ($years as $i=>$year) : ?>
                <option value="<?= $year; ?>" <?= isset($_GET['filter-year']) && $_GET['filter-year'] === $year ? 'selected' : ''; ?>><?= $year; ?></option>
                <?php
              endforeach; ?>
            </select>
          </div>
          <?php
        elseif ($tax === 'timeline') : ?>
          <div class="col-xs-12 filter">
            <label for="archive-filter-timeline" class="screen-reader-text"><?= __('Past & Upcoming', '_ws'); ?></label>
            <select id="archive-filter-timeline" name="filter-timeline">
              <option value="">Select Filter</option>
              <option value="upcoming" <?= isset($_GET['filter-timeline']) && $_GET['filter-timeline'] === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
              <option value="past" <?= isset($_GET['filter-timeline']) && $_GET['filter-timeline'] === 'past' ? 'selected' : ''; ?>>Past</option>
            </select>
          </div>
          <?php
        elseif ($tax === 'search') : ?>
          <div class="col-xs-12 filter relative">
            <label for="archive-filter-search"><?= __('Search', '_ws'); ?></label>
            <input id="archive-filter-search" name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>" type="text" />
            <?= do_shortcode('[svg id="search" class="search-icon"]'); ?>
          </div>
          <?php
        else :
          $tax = get_taxonomy($tax); ?>
          <div class="col-xs-12 filter">
            <label for="archive-filter-<?= $tax->name; ?>">Filter by <?= $tax->labels->singular_name; ?></label>
            <select id="archive-filter-<?= $tax->name; ?>" name="filter-<?= $tax->name; ?>">
              <option value="">All <?= $tax->label; ?></option>
              <?php
              $terms = get_terms(array('taxonomy' => $tax->name));
              foreach ($terms as $term) : ?>
                <option value="<?= $term->slug; ?>" <?= isset($_GET['filter-' . $tax->name]) && $_GET['filter-' . $tax->name] === $term->slug ? 'selected' : '' ?>><?= $term->name; ?></option>
                <?php
              endforeach; ?>
            </select>
          </div>
          <?php
        endif;
      endforeach; ?>
    </div>
  </form>
  <?php
}
