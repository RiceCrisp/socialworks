<?php
function _ws_register_meta() {
  $metas = [
    '_header_footer_light_header' => 'boolean',
    '_header_footer_hide_header' => 'boolean',
    '_header_footer_hide_footer' => 'boolean',
    '_seo_title' => 'string',
    '_seo_description' => 'string',
    '_seo_keywords' => 'string',
    '_seo_canonical' => 'string',
    '_seo_no_index' => 'boolean',
    '_seo_no_follow' => 'boolean',
    '_seo_disallow_search' => 'boolean',
    '_social_title' => 'string',
    '_social_description' => 'string',
    '_social_image' => 'integer',
    '_social_twitter' => 'string',
    '_sitemap_freq' => 'string',
    '_sitemap_priority' => 'string',
    '_sitemap_remove' => 'boolean',
    '_link_url' => 'string',
    '_link_type' => 'string',
    '_featured_image_x' => 'string',
    '_featured_image_y' => 'string',
    '_author_name' => 'string',
    '_author_image' => 'integer',
    '_event_start_date' => 'string',
    '_event_has_start_time' => 'boolean',
    '_event_end_date' => 'string',
    '_event_has_end_time' => 'boolean',
    '_event_date_tbd' => 'boolean',
    '_event_has_location' => 'boolean',
    '_event_location_name' => 'string',
    '_event_location_street' => 'string',
    '_event_location_city' => 'string',
    '_event_location_state' => 'string',
    '_event_location_zip' => 'string',
    '_event_location_override' => 'string',
    '_person_position' => 'string',
    '_person_linkedin' => 'string',
    '_person_instagram' => 'string',
    '_person_twitter' => 'string',
    '_person_facebook' => 'string',
    '_case_study_client' => 'string',
    '_case_study_logo' => 'integer',
    '_resource_form_heading' => 'string',
    '_resource_form' => 'string'
  ];

  foreach ($metas as $meta=>$type) {
    register_meta('post', $meta, [
      'show_in_rest' => true,
      'single' => true,
      'type' => $type,
      'auth_callback' => function() {
        return current_user_can('edit_posts');
      }
    ]);
  }
}
add_action('init', '_ws_register_meta');
