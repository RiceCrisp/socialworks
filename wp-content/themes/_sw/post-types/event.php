<?php
// Register event post type
function _sw_event_post_type() {
  $labels = array(
    'name' => 'Events',
    'singular_name' => 'Event',
    'add_new_item' => 'Add New Event',
    'edit_item' => 'Edit Event',
    'new_item' => 'New Event',
    'view_item' => 'View Event',
    'search_items' => 'Search Events',
    'not_found' => 'No events found',
    'not_found_in_trash' => 'No events found in Trash',
    'parent_item_colon' => 'Parent Event:',
    'all_items' => 'All Events',
    'archives' => 'Event Archives',
    'insert_into_item' => 'Insert into event',
    'uploaded_to_this_item' => 'Uploaded to this event',
    'featured_image' => 'Featured image',
    'set_featured_image' => 'Set featured image',
    'remove_featured_image' => 'Remove featured image',
    'use_featured_image' => 'Use as featured image'
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Sortable/filterable events',
    'public' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_menu' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 20,
    'menu_icon' => 'dashicons-calendar',
    'capability_type' => 'post',
    'hierarchical' => false,
    'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
    'register_meta_box_cb' => null,
    'taxonomies' => array(),
    'has_archive' => false,
    'rewrite' => array('slug'=>'events', 'with_front'=>false),
    'query_var' => true
  );
  register_post_type('event', $args);
}
add_action('init', '_sw_event_post_type');

// Fill meta box
function _sw_event_meta_fields() {
  $times = array('', '8:00 AM', '8:15 AM', '8:30 AM', '8:45 AM', '9:00 AM', '9:15 AM', '9:30 AM', '9:45 AM', '10:00 AM', '10:15 AM', '10:30 AM', '10:45 AM', '11:00 AM', '11:15 AM', '11:30 AM', '11:45 AM', '12:00 PM', '12:15 PM', '12:30 PM', '12:45 PM', '1:00 PM', '1:15 PM', '1:30 PM', '1:45 PM', '2:00 PM', '2:15 PM', '2:30 PM', '2:45 PM', '3:00 PM', '3:15 PM', '3:30 PM', '3:45 PM', '4:00 PM', '4:15 PM', '4:30 PM', '4:45 PM', '5:00 PM', '5:15 PM', '5:30 PM', '5:45 PM', '6:00 PM', '6:15 PM', '6:30 PM', '6:45 PM', '7:00 PM', '7:15 PM', '7:30 PM', '7:45 PM', '8:00 PM', '8:15 PM', '8:30 PM', '8:45 PM', '9:00 PM', '9:15 PM', '9:30 PM', '9:45 PM', '10:00 PM', '10:15 PM', '10:30 PM', '10:45 PM', '11:00 PM', '11:15 PM', '11:30 PM', '11:45 PM', '12:00 AM', '12:15 AM', '12:30 AM', '12:45 AM', '1:00 AM', '1:15 AM', '1:30 AM', '1:45 AM', '2:00 AM', '2:15 AM', '2:30 AM', '2:45 AM', '3:00 AM', '3:15 AM', '3:30 AM', '3:45 AM', '4:00 AM', '4:15 AM', '4:30 AM', '4:45 AM', '5:00 AM', '5:15 AM', '5:30 AM', '5:45 AM', '6:00 AM', '6:15 AM', '6:30 AM', '6:45 AM', '7:00 AM', '7:15 AM', '7:30 AM', '7:45 AM');
  wp_nonce_field(basename(__FILE__), 'event-nonce');
  $start_date = get_post_meta(get_the_ID(), '_event-start-date', true);
  $end_date = get_post_meta(get_the_ID(), '_event-end-date', true);
  $start_time = get_post_meta(get_the_ID(), '_event-start-time', true);
  $end_time = get_post_meta(get_the_ID(), '_event-end-time', true);
  $sortable_start = get_post_meta(get_the_ID(), '_event-sortable-start', true);
  $sortable_end = get_post_meta(get_the_ID(), '_event-sortable-end', true);
  $json_start = get_post_meta(get_the_ID(), '_event-json-start', true);
  $json_end = get_post_meta(get_the_ID(), '_event-json-end', true);
  $lat = get_post_meta(get_the_ID(), '_event-lat', true);
  $lng = get_post_meta(get_the_ID(), '_event-lng', true);
  $location = get_post_meta(get_the_ID(), '_event-location', true); ?>
  <div id="event-meta-inside" class="custom-meta-inside">
    <ul>
      <li class="row">
        <div class="col-xs-6">
          <label for="event-start-date">Start Date</label>
          <input id="event-start-date" name="event-start-date" type="text" value="<?= $start_date; ?>" />
        </div>
        <div class="col-xs-6">
          <label for="event-start-time">Start Time</label>
          <select id="event-start-time" name="event-start-time">
            <?php
            foreach ($times as $time) {
              echo '<option value="' . $time . '" ' . ($start_time==$time ? 'selected' : '') . '>' . $time . '</option>';
            } ?>
          </select>
          <input id="event-sortable-start" name="event-sortable-start" type="hidden" value="<?= $sortable_start; ?>" />
          <input id="event-json-start" name="event-json-start" type="hidden" value="<?= $json_start; ?>" />
        </div>
      </li>
      <li class="row">
        <div class="col-xs-6">
          <label for="event-end-date">End Date</label>
          <input id="event-end-date" name="event-end-date" type="text" value="<?= $end_date; ?>" />
        </div>
        <div class="col-xs-6">
          <label for="event-end-time">End Time</label>
          <select id="event-end-time" name="event-end-time">
            <?php
            foreach ($times as $time) {
              echo '<option value="' . $time . '" ' . ($end_time==$time ? 'selected' : '') . '>' . $time . '</option>';
            } ?>
          </select>
          <input id="event-sortable-end" name="sortable-end" type="hidden" value="<?= $sortable_end; ?>" />
          <input id="event-json-end" name="event-json-end" type="hidden" value="<?= $json_end; ?>" />
        </div>
      </li>
    </li>
    <li class="row">
      <div class="col-xs-6">
        <label for="event-lat">Latitude</label>
        <input id="event-lat" name="event-lat" type="number" value="<?= $lat; ?>" />
      </div>
      <div class="col-xs-6">
        <label for="event-lng">Longitude</label>
        <input id="event-lng" name="event-lng" type="number" value="<?= $lng; ?>" />
      </div>
      <div class="col-xs-12">
        <label for="event-location">Location</label>
        <textarea id="event-location" name="event-location" class="text-editor"><?= $location; ?></textarea>
        <?php
        if ($location) : ?>
          <iframe id="event-map" width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=<?= get_option('google_maps') ?: 'AIzaSyB5dOtdhz53nCEusX4aU4yRKkOGns_Dsn8' ?>&q=<?= str_replace(' ', '+', strip_tags($location)); ?>" allowfullscreen></iframe>
        <?php
        endif; ?>
      </div>
    </li>
    </ul>
  </div>
  <?php
}

// Create meta box
function _sw_event_meta() {
  add_meta_box('event_meta', 'Event', '_sw_event_meta_fields', 'event', 'normal', 'high');
}
add_action('admin_init', '_sw_event_meta');

// Save meta values
function _sw_save_event_meta($post_id) {
  if (!isset($_POST['event-nonce']) || !wp_verify_nonce($_POST['event-nonce'], basename(__FILE__))) {
    return $post_id;
  }
  if (!current_user_can('edit_post', $post_id)) {
    return $post_id;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  $event_start_date = isset($_POST['event-start-date']) ? $_POST['event-start-date'] : '';
  update_post_meta($post_id, '_event-start-date', $event_start_date);

  $event_start_time = isset($_POST['event-start-time']) ? $_POST['event-start-time'] : '';
  update_post_meta($post_id, '_event-start-time', $event_start_time);

  $event_sortable_start = isset($_POST['event-sortable-start']) ? $_POST['event-sortable-start'] : '';
  update_post_meta($post_id, '_event-sortable-start', $event_sortable_start);

  $event_json_start = isset($_POST['event-json-start']) ? $_POST['event-json-start'] : '';
  update_post_meta($post_id, '_event-json-start', $event_json_start);

  $event_end_date = isset($_POST['event-end-date']) ? $_POST['event-end-date'] : '';
  update_post_meta($post_id, '_event-end-date', $event_end_date);

  $event_end_time = isset($_POST['event-end-time']) ? $_POST['event-end-time'] : '';
  update_post_meta($post_id, '_event-end-time', $event_end_time);

  $event_sortable_end = isset($_POST['event-sortable-end']) ? $_POST['event-sortable-end'] : '';
  update_post_meta($post_id, '_event-sortable-end', $event_sortable_end);

  $event_json_end = isset($_POST['event-json-end']) ? $_POST['event-json-end'] : '';
  update_post_meta($post_id, '_event-json-end', $event_json_end);

  $event_location = isset($_POST['event-location']) ? $_POST['event-location'] : '';
  update_post_meta($post_id, '_event-location', $event_location);

  $event_location_lat = isset($_POST['event-location-lat']) ? $_POST['event-location-lat'] : '';
  update_post_meta($post_id, '_event-location-lat', $event_location_lat);

  $event_location_lng = isset($_POST['event-location-lng']) ? $_POST['event-location-lng'] : '';
  update_post_meta($post_id, '_event-location-lng', $event_location_lng);
}
add_action('save_post', '_sw_save_event_meta');

// Add start date column
function _sw_date_column($columns) {
  $columns['start_date'] = 'Start Date';
  return $columns;
}
add_filter('manage_edit-event_columns', '_sw_date_column');

// Column content
function _sw_column_content($column_name, $post_id) {
  if ('start_date'!=$column_name) {
    return;
  }
  $start_dates = get_post_meta($post_id, '_event-start-date', true);
  echo $start_dates;
}
add_action('manage_event_posts_custom_column', '_sw_column_content', 10, 2);

// Make column sortable
function _sw_sortable_column($columns) {
  $columns['start_date'] = 'start_date';
  return $columns;
}
add_filter('manage_edit-event_sortable_columns', '_sw_sortable_column');

// Sort column by date and then time
function _sw_orderby($query) {
  if (!is_admin()) {
    return;
  }
  $orderby = $query->get('orderby');
  if ($orderby=='start_date') {
    $query->set('meta_key', '_event-sortable-start');
    $query->set('orderby', 'meta_value_num');
  }
}
add_action('pre_get_posts', '_sw_orderby');
