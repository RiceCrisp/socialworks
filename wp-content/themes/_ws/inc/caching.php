<?php
// Register caching menu
function _ws_caching_menu() {
	add_options_page('Caching', 'Caching', 'manage_options', 'caching', '_ws_caching_page');
}
add_action('admin_menu', '_ws_caching_menu');

// Register caching fields
function _ws_caching_fields() {
  register_setting('caching', 'cache');
}
add_action('admin_init', '_ws_caching_fields');

// Create caching page
function _ws_caching_page() { ?>
	<div class="wrap options-page caching-options">
		<form action="options.php" method="post">
      <h1>Caching</h1>
      <?php
			settings_fields('caching');
			do_settings_sections('caching');
      if (!wp_is_writable(get_home_path() . '.htaccess')) {
        echo '<div class="warning">Your .htaccess file needs to be writable for these options to have any affect.</div>';
      } ?>
      <section <?= !wp_is_writable(get_home_path() . '.htaccess') ? 'class="disabled"' : ''; ?>>
        <p>Browser caching helps speed up the user's experience on repeat views by saving files locally so they don't have to be requested for every page. Here you can define the maximum age files are stored in seconds.</p>
        <p><i>0 = No Caching<br />One hour = 3600<br />One day = 86400<br />One week = 604800<br />One month = 2628000<br />One year = 31536000</i></p>
        <label for="cache_media">Media <small>Images, Icons, Fonts</small></label>
        <input id="cache_media" name="cache[media]" type="number" min="0" value="<?= get_option('cache')['media'] ?: 604800; ?>" />
        <label for="cache_css">CSS</label>
        <input id="cache_css" name="cache[css]" type="number" min="0" value="<?= get_option('cache')['css'] ?: 86400; ?>" />
        <label for="cache_js">JS</label>
        <input id="cache_js" name="cache[js]" type="number" min="0" value="<?= get_option('cache')['js'] ?: 86400; ?>" />
        <label for="cache_html">HTML</label>
        <input id="cache_html" name="cache[html]" type="number" min="0" value="<?= get_option('cache')['html'] ?: 86400; ?>" />
      </section>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

// Write caching options to htaccess
function _ws_update_caching($old, $new) {
  $caching = '<IfModule mod_headers.c>
  Header set Connection keep-alive
  # Media
  <filesmatch "\.(eot|woff|otf|ttf|jpg|jpeg|png|gif|tiff|bmp|ico|svg)$">
    Header set Cache-Control "max-age=' . ($new['media'] ?: '604800') . ', public"
  </filesmatch>
  # CSS
  <filesmatch "\.(css)$">
    Header set Cache-Control "max-age=' . ($new['css'] ?: '86400') . ', public"
  </filesmatch>
  # JS
  <filesmatch "\.(js)$">
    Header set Cache-Control "max-age=' . ($new['js'] ?: '86400') . ', public"
  </filesmatch>
  # HTML
  <filesMatch "\.(x?html?|php)$">
    Header set Cache-Control "max-age=' . ($new['html'] ?: '86400') . ', public"
  </filesMatch>
</IfModule>';
  insert_with_markers(get_home_path() . '.htaccess', 'Caching', $caching);
}
add_action('update_option_cache', '_ws_update_caching', 10, 2);
