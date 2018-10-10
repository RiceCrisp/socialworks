<?php
// Register tracking and analytics menu
function _sw_amp_menu() {
  add_options_page('AMP', 'AMP', 'manage_options', 'amp', '_sw_amp_page');
}
add_action('admin_menu', '_sw_amp_menu');

// Register tracking and analytics fields
function _sw_amp_fields() {
  register_setting('amp', 'amp');
  register_setting('amp', 'amp_triggers');
}
add_action('admin_init', '_sw_amp_fields');

// Create tracking and analytics page
function _sw_amp_page() { ?>
  <div class="wrap options-page amp-options">
    <form action="options.php" method="post">
      <h1>AMP (Accelerated Mobile Pages)</h1>
      <?php
      settings_fields('amp');
      do_settings_sections('amp'); ?>
      <section>
        <h2>Post Types</h2>
        <p>Select the post types for which you want to generate accelerated mobile pages.</p>
        <ul>
          <?php
          $ps = get_post_types(array('public' => true, 'publicly_queryable' => true), 'names');
          unset($ps['attachment']);
          foreach ($ps as $p) : ?>
            <li>
              <input id="amp[<?= $p; ?>]" name="amp[<?= $p; ?>]" type="checkbox" <?= isset(get_option('amp')[$p]) ? 'checked' : ''; ?> />
              <label for="amp[<?= $p; ?>]"><?= ucwords($p); ?></label>
            </li>
          <?php
          endforeach; ?>
        </ul>
      </section>
      <section id="triggers">
        <h2>Google Analytics Triggers</h2>
        <p>Accelerated Mobile Pages use a different version of analytics than their desktop counterparts. To trigger custom events, you will need to put the appropriate JSON below.</p>
        <textarea id="amp_triggers" name="amp_triggers" v-model="triggers" placeholder="// Your custom triggers"><?= get_option('amp_triggers'); ?></textarea>
        <pre>"vars": {
  "account": "UA-XXXXX-Y"
},
"triggers": {
  "trackPageview": {
    "on": "visible",
    "request": "pageview"
  },
  {{ tabbedTriggers || '// Your custom triggers' }}
}</pre>
      </section>
      <input name="Submit" type="submit" class="button-primary" value="Save Changes" />
    </form>
  </div>
<?php
}

function _sw_amp_rewrite() {
  add_rewrite_rule('^amp/(.*)/(.*)/?', 'index.php?post_type=$matches[1]&name=$matches[2]&amp=1', 'top');
}
add_action('init', '_sw_amp_rewrite');

function _sw_amp_query_var($vars) {
  $vars[] = 'amp';
  return $vars;
}
add_filter('query_vars', '_sw_amp_query_var');

function _sw_amp_template($template) {
  if (get_query_var('amp')) {
    $template = locate_template(array('templates/amp.php'));
  }
  return $template;
}
add_filter('template_include', '_sw_amp_template', 99);

function _sw_amp_header() {
  $output = '';
  if (get_query_var('amp')) {
    $output .= '<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>';
    $output .= '
    <style amp-custom>' .
      preg_replace(
        array(
          '/@import.*fonts\.googleapis\.com(.*?);/',
          '/ !important/'
        ),
        array(
          '',
          ''
        ),
        file_get_contents(get_template_directory() . '/dist/css/wp/wp.min.css')
      ) . '.page-header,.archive-header,.single-header{' . rtrim(_sw_thumbnail_background(), ';') . '}
    </style>';
    if (get_option('google_analytics_id')) {
      $output .= '<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>';
    }
  }
  echo $output;
}
add_action('wp_head', '_sw_amp_header');

function _sw_amp_footer() {
  $output = '';
  if (get_query_var('amp') && get_option('google_analytics_id')) {
    $output .= '
    <amp-analytics type="googleanalytics">
      <script type="application/json">
        {
          "vars": {
            "account": "' . get_option('google_analytics_id') . '"
          },
          "triggers": {
            "trackPageview": {
              "on": "visible",
              "request": "pageview"
            }' .
            (get_option('amp_triggers') ? ', ' . get_option('amp_triggers') : '') . '
          }
        }
      </script>
    </amp-analytics>';
  }
  echo $output;
}
add_action('wp_footer', '_sw_amp_footer');

function _sw_amp_content($content) {
  if (get_query_var('amp')) {
    $content = preg_replace(
      array('/(<img )(.+)(>)/', '/style=\".*\"/'),
      array('<amp-img layout="responsive" $2</amp-img>', ''),
      $content
    );
  }
  return $content;
}
add_filter('the_content', '_sw_amp_content');
