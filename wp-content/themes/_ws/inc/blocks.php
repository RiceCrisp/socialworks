<?php
// Add/remove theme support
function _ws_blocks_setup() {
  $themeJson = json_decode(file_get_contents(get_template_directory() . '/theme.json'), true);
  $colors = $themeJson['global']['colors'];
  $gradients = $themeJson['global']['gradients'];
  add_theme_support('editor-color-palette', $colors);
  add_theme_support('editor-gradient-presets', $gradients);
  add_theme_support('disable-custom-font-sizes');
  add_theme_support('responsive-embeds');
  add_theme_support('editor-font-sizes', []);
  remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', '_ws_blocks_setup');

// Enqueue block editor scripts
function _ws_enqueue_block_scripts() {
  wp_localize_script('blocks-js', 'locals', [
    'google_maps' => get_option('google_maps'),
    'svgs' => get_option('svg') ?: [],
    'posts_per_page' => get_option('posts_per_page')
  ]);
  wp_enqueue_script('blocks-js');
  wp_enqueue_script('sidebar-js');
}
add_action('enqueue_block_editor_assets', '_ws_enqueue_block_scripts');

// Menu admin menu item for reusable blocks
function _ws_reusable_blocks_admin_menu() {
  add_menu_page('Reusable Blocks', 'Reusable Blocks', 'edit_posts', 'edit.php?post_type=wp_block', '', 'dashicons-editor-table', 40);
}
add_action('admin_menu', '_ws_reusable_blocks_admin_menu');

// Setup custom block categories
function _ws_block_categories($categories) {
  return array_merge(
    $categories,
    [
      [
        'slug' => 'ws-top-level',
        'title' => __('Top Level (WS)', '_ws')
      ],
      [
        'slug' => 'ws-layout',
        'title' => __('Layout (WS)', '_ws')
      ],
      [
        'slug' => 'ws-dynamic',
        'title' => __('Dynamic (WS)', '_ws')
      ],
      [
        'slug' => 'ws-bit',
        'title' => __('Bits (WS)', '_ws')
      ],
      [
        'slug' => 'ws-meta',
        'title' => __('Metas (WS)', '_ws')
      ],
      [
        'slug' => 'ws-calculator',
        'title' => __('Calculator (WS)', '_ws')
      ]
    ]
  );
}
add_filter('block_categories', '_ws_block_categories', 10, 2);

// Wrap sections in consistent markup
function _ws_block_wrapping($a, $content, $element = 'section') {
  $classesArray = _ws_block_build_classes($a, explode(' ', trim($a['className'])));
  $stylesArray = _ws_block_build_styles($a);

  $backgroundClassesArray = _ws_block_build_background_classes($a, explode(' ', $a['className']));
  $backgroundStylesArray = _ws_block_build_background_styles($a);

  $anchor = isset($a['anchor']) && $a['anchor'] ? 'id="' . $a['anchor'] . '"' : '';
  $classes = count($classesArray) > 0 ? 'class="' . implode(' ', $classesArray) . '"' : '';
  $styles = count($stylesArray) > 0 ? 'style="' . implode('', $stylesArray) . '"' : '';
  $backgroundClasses = count($backgroundClassesArray) > 0 ? 'class="' . implode(' ', $backgroundClassesArray) . '"' : '';
  $backgroundStyles = count($backgroundStylesArray) > 0 ? 'style="' . implode('', $backgroundStylesArray) . '"' : '';

  ob_start(); ?>
    <<?= $element . ' ' . $anchor . ' ' . $classes . ' ' . $styles; ?>>
      <?php
      if (in_array('has-background', $classesArray)) : ?>
        <div <?= $backgroundClasses . ' ' . $backgroundStyles; ?>>
          <?php
          $backgroundMediaUrl = false;
          if (isset($a['backgroundMedia']) && $a['backgroundMedia']) {
            $backgroundMediaUrl = wp_get_attachment_image_src($a['backgroundMedia'], 'full')[0] ?: wp_get_attachment_url($a['backgroundMedia']);
          }
          $backgroundPosition = isset($a['backgroundX']) || isset($a['backgroundY']) ? 'style="background-position:' . ($a['backgroundX'] ?? 0.5) * 100 . '% ' . ($a['backgroundY'] ?? 0.5) * 100 . '%"' : '';
          if ($backgroundMediaUrl) :
            if (substr($backgroundMediaUrl, -4) === '.mp4') :
              $backgroundVideoPoster = $a['backgroundVideoPoster'] && $a['backgroundVideoPoster'] ? 'poster="' . $a['backgroundVideoPoster'] . '"' : ''; ?>
              <div class="block-background-media <?= isset($a['backgroundParallax']) && $a['backgroundParallax'] ? 'parallax-bg' : ''; ?>">
                <video class="block-background-video" autoplay loop muted playsinline <?= $backgroundVideoPoster; ?>>
                  <source src="<?= $backgroundMediaUrl; ?>" type="video/mp4" />
                </video>
              </div>
              <?php
            else: ?>
              <div class="block-background-media lazy-load <?= isset($a['backgroundParallax']) && $a['backgroundParallax'] ? 'parallax-bg' : ''; ?>" data-src="<?= $backgroundMediaUrl; ?>" <?= $backgroundPosition; ?>></div>
              <?php
            endif;
          endif; ?>
        </div>
        <?php
      endif; ?>
      <?= $content; ?>
    </<?= $element; ?>>
  <?php
  return ob_get_clean();
}

function _ws_block_build_classes($a, $classesArray) {
  $output = $classesArray;
  $hasThemeTextColor = isset($a['textColor']) && $a['textColor'];
  $hasCustomTextColor = isset($a['style']) && isset($a['style']['color']) && isset($a['style']['color']['text']);
  if ($hasThemeTextColor || $hasCustomTextColor) {
    $output[] = 'has-text-color';
    if ($hasThemeTextColor) {
      $output[] = 'has-' . $a['textColor'] . '-color';
    }
  }
  $hasThemeBackgroundColor = isset($a['backgroundColor']) && $a['backgroundColor'];
  $hasCustomBackgroundColor = isset($a['style']) && isset($a['style']['color']) && isset($a['style']['color']['background']);
  $hasThemeBackgroundGradient = isset($a['gradient']) && $a['gradient'];
  $hasCustomBackgroundGradient = isset($a['style']) && isset($a['style']['color']) && isset($a['style']['color']['gradient']);
  $hasBackgroundMedia = isset($a['backgroundMedia']) && $a['backgroundMedia'];
  if ($hasThemeBackgroundColor || $hasCustomBackgroundColor || $hasThemeBackgroundGradient || $hasCustomBackgroundGradient || $hasBackgroundMedia) {
    $output[] = 'has-background';
  }
  return $output;
}

function _ws_block_build_background_classes($a, $classesArray) {
  $output = ['block-background'];
  $hasThemeBackgroundColor = isset($a['backgroundColor']) && $a['backgroundColor'];
  $hasCustomBackgroundColor = isset($a['style']) && isset($a['style']['color']) && isset($a['style']['color']['background']);
  if ($hasThemeBackgroundColor || $hasCustomBackgroundColor) {
    $output[] = 'has-background-color';
    if ($hasThemeBackgroundColor) {
      $output[] = 'has-' . $a['backgroundColor'] . '-background-color';
    }
  }
  $hasThemeGradient = isset($a['gradient']) && $a['gradient'];
  $hasCustomGradient = isset($a['style']) && isset($a['style']['color']) && isset($a['style']['color']['gradient']);
  if ($hasThemeGradient || $hasCustomGradient) {
    $output[] = 'has-background-gradient';
    if ($hasThemeGradient) {
      $output[] = 'has-' . $a['gradient'] . '-gradient-background';
    }
  }
  if (isset($a['backgroundSize']) && $a['backgroundSize']) {
    $output[] = 'has-' . $a['backgroundSize'] . '-background-size';
  }
  if (isset($a['backgroundOverlay']) && $a['backgroundOverlay']) {
    $output[] = 'has-background-overlay';
  }
  return $output;
}

function _ws_block_build_styles($a) {
  $output = [];
  if (isset($a['style']) && isset($a['style']['color']) && isset($a['style']['color']['text'])) {
    $output[] = 'color:' . $a['style']['color']['text'] . ';';
  }
  return $output;
}

function _ws_block_build_background_styles($a) {
  $output = [];
  if (isset($a['style']) && isset($a['style']['color']) && isset($a['style']['color']['background'])) {
    $output[] = 'background-color:' . $a['style']['color']['background'] . ';';
  }
  if (isset($a['style']) && isset($a['style']['color']) && isset($a['style']['color']['gradient'])) {
    $output[] = 'background-image:' . $a['style']['color']['gradient'] . ';';
  }
  return $output;
}

// Pull server render files from block directory
$GLOBALS['block_renders'] = [];
function rglob($pattern) {
  $files = glob($pattern);
  foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
    $files = array_merge($files, rglob($dir.'/'.basename($pattern)));
  }
  return $files;
}

foreach (rglob(get_template_directory() . '/blocks/**/render.php') as $filename) {
  include_once $filename;
  $fileparts = explode('/', $filename);
  global $block_renders;
  $block_renders[] = $fileparts[count($fileparts) - 2];
}

// Register server block renders
function _ws_register_dynamic_blocks() {
  global $block_renders;
  foreach ($block_renders as $block) {
    register_block_type('ws/' . $block, [
      'editor_script' => 'blocks-js',
      'render_callback' => '_ws_block_' . str_replace('-', '_', $block)
    ]);
  }
}
add_action('init', '_ws_register_dynamic_blocks');
