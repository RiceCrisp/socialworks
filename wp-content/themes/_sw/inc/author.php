<?php
// Custom author
function _sw_custom_author($author) {
  global $post;
  return get_post_meta($post->ID, '_banner-author', true) ?: $author;
}
add_filter('the_author', '_sw_custom_author');

// Custom avatar
function _sw_custom_avatar_field($user) { ?>
  <h3>Custom Avatar</h3>
  <table class="form-table">
    <tbody>
      <tr>
        <th><label for="avatar-img">Custom Avatar</label></th>
        <td>
          <button class="button media-selector" target="#custom-avatar">Select Image</button>
          <input id="custom-avatar" style="width: 100%; max-width: 400px;" name="custom-avatar" type="text" value="<?= esc_attr(get_the_author_meta('custom_avatar', $user->ID)); ?>" />
        </td>
      </tr>
      </div>
    </tbody>
  </table>
<?php
}
add_action('show_user_profile', '_sw_custom_avatar_field');
add_action('edit_user_profile', '_sw_custom_avatar_field');

// Save custom avatar
function _sw_save_custom_avatar_field($user_id) {
	if (!current_user_can('edit_user', $user_id)) {
    return false;
  }
	update_user_meta($user_id, 'custom_avatar', $_POST['custom-avatar']);
}
add_action('personal_options_update', '_sw_save_custom_avatar_field');
add_action('edit_user_profile_update', '_sw_save_custom_avatar_field');

// Add custom avatar field
function _sw_custom_avatar($avatar, $default) {
	$custom_avatar = get_the_author_meta('custom_avatar');
  if (isset($custom_avatar) && $custom_avatar) {
    return $custom_avatar;
  } else if ($avatar) {
		return $avatar;
	} else {
		return $default;
  }
}
add_filter('get_avatar_url', '_sw_custom_avatar', 10, 2);
