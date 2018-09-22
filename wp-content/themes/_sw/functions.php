<?php
date_default_timezone_set('America/New_York');
@ini_set('upload_max_filesize', '64M');
@ini_set('post_max_size', '64M');
@ini_set('max_execution_time', '60');

function _sw_setup() {
  // Make theme available for translation.
  load_theme_textdomain('_sw', get_template_directory() . '/languages');

  add_theme_support('custom-logo');
  add_theme_support('post-thumbnails');
  // set_post_thumbnail_size(250, 250);
  add_image_size('standard', 576, 576);
  add_theme_support('html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption'
  ));

  // Add excerpt support for pages
  add_post_type_support('page', 'excerpt');

  // Register nav locations
  register_nav_menus(array(
    'header-left' => esc_html__('Header Left', '_sw'),
    'header-right' => esc_html__('Header Right', '_sw'),
    // 'footer' => esc_html__('Footer', '_sw')
  ));

  // Remove junk from head
  remove_action('wp_head', 'wp_generator'); // Wordpress version
  remove_action('wp_head', 'rsd_link'); // Really Simple Discovery
  remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer
  remove_action('wp_head', 'print_emoji_detection_script', 7); // Emojis :(
  remove_action('wp_print_styles', 'print_emoji_styles'); // Emojis :(
  remove_action('wp_head', 'wp_shortlink_wp_head'); // Page shortlink
  remove_action('wp_head', 'start_post_rel_link'); // Navigation links
  remove_action('wp_head', 'parent_post_rel_link'); // Navigation links
  remove_action('wp_head', 'index_rel_link'); // Navigation links
  remove_action('wp_head', 'adjacent_posts_rel_link'); // Navigation links
  remove_action('wp_head', 'rest_output_link_wp_head'); // JSON
  remove_action('wp_head', 'wp_oembed_add_discovery_links'); // JSON
  remove_action('wp_head', 'rel_canonical'); // If there's more than one canonical, neither will work, so we remove the default one and use ours

  // Enable shortcodes in widgets
  add_filter('widget_text', 'do_shortcode');

  // Add tinymce styles
  add_editor_style(get_template_directory_uri() . '/dist/css/wp/globals.min.css');
}
add_action('after_setup_theme', '_sw_setup');

// Add custom image size
function _sw_custom_sizes($sizes) {
  return array_merge($sizes, array(
    'standard' => 'Standard'
  ));
}
add_filter('image_size_names_choose', '_sw_custom_sizes');

// Remove trackback/pingback support
function _sw_remove_post_support() {
  remove_post_type_support('post', 'trackbacks');
}
add_action('init', '_sw_remove_post_support');

// Remove jquery migrate script
function _sw_remove_jquery_migrate($scripts) {
  if (!is_admin()) {
    $scripts->remove('jquery');
    $scripts->add('jquery', false, array('jquery-core'), '1.10.2');
  }
}
add_filter('wp_default_scripts', '_sw_remove_jquery_migrate');

// Disable author archives
function _sw_disable_author_archives() {
  if (is_author()) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
  } else {
    redirect_canonical();
  }
}
remove_filter('template_redirect', 'redirect_canonical');
add_action('template_redirect', '_sw_disable_author_archives');

// Register scripts/styles
function _sw_register_scripts() {
  wp_register_style('font-css', 'https://fonts.googleapis.com/css?family=Montserrat:300,400,600');
  wp_register_style('wp-css', get_template_directory_uri() . '/dist/css/wp/wp.min.css', array(), '1.0');
  wp_register_style('admin-css', get_template_directory_uri() . '/dist/css/admin/admin.min.css', array(), '1.0');
  wp_register_style('jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');

  wp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'), false, true);
  wp_register_script('amp', 'https://cdn.ampproject.org/v0.js', array(), null, false);
  wp_register_script('wp-js', get_template_directory_uri() . '/dist/js/wp/wp.min.js', array(), '1.0', true);
  wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . (get_option('google_maps') ?: 'AIzaSyB5dOtdhz53nCEusX4aU4yRKkOGns_Dsn8') . '&libraries=places&callback=initMap', array('wp-js'), false, true);
  wp_register_script('admin-js', get_template_directory_uri() . '/dist/js/admin/admin.min.js', array('jquery', 'jquery-ui', 'wp-color-picker'), '1.0', true);
  wp_register_script('pagebuilder-js', get_template_directory_uri() . '/dist/js/admin/pagebuilder.min.js', array('jquery', 'jquery-ui'), '1.0', true);
  wp_register_script('event-js', get_template_directory_uri() . '/dist/js/admin/event.min.js', array('jquery', 'jquery-ui'), '1.0', true);
  wp_register_script('options-js', get_template_directory_uri() . '/dist/js/admin/options.min.js', array('jquery'), '1.0', true);
}
add_action('wp_loaded', '_sw_register_scripts');

// Make frontend javascript defer
function _sw_script_attribute($tag, $handle) {
  // if ($handle == 'wp-js') {
  //   return str_replace(' src', ' defer src', $tag);
  // }
  if ($handle == 'amp' || $handle == 'amp-analytics') {
    return str_replace(' src', ' async src', $tag);
  }
  return $tag;
}
add_filter('script_loader_tag', '_sw_script_attribute', 10, 2);

// Enqueue frontend scripts/styles
function _sw_wp_enqueue() {
  wp_deregister_script('wp-embed'); // Remove embed script

  global $post;
  global $post_type;
  global $wp_query;
  wp_localize_script('wp-js', 'locals', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'wp_query' => $wp_query
  ));
  wp_enqueue_style('font-css');
  if (get_query_var('amp')) {
    wp_enqueue_script('amp');
  }
  else {
    wp_enqueue_style('wp-css');
    if (is_page_template('templates/front-page.php') || $post_type == 'event') {
      wp_enqueue_script('google-maps');
    }
    else {
      wp_enqueue_script('wp-js');
    }
  }
}
add_action('wp_enqueue_scripts', '_sw_wp_enqueue');

// Enqueue admin scripts/styles
function _sw_admin_enqueue($hook) {
  global $post_type;
  wp_enqueue_style('admin-css');
  wp_enqueue_style('jquery-ui-css');
  wp_enqueue_style('wp-color-picker');

  wp_enqueue_media();
  wp_enqueue_editor();
  wp_localize_script('admin-js', 'locals', array(
    'ajax_url' => admin_url('admin-ajax.php')
  ));
  wp_enqueue_script('admin-js');
  if ($post_type == 'event') {
    if ($hook == 'post-new.php' || $hook == 'post.php') {
      wp_enqueue_script('event-js');
    }
  }
  if (substr($hook, 0, 13) == 'settings_page') {
    wp_enqueue_script('options-js');
  }
  // Pagebuilder script
  if ($post_type == 'page' && ($hook == 'post.php' || $hook == 'post-new.php')) {
    global $post;
    if (get_post_meta($post->ID, '_wp_page_template', true) == 'templates/pagebuilder.php') {
      add_filter('wp_default_editor', function() {
        return 'html';
      });
      wp_localize_script('pagebuilder-js', 'pbInfo', array(
        'ajax_url' => admin_url('admin-ajax.php')
      ));
      wp_enqueue_script('pagebuilder-js');
    }
  }
}
add_action('admin_enqueue_scripts', '_sw_admin_enqueue');

// Remove h1 and lower headlines from editor
function _ws_tiny_mce_formats($init) {
  $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre';
  return $init;
}
add_filter('tiny_mce_before_init', '_ws_tiny_mce_formats');

// Register widget areas
function _sw_register_widget_areas() {
  register_sidebar(array(
    'name' => 'Sidebar',
    'id' => 'sidebar',
    'description' => 'Widgets in this area will appear on pages with a left or right sidebar'
  ));
  register_sidebar(array(
    'name' => 'Footer',
    'id' => 'footer',
    'description' => 'Widgets in this area will appear in the footer'
  ));
}
add_action('widgets_init', '_sw_register_widget_areas');

// Set excerpt length
function _sw_excerpt_length($length) {
  return 35;
}
add_filter('excerpt_length', '_sw_excerpt_length', 999);

// Custom post types
foreach (glob(get_template_directory() . '/post-types/*.php') as $filename) {
  require_once $filename;
}

// Template fields
foreach (glob(get_template_directory() . '/template-metas/*.php') as $filename) {
  require_once $filename;
}

// Custom taxonomies
foreach (glob(get_template_directory() . '/taxonomies/*.php') as $filename) {
  require_once $filename;
}

// Miscellaneous files
foreach (glob(get_template_directory() . '/inc/*.php') as $filename) {
  require_once $filename;
}
