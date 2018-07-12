<?php
/* Additional shortcodes are found in page-builder.php */

// Responsive video
function _sw_shortcode_video($atts) {
  $a = shortcode_atts(array(
    'url' => 'https://www.youtube.com/embed/aqz-KE-bpKQ?ecver=1',
    'type' => 'youtube',
    'fullscreen' => true
  ), $atts);
  $output = '';
  if ($a['type']=='local') {
    $output = '<video' . ($a['fullscreen'] ? ' class="no-fullscreen"' : '') . ' controls><source src="' . $a['url'] . '" type="video/mp4" /></video>';
  } else {
    $output = '<div class="video-container"><iframe src="' . $a['url'] . '" frameborder="0" ' . ($a['fullscreen'] ? 'allowfullscreen' : '') . '></iframe></div>';
  }
  return $output;
}
add_shortcode('responsive_video', '_sw_shortcode_video');

// Simple email form
function _sw_shortcode_form($atts) {
  $a = shortcode_atts(array(
    'to' => get_bloginfo('admin_email')
  ), $atts);
  $output = '<form class="ajax-form" type="post" action="">' .
    '<label for="name">Name*</label>' .
    '<input id="name" name="name" type="text" required />' .
    '<label for="email">Email*</label>' .
    '<input id="email" name="email" type="email" required />' .
    '<label for="message">Message*</label>' .
    '<textarea id="message" name="message" required></textarea>' .
    '<input type="hidden" name="apiaryProductContainer" value="" />' .
    '<input type="hidden" name="reniatnoCtcudorPyraipa" value="Pooh Bear" />' .
    '<input type="hidden" name="to" value="' . $a['to'] . '" />' .
    '<input type="hidden" name="form" value="contact" />' .
    '<input type="hidden" name="action" value="_sw_send_email" />' .
    '<input type="submit" value="Submit" />' .
    '<div class="error-msg"></div>' .
  '</form>';
  return $output;
}
add_shortcode('form', '_sw_shortcode_form');

// Useful for svg's in the visual editor (WordPress removes svg elements when switching between visual and text)
function _sw_shortcode_svg($atts) {
  $a = shortcode_atts(array(
    'id' => null,
    'class' => null
  ), $atts);
  $output = '';
  if ($a['id']) {
    $output = '<svg' . ($a['class'] ? ' class="' . $a['class'] . '"' : '') . '><use xlink:href="/wp-content/themes/_sw/template-parts/sprites.svg#' . $a['id'] . '"></use></svg>';
  }
  return $output;
}
add_shortcode('svg', '_sw_shortcode_svg');

// Social icons/links
function _sw_shortcode_social($atts) {
  $a = shortcode_atts(array(
    'icons' => array('facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'pinterest', 'soundcloud', 'tumblr')
  ), $atts);
  if (!is_array($a['icons'])) {
    $a['icons'] = explode(',', $a['icons']);
    $a['icons'] = array_map(function($v) {
      return trim($v);
    }, $a['icons']);
  }
  $output = '<div class="social-icons">';
  foreach ($a['icons'] as $s) {
    if ($url = get_option('social_' . $s)) {
      $output .= '<a href="' . $url . '" target="_blank" aria-label="Visit ' . ucwords($s) . '"><svg><use xlink:href="/wp-content/themes/_sw/template-parts/sprites.svg#' . $s . '"></use></svg></a>';
    }
  }
  $output .= '</div>';
  return $output;
}
add_shortcode('social_icons', '_sw_shortcode_social');

// Google Map
function _sw_shortcode_google_map($atts) {
  $a = shortcode_atts(array(
    'location' => ''
  ), $atts);
  $output = '';
  if ($a['location']) {
    $output = '<iframe class="google-map" width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=' . (get_option('google_maps') ?: 'AIzaSyB5dOtdhz53nCEusX4aU4yRKkOGns_Dsn8') . '&q=' . str_replace(' ', '+', strip_tags($a['location'])) . '" allowfullscreen></iframe>';
  }
  return $output;
}
add_shortcode('google_map', '_sw_shortcode_google_map');

// Locations
function _sw_shortcode_location_list($atts) {
  $output = '';
  $locs = get_posts(array('post_type'=>'location', 'post_status'=>'publish', 'posts_per_page'=>-1));
  foreach ($locs as $i=>$loc) {
    $contact = get_post_meta($loc->ID, '_contact', true);
    $name = get_post_meta($loc->ID, '_location', true);
    $output .= '<h3>' . $loc->post_title . '</h3>
    <div class="row">
      <div class="col-sm-6">' . $name . '</div>
      <div class="col-sm-6">' . $contact . '</div>
    </div>
    <iframe class="google-map" width="600" height="360" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=' . (get_option('google_maps') ?: 'AIzaSyB5dOtdhz53nCEusX4aU4yRKkOGns_Dsn8') . '&q=' . str_replace(' ', '+', strip_tags($name)) . '" allowfullscreen></iframe>';
  }
  return $output;
}
add_shortcode('location_list', '_sw_shortcode_location_list');

// Video Link
function _sw_shortcode_wistia($atts, $content = null) {
  $a = shortcode_atts(array(
    'buttons' => ''
  ), $atts);
  $output = '<div class="lightbox" role="dialog">
    <div class="container row">
      <div class="col-xs-12">
        <div class="lightbox-header">
          <button class="lightbox-hide" aria-label="Close Dialog">
            <svg viewBox="0 0 24 24"><path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"></path></svg>
          </button>
        </div>
        <div class="lightbox-content">';
          $output .= $content;
        $output .= '</div>
      </div>
    </div>
  </div>';
  return $output;
}
add_shortcode('wistia', '_sw_shortcode_wistia');

// Social media share buttons
function _sw_share_buttons($atts) {
  $a = shortcode_atts(array(
    'buttons' => ''
  ), $atts);
  $btns = array_map('trim', explode(',', $a['buttons']));
  $output = '';
  $output .= '<div class="share-btns">';
    if (in_array('facebook', $btns) || !$a['buttons']) {
      $output .= '<a class="share-button" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode(get_permalink()) . '" target="_blank">
        [svg id="facebook"]
      </a>';
    }
    if (in_array('linkedin', $btns) || !$a['buttons']) {
      $output .= '<a class="share-button" href="https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode(get_permalink()) . '" target="_blank">
        [svg id="linkedin"]
      </a>';
    }
    if (in_array('twitter', $btns) || !$a['buttons']) {
      $output .= '<a class="share-button" href="https://twitter.com/intent/tweet?url=' . urlencode(get_permalink()) . '" target="_blank">
        [svg id="twitter"]
      </a>';
    }
    if (in_array('email', $btns) || !$a['buttons']) {
      $output .= '<a class="share-button" href="mailto:?subject=' . get_the_title() . '&amp;body=' . get_permalink() . '" target="_blank">
        [svg id="mail"]
      </a>';
    }
  $output .= '</div>';
  return do_shortcode($output);
}
add_shortcode('share_buttons', '_sw_share_buttons');

// Row
function _sw_shortcode_row($atts, $content = null) {
  $a = shortcode_atts(array(
    'id' => '',
    'class' => ''
  ), $atts);
  $output = '';
  if (!empty($content)) {
    $output = '<div ' . ($a['id'] ? 'id="' . $a['id'] . '" ' : '') . 'class="row' . ($a['class'] ? ' ' . $a['class'] : '') . '" style="margin-left: -20px; margin-right: -20px;">' . do_shortcode($content) . '</div>';
  }
  return $output;
}
add_shortcode('row', '_sw_shortcode_row');

// Column
function _sw_shortcode_column($atts, $content = null) {
  $a = shortcode_atts(array(
    'id' => '',
    'class' => '',
    'size' => ''
  ), $atts);
  $output = '';
  $size_str = '';
  if ($a['size'] == '1/2') {
    $size_str .= 'col-md-6 col-sm-12';
  }
  else if ($a['size'] == '1/3') {
    $size_str .= 'col-lg-4 col-md-12';
  }
  else if ($a['size'] == '1/4') {
    $size_str .= 'col-xl-3 col-md-6 col-sm-12';
  }
  else if ($a['size'] == '2/3') {
    $size_str .= 'col-lg-8 col-md-12';
  }
  else {
    $size_str .= 'col-xs-12';
  }
  if (!empty($content)) {
    $output = '<div ' . ($a['id'] ? 'id="' . $a['id'] . '" ' : '') . 'class="' . $size_str . ($a['class'] ? ' ' . $a['class'] : '') . '">' . do_shortcode($content) . '</div>';
  }
  return $output;
}
add_shortcode('column', '_sw_shortcode_column');
