<?php
// Pagebuilder modules
$modules = array(
  'text' => array(
    'title' => 'Text',
    'desc' => 'Text section has a shorter width so that text lines are easier to read',
    'attrs' => array(
    ),
    'frontend' => function($content) {
      return '<div class="container row">
        <div class="col-xl-8 col-xl-offset-2 col-lg-10 col-lg-offset-1">' . $content . '</div>
      </div>';
    },
    'backend' => function() {
      $output = '<li class="row">
        <div class="col-xs-12">
          <textarea id="text-00" class="text-editor" v-model="section.content"></textarea>
        </div>
      </li>';
      return $output;
    }
  )
);

foreach ($modules as $name=>$module) {
  add_shortcode($name, 'pb_shortcode');
}
function pb_shortcode($atts, $content = null, $shortcode = '') {
  global $modules;
  $module = $modules[$shortcode];
  $standard_attrs = array(
    'id' => '',
    'class' => '',
    'bg_color' => '',
    'bg_img' => '',
    'bg_pos' => ''
  );
  $attrs = array_merge($standard_attrs, $module['attrs']);
  $a = shortcode_atts($attrs, $atts);
  $output = '<section ' . ($a['id'] ? 'id="' . $a['id'] . '" ' : '') . 'class="pb-item pb-' . $shortcode . ($a['class'] ? ' ' . $a['class'] : '') . '" ' . 'style="' . ($a['bg_color'] ? 'background-color:' . $a['bg_color'] . ';' : '') . ($a['bg_img'] ? 'background-image:url(' . $a['bg_img'] . ');' : '') . ($a['bg_pos'] ? 'background-position: ' . $a['bg_pos'] . ';' : '') . '">';

  $custom_attrs = array_intersect_key($a, $module['attrs']);
  array_unshift($custom_attrs, $content);
  $output .= do_shortcode(call_user_func_array($module['frontend'], $custom_attrs));

  $output .= '</section>';
  return $output;
}

// Build pagebuilder
function _sw_pagebuilder($post) {
  if (get_post_meta($post->ID, '_wp_page_template', true) != 'templates/pagebuilder.php') {
    return;
  }
  wp_nonce_field(basename(__FILE__), 'pagebuilder-nonce'); ?>
  <div id="page-builder" v-show="pb">
    <h1>Page Builder</h1>
    <button id="add-section-top" class="button" @click.prevent="showSectionsFunc('top')"><span class="dashicons dashicons-plus"></span>Add Section</button>
    <div v-if="showSections" class="lightbox">
      <div class="lightbox-bg" @click="showSections = false"></div>
      <div class="lightbox-header sortable-header">
        <h4 class="sortable-title">Add Section</h4>
        <span class="dashicons dashicons-no-alt lightbox-close" @click="showSections = false"></span>
      </div>
      <div class="lightbox-content">
        <ul class="section-selections">
          <li v-for="(s, k, i) in sections">
            <button class="button" @click.prevent="addSection(k)">
              <h4>{{s.title}}</h4>
              <p>{{s.desc}}</p>
            </button>
          </li>
        </ul>
      </div>
    </div>
    <!-- <button id="load-section" class="button"><span class="dashicons dashicons-category"></span>Load Section</button> -->
    <ul id="sections" class="sortable-container">
      <li v-for="(section, i) in pbData" :key="section.u_id" :class="'sortable-item section postbox pb-' + section.slug">
        <div class="sortable-header">
          <h4 class="sortable-title">{{sections ? sections[section.slug].title : ''}}</h4>
          <span class="dashicons dashicons-move sortable-handle"></span>
          <span class="dashicons dashicons-admin-generic section-options-btn" @click="showOptions(section)"></span>
          <span class="dashicons dashicons-trash sortable-delete" @click.stop="deleteSection(i)"></span>
        </div>
        <div v-show="section.showOptions" class="lightbox options-lightbox">
          <div class="lightbox-bg" @click="showOptions(section) = false"></div>
          <div class="lightbox-header sortable-header">
            <h4 class="sortable-title">{{sections ? sections[section.slug].title : ''}} Options</h4>
            <!-- <svg width="20" height="20" viewBox="0 0 24 24">
              <path d="M15.003 3h2.997v5h-2.997v-5zm8.997 1v20h-24v-24h20l4 4zm-19 5h14v-7h-14v7zm16 4h-18v9h18v-9z"></path>
            </svg> -->
            <span class="dashicons dashicons-no-alt lightbox-close" @click="section.showOptions = false"></span>
          </div>
          <div class="lightbox-content">
            <ul>
              <li class="row">
                <div class="col-xs-6">
                  <label :for="'custom-id-' + i">ID</label>
                  <input :id="'custom-id-' + i" type="text" v-model="section.options.id" />
                </div>
                <div class="col-xs-6">
                  <label :for="'custom-class-' + i">Class</label>
                  <input :id="'custom-class-' + i" type="text" v-model="section.options.class" />
                </div>
              </li>
              <li>
                <div class="col-xs-12">
                  <fieldset>
                    <legend>Background</legend>
                    <input :id="'background-' + i + '-color'" class="slide-tab" type="radio" v-model="section.options.bg" value="color" />
                    <label :for="'background-' + i + '-color'"><span>Colors</span><span class="dashicons dashicons-yes"></span></label>
                    <input :id="'background-' + i + '-img'" class="slide-tab" type="radio" v-model="section.options.bg" value="img" />
                    <label :for="'background-' + i + '-img'"><span>Image</span><span class="dashicons dashicons-yes"></span></label>
                    <div v-show="section.options.bg == 'color'" class="background-slide">
                      <input :id="'background-color-' + i + '-orange'" class="color-btn" type="radio" v-model="section.options.bg_color" value="orange" />
                      <label :for="'background-color-' + i + '-orange'" style="background-color:orange;"><span class="dashicons dashicons-yes"></span></label>
                      <input :id="'background-color-' + i + '-blue'" class="color-btn" type="radio" v-model="section.options.bg_color" value="blue" />
                      <label :for="'background-color-' + i + '-blue'" style="background-color:blue;"><span class="dashicons dashicons-yes"></span></label>
                    </div>
                    <div v-show="section.options.bg == 'img'" class="background-slide">
                      <div class="row">
                        <button class="button media-selector" :target="'#background-img-' + i">Select Image</button>
                        <input :id="'background-img-' + i" class="flex-1" type="text" v-model="section.options.bg_img" />
                      </div>
                      <div v-if="section.options.bg_img" class="media-preview" :class="{overlay: section.options.bg_overlay}">
                        <img :src="section.options.bg_img" alt="Preview" />
                        <div :style="{left: section.options.bg_x + '%', top: section.options.bg_y + '%'}" class="media-focus"></div>
                      </div>
                      <div v-if="section.options.bg_img" class="row banner-align">
                        <div>
                          <label :for="'background-x-' + i">X</label>
                          <div class="row">
                            <input type="number" min="0" max="100" v-model="section.options.bg_x" />
                            <div class="pre-input">%</div>
                          </div>
                        </div>
                        <div>
                          <label :for="'background-y-' + i">Y</label>
                          <div class="row">
                            <input type="number" min="0" max="100" v-model="section.options.bg_y" />
                            <div class="pre-input">%</div>
                          </div>
                        </div>
                      </div>
                      <div v-if="section.options.bg_img" class="overlay-option">
                        <input :id="'background-overlay-' + i" type="checkbox" v-model="section.options.bg_overlay" />
                        <label :for="'background-overlay-' + i">Overlay</label>
                      </div>
                    </div>
                  </fieldset>
                  <!-- <input :id="'background-' + i" name="background" type="radio" />
                  <label :for="'background-' + i">Background</label>

                  <label :for="'background-color-' + i">Background Color</label>
                  <input :id="'background-color-' + i" class="color-picker" data-alpha="true" type="text" v-model="section.options.bg_color" /> -->
                </div>
              </li>
              <!-- <li>
                <div class="col-xs-12">
                  <label :for="'background-img-' + i">Background Image</label>
                  <div class="row">
                    <button class="button media-selector" :target="'#background-img-' + i">Select Image</button>
                    <input :id="'background-img-' + i" class="flex-1" type="text" v-model="section.options.bg_img" />
                  </div>
                </div>
              </li> -->
            </ul>
          </div>
        </div>
        <div class="sortable-content section-inside">
          <ul>
            <?php
            global $modules;
            foreach ($modules as $key=>$module) : ?>
              <template v-if="section.slug == '<?= $key; ?>'">
                <?= call_user_func_array($module['backend'], array()); ?>
              </template>
            <?php
            endforeach; ?>
          </ul>
        </div>
      </li>
    </ul>
    <button id="add-section-bottom" class="button" @click.prevent="showSectionsFunc('bottom')"><span class="dashicons dashicons-plus"></span>Add Section</button>
  </div>
  <?php
}
add_action('edit_form_after_editor', '_sw_pagebuilder');

// Ajax get sections
function _sw_get_sections() {
  global $modules;
  echo json_encode($modules);
  wp_die();
}
add_action('wp_ajax__sw_get_sections', '_sw_get_sections');
