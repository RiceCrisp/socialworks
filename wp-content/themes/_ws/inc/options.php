<?php
// Register site options menu
function _ws_site_options_menu() {
  add_options_page('Site Options', 'Site Options', 'manage_options', 'site', '_ws_site_options_page');
}
add_action('admin_menu', '_ws_site_options_menu');

// Register site options fields
function _ws_site_options_fields() {
  // General
  register_setting('site', 'disable_comments');
  register_setting('site', 'google_maps');

  // SEO
  register_setting('site', 'seo_meta_title');

  // Contact
  register_setting('site', 'site_tel');
  register_setting('site', 'site_email');
  register_setting('site', 'site_location');
  register_setting('site', 'site_location_id');
  register_setting('site', 'site_location_street');
  register_setting('site', 'site_location_city');
  register_setting('site', 'site_location_state');
  register_setting('site', 'site_location_zip');
  register_setting('site', 'site_location_country');
  register_setting('site', 'site_hours');
  register_setting('site', 'site_hours_open_sun');
  register_setting('site', 'site_hours_close_sun');
  register_setting('site', 'site_hours_open_mon');
  register_setting('site', 'site_hours_close_mon');
  register_setting('site', 'site_hours_open_tue');
  register_setting('site', 'site_hours_close_tue');
  register_setting('site', 'site_hours_open_wed');
  register_setting('site', 'site_hours_close_wed');
  register_setting('site', 'site_hours_open_thu');
  register_setting('site', 'site_hours_close_thu');
  register_setting('site', 'site_hours_open_fri');
  register_setting('site', 'site_hours_close_fri');
  register_setting('site', 'site_hours_open_sat');
  register_setting('site', 'site_hours_close_sat');

  // Social
  register_setting('site', 'social_facebook');
  register_setting('site', 'social_twitter');
  register_setting('site', 'social_instagram');
  register_setting('site', 'social_youtube');
  register_setting('site', 'social_linkedin');
}
add_action('admin_init', '_ws_site_options_fields');

// Create site options page
function _ws_site_options_page() { ?>
  <div class="wrap options-page site-options">
    <form action="options.php" method="post">
      <h1>Site Options</h1>
      <?php
      settings_fields('site');
      do_settings_sections('site'); ?>
      <section>
        <h2>General</h2>
        <div class="inline-option">
          <label for="disable_comments">Disable Comments</label>
          <input id="disable_comments" name="disable_comments" type="checkbox" <?= get_option('disable_comments') ? 'checked' : ''; ?> />
        </div>
        <label for="google_maps">Google Maps API Key</label>
        <input id="google_maps" name="google_maps" type="text" value="<?= get_option('google_maps'); ?>" />
      </section>
      <section>
        <h2>SEO</h2>
        <label for="seo_meta_title">Text to append to meta titles</label>
        <input id="seo_meta_title" name="seo_meta_title" type="text" value="<?= get_option('seo_meta_title'); ?>" />
      </section>
      <section>
        <h2>Contact</h2>
        <label for="site_tel">Telephone</label>
        <input id="site_tel" name="site_tel" type="text" value="<?= get_option('site_tel'); ?>" />
        <label for="site_email">Email</label>
        <input id="site_email" name="site_email" type="email" value="<?= get_option('site_email'); ?>" />
        <div class="inline-option">
          <label for="site_hours">Hours</label>
          <input id="site_hours" name="site_hours" type="checkbox" <?= get_option('site_hours') ? 'checked' : ''; ?> />
        </div>
        <div class="hours">
        <?php
        $days = array('Sunday' => 'sun', 'Monday' => 'mon', 'Tuesday' => 'tue', 'Wednesday' => 'wed', 'Thursday' => 'thu', 'Friday' => 'fri', 'Saturday' => 'sat');
        foreach ($days as $key => $day) : ?>
          <fieldset>
            <legend><?= $key; ?></legend>
            <label for="site_hours_open_<?= $day; ?>">Open</label>
            <select id="site_hours_open_<?= $day; ?>" name="site_hours_open_<?= $day; ?>">
            <?php for ($i = 0; $i < 24; $i++) : ?>
              <option value="<?= $i; ?>:00" <?= get_option('site_hours_open_' . $day) == ($i . ':00') ? 'selected' : ''; ?>><?= $i; ?>:00</option>
              <option value="<?= $i; ?>:30" <?= get_option('site_hours_open_' . $day) == ($i . ':30') ? 'selected' : ''; ?>><?= $i; ?>:30</option>
            <?php endfor; ?>
            </select>
            <label for="site_hours_close_<?= $day; ?>">Close</label>
            <select id="site_hours_close_<?= $day; ?>" name="site_hours_close_<?= $day; ?>">
              <?php
              for ($i = 0; $i < 24; $i++) : ?>
                <option value="<?= $i; ?>:00" <?= get_option('site_hours_close_' . $day) == ($i . ':00') ? 'selected' : ''; ?>><?= $i; ?>:00</option>
                <option value="<?= $i; ?>:30" <?= get_option('site_hours_close_' . $day) == ($i . ':30') ? 'selected' : ''; ?>><?= $i; ?>:30</option>
              <?php
              endfor; ?>
              <option value="23:59" <?= get_option('site_hours_close_' . $day) == '23:59' ? 'selected' : ''; ?>>23:59</option>
            </select>
          </fieldset>
        <?php
        endforeach; ?>
        </div>
        <label for="site_location">Address</label>
        <input id="site_location_street" name="site_location_street" type="text" placeholder="Street Address" value="<?= get_option('site_location_street'); ?>" />
        <input id="site_location_city" name="site_location_city" type="text" placeholder="City" value="<?= get_option('site_location_city'); ?>" />
        <input id="site_location_state" name="site_location_state" type="text" placeholder="State" maxlength="2" size="2" value="<?= get_option('site_location_state'); ?>" />
        <input id="site_location_zip" name="site_location_zip" type="text" placeholder="Zip" maxlength="5" size="5" max="99999" value="<?= get_option('site_location_zip'); ?>" />
      </section>
      <section>
        <h2>Social</h2>
        <label for="social_facebook">Facebook</label>
        <input id="social_facebook" name="social_facebook" type="text" value="<?= get_option('social_facebook'); ?>" />
        <label for="social_twitter">Twitter</label>
        <input id="social_twitter" name="social_twitter" type="text" value="<?= get_option('social_twitter'); ?>" />
        <label for="social_instagram">Instagram</label>
        <input id="social_instagram" name="social_instagram" type="text" value="<?= get_option('social_instagram'); ?>" />
        <label for="social_youtube">YouTube</label>
        <input id="social_youtube" name="social_youtube" type="text" value="<?= get_option('social_youtube'); ?>" />
        <label for="social_linkedin">LinkedIn</label>
        <input id="social_linkedin" name="social_linkedin" type="text" value="<?= get_option('social_linkedin'); ?>" />
      </section>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}

// Disable/enable comments
function _ws_comments($open) {
  if (get_option('disable_comments')) {
    $open = false;
  }
  return $open;
}
add_filter('comments_open', '_ws_comments');

// Build structured data json
function _ws_structured_data() {
  if (!is_singular()) {
    return;
  }
  ?>
  <script type="application/ld+json">
  [
    {
      "@context": "http://schema.org",
      "@type": "LocalBusiness",
      "url": "<?= home_url(); ?>",
      "name": "<?= bloginfo('name'); ?>",
      "logo": "<?= wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0]; ?>",
      <?php
      if (get_option('site_tel')) : ?>
        "telephone": "<?= get_option('site_tel'); ?>",
      <?php
      endif;
      if (get_option('theme_email')) : ?>
        "email": "mailto:<?= get_option('site_email'); ?>",
      <?php
      endif;
      if (get_option('site_location')) : ?>
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "<?= get_option('site_location_street'); ?>",
          "addressLocality": "<?= get_option('site_location_city'); ?>",
          "addressRegion": "<?= get_option('site_location_state'); ?>",
          "postalCode": "<?= get_option('site_location_zip'); ?>",
          "addressCountry": "US"
        }
      <?php
      endif; ?>
      "sameAs": [
        "<?= get_option('social_facebook'); ?>",
        "<?= get_option('social_twitter'); ?>",
        "<?= get_option('social_instagram'); ?>",
        "<?= get_option('social_youtube'); ?>",
        "<?= get_option('social_linkedin'); ?>"
      ],
      <?php
      if (get_option('site_hours')) : ?>
        "openingHoursSpecification": [
          {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Sunday",
            "opens": "<?= get_option('site_hours_open_sun'); ?>",
            "closes": "<?= get_option('site_hours_close_sun'); ?>"
          },
          {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Monday",
            "opens": "<?= get_option('site_hours_open_mon'); ?>",
            "closes": "<?= get_option('site_hours_close_mon'); ?>"
          },
          {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Tuesday",
            "opens": "<?= get_option('site_hours_open_tue'); ?>",
            "closes": "<?= get_option('site_hours_close_tue'); ?>"
          },
          {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Wednesday",
            "opens": "<?= get_option('site_hours_open_wed'); ?>",
            "closes": "<?= get_option('site_hours_close_wed'); ?>"
          },
          {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Thursday",
            "opens": "<?= get_option('site_hours_open_thu'); ?>",
            "closes": "<?= get_option('site_hours_close_thu'); ?>"
          },
          {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Friday",
            "opens": "<?= get_option('site_hours_open_fri'); ?>",
            "closes": "<?= get_option('site_hours_close_fri'); ?>"
          },
          {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Saturday",
            "opens": "<?= get_option('site_hours_open_sat'); ?>",
            "closes": "<?= get_option('site_hours_close_sat'); ?>"
          }
        ]
      <?php
      endif; ?>
    }
    <?php
    if (get_post_type() == 'post') : ?>
      ,{
        "@context": "http://schema.org",
        "@type": "Article",
        "headline": "<?= get_the_title(); ?>",
        "image": {
          "@type": "ImageObject",
          "url": "<?= get_the_post_thumbnail_url(get_the_id(), 'full'); ?>",
          "width": "<?= wp_get_attachment_image_src(get_post_thumbnail_id(), 'full')[1] ?>",
          "height": "<?= wp_get_attachment_image_src(get_post_thumbnail_id(), 'full')[2] ?>",
        },
        "datePublished": "<?= get_the_date('Y-m-d\TH:i'); ?>",
        "dateModified": "<?= get_the_modified_date('Y-m-d\TH:i'); ?>",
      }
    <?php
    endif;
    if (get_post_type() == 'event') : ?>
      ,{
        "@context": "http://schema.org",
        "@type": "Event",
        "name": "<?= get_the_title(); ?>",
        "startDate": "<?= get_post_meta(get_the_ID(), '_event-json-start', true); ?>",
        "endDate": "<?= get_post_meta(get_the_ID(), '_event-json-end', true); ?>",
        "image": "<?= get_the_post_thumbnail_url(get_the_id(), 'full'); ?>",
        // "location": {
        //   "@type": "Place",
        //   "name": "<?= get_post_meta(get_the_ID(), '_event-location-name', true); ?>",
        //   "address": {
        //     "@type": "PostalAddress",
        //     "streetAddress": "<?= get_post_meta(get_the_ID(), '_event-location-street', true); ?>",
        //     "addressLocality": "<?= get_post_meta(get_the_ID(), '_event-location-city', true); ?>",
        //     "addressRegion": "<?= get_post_meta(get_the_ID(), '_event-location-state', true); ?>",
        //     "postalCode": "<?= get_post_meta(get_the_ID(), '_event-location-zip', true); ?>",
        //     "addressCountry": "<?= get_post_meta(get_the_ID(), '_event-location-country', true); ?>"
        //   }
        // }
      }
    <?php
    endif;
    if (get_post_type() == 'job') : ?>
      ,{
        "@context": "http://schema.org",
        "@type": "JobPosting",
        "title": "<?= get_the_title(); ?>"
      }
    <?php
    endif; ?>
  ]
  </script>
  <?php
}
add_action('wp_head', '_ws_structured_data');
