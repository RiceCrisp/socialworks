<?php
// Register tracking and analytics menu
function _sw_analytics_menu() {
  add_options_page('Tracking &amp; Analytics', 'Tracking &amp; Analytics', 'manage_options', 'analytics', '_sw_analytics_page');
}
add_action('admin_menu', '_sw_analytics_menu');

// Register tracking and analytics fields
function _sw_analytics_fields() {
  register_setting('analytics', 'tag_manager_id');
  register_setting('analytics', 'advanced_tracking_head');
  register_setting('analytics', 'advanced_tracking_footer');
}
add_action('admin_init', '_sw_analytics_fields');

// Create tracking and analytics page
function _sw_analytics_page() { ?>
  <div class="wrap options-page analytics-options">
    <form action="options.php" method="post">
      <h1>Tracking &amp; Analytics</h1>
      <?php
      settings_fields('analytics');
      do_settings_sections('analytics'); ?>
      <section>
        <h2>Google Tag Manager</h2>
        <label for="tag_manager_id">Google Tag Manager ID</label>
        <input id="tag_manager_id" name="tag_manager_id" type="text" value="<?= get_option('tag_manager_id'); ?>" placeholder="GTM-XXXXXXX" />
      </section>
      <section>
        <h2>Advanced</h2>
        <p>Insert third party code or advanced Google scripts in the head or footer.<br /><i>Note: You'll need to include the &lt;script&gt; tags as they are not automatically added to these fields.</i></p>
        <label for="advanced_tracking_head">Additional Scripts - Head</label>
        <textarea id="advanced_tracking_head" name="advanced_tracking_head"><?= get_option('advanced_tracking_head'); ?></textarea>
        <label for="advanced_tracking_footer">Additional Scripts - Footer</label>
        <textarea id="advanced_tracking_footer" name="advanced_tracking_footer"><?= get_option('advanced_tracking_footer'); ?></textarea>
      </section>
      <input name="Submit" type="submit" class="button-primary" value="Save Changes" />
    </form>
  </div>
  <?php
}

// Add tracking codes to the header
function _sw_analytics_header() {
  $tag_manager_id = get_option('tag_manager_id');
  $head = get_option('advanced_tracking_head', '');
  if ($tag_manager_id && !get_query_var('amp')) : ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer', '<?= $tag_manager_id; ?>');</script>
    <!-- End Google Tag Manager -->
  <?php
  endif;
  echo $head;
}
add_action('wp_head', '_sw_analytics_header');

// Add tracking codes to the footer
function _sw_analytics_footer() {
  $footer = get_option('advanced_tracking_footer', '');
  echo $footer;
}
add_action('wp_footer', '_sw_analytics_footer');
