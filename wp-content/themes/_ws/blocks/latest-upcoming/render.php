<?php
function _ws_block_latest_upcoming($a = []) {
  $a['className'] = 'wp-block-ws-latest-upcoming ' . ($a['className'] ?? '');
  $postTypes = $a['postTypes'] ?? [];
  $numPosts = $a['numPosts'] ?? 3;
  $numPosts = isset($a['allPosts']) && $a['allPosts'] ? -1 : $numPosts;
  $taxTerms = $a['taxTerms'] ?? [];
  $horizontalScroll = isset($a['horizontalScroll']) && $a['horizontalScroll'] ? 'horizontal-scroll' : '';
  $type = $postTypes[0] ?? 'none';
  preg_match('/is-style-([^\s]+)/', $a['className'], $matches);
  if (isset($matches[1])) {
    $type = $matches[1];
  }
  ob_start(); ?>
    <div class="row <?= $type; ?>-row <?= $horizontalScroll; ?>">
      <?php
      $posts = get_posts(_ws_latest_upcoming_build_args($postTypes, $numPosts, $taxTerms));
      if (count($postTypes) === 1 && $postTypes[0] === 'event') {
        $posts = array_filter($posts, function($v) {
          return time() <= strtotime(get_post_meta($v->ID, '_event_start_date', true));
        });
      }
      global $post;
      foreach ($posts as $i=>$post) {
        setup_postdata($post);
        get_template_part('parts/archive', $type);
      }
      wp_reset_postdata(); ?>
    </div>
  <?php
  return _ws_block_wrapping($a, ob_get_clean(), 'div');
}

function _ws_latest_upcoming_build_args($postTypes, $numPosts, $taxTerms) {
  global $post;
  $args = [
    'post_type' => $postTypes,
    'posts_per_page' => $numPosts,
    'post_status' => 'publish'
  ];
  // Tax query
  $queries = [];
  foreach ($taxTerms as $tax=>$terms) {
    if (!empty($terms)) {
      array_push($queries, [
        'taxonomy' => $tax,
        'field' => 'id',
        'terms' => $terms
      ]);
    }
  }
  if (!empty($queries)) {
    $queries['relation'] = 'OR';
    $args['tax_query'] = $queries;
  }
  // Event ordering
  if (count($postTypes) === 1 && $postTypes[0] === 'event') {
    $args['meta_query'] = [
      [
        'key' => '_event_start_date',
        'value' => date('Y-m-d\TH:i:s'),
        'compare' => '>='
      ]
    ];
    $args['orderby'] = 'meta_value';
    $args['meta_key'] = '_event_start_date';
    $args['order'] = 'DESC';
  }
  return $args;
}
