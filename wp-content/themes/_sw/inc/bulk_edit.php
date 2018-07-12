<?php
// Register bulk edit menu
function _sw_bulk_edit_menu() {
  add_options_page('Bulk Edit', 'Bulk Edit', 'manage_options', 'bulk', '_sw_bulk_edit_page');
}
add_action('admin_menu', '_sw_bulk_edit_menu');

// Register site options fields
function _sw_bulk_edit_fields() {
  // General
  register_setting('bulk', 'test');
}
add_action('admin_init', '_sw_bulk_edit_fields');

// Create site options page
function _sw_bulk_edit_page() { ?>
  <div class="wrap options-page bulk-edit">
    <form action="options.php" method="post">
      <h1>Bulk Edit</h1>
      <?php
      settings_fields('bulk');
      do_settings_sections('bulk'); ?>
      <section>
        <h2>Export</h2>
        <p>Download a .csv file of all public posts/pages.</p>
        <fieldset>
          <legend>Post Types</legend>
          <ul>
            <?php
            $postTypes = get_post_types(array('public'=>true));
            unset($postTypes['attachment']);
            foreach ($postTypes as $p) {
              echo '<li class="inline-option"><input id="' . $p . '" name="post_types" type="checkbox" value="' . $p . '" checked /><label for="' . $p . '">' . get_post_type_object($p)->labels->name . '</label></li>';
            } ?>
          </ul>
        </fieldset>
        <input id="bulk-download" class="button" type="button" value="Download" />
      </section>
      <section>
        <h2>Import</h2>
        <p>Upload a .csv file with all fields that you want to update. First column should be the post/page id, but the rest of the columns can be any meta field that you want to update. Be sure to include the field name in the first row. If using the previously exported csv file, delete the "url" column. It is only included to help associate a post id with the title.</p>
        <p><i>Example:</i>
          <table class="bulk-table">
            <tr>
              <td>id</td>
              <td>_seo-title</td>
              <td>_seo-desc</td>
            </tr>
            <tr>
              <td>1</td>
              <td>Title 1</td>
              <td>Description 1</td>
            </tr>
            <tr>
              <td>2</td>
              <td>Title 2</td>
              <td>Description 2</td>
            </tr>
          </table>
        </p>
        <!-- <ul>
          <li class="inline-option">
            <input id="overwrite" type="checkbox" checked /><label for="overwrite">Skip Blank Cells <small>Uncheck if want blank cells to erase previous values</small></label>
          </li>
        </ul> -->
        <div>
          <input id="file-upload" name="upload" type="file" accept=".csv" />
        </div>
        <input id="bulk-upload" class="button" type="button" value="Upload" />
      </section>
    </form>
  </div>
  <?php
}
