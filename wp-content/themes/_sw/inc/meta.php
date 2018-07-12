<?php
// Create meta tags in the header
function _sw_meta() {
  if (!is_singular()) {
    return;
  }

  global $post;
  $seo_title = get_post_meta(get_the_ID(), '_seo-title', true);
  $seo_desc = get_post_meta(get_the_ID(), '_seo-desc', true);
  $seo_keywords = get_post_meta(get_the_ID(), '_seo-keywords', true);
  $seo_canonical = get_post_meta(get_the_ID(), '_seo-canonical', true);
  $seo_no_index = get_post_meta(get_the_ID(), '_seo-no-index', true);
  $seo_no_follow = get_post_meta(get_the_ID(), '_seo-no-follow', true);
  $social_title = get_post_meta(get_the_ID(), '_social-title', true);
  $social_desc = get_post_meta(get_the_ID(), '_social-desc', true);
  $social_img = get_post_meta(get_the_ID(), '_social-img', true);
  $social_twitter = get_post_meta(get_the_ID(), '_social-twitter', true);
  $gen_title = get_the_title();
  $content = substr(strip_tags($post->post_content), 0, 170); // Google result is 160
  $gen_desc = get_the_excerpt() ? get_the_excerpt() : $content;
  $gen_img = get_the_post_thumbnail_url();
  $html = '';

  // SEO
  if ($seo_title || $gen_title) {
    $html .= '<title>' . ($seo_title ?: $gen_title) . (get_option('seo_meta_title') && !is_front_page() ? ' ' . get_option('seo_meta_title') : '') . '</title>';
  }
  if ($seo_desc || $gen_desc) {
    $html .= '<meta name="description" content="' . htmlentities($seo_desc ?: $gen_desc) . '" />';
  }
  if ($seo_keywords) {
    $html .= '<meta name="keywords" content="' . $seo_keywords . '" />';
  }
  $robots = $seo_no_index ? 'NOINDEX, ' : 'INDEX, ';
  $robots .= $seo_no_follow ? 'NOFOLLOW' : 'FOLLOW';
  $html .= '<meta name="robots" content="' . $robots . '" />';
  if (get_query_var('amp')) {
    $html .= '<link rel="canonical" href="' . get_permalink() . '" />';
  }
  else if (array_key_exists(get_post_type(), get_option('amp') ?: array())) {
    $html .= '<link rel="amphtml" href="' . get_home_url() . '/amp/' . get_post_type() . '/' . $post->post_name . '/" />';
  }
  else {
    $html .= '<link rel="canonical" href="' . ($seo_canonical ?: get_permalink()) . '" />';
  }

  // Open Graph Protocol
  if ($social_title || $seo_title || $gen_title) {
    $html .= '<meta property="og:title" content="' . htmlentities($social_title ?: $seo_title ?: $gen_title) . '" />';
  }
  $html .= '<meta property="og:type" content="article" />';
  $html .= '<meta property="og:url" content="' . ($seo_canonical ?: get_permalink()) . '" />';
  if ($social_img) {
    $html .= '<meta property="og:image" content="' . $social_img . '" />';
  }

  // Optional
  if ($social_desc || $seo_desc || $gen_desc) {
    $html .= '<meta property="og:description" content="' . htmlentities($social_desc ?: $seo_desc ?: $gen_desc) . '" />';
  }
  $html .= '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />';

  // Twitter
  $html .= $social_img ? '<meta name="twitter:card" content="summary_large_image" />' : '<meta name="twitter:card" content="summary" />';
  if ($social_twitter) {
    $html .= '<meta name="twitter:site" content="@' . $social_twitter . '" />';
  }
  if ($social_title || $seo_title || $gen_title) {
    $html .= '<meta name="twitter:title" content="' . htmlentities($social_title ?: $seo_title ?: $gen_title) . '" />';
  }
  if ($social_desc || $seo_desc || $gen_desc) {
    $html .= '<meta name="twitter:description" content="' . htmlentities($social_desc ?: $seo_desc ?: $gen_desc) . '" />';
  }
  if ($gen_img) {
    $html .= '<meta name="twitter:image" content="' . ($social_img ?: $gen_img) . '" />';
  }

  echo $html;
}
add_action('wp_head', '_sw_meta');
