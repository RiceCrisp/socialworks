//=include vue/dist/vue.js

// SEO advanced options toggle
jQuery('#seo-meta-inside #seo-advanced-options-toggle').on('click', function(e) {
  e.preventDefault();
  jQuery('#seo-meta-inside #seo-advanced-options').slideToggle();
});

// Handle SEO preview
jQuery('#seo-meta-inside #seo-title').on('input', function() {
  jQuery('#seo-meta-inside #seo-preview-title .title').html(jQuery(this).val());
});
jQuery('#seo-meta-inside #seo-canonical').on('input', function() {
  jQuery('#seo-meta-inside #seo-preview-url').html(jQuery(this).val());
});
jQuery('#seo-meta-inside #seo-desc').on('input', function() {
  jQuery('#seo-meta-inside #seo-preview-desc').html(jQuery(this).val());
});
if (jQuery('#seo-meta-inside #seo-title').val()) {
  jQuery('#seo-meta-inside #seo-title').trigger('input');
}
if (jQuery('#seo-meta-inside #seo-canonical').val()) {
  jQuery('#seo-meta-inside #seo-canonical').trigger('input');
}
if (jQuery('#seo-meta-inside #seo-desc').val()) {
  jQuery('#seo-meta-inside #seo-desc').trigger('input');
}

// Media selector
var mediaUploader;
jQuery(document).on('click', '.media-selector', function(e) {
  e.preventDefault();
  // var target = jQuery(this).attr('target');
  // var size = jQuery(this).attr('size');
  mediaUploader = wp.media.frames.file_frame = wp.media({
    title: 'Select Image',
    button: {
    text: 'Select Image'
  }, multiple: false });
  mediaUploader.on('select', function() {
    var attachment = mediaUploader.state().get('selection').first().toJSON();
    console.log(attachment);
    jQuery(e.target).next().val(attachment.id);
    if (attachment.sizes && attachment.sizes.standard) {
      jQuery(e.target).next().next().find('img').attr('src', attachment.sizes.standard.url);
    }
    else {
      jQuery(e.target).next().next().find('img').attr('src', attachment.url);
    }
    jQuery(e.target).next()[0].dispatchEvent(new Event('input', {'bubbles': true}));
  });
  mediaUploader.open();
});
jQuery(document).on('click', '.media-preview button', function(e) {
  e.preventDefault();
  jQuery(e.target).closest('.media-preview').find('img').attr('src', '');
  jQuery(e.target).closest('.media-preview').prev().val('');
  jQuery(e.target).closest('.media-preview').prev()[0].dispatchEvent(new Event('input', {'bubbles': true}));
});

// Actually enfore the min/max for inputs
jQuery('input[type=number]').on('input keypress', function(e) {
  // if (e.key=='-' || e.key=='.' || e.key=='+') {
  //   return false;
  // }
  if (jQuery(this).val() > parseInt(jQuery(this).attr('max'))) {
    jQuery(this).val(jQuery(this).attr('max'));
  } else if (jQuery(this).val() < parseInt(jQuery(this).attr('min'))) {
    jQuery(this).val(jQuery(this).attr('min'));
  }
});

// Post selector
function checkPostSelectors() {
  jQuery('.post-selector').each(function() {
    if (jQuery(this).attr('touched')) {
      return true;
    }
    jQuery(this).attr('touched', true);
    jQuery(this).attr('type', 'hidden');
    jQuery.ajax({
      context: this,
      url: locals.ajax_url,
      method: 'post',
      data: {
        action: '_sw_post_selector_types',
        id: jQuery(this).attr('id'),
        value: jQuery(this).val(),
        post_type: jQuery(this).attr('post-type') || '',
        multiple: jQuery(this).attr('multiple') || ''
      },
      success: function(res) {
        jQuery(this).after(res);
        var field = jQuery(this);
        jQuery('.chips').sortable({
          forcePlaceholderSize: true,
          start: function(e, ui) {
            var removed = field.val().split(',');
            removed.splice(ui.item.index(), 1);
            field.val(removed.join(','));
          },
          stop: function(e, ui) {
            var removed = field.val().split(',');
            removed.splice(ui.item.index(), 0, ui.item.attr('pid'));
            field.val(removed);
            field[0].dispatchEvent(new Event('input', {'bubbles': true}));
          }
        });
        if (jQuery(this).attr('multiple')) {
          // Multi add
          jQuery(this).next().next().on('change', '.post-selector-id input[type=radio]', function() {
            var field = jQuery(this).closest('.post-selector-container').prev().prev();
            if (field.val()) {
              field.val(field.val() + ',' + jQuery(this).val());
            }
            else {
              field.val(jQuery(this).val());
            }
            jQuery(this).closest('.post-selector-container').prev().append('<li pid="' + jQuery(this).val() + '"><span>' + jQuery(this).next().html() + '</span><button><span class="dashicons dashicons-no-alt"></span></button></li>');
            field[0].dispatchEvent(new Event('input', {'bubbles': true}));
          });
          // Multi remove
          jQuery(this).next().on('click', 'button', function(e) {
            e.preventDefault();
            var chip = jQuery(this).closest('li');
            var removed = jQuery(this).closest('.chips').prev().val().split(',');
            removed.splice(chip.index(), 1);
            jQuery(this).closest('.chips').prev().val(removed.join(','));
            jQuery(this).closest('.chips').prev()[0].dispatchEvent(new Event('input', {'bubbles': true}));
            chip.remove();
          });
        }
      }
    });
    // View post types
    jQuery('body').on('change', '.post-selector-type input[type=radio]', function() {
      var field = jQuery(this).closest('.post-selector-container').prev();
      jQuery.ajax({
        context: this,
        url: locals.ajax_url,
        method: 'post',
        data: {
          action: '_sw_post_selector_posts',
          id: field.attr('id'),
          value: field.val(),
          post_type: jQuery(this).val()
        },
        success: function(res) {
          jQuery(this).closest('.post-selector-container').children('.post-selector-id').html(res);
        }
      });
    });
    // Save post type
    jQuery('body').on('input', '.post-selector-filter', function() {
      var value = jQuery(this).val().toLowerCase();
      jQuery(this).next().children().each(function() {
        try {
          var thisValue = jQuery(this).children('label').html().toLowerCase();
          if (thisValue.includes(value)) {
            jQuery(this).show();
          } else {
            jQuery(this).hide();
          }
        } catch (err) {}
      });
    });
    // Single change
    jQuery('body').on('change', '.post-selector:not([multiple]) ~ .post-selector-container .post-selector-id input[type=radio]', function() {
      var field = jQuery(this).closest('.post-selector-container').prev();
      field.val(jQuery(this).val());
      field[0].dispatchEvent(new Event('input', {'bubbles': true}));
    });
  });
}
checkPostSelectors();

// SVG selector
function checkSvgSelectors() {
  jQuery('.svg-selector').each(function() {
    if (jQuery(this).attr('touched')) {
      return true;
    }
    jQuery(this).attr('touched', true);
    jQuery(this).attr('type', 'hidden');
    jQuery.ajax({
      context: this,
      url: locals.ajax_url,
      method: 'post',
      data: {
        action: '_sw_svg_selector',
        id: jQuery(this).attr('id'),
        value: jQuery(this).val()
      },
      success: function(res) {
        jQuery(this).after(res);
      }
    });
  });
  jQuery(document).on('click', function(e) {
    jQuery('.svg-selector-container ul').each(function() {
      jQuery(this).css('display', 'none');
    });
    if (e.target.closest('.svg-selector-container button')) {
      e.preventDefault();
      jQuery(e.target).closest('.svg-selector-container').find('ul').css('display', 'flex');
    }
  });
  jQuery('body').on('change', '.svg-selector-container input[type=radio]', function() {
    var svg = jQuery(this).closest('.svg-selector-container').find('.svg-selection use');
    svg.attr('xlink:href', '/wp-content/themes/_sw/template-parts/sprites.svg#' + jQuery(this).val());
    var field = jQuery(this).closest('.svg-selector-container').prev();
    field.val(jQuery(this).val());
    field[0].dispatchEvent(new Event('input', { 'bubbles': true }));
  });
}
checkSvgSelectors();

// Initialize any text editors
jQuery(document).ready(function() {
  jQuery('.text-editor').each(function() {
    if (jQuery(this).closest('.sortable-item').length) {
      return true;
    }
    wp.editor.initialize(this.id, {
      mediaButtons: true,
      tinymce: {
        block_formats: 'Paragraph=p;Header 2=h2;Header 3=h3;Header 4=h4;Preformatted=pre',
        toolbar1: 'formatselect, bold, italic, bullist, numlist, blockquote, alignleft, aligncenter, alignright, link, unlink, undo, redo',
        content_css: '/wp-content/themes/_sw/dist/css/wp/globals.min.css'
      },
      quicktags: true
    });
  });
});

// Check order of sortable items (we add a tildes so that no fields share the same id at any single moment)
function checkOrder() {
  jQuery('.sortable-container').each(function() {
    jQuery(this).find('.sortable-item').each(function(i) {
      // We want to remove wp.editor fields first before their ids change
      jQuery(this).find('.text-editor').each(function() {
        wp.editor.remove(this.id);
      });
      jQuery(this).find('label').each(function() {
        if (jQuery(this).attr('for')) {
          var oldFor = jQuery(this).attr('for');
          jQuery(this).attr('for', oldFor.replace(/\d+/g, '~'+i));
        }
      });
      jQuery(this).find('input, textarea').each(function() {
        if (jQuery(this).attr('id')) {
          var oldId = jQuery(this).attr('id');
          jQuery(this).attr('id', oldId.replace(/\d+/g, '~'+i));
        }
        if (jQuery(this).attr('name')) {
          var oldName = jQuery(this).attr('name');
          jQuery(this).attr('name', oldName.replace(/\d+/g, '~'+i));
        }
      });
      jQuery(this).find('button').each(function() {
        if (jQuery(this).attr('target')) {
          var oldTarget = jQuery(this).attr('target');
          jQuery(this).attr('target', oldTarget.replace(/\d+/g, '~'+i));
        }
      });
    });
    // Remove the tildes
    jQuery(this).find('label').each(function() {
      var v = jQuery(this).attr('for')
      if (v) {
        jQuery(this).attr('for', v.replace(/~/g, ''));
      }
    });
    jQuery(this).find('input, textarea').each(function() {
      var v = jQuery(this).attr('id')
      if (v) {
        jQuery(this).attr('id', v.replace(/~/g, ''));
      }
      var v = jQuery(this).attr('name')
      if (v) {
        jQuery(this).attr('name', v.replace(/~/g, ''));
      }
    });
    jQuery(this).find('button').each(function() {
      var v = jQuery(this).attr('target')
      if (v) {
        jQuery(this).attr('target', v.replace(/~/g, ''));
      }
    });
    checkPostSelectors();
    checkSvgSelectors();
    jQuery(this).find('.text-editor').each(function() {
      wp.editor.initialize(this.id, {
        mediaButtons: true,
        tinymce: {
          block_formats: 'Paragraph=p;Header 2=h2;Header 3=h3;Header 4=h4;Preformatted=pre',
          toolbar1: 'formatselect, bold, italic, bullist, numlist, blockquote, alignleft, aligncenter, alignright, link, unlink, undo, redo',
          content_css: '/wp-content/themes/_sw/dist/css/wp/globals.min.css'
        },
        quicktags: true
      });
    });
  });
}

// Sortable items are draggable if they have a child element with the "sortable-handle" class
jQuery(document).ready(function() {
  var start = 0;
  var end = 0;
  jQuery('.sortable-container').sortable({
    axis: 'y',
    handle: '.sortable-handle',
    cursor: 'ns-resize',
    placeholder: 'sortable-placeholder',
    forcePlaceholderSize: true,
    start: function(e, ui) {
      start = ui.item.index();
    },
    stop: function(e, ui) {
      end = ui.item.index();
      if (typeof pb !== 'undefined') {
        pb.reorder(start, end);
      }
      else {
        checkOrder();
      }
    }
  });
  checkOrder();
});
// Delete sortable item functionality
jQuery('body').on('click', '.sortable-delete', function(e) {
  e.preventDefault();
  jQuery(this).closest('.sortable-item').remove();
  checkOrder();
});
