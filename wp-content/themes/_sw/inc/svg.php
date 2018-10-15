<?php
// Register svg options menu
function _sw_svg_options_menu() {
  add_options_page('SVG Manager', 'SVG Manager', 'manage_options', 'svg', '_sw_svg_options_page');
}
add_action('admin_menu', '_sw_svg_options_menu');

// Register svg options fields
function _sw_svg_options_fields() {
  // General
  register_setting('svg', 'svg');
}
add_action('admin_init', '_sw_svg_options_fields');

// Create svg options page
function _sw_svg_options_page() { ?>
  <div class="wrap options-page svg-options">
    <form action="options.php" method="post">
      <h1>SVG Manager</h1>
      <p><b>ID</b> - Unique name used by the SVG shortcode to identify the icon. Hyphen delimited.</p>
      <p><b>Title</b> - Name of SVG. Used by screenreaders to identify/describe the icon.</p>
      <p><b>ViewBox</b> - Defines the dimensions of the icon, usually coorelating to Illustrator Artboard dimensions. Must be 4 numbers (float) long with the the first two numbers being the x,y coordinates of the top left of the artboard (typically 0, 0), and the last two numbers being the width and height of the artboard, respectively.</p>
      <p><b>Paths</b> - This is the actual path data. It is encouraged that you only use path elements, so as too prevent stroke styles or fill rules from changing expected behavior, but all valid SVG elements are allowed. The result will be displayed in real-time with a black fill property. Colors and other styles will most likely be different in actual implementation.</p>
      <p><i><b>Tip:</b> Icon should fill most, if not all, of the ViewBox and should likely be a perfect square.</i></p>
      <hr />
      <p>Remember to save changes after adding any new SVG's. If you accidentally delete an SVG, just refresh the page without saving changes.</p>
      <?php
      settings_fields('svg');
      do_settings_sections('svg');
      $filename = get_template_directory() . '/template-parts/sprites.svg';
      if (!wp_is_writable($filename)) {
        echo '<div class="warning">You do not have write permissions to the SVG file.</div>';
      } ?>
      <label for="svg-import">Import Data <small>.svg file</small></label>
      <input id="svg-import" type="file" accept=".svg" />
      <span class="import-msg"></span>
      <section <?= !wp_is_writable($filename) ? 'class="disabled"' : ''; ?>>
        <button id="add-svg-top" class="button">Add SVG</button>
        <ul class="sortable-container">
          <?php
          $index = 0;
          $svgs = get_option('svg') ?: array();
          foreach ($svgs as $index=>$svg) : ?>
            <li class="sortable-item svg">
              <div class="sortable-header">
                <span class="dashicons dashicons-move sortable-handle"></span>
                <span class="dashicons dashicons-trash sortable-delete"></span>
              </div>
              <div class="sortable-content row">
                <div class="col-xs-6 svg-container">
                  <svg v-if="validViewbox" v-bind:view-box.camel="validViewbox" v-html="paths"></svg>
                </div>
                <div class="col-xs-6">
                  <label for="svg[<?= $index; ?>][id]">ID</label>
                  <input id="svg[<?= $index; ?>][id]" name="svg[<?= $index; ?>][id]" type="text" value="<?= $svg['id']; ?>" />
                  <label for="svg[<?= $index; ?>][title]">Title</label>
                  <input id="svg[<?= $index; ?>][title]" name="svg[<?= $index; ?>][title]" type="text" value="<?= $svg['title']; ?>" />
                  <label for="svg[<?= $index; ?>][viewbox]">ViewBox</label>
                  <input id="svg[<?= $index; ?>][viewbox]" v-bind:class="{invalid: !valid}" name="svg[<?= $index; ?>][viewbox]" type="text" v-model="viewbox" value="<?= $svg['viewbox']; ?>" />
                </div>
                <div class="col-xs-12">
                  <label for="svg[<?= $index; ?>][path]">Paths</label>
                  <textarea id="svg[<?= $index; ?>][path]" name="svg[<?= $index; ?>][path]" v-model="paths"><?= $svg['path']; ?></textarea>
                </div>
              </div>
            </li>
          <?php
          endforeach; ?>
        </ul>
        <button id="add-svg-bottom" class="button">Add SVG</button>
      </section>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}

// Write svgs to file
function _sw_update_svgs($old, $new) {
  $filename = get_template_directory() . '/template-parts/sprites.svg';
  $handle = fopen($filename, 'w');
  $output = '<?php header("Content-type: image/svg+xml"); ?>' . "\n" . '<svg version="1.1" xmlns="http://www.w3.org/2000/svg">' . "\n";
  foreach ($new as $svg) {
    $paths = explode("\n", $svg['path']);
    $output .= "\t" . '<symbol id="' . $svg['id'] . '" viewBox="' . $svg['viewbox'] . '" rel="img">' . "\n\t\t" . '<title>' . $svg['title'] . '</title>';
    foreach ($paths as $path) {
      $output .= "\n\t\t" . $path;
    }
    $output .= "\n\t" . '</symbol>' . "\n";
  }
  $output .= '</svg>';
  $file = fwrite($handle, $output);
  fclose($handle);
}
add_filter('update_option_svg', '_sw_update_svgs', 10, 2);
