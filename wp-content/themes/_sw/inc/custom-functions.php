<?php
// Prints taxonomy
function _sw_taxonomies($preCat='Posted in ', $preTag='Tagged ') {
  if (get_post_type() != 'page') {
    /* translators: used between list items, there is a space after the comma */
    $categories_list = get_the_category_list(esc_html__(', ', '_sw'));
    if ($categories_list && _sw_categorized_blog()) {
      printf('<p class="cat-links">' . $preCat . $categories_list . '</p>');
    }

    /* translators: used between list items, there is a space after the comma */
    $tags_list = get_the_tag_list('', esc_html__(', ', '_sw'));
    if ($tags_list) {
      printf('<p class="tags-links">' . $preTag . $tags_list . '</p>');
    }
  }
}

function _sw_categorized_blog() {
  if (false === ($cats = get_transient('_sw_categories'))) {
    // Create an array of all the categories that are attached to posts.
    $cats = get_categories(array(
      'fields'     => 'ids',
      'hide_empty' => 1,
      // We only need to know if there is more than one category.
      'number'     => 2,
    ));
    // Count the number of categories that are attached to the posts.
    $cats = count($cats);
    set_transient('_sw_categories', $cats);
  }
  if ($cats > 1) {
    // This blog has more than 1 category so _s_categorized_blog should return true.
    return true;
  } else {
    // This blog has only 1 category so _s_categorized_blog should return false.
    return false;
  }
}

// Display custom taxonomies
function _sw_custom_taxonomies() {
  $post = get_post();
  $post_type = $post->post_type;
  $taxonomies = get_object_taxonomies($post_type, 'objects');
  $output = '<div class="custom-taxonomies">';

  foreach ($taxonomies as $taxonomy_slug=>$taxonomy){
    $terms = get_the_terms($post->ID, $taxonomy_slug);
    if (!empty($terms)) {
      foreach ($terms as $term) {
       $output .= sprintf('<a class="tax" href="%1$s">%2$s</a>', // list
           esc_url(get_term_link($term->slug, $taxonomy_slug)),
           esc_html($term->name)
         );
      }
    }
  }
  $output .= '</div>';
  return $output;
}

// Get image url by slug
function _sw_get_attachment_url_by_slug($slug) {
  $args = array(
    'post_type' => 'attachment',
    'name' => sanitize_title($slug),
    'posts_per_page' => 1,
    'post_status' => 'inherit'
  );
  $_header = get_posts($args);
  $header = $_header ? array_pop($_header) : null;
  return $header ? wp_get_attachment_url($header->ID) : '';
}

// Get featured image for images
function _sw_thumbnail($id = null, $size = 'large', $lazy = false) {
  if (!$id) {
    global $post;
    if ($post) {
      $id = $post->ID;
    }
    else {
      return;
    }
  }
  if (has_post_thumbnail($id)) {
    $x = get_post_meta($id, '_banner-x', true);
    $y = get_post_meta($id, '_banner-y', true);
    $xy = $x != '' && $y != '' ?  $x . '% ' . $y . '%' : '';
    $output = '<div class="thumbnail-container">';
      $output .= $lazy ? '<img class="lazy-load" data-src="' . get_the_post_thumbnail_url($id, $size) . '"' : '<img src="' . get_the_post_thumbnail_url($id, $size) . '"';
      $output .= ' alt="' . get_the_title($id) . '" style="object-fit:cover;' . ($xy ? 'object-position:' . $xy . ';' : '') . '" data-object-fit="cover" data-object-position="' . $xy . '" />';
    $output .= '</div>';
    return $output;
  } else {
    return '';
  }
}

function _sw_img($id = null, $size = 'large', $lazy = false) {
  if (!$id) {
    return '';
  }
  $output = '<div class="thumbnail-container">';
    $output .= $lazy ? '<img class="lazy-load" data-src="' . wp_get_attachment_image_src($id, $size)[0] . '"' : '<img src="' . wp_get_attachment_image_src($id, $size)[0] . '"';
    $output .= ' alt="' . get_post_meta($id, '_wp_attachment_image_alt', true) . '" style="object-fit:cover;" data-object-fit="cover" />';
  $output .= '</div>';
  return $output;
}

// Get featured image for background images
function _sw_thumbnail_background($id = null, $size = 'full') {
  if (!$id) {
    global $post;
    if ($post) {
      $id = $post->ID;
    }
    else {
      return;
    }
  }
  if (has_post_thumbnail($id)) {
    $x = get_post_meta($id, '_banner-x', true);
    $y = get_post_meta($id, '_banner-y', true);
    $xy = $x != '' && $y != '' ? $x . '% ' . $y . '%' : 'center';
    $output = 'background:#f55039 url(' . get_the_post_thumbnail_url($id, $size) . ') ' . $xy . '/cover;';
    return $output;
  } else {
    return '';
  }
}

// Custom excerpt function that returns plain text
function _sw_excerpt($id = null, $limit = 120, $end = '[...]') {
  if (!$id) {
    global $post;
    $id = $post->ID;
  }
  $excerpt = '';
  if ($e = get_post($id)->post_excerpt) {
    $excerpt = $e;
  }
  else if ($e = get_post_meta($id, '_banner-subheadline', true)) {
    $excerpt = $e;
  }
  else if ($e = get_post_meta($id, '_seo-desc', true)) {
    $excerpt = $e;
  }
  else if ($e = get_post($id)->post_content) {
    $excerpt = $e;
  }
  $excerpt = strip_tags($excerpt);
  $excerpt = preg_replace('/\[\[.*?\]\]/', '', $excerpt);
  $excerpt = strip_shortcodes($excerpt);
  $excerpt = htmlspecialchars($excerpt);
  $excerpt = preg_replace('/\n/', '', $excerpt);
  $excerpt = preg_replace('/&amp;nbsp;|\r/', ' ', $excerpt);
  $excerpt = trim($excerpt);
  if (strlen($excerpt) <= $limit) {
    return $excerpt;
  }
  $excerpt = substr($excerpt, 0, $limit);
  $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
  $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
  $excerpt = $excerpt . ' ' . $end;
  return $excerpt;
}

// Get directions button
function _sw_directions_url($id = null) {
  if (!$id) {
    global $post;
    $id = $post->ID;
  }
  $loc_readable = get_post_meta($id, '_location-readable', true);
  $url = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode(str_replace(' <br>', ', ', $loc_readable));
  return $url;
}

// Function to determine if post is in menu
function _sw_in_menu($menu = null, $object_id = null) {
  $menu_object = wp_get_nav_menu_items(esc_attr($menu));
  if (!$menu_object) {
    return false;
  }
  $menu_items = wp_list_pluck($menu_object, 'object_id');
  if (!$object_id) {
    global $post;
    $object_id = get_queried_object_id();
  }
  return in_array((int)$object_id, $menu_items);
}

// Convert wp_get_nav_menu_items into something useful (turns flat array of menu items into hierarchical array)
function _sw_build_menu(array $elements, $parentId = 0) {
  $branch = array();
  foreach ($elements as $element) {
    if ($element->menu_item_parent == $parentId) {
      $children = _sw_build_menu($elements, $element->ID);
      if ($children) {
        $element->_sw_children = $children;
      }
      $branch[$element->ID] = $element;
      unset($element);
    }
  }
  return $branch;
}

// Get breadcrumbs from url
function _sw_breadcrumbs($separator = '&nbsp;&nbsp;|&nbsp;&nbsp;', $excludeCurrent = true) {
  $p_ids = array();
  $ps = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
  // print_r($ps);
  for ($i = 0; $i < count($ps); $i++) {
    $url = '';
    for ($ii = 0; $ii <= $i; $ii++) {
      $url .= $ps[$ii] . '/';
    }
    array_push($p_ids, url_to_postid($url));
  }
  if ($excludeCurrent) {
    array_pop($p_ids);
  }
  $output = '';
  foreach ($p_ids as $p_id) {
    $output .= '<a href="' . get_permalink($p_id) . '">' . get_the_title($p_id) . '</a>' . $separator;
  }
  $output = trim($output, $separator);
  return $output;
}

function _sw_media_selector($id = '', $name = '', $value = '') {
  $output = '<div class="media-selector-container">
    <button class="button media-selector">Select Image</button>
    <input id="' . $id . '" name="' . $name . '" type="hidden" value="' . $value . '" />
    <div class="media-preview">
      <img src="' . wp_get_attachment_image_src($value, 'standard')[0] . '" />
      <button><span class="dashicons dashicons-trash"></span></button>
    </div>
  </div>';
  return $output;
}
