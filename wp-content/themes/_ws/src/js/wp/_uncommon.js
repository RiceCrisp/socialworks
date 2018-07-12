/*
 * THIS FILE CONTAINS FUNCTIONALITY THAT HAS SHOWN UP IN PAST PROJECTS BUT NOT FREQUENTLY ENOUGH TO BE CONSIDERED STANDARD.
 * FEEL FREE TO INCLUDE THIS FILE IN WP.JS OR PULL FUNCTIONS OUT THAT ARE FOUND TO BE USEFUL.
 */

// Service worker logic for PWA's
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/sw.js').then(function(registration) {
      // Registration was successful
      console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
      // registration failed :(
      console.log('ServiceWorker registration failed: ', err);
    });
  });
}

// Helper function for sending form data via ajax
function serializeForm(form) {
  var field, s = [];
  var len = form.elements.length;
  for (i = 0; i < len; i++) {
    field = form.elements[i];
    if (field.name && !field.disabled && field.type != 'file' && field.type != 'reset' && field.type != 'submit' && field.type != 'button') {
      if (field.type == 'select-multiple') {
        for (ii = form.elements[i].options.length - 1; ii >= 0; ii--) {
          if (field.options[ii].selected) {
            s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[ii].value);
          }
        }
      } else if ((field.type != 'checkbox' && field.type != 'radio') || field.checked) {
        s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value);
      }
    }
  }
  return s.join('&').replace(/%20/g, '+');
}

// Actually enforce the min/max for inputs
var numbers = document.querySelectorAll('input[type=number]');
for (i = 0; i < numbers.length; i++) {
  numbers[i].addEventListener('input', checkMinMax);
  numbers[i].addEventListener('keypress', checkMinMax);
}
function checkMinMax() {
  if (this.value > parseInt(this.getAttribute('max'))) {
    this.value = this.getAttribute('max');
  } else if (this.value < parseInt(this.getAttribute('min'))) {
    this.value = this.getAttribute('min');
  }
}

// Slider Logic (Tiny Slider library)
//=include tiny-slider/dist/min/tiny-slider.js
var tinys = document.querySelectorAll('.slider-container');
for (i = 0; i < tinys.length; i++) {
  var tiny = tns({
    container: tinys[i].querySelector('.slider'),
    items: 1,
    mouseDrag: true
  });
}

// Display google maps (this counts toward api call count, so use sparingly or opt for the iframe instead)
function initMap() {
  var mapz = document.querySelectorAll('.google-map');
  for (i = 0; i < mapz.length; i++) {
    var mapObj = mapz[i];
    if (mapObj.querySelector('.map-canvas > *')) {
      google.maps.event.trigger(mapObj.map, 'resize');
      mapObj.map.fitBounds(mapObj.bounds);
      if (zoom = mapObj.querySelector('.map-canvas').getAttribute('zoom')) {
        mapObj.map.setZoom(mapObj.map.getZoom() - parseInt(zoom));
      } else {
        mapObj.map.setZoom(mapObj.map.getZoom() - 1);
      }
      continue;
    }
    var map = new google.maps.Map(mapObj.querySelector('.map-canvas'), {
      center: {lat: 37.09, lng: -95.71},
      scrollwheel: false,
      streetViewControl: false,
      mapTypeControl: false,
      fullscreenControl: false,
      zoom: 4
    });
    var markers = [];
    var locs = mapObj.querySelectorAll('.location');
    for (ii = 0; ii < locs.length; ii++) {
      var coords = locs[ii].value.split('~%~%~');
      var directions = 'https://www.google.com/maps/dir/?api=1&destination=' + encodeURI(coords[4].replace('<br>', ', '));
      var infoWindow = new google.maps.InfoWindow({
        content: '<div class="map-info-window"><a href="' + coords[2] + '">' + coords[3] + '</a><p>' + coords[4] + '</p><a href="' + directions + '" target="_blank">Get Directions</a></div>'
      });
      markers[ii] = new google.maps.Marker({
        position: {
          lat: parseFloat(coords[0]),
          lng: parseFloat(coords[1])
        },
        map: map
      });
      markers[ii].addListener('click', function() {
        infoWindow.open(map, markers[ii]);
      });
    }
    var bounds = new google.maps.LatLngBounds();
    for (ii = 0; ii < markers.length; ii++) {
      bounds.extend(markers[ii].getPosition());
    }
    map.fitBounds(bounds);
    map.set('mapObj', mapObj);
    mapObj.map = map;
    mapObj.bounds = bounds;
    google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
      if (zoom = this.mapObj.querySelector('.map-canvas').getAttribute('zoom')) {
        this.setZoom(this.getZoom() - parseInt(zoom));
      } else {
        this.setZoom(this.getZoom() - 1);
      }
    });
  }
}

// Logic for ajax email forms
var forms = document.querySelectorAll('.ajax-form');
for (i = 0; i < forms.length; i++) {
  forms[i].addEventListener('submit', function(event) {
    event.preventDefault();
    var btn = this.querySelector('input[type=submit]');
    var btnText = btn.value;
    var errorDiv = this.querySelector('.error-msg');
    btn.value = 'Sending...';
    btn.disabled = true;
    var req = new XMLHttpRequest();
    req.open('POST', locals.ajax_url, true);
    req.onload = function() {
      var jsonRes = JSON.parse(req.response);
      if ((req.status >= 200 && req.status < 400) && jsonRes.success) {
        errorDiv.innerHtml = '';
        btn.outerHTML = jsonRes.data;
      } else {
        btn.value = btnText;
        btn.disabled = false;
        errorDiv.innerHTML = jsonRes.data;
      }
    }
    req.onerror = function() {
      this.querySelector('.error-msg').innerHTML = '<p>We are sorry, but it appears that something has gone wrong. Please try again at a later time.</p>';
    }
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    req.send(serializeForm(this));
  });
}
