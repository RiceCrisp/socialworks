// Site Options
jQuery('.site-options #site_hours').on('change', function() {
  hoursCheck();
});
var hoursCheck = (function hoursCheck() {
  if (jQuery('.site-options #site_hours').prop('checked')) {
    jQuery('.site-options .hours').show();
  } else {
    jQuery('.site-options .hours').hide();
  }
  return hoursCheck;
}());

// Sitemap
jQuery('.sitemap-options .inline-option .button').on('click', function(e) {
  e.preventDefault();
  jQuery(this).addClass('disabled');
  jQuery.ajax({
    context: this,
    url: locals.ajax_url,
    method: 'post',
    data: {
      action: '_sw_generate_sitemap'
    },
    success: function(res) {
      if (res.success) {
        jQuery('.sitemap-options #last_mod').val(res.data);
        jQuery('.sitemap-options #submit').click();
      }
    }
  });
});

// SVG Manager
function checkSVG() {
  var svgs = document.querySelectorAll('.svg-options .sortable-item');
  for (i = 0; i < svgs.length; i++) {
    if (svgs[i].querySelector('[v-model]')) {
      new Vue({
        el: svgs[i],
        data: {
          id: svgs[i].querySelector('[v-model=id]') ? svgs[i].querySelector('[v-model=id]').value : '',
          viewbox: svgs[i].querySelector('[v-model=viewbox]') ? svgs[i].querySelector('[v-model=viewbox]').value : '',
          paths: svgs[i].querySelector('[v-model=paths]') ? svgs[i].querySelector('[v-model=paths]').value : '',
          valid: true
        },
        computed: {
          validViewbox: function() {
            var match = this.viewbox.match(/(-?\d+(\.\d+)?) (-?\d+(\.\d+)?) (-?\d+(\.\d+)?) (-?\d+(\.\d+)?)/g);
            if (match && match[0] == this.viewbox) {
              this.valid = true;
              return this.viewbox;
            }
            else if (this.viewbox == '') {
              this.valid = true;
            }
            else {
              this.valid = false;
            }
          }
        },
        watch: {
          id: function(newVal, oldVal) {
            var ids = document.querySelectorAll('.svg-id');
            var c = [];
            for (var i = 0; i < ids.length; i++) {
              ids[i].classList.remove('invalid');
              if (newVal == ids[i].value) {
                c.push(ids[i]);
              }
            }
            if (c.length > 1) {
              for (var i = 0; i < c.length; i++) {
                c[i].classList.add('invalid');
              }
            }
          }
        }
      });
    }
  }
}
checkSVG();
var svgString = '<li class="sortable-item svg">\
  <div class="sortable-header">\
    <span class="dashicons dashicons-move sortable-handle"></span>\
    <span class="dashicons dashicons-trash sortable-delete"></span>\
  </div>\
  <div class="sortable-content row">\
    <div class="col-xs-6">\
      <svg v-if="validViewbox" v-bind:view-box.camel="validViewbox" v-html="paths"></svg>\
    </div>\
    <div class="col-xs-6">\
      <label for="svg[00][id]">ID</label>\
      <input id="svg[00][id]" name="svg[00][id]" type="text" value="" />\
      <label for="svg[00][title]">Title</label>\
      <input id="svg[00][title]" name="svg[00][title]" type="text" value="" />\
      <label for="svg[00][viewbox]">ViewBox</label>\
      <input id="svg[00][viewbox]" v-bind:class="{invalid: !valid}" name="svg[00][viewbox]" type="text" v-model="viewbox" value="" />\
    </div>\
    <div class="col-xs-12">\
      <label for="svg[00][path]">Paths</label>\
      <textarea id="svg[00][path]" name="svg[00][path]" v-model="paths"></textarea>\
    </div>\
  </div>\
</li>';
jQuery('#add-svg-top').on('click', function(e) {
  e.preventDefault();
  jQuery(this).next().prepend(svgString);
  checkOrder();
  checkSVG();
});
jQuery('#add-svg-bottom').on('click', function(e) {
  e.preventDefault();
  jQuery(this).prev().append(svgString);
  checkOrder();
  checkSVG();
});
jQuery('#svg-import').on('change', function(e) {
  var reader = new FileReader();
  reader.onload = function() {
    try {
      var result = reader.result.replace(/[\n\t\r]+/g, '');
      var xml = jQuery.parseXML(result);
      var svgs = xml.querySelectorAll('symbol') || xml.querySelectorAll('svg');
      for (var i = svgs.length-1; i >= 0; i--) {
        var id = svgs[i].id || '';
        var title = svgs[i].querySelector('title').innerHTML || '';
        var viewBox = svgs[i].getAttribute('viewBox') || '';
        var paths = svgs[i].querySelectorAll('*:not(title)') || '';
        var output = '';
        for (var ii = 0; ii < paths.length; ii++) {
          output += paths[ii].outerHTML.replace(/xmlns=".*" /g, '') + "\n";
        }
        output = output.slice(0, -1);
        jQuery('.sortable-container').prepend('<li class="sortable-item svg">\
          <div class="sortable-header">\
            <span class="dashicons dashicons-move sortable-handle"></span>\
            <span class="dashicons dashicons-trash sortable-delete"></span>\
          </div>\
          <div class="sortable-content row">\
            <div class="col-xs-6">\
              <svg v-if="validViewbox" v-bind:view-box.camel="validViewbox" v-html="paths"></svg>\
            </div>\
            <div class="col-xs-6">\
              <label for="svg[00][id]">ID</label>\
              <input id="svg[00][id]" name="svg[00][id]" type="text" value="' + id + '" />\
              <label for="svg[00][title]">Title</label>\
              <input id="svg[00][title]" name="svg[00][title]" type="text" value="' + title + '" />\
              <label for="svg[00][viewbox]">ViewBox</label>\
              <input id="svg[00][viewbox]" v-bind:class="{invalid: !valid}" name="svg[00][viewbox]" type="text" v-model="viewbox" value="' + viewBox + '" />\
            </div>\
            <div class="col-xs-12">\
              <label for="svg[00][path]">Paths</label>\
              <textarea id="svg[00][path]" name="svg[00][path]" v-model="paths">' + output + '</textarea>\
            </div>\
          </div>\
        </li>');
        checkOrder();
        checkSVG();
      }
      document.querySelector('.import-msg').innerHTML = 'Data successfully imported.';
      document.querySelector('.import-msg').style.color = 'green';
    }
    catch (err) {
      document.querySelector('.import-msg').innerHTML = 'There was an error reading the file. Confirm it is a .svg file and that the data is valid.';
      document.querySelector('.import-msg').style.color = 'red';
    }
  };
  reader.readAsText(e.target.files[0]);
});

// AMP
if (document.querySelector('#triggers')) {
  new Vue({
    el: '#triggers',
    data: {
      triggers: '',
    },
    computed: {
      tabbedTriggers: function() {
        return this.triggers.replace(/\n/g, '\n  ');
      }
    }
  });
}

// 301 Redirects
jQuery('.redirect-options .add button').on('click', function(e) {
  e.preventDefault();
  var container = jQuery(this).closest('.redirect');
  var oldVal = jQuery(this).closest('.redirect').find('.old input').val();
  var newVal = jQuery(this).closest('.redirect').find('.new input').val();
  // if (oldVal.charAt(0) != '/' || !oldVal.includes('http://') || !oldVal.includes('https://')) {
  //   oldVal = '/' + oldVal;
  // }
  // if (newVal.charAt(0) != '/' || !newVal.includes('http://') || !newVal.includes('https://')) {
  //   newVal = '/' + newVal;
  // }
  jQuery(this).closest('.redirect').find('.old input').val('');
  jQuery(this).closest('.redirect').find('.new input').val('');
  jQuery('.sortable-container').prepend('<li class="sortable-item redirect">\
    <div class="old">\
      <input name="redirects[00][old]" type="text" value="' + oldVal + '" readonly />\
    </div>\
    <div class="new">\
      <input name="redirects[00][new]" type="text" value="' + newVal + '" readonly />\
    </div>\
    <span class="dashicons dashicons-move sortable-handle"></span>\
    <span class="dashicons dashicons-trash sortable-delete"></span>\
  </li>');
  checkOrder();
});
jQuery('#redirect-import').on('change', function(e) {
  var reader = new FileReader();
  reader.onload = function() {
    try {
      var results = reader.result.split("\n");
      for (i = 0; i < results.length; i++) {
        var result = results[i].split(',');
        var oldVal = result[0];
        var newVal = result[1];
        if (oldVal.charAt(0) != '/' || !oldVal.includes('http://') || !oldVal.includes('https://')) {
          oldVal = '/' + oldVal;
        }
        if (newVal.charAt(0) != '/' || !newVal.includes('http://') || !newVal.includes('https://')) {
          newVal = '/' + newVal;
        }
        jQuery('.sortable-container').prepend('<li class="sortable-item redirect">\
          <div class="old">\
            <input name="redirects[00][old]" type="text" value="' + oldVal + '" readonly />\
          </div>\
          <div class="new">\
            <input name="redirects[00][new]" type="text" value="' + newVal + '" readonly />\
          </div>\
          <span class="dashicons dashicons-move sortable-handle"></span>\
          <span class="dashicons dashicons-trash sortable-delete"></span>\
        </li>');
        checkOrder();
      }
      document.querySelector('.import-msg').innerHTML = 'Data successfully imported.';
      document.querySelector('.import-msg').style.color = 'green';
    }
    catch (err) {
      document.querySelector('.import-msg').innerHTML = 'There was an error reading the file. Confirm it is a .csv file and that the data is valid (old url in first column and new url in second column).';
      document.querySelector('.import-msg').style.color = 'red';
    }
  };
  reader.readAsText(e.target.files[0]);
});

// function initMap() {
//   if (!document.getElementById('site_map')) {
//     return;
//   }
//   var map = new google.maps.Map(document.getElementById('site_map'), {
//     center: {lat: 37.09, lng: -95.71},
//     scrollwheel: false,
//     zoom: 4
//   });
//   var input = document.getElementById('site_location');
//   var locId = document.getElementById('site_location_id');
//   var autocomplete = new google.maps.places.Autocomplete(input);
//   autocomplete.bindTo('bounds', map);
//   var infowindow = new google.maps.InfoWindow();
//   var marker = new google.maps.Marker({
//     map: map,
//     anchorPoint: new google.maps.Point(0, -29)
//   });
//   var service = new google.maps.places.PlacesService(map);
//
//   var changePlace = function() {
//     infowindow.close();
//     marker.setVisible(false);
//     var place = autocomplete.getPlace();
//     if (!place && locId.value) {
//       service.getDetails({placeId: locId.value}, function(place2, status) {
//         place = place2;
//         changeLoc();
//       });
//     } else if (place) {
//       changeLoc();
//     }
//
//     function changeLoc() {
//       if (!place.geometry) {
//         event.preventDefault();
//         window.alert("No details available for input: '" + place.name + "'");
//         return;
//       }
//       if (place.geometry.viewport) {
//         map.fitBounds(place.geometry.viewport);
//       } else {
//         map.setCenter(place.geometry.location);
//         map.setZoom(15);
//       }
//       marker.setIcon(/** @type {google.maps.Icon} */({
//         url: place.icon,
//         size: new google.maps.Size(71, 71),
//         origin: new google.maps.Point(0, 0),
//         anchor: new google.maps.Point(17, 34),
//         scaledSize: new google.maps.Size(35, 35)
//       }));
//       marker.setPosition(place.geometry.location);
//       marker.setVisible(true);
//       var address = '';
//       if (place.address_components) {
//         var number = '', street = '', room = '', city = '', state = '', zip = '', country = '';
//         for (var i = 0; i < place.address_components.length; i++) {
//           var addressType = place.address_components[i].types[0];
//           if (addressType == 'street_number') {
//             number = place.address_components[i].short_name;
//           } else if (addressType == 'route') {
//             street = place.address_components[i].short_name;
//           } else if (addressType == 'subpremise') {
//             room = '#' + place.address_components[i].long_name;
//           } else if (addressType == 'locality') {
//             city = place.address_components[i].long_name;
//           } else if (addressType == 'administrative_area_level_1') {
//             state = place.address_components[i].short_name;
//           } else if (addressType == 'postal_code') {
//             zip = place.address_components[i].short_name;
//           } else if (addressType == 'country') {
//             country = place.address_components[i].short_name;
//           }
//         }
//         address = number + ' ' + street + ' ' + room + '<br>' + city + ', ' + state + ' ' + zip;
//       }
//       infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
//       infowindow.open(map, marker);
//       document.getElementById('site_location_id').value = place.place_id;
//       // document.getElementById('theme_location_name').value = place.name;
//       document.getElementById('site_location_street').value = number + ' ' + street + ' ' + room;
//       document.getElementById('site_location_city').value = city;
//       document.getElementById('site_location_state').value = state;
//       document.getElementById('site_location_zip').value = zip;
//       document.getElementById('site_location_country').value = country;
//       // document.getElementById('theme_location_readable').value = address;
//     }
//   };
//   autocomplete.addListener('place_changed', changePlace);
//   // Show place when page loads
//   google.maps.event.addListenerOnce(map, 'idle', changePlace);
// }
