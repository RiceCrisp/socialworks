<?php
// Register scripts/styles
function _ws_register_scripts() {
  $dist = get_template_directory() . '/dist/assets.json';
  $manifest = file_exists($dist) ? json_decode(file_get_contents($dist, true), true) : false;
  if ($manifest) {
    // CSS
    wp_register_style(
      'font-css',
      'https://fonts.googleapis.com/css?family=Montserrat:300,400,600&display=swap'
    );
    wp_register_style(
      'front-css',
      get_template_directory_uri() . '/dist/' . $manifest['front-css']['css'],
      [],
      null
    );
    $frontCSS = array_filter($manifest, function($key) {
      return substr($key, 0, 10) === 'front-css-';
    }, ARRAY_FILTER_USE_KEY);
    foreach ($frontCSS as $name => $files) {
      wp_register_style(
        $name,
        get_template_directory_uri() . '/dist/' . $files['css'],
        [],
        null
      );
    }
    wp_register_style(
      'admin-css',
      get_template_directory_uri() . '/dist/' . $manifest['admin-css']['css'],
      [],
      null
    );

    // JS
    wp_register_script(
      'front-js',
      get_template_directory_uri() . '/dist/' . $manifest['front-js']['js'],
      [],
      null
    );
    $frontJS = array_filter($manifest, function($key) {
      return substr($key, 0, 9) === 'front-js-';
    }, ARRAY_FILTER_USE_KEY);
    foreach ($frontJS as $name => $files) {
      wp_register_script(
        $name,
        get_template_directory_uri() . '/dist/' . $files['js'],
        ['front-js'],
        null
      );
    }
    wp_register_script(
      'google-charts',
      'https://www.gstatic.com/charts/loader.js',
      [],
      null
    );
    wp_register_script(
      'amp',
      'https://cdn.ampproject.org/v0.js',
      [],
      null
    );
    wp_register_script(
      'google-maps',
      'https://maps.googleapis.com/maps/api/js?key=' . get_option('google_maps_key') . '&callback=initMap',
      ['front-js-google-map']
    );
    wp_register_script(
      'options-js',
      get_template_directory_uri() . '/dist/' . $manifest['options-js']['js'],
      ['wp-blocks', 'wp-components', 'wp-editor', 'wp-element', 'wp-edit-post'],
      null,
      true
    );
    wp_register_script(
      'blocks-js',
      get_template_directory_uri() . '/dist/' . $manifest['blocks-js']['js'],
      ['wp-blocks', 'wp-element', 'wp-hooks'],
      null,
      true
    );
  }
}
add_action('wp_loaded', '_ws_register_scripts');

// Make frontend javascript defer and remove type attribute
function _ws_script_attribute($tag, $handle) {
  $tag = str_replace(' type=\'text/javascript\'', '', $tag);
  if (substr($handle, 0, 6) === 'front-' || $handle === 'google-maps') {
    return str_replace(' src', ' defer src', $tag);
  }
  if ($handle === 'amp' || $handle === 'amp-analytics') {
    return str_replace(' src', ' async src', $tag);
  }
  return $tag;
}
add_filter('script_loader_tag', '_ws_script_attribute', 10, 2);

// Remove stylesheet type attribute
function _ws_style_attribute($tag) {
  $tag = str_replace(' type=\'text/css\'', '', $tag);
  return $tag;
}
add_filter('style_loader_tag', '_ws_style_attribute', 10, 2);

// Enqueue frontend scripts/styles
function _ws_wp_enqueue() {
  global $post;
  global $post_type;
  $dist = get_template_directory() . '/dist/assets.json';
  $manifest = file_exists($dist) ? json_decode(file_get_contents($dist, true), true) : false;
  $frontCSS = [];
  $frontJS = [];
  if ($manifest) {
    $frontCSS = array_filter($manifest, function($key) {
      return substr($key, 0, 10) === 'front-css-';
    }, ARRAY_FILTER_USE_KEY);
    $frontJS = array_filter($manifest, function($key) {
      return substr($key, 0, 9) === 'front-js-';
    }, ARRAY_FILTER_USE_KEY);
  }

  wp_deregister_script('wp-embed'); // Remove embed script

  // CSS
  // wp_enqueue_style('font-css');
  wp_enqueue_style('front-css');
  wp_enqueue_style('front-css-social-links');
  foreach ($frontCSS as $name => $files) {
    if ($post && strpos($post->post_content, 'wp:ws/' . substr($name, 10)) !== false) {
      wp_enqueue_style($name);
    }
  }
  if (is_single()) {
    wp_enqueue_style('front-css-section');
    wp_enqueue_style('front-css-split');
    wp_enqueue_style('front-css-content-sidebar');
    wp_enqueue_style('front-css-breadcrumbs');
    wp_enqueue_style('front-css-card');
    wp_enqueue_style('front-css-form');
    wp_enqueue_style('front-css-social-share');
  }
  if (is_search()) {
    wp_enqueue_style('front-css-section');
    wp_enqueue_style('front-css-archive');
  }

  // JavaScript
  if (get_query_var('amp')) {
    wp_enqueue_script('amp');
  }
  else {
    wp_enqueue_script('front-js');
  }
  foreach ($frontJS as $name => $files) {
    if ($post && strpos($post->post_content, 'wp:ws/' . substr($name, 9)) !== false) {
      wp_enqueue_script($name);
    }
  }
  if ($post && strpos($post->post_content, 'wp:ws/google-map') !== false) {
    wp_localize_script('front-js-google-map', 'locals', [
      'googleMapsKey' => get_option('google_maps_key'),
      'googleMapsStyles' => get_option('google_maps_styles')
    ]);
    wp_enqueue_script('google-maps');
  }
  if (is_search()) {
    wp_enqueue_script('front-js-archive');
  }

}
add_action('wp_enqueue_scripts', '_ws_wp_enqueue');

// Enqueue admin scripts/styles
function _ws_admin_enqueue($hook) {
  global $post_type;
  wp_enqueue_style('admin-css');
  wp_enqueue_style('blocks-admin-css');
  wp_localize_script('blocks-js', 'locals', [
    'postType' => $post_type,
    'seoMetaTitle' => get_option('seo_meta_title'),
    'svgs' => get_option('svg') ?: [],
    'postsPerPage' => get_option('posts_per_page'),
    'googleMapsKey' => get_option('google_maps_key'),
    'googleMapsStyles' => get_option('google_maps_styles')
  ]);
  if (in_array($hook, ['settings_page_analytics', 'settings_page_redirects', 'settings_page_site_options', 'settings_page_sitemap', 'settings_page_svg', 'tools_page_bulk', 'profile.php', 'user-edit.php', 'nav-menus.php'])) {
    wp_enqueue_style('wp-edit-blocks');
    wp_enqueue_media();
    global $colors;
    wp_localize_script('options-js', 'locals', [
      'ajax_url' => admin_url('admin-ajax.php'),
      'colors' => $colors,
      'svgs' => get_option('svg') ?: [],
      'redirects' => get_option('redirects') ?: [],
      'ampPostTypes' => get_option('amp') ?: [],
      'ampTriggers' => get_option('amp_triggers'),
      'sitemapPostTypes' => get_option('sitemap_post_types') ?: [],
      'lastMod' => get_option('last_mod'),
      'logo' => _ws_logo(),
      'disableComments' => get_option('disable_comments'),
      'googleMapsKey' => get_option('google_maps_key'),
      'googleMapsStyles' => get_option('google_maps_styles'),
      'seoMetaTitle' => get_option('seo_meta_title'),
      'sitePhone' => get_option('site_phone'),
      'siteEmail' => get_option('site_email'),
      'siteLocationStreet' => get_option('site_location_street'),
      'siteLocationCity' => get_option('site_location_city'),
      'siteLocationState' => get_option('site_location_state'),
      'siteLocationZip' => get_option('site_location_zip'),
      'socialLinks' => get_option('social_links'),
      'tagManagerId' => get_option('tag_manager_id'),
      'pardotEmail' => get_option('pardot_email'),
      'pardotPassword' => get_option('pardot_password'),
      'pardotKey' => get_option('pardot_key'),
      'pardotAccount' => get_option('pardot_account'),
      'marketoEndpoint' => get_option('marketo_endpoint'),
      'marketoToken' => get_option('marketo_token'),
      'pwa' => get_option('pwa'),
      'cache' => get_option('cache'),
      'customAvatar' => get_the_author_meta('custom_avatar', $_GET['user_id'] ?? get_current_user_id())
    ]);
    wp_enqueue_script('options-js');
  }
}
add_action('admin_enqueue_scripts', '_ws_admin_enqueue');

// Prefetch and preconnect unregistered scripts
function _ws_prefetch() {
  global $post;
  if ($post) :
    if (strpos($post->post_content, '<!-- wp:ws/form') !== false) : ?>
      <!-- <link rel="preconnect" href="https://js.hsforms.net/" />
      <link rel="dns-prefetch" href="https://js.hsforms.net/" /> -->
      <?php
    endif;
    if (get_option('tag_manager_id')) : ?>
      <link rel="preconnect" href="https://www.googletagmanager.com" />
      <link rel="dns-prefetch" href="https://www.googletagmanager.com" />
      <link rel="preconnect" href="https://www.google-analytics.com" />
      <link rel="dns-prefetch" href="https://www.google-analytics.com" />
      <?php
    endif;
  endif; ?>
  <!-- font dns-prefetch handled by registration -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <?php
}
add_action('wp_head', '_ws_prefetch', 1);
