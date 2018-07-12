// Close lightbox if cancelled
jQuery(document).on('click', '.lightbox-cancel', function(e) {
  e.preventDefault();
  jQuery(this).parents('.lightbox').hide();
  jQuery(this).parents('.lightbox:not(#module-choices)').remove();
  jQuery('.module-choice button').unbind();
});
jQuery('#module-choices').css('display', 'flex').hide();
jQuery('#content-tmce').hide();

function fireEditors() {
  tinyMCE.triggerSave();
  jQuery('.text-editor').each(function() {
    jQuery(this)[0].dispatchEvent(new Event('input', {'bubbles': true}));
  });
}

// Prevent "you are leaving the site" warning
jQuery(document).ready(function() {
  jQuery(window).off('beforeunload', null);
});

// Create the vue instance
var pb = new Vue({
  el: '#post-body-content',
  data: {
    pbData: [],
    msg: '',
    pb: true,
    sections: false,
    showSections: false,
    reOrder: false,
    publish: false,
    preview: false,
    position: 'top'
  },
  beforeCreate: function() {
    jQuery('#postdivrich').attr('v-show', '!pb');
    jQuery('#postdivrich').before('<button id="page-builder-btn" class="button" @click.prevent="switchPb()"><span v-bind:class="\'dashicons dashicons-\' + (pb ? \'text\' : \'hammer\')"></span>Switch to {{pb ? "Editor" : "PageBuilder"}}</button><div v-if="msg">{{msg}}</div>');
  },
  beforeMount: function() {
    var t = this;
    var output = '';
    var xml = this.$el.querySelector('#content').value;
    xml = xml.replace(/\[\[/g, '<').replace(/\]\]/g, '>').replace(/\&/g, '~~ampersand~~');
    xml = '<pb>' + xml + '</pb>';
    try {
      xml = jQuery.parseXML(xml);
      xml = jQuery(xml).children().children();
      if (xml) {
        this.pbData = [];
        for (i = 0; i < xml.length; i++) {
          var html = xml[i].innerHTML.replace(/~~ampersand~~/g, '&');
          this.$set(this.pbData, i, {});
          this.$set(this.pbData[i], 'slug', xml[i].nodeName);
          this.$set(this.pbData[i], 'content', html);
          this.$set(this.pbData[i], 'showOptions', false);
          this.$set(this.pbData[i], 'u_id', Math.random().toString(36).substr(2, 9));
          this.$set(this.pbData[i], 'options', {});
          for (ii = 0; ii < xml[i].attributes.length; ii++) {
            var attr = xml[i].attributes[ii].nodeName;
            var val = xml[i].attributes[ii].nodeValue.replace(/~~ampersand~~/g, '&');
            this.$set(this.pbData[i].options, attr, val);
          }
        }
      }
    }
    catch (e) {
      this.pb = false;
      this.msg = 'Your shortcodes are invalid. Please review and fix errors.';
    }
  },
  mounted: function() {
    jQuery.ajax({
      context: this,
      url: pbInfo.ajax_url,
      method: 'get',
      data: {
        action: '_ws_get_sections'
      },
      success: function(res) {
        this.sections = JSON.parse(res);
      }
    });
  },
  updated: function() {
    jQuery('.color-picker').wpColorPicker();
    if (this.reOrder) {
      checkOrder();
      this.reOrder = false;
    }
  },
  methods: {
    switchPb: function() {
      if (this.pb) { // going to text
        fireEditors();
      }
      else { // going to pb
        jQuery('.text-editor').each(function() {
          wp.editor.remove(this.id);
        });
        var output = '';
        var content = jQuery('#content').val();
        var xml = content.replace(/\[\[/g, '<').replace(/\]\]/g, '>').replace(/\&/g, '~~ampersand~~');
        xml = '<pb>' + xml + '</pb>';
        try {
          xml = jQuery.parseXML(xml);
          xml = jQuery(xml).children().children();
          if (xml) {
            this.pbData = [];
            for (i = 0; i < xml.length; i++) {
              var html = xml[i].innerHTML.replace(/~~ampersand~~/g, '&');
              this.$set(this.pbData, i, {});
              this.$set(this.pbData[i], 'slug', xml[i].nodeName);
              this.$set(this.pbData[i], 'content', html);
              this.$set(this.pbData[i], 'showOptions', false);
              this.$set(this.pbData[i], 'u_id', Math.random().toString(36).substr(2, 9));
              this.$set(this.pbData[i], 'options', {});
              for (ii = 0; ii < xml[i].attributes.length; ii++) {
                var attr = xml[i].attributes[ii].nodeName;
                var val = xml[i].attributes[ii].nodeValue.replace(/~~ampersand~~/g, '&');
                this.$set(this.pbData[i].options, attr, val);
              }
            }
          }
          this.msg = '';
        }
        catch (e) {
          this.msg = 'Your shortcodes are invalid. Please review and fix errors.';
        }
        this.reOrder = true;
      }
      this.pb = !this.pb;
      if (this.msg) {
        this.pb = false;
      }
    },
    reorder: function(start, end) {
      this.pbData.splice(end, 0, this.pbData.splice(start, 1)[0]);
      this.reOrder = true;
    },
    deleteSection: function(i) {
      var v = confirm('Do you want to delete this section?');
      if (v) {
        this.pbData.splice(i, 1);
        jQuery('.text-editor').each(function() {
          wp.editor.remove(this.id);
        });
        this.reOrder = true;
      }
    },
    addSection: function(slug) {
      jQuery('.text-editor').each(function() {
        wp.editor.remove(this.id);
      });
      if (this.position == 'top') {
        this.pbData.unshift({
          'slug': slug,
          'content': '',
          'showOptions': false,
          'u_id': Math.random().toString(36).substr(2, 9),
          'options': {}
        });
      }
      else {
        this.pbData.push({
          'slug': slug,
          'content': '',
          'showOptions': false,
          'u_id': Math.random().toString(36).substr(2, 9),
          'options': {}
        });
      }
      this.showSections = false;
      this.reOrder = true;
    },
    showSectionsFunc: function(pos) {
      this.showSections = true;
      this.position = pos || 'top';
    },
    showOptions: function(section) {
      section.showOptions = true;
      jQuery('.color-picker').wpColorPicker({
        change: function(event, ui) {
          jQuery(this)[0].dispatchEvent(new Event('input', {'bubbles': true}));
        }
      });
      jQuery('.color-picker').wpColorPicker({
        clear: function() {
          jQuery(this).prev().children('input')[0].dispatchEvent(new Event('input', {'bubbles': true}));
        }
      });
    },
    checkPublish: function() {
      fireEditors();
      this.$nextTick(function() {
        this.publish = true;
        jQuery('#publish').click();
      });
    },
    checkPreview: function() {
      fireEditors();
      this.$nextTick(function() {
        this.preview = true;
        jQuery('#post-preview').click();
      });
    }
  },
  watch: {
    pbData: {
      handler: function(newVal, oldVal) {
        if (typeof tinyMCE === 'undefined') {
          return;
        }
        var output = '';
        for (i = 0; i < newVal.length; i++) {
          var attrs = '';
          var options = Object.keys(newVal[i].options);
          for (ii = 0; ii < options.length; ii++) {
            if (v = newVal[i].options[options[ii]]) {
              attrs += ' ' + options[ii] + '="' + v + '"';
            }
          }
          output += '[[' + newVal[i].slug + attrs + ']]' + newVal[i].content + '[[/' + newVal[i].slug + ']]\n';
        }
        jQuery('#content').html(output);
      },
      deep: true
    }
  }
});

jQuery('#publish').on('click', function(e) {
  if (pb.publish) {
    return;
  }
  else {
    e.preventDefault();
    pb.checkPublish();
  }
});

jQuery('#post-preview').on('click', function(e) {
  if (pb.preview) {
    pb.preview = false;
    return;
  }
  else {
    e.preventDefault();
    pb.checkPreview();
  }
});
