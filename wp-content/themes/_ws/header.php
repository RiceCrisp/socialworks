<!DOCTYPE html>
<html <?= get_query_var('amp') ? '⚡ ' : ''; ?><?php language_attributes(); ?>>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#FFFFFF">
  <!-- <meta http-equiv="Content-Security-Policy" content="script-src 'unsafe-inline' 'self' https://apis.google.com"> -->
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php
  if ($tag_manager_id = get_option('tag_manager_id')) : ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $tag_manager_id; ?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
  <?php
  endif; ?>
  <a class="screen-reader-text" href="#main">Skip to content</a>
  <header id="site-header" class="fixed">
    <div class="container row">
      <div class="logo-title">
        <?php
        if (has_custom_logo()) : ?>
          <div class="logo"><?php the_custom_logo(); ?></div>
        <?php
        else : ?>
          <h1 class="site-title"><a href="<?= esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
        <?php
        endif; ?>
      </div>
      <input id="hamburger" type="checkbox" />
      <label for="hamburger" class="hamburger">
        <svg viewBox="0 0 100 100" rel="img">
          <title>Menu</title>
          <path class="closed" d="M10 22 L90 22 M10 50 L90 50 M10 78 L90 78" />
          <path class="opened" d="M20 20 L80 80 Z M80 20 L20 80 Z" />
        </svg>
      </label>
      <div class="menu-container">
        <?php
        class WPDocs_Walker_Nav_Menu extends Walker_Nav_Menu {
          public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
              $t = '';
              $n = '';
            } else {
              $t = "\t";
              $n = "\n";
            }
            $indent = ($depth) ? str_repeat($t, $depth) : '';
            $classes = empty($item->classes) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
            $args = apply_filters('nav_menu_item_args', $args, $item, $depth);
            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
            $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';
            $output .= $indent . '<li' . $id . $class_names .'>';
            $atts = array();
            $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
            $atts['target'] = ! empty($item->target)     ? $item->target     : '';
            $atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
            $atts['href']   = ! empty($item->url)        ? $item->url        : '';
            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
            $attributes = '';
            foreach ($atts as $attr => $value) {
              if (! empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
              }
            }
            $title = apply_filters('the_title', $item->title, $item->ID);
            $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= '</a>';
            $item_output .= do_shortcode('<input id="' . $item->object_id . '" type="checkbox" /><label class="dropdown" for="' . $item->object_id . '">[svg id="arrow-down"]</label>');
            $item_output .= $args->after;
            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
          }
        }
        wp_nav_menu(array(
          'theme_location' => 'header',
          'container' => 'nav',
          'container_class' => 'header-menu',
          'walker' => new WPDocs_Walker_Nav_Menu(), // Comment this line to remove mobile dropdowns
          'item_spacing' => 'discard'
        )); ?>
      </div>
    </div>
  </header>
