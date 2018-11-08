"use strict";

/*
 * THIS FILE CONTAINS FUNCTIONALITY THAT HAS SHOWN UP FREQUENTLY ENOUGH IN PAST PROJECTS THAT IT HAS BECOME FAIRLY STANDARD.
 * HOWEVER, IF A PROJECT DOESN'T REQUIRE CERTAIN METHODS/LIBRARIES, COMMENT THEM OUT TO LIMIT FILE SIZE.
 */
 // console.time('JS Runtime');

//=include svg4everybody/dist/svg4everybody.min.js
//=include objectFitPolyfill/dist/objectFitPolyfill.min.js

// Sets the correct transition-end event depending on browser
var transitionEvent = (function whichTransitionEvent() {
  var t;
  var el = document.createElement('fakeelement');
  var transitions = {
    'transition':'transitionend',
    'OTransition':'oTransitionEnd',
    'MozTransition':'transitionend',
    'WebkitTransition':'webkitTransitionEnd'
  }
  for (t in transitions) {
    if (el.style[t] !== undefined) {
      return transitions[t];
    }
  }
})();

// Helper function to see if we're in a child element (for elements added dynamically)
function checkParents(el, selector) {
  var cur = el;
  var all = document.querySelectorAll(selector);
  while (cur) {
    for (var i = 0; i < all.length; i++) {
      if (all[i] == cur) {
        return cur;
      }
    }
    cur = cur.parentNode;
  }
  return false;
}

// Helper function for scrolling
function onScreen(el, offset) {
  if (offset == null) {
    offset = 0;
  }
  var isVisible = false;
  if (el) {
    if (el.offsetParent == null) {
      return false;
    }
    var elemTop = el.getBoundingClientRect().top;
    var elemBottom = el.getBoundingClientRect().bottom;
    isVisible = ((elemTop >= 0) && (elemTop <= window.innerHeight + offset)) // if top of element is on screen
    || ((elemBottom >= 0) && (elemBottom <= window.innerHeight - offset)) ; // if bottom of element is on screen
  }
  return isVisible;
}

(function() {
  // Lightbox logic
  const lbs = document.querySelectorAll('a.lightbox-link');
  for (var i = 0; i < lbs.length; i++) {
    lbs[i].addEventListener('click', function(event) {
      event.preventDefault();
      const lb = document.createElement('div');
      lb.classList.add('lightbox');
      lb.setAttribute('role', 'dialog');
      lb.innerHTML = '<div class="container row">\
        <div class="col-xs-12 lightbox-content">\
          <div class="video-container">\
            <iframe src="' + this.getAttribute('href') + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>\
          </div>\
        </div>\
        <button class="lightbox-close-click" aria-label="Close Lightbox"></button>\
      </div>';
      document.body.insertBefore(lb, document.body.firstChild);
      document.body.classList.add('no-scroll');
      window.setTimeout(function() {
        document.querySelector('.lightbox').classList.add('show');
      }, 10);
    });
  }
  document.addEventListener('click', function(event) {
    if (event.target.classList.contains('lightbox-close-click')) {
      const lightbox = document.querySelector('.lightbox');
      lightbox.classList.remove('show');
      lightbox.addEventListener(transitionEvent, function lightboxListener(event) {
        event.target.removeEventListener(event.type, lightboxListener);
        document.body.classList.remove('no-scroll');
        const lightbox = document.querySelector('.lightbox');
        try {
          lightbox.parentElement.removeChild(lightbox);
        }
        catch (err) {}
      });
    }
  });

  // Add class to header if page is not at its top
  var scrollCheck = (function scrollCheck() {
    if (window.scrollY > 50) {
      document.querySelector('#site-header').classList.add('scroll-menu');
    } else {
      document.querySelector('#site-header').classList.remove('scroll-menu');
    }
    return scrollCheck;
  })();
  window.addEventListener('scroll', function() {
    scrollCheck();
  });

  // Social Media share buttons
  var shares = document.querySelectorAll('.share-button');
  for (var i = 0; i < shares.length; i++) {
    shares[i].addEventListener('click', function(event) {
      event.preventDefault();
      window.open(this.getAttribute('href'), this.getAttribute('target'), 'resizeable,width=550,height=520');
    })
  }

  // Infinite scroll
  var btns = document.querySelectorAll('.infinite-scroll-btn')
  if (btns) {
    for (var i = 0; i < btns.length; i++) {
      var div = document.createElement('div');
      div.classList.add('col-xs-12', 'load-more');
      var btn = document.createElement('button');
      btn.innerHTML = 'Load More';
      div.appendChild(btn);
      btns[i].appendChild(div);
    }
  }
  document.addEventListener('click', function(event) {
    var btn = checkParents(event.target, '.load-more button');
    if (btn) {
      event.preventDefault();
      var loopContainer = btn.parentNode.parentNode;
      btn.innerHTML = 'Loading...';
      btn.disabled = true;
      var req = new XMLHttpRequest();
      req.open('POST', locals.ajax_url, true);
      req.onload = function() {
        var jsonRes = JSON.parse(req.response);
        if ((req.status >= 200 && req.status < 400) && jsonRes.success) {
          if (jsonRes.data) {
            btn.parentNode.insertAdjacentHTML('beforebegin', jsonRes.data.output);
            btn.innerHTML = 'Load More';
            btn.disabled = false;
            var page = loopContainer.getAttribute('page')
            if (page) {
              loopContainer.setAttribute('page', parseInt(page) + 1);
            }
            else {
              loopContainer.setAttribute('page', 3);
            }
            if (!jsonRes.data.more) {
              btn.parentNode.removeChild(btn);
            }
            findLazy();
            objectFitPolyfill();
          }
        } else {
          btn.innerHTML = 'Load More';
          btn.disabled = false;
        }
      };
      req.onerror = function() {
        btn.outerHTML = 'We are sorry, but it appears that something has gone wrong. Please try again at a later time.';
      };
      req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
      var loop = loopContainer.querySelector('.loop-var') ? JSON.parse(loopContainer.querySelector('.loop-var').value) : '';
      var type = loopContainer.hasAttribute('type') ? '&type=' + loopContainer.getAttribute('type') : '';
      var l = encodeURIComponent(JSON.stringify(loop || locals.wp_query));
      req.send('action=_sw_get_more_posts&page=' + (loopContainer.getAttribute('page') || '2') + '&loop=' + l + type);
    }
  });
  var scrolls = document.querySelectorAll('.infinite-scroll');
  if (scrolls) {
    for (var i = 0; i < scrolls.length; i++) {
      var loopContainer = scrolls[i];
      var loading = false;
      var load = document.createElement('div');
      load.classList.add('col-xs-12', 'load-more');
      load.innerHTML = 'Loading...';
      loopContainer.appendChild(load);
      infiniteCheck(loopContainer);
    }
    window.addEventListener('scroll', function() {
      var containers = document.querySelectorAll('.infinite-scroll');
      for (var i = 0; i < containers.length; i++) {
        infiniteCheck(containers[i]);
      }
    });
  }
  var loading = false;
  function infiniteCheck(loopContainer) {
    var load = loopContainer.querySelector('.load-more');
    if (onScreen(load) && !loading) {
      loading = true;
      var req = new XMLHttpRequest();
      req.open('POST', locals.ajax_url, true);
      req.onload = function() {
        var jsonRes = JSON.parse(req.response);
        if ((req.status >= 200 && req.status < 400) && jsonRes.success) {
          if (jsonRes.data) {
            load.insertAdjacentHTML('beforebegin', jsonRes.data.output);
            var page = loopContainer.getAttribute('page');
            if (page) {
              loopContainer.setAttribute('page', parseInt(page) + 1);
            }
            else {
              loopContainer.setAttribute('page', 3);
            }
            if (!jsonRes.data.more) {
              load.parentNode.removeChild(load);
            }
            loading = false;
            findLazy();
            objectFitPolyfill();
            infiniteCheck(loopContainer);
          }
        } else {
          // Don't do anything
        }
      };
      req.onerror = function() {
        load.outerHTML = 'We are sorry, but it appears that something has gone wrong. Please try again at a later time.';
      };
      req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
      var loopVar = loopContainer.querySelector('.loop-var');
      if (loopVar) {
        var loop = JSON.parse(loopVar.value);
      }
      var type = loopContainer.hasAttribute('type') ? '&type=' + loopContainer.getAttribute('type') : '';
      var l = encodeURIComponent(JSON.stringify(typeof loop !== 'undefined' ? loop: locals.wp_query));
      req.send('action=_sw_get_more_posts&page=' + (loopContainer.getAttribute('page') || '2') + '&loop=' + l + type);
    }
  }

  // Replace select elements with more customizable unordered list
  // var selects = document.querySelectorAll('select');
  // for (i = 0; i < selects.length; i++) {
  //   var list = '';
  //   var hidden = '';
  //   var visible = '';
  //   var options = selects[i].querySelectorAll('option');
  //   for (ii = 0; ii < options.length; ii++) {
  //     var selected = '';
  //     if (options[ii].selected) {
  //       hidden = options[ii].value;
  //       visible = options[ii].text;
  //       selected = ' class="selected"';
  //     }
  //     list += '<li' + selected + ' value="' + options[ii].value + '">' + options[ii].text + '</li>';
  //   }
  //   var id = selects[i].getAttribute('id') ? 'id="' + selects[i].getAttribute('id') + '"' : '';
  //   var name = selects[i].getAttribute('name') ? 'name="' + selects[i].getAttribute('name') + '"' : '';
  //   selects[i].insertAdjacentHTML('afterend', '<div class="select"><input ' + id + ' ' + name + ' value="' + hidden + '" type="hidden" /><div><span>' + visible + '</span><svg><use xlink:href="/wp-content/themes/_sw/template-parts/sprites.svg#arrow-down"></use></svg></div><ul>' + list + '</ul></div>');
  //   selects[i].parentNode.removeChild(selects[i]);
  // }
  // document.addEventListener('click', function(event) {
  //   // If we click in the select div
  //   var div = checkParents(event.target, '.select');
  //   if (div) {
  //     if (!div.classList.contains('open')) {
  //       var options = document.querySelectorAll('.select');
  //       for (i = 0; i < options.length; i++) {
  //         options[i].classList.remove('open');
  //       }
  //       div.classList.add('open');
  //     }
  //     else {
  //       div.classList.remove('open');
  //     }
  //   }
  //   // If we click on the list
  //   var li = checkParents(event.target, '.select li');
  //   if (li) {
  //     var div = checkParents(li, '.select');
  //     for (i = 0; i < li.parentNode.children.length; i++) {
  //       li.parentNode.children[i].classList.remove('selected');
  //     }
  //     li.classList.add('selected');
  //     div.querySelector('input[type=hidden]').value = li.getAttribute('value');
  //     var evt = document.createEvent('HTMLEvents');
  //     evt.initEvent('change', true, true);
  //     div.querySelector('input[type=hidden]').dispatchEvent(evt);
  //     div.querySelector('span').innerHTML = li.innerHTML;
  //     li.parentNode.parentNode.classList.remove('open');
  //   }
  //   // If we click anywhere that's not the select div
  //   if (!checkParents(event.target, '.select')) {
  //     var options = document.querySelectorAll('.select');
  //     for (i = 0; i < options.length; i++) {
  //       options[i].classList.remove('open');
  //     }
  //   }
  // });

  // Lazy load
  var lazys = document.querySelectorAll('.lazy-load[data-src]:not([src])');
  function findLazy() {
    lazys = document.querySelectorAll('.lazy-load[data-src]:not([src])');
    for (var i = 0; i < lazys.length; i++) {
      if (lazys[i].tagName=='IMG') {
        lazys[i].src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
      }
    }
    lazyCheck();
  }
  function lazyCheck() {
    for (var i = 0; i < lazys.length; i++) {
      if (onScreen(lazys[i], 200)) {
        if (lazys[i].tagName=='IMG') {
          if (lazys[i].getAttribute('src')=='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7') {
            lazys[i].src = lazys[i].dataset['src'];
            if (lazys[i].hasAttribute('data-object-fit')) {
              objectFitPolyfill(lazys[i]);
            }
          }
        }
        else {
          if (!lazys[i].style.backgroundImage) {
            lazys[i].style.backgroundImage = 'url(' + lazys[i].dataset['src'] + ')';
          }
        }
      }
    }
  };
  window.addEventListener('DOMContentLoaded', function() {
    findLazy();
  }, false);
  window.addEventListener('scroll', function() {
    lazyCheck();
  });

  // Toggle slide
  var checkToggles = (function checkToggles() {
    var toggleBtns = document.querySelectorAll('.toggle');
    for (var i = 0; i < toggleBtns.length; i++) {
      var btn = toggleBtns[i];
      var t = document.querySelector(btn.getAttribute('target'));
      t.style.height = 0;
      t.classList.remove('open');
      t.classList.add('closed');
      btn.addEventListener('click', function(event) {
        event.preventDefault();
        var t = document.querySelector(this.getAttribute('target'));
        if (t.style.height == '0px') {
          t.style.height = t.scrollHeight + 'px';
          this.classList.remove('closed');
          this.classList.add('open');
          t.classList.remove('closed');
          t.classList.add('open');
          if (this.classList.contains('location-heading')) {
            this.parentElement.classList.add('shadow');
          }
          if (this.parentElement.parentElement.parentElement.parentElement.classList.contains('suite')) {
            this.parentElement.parentElement.parentElement.classList.add('shadow');
          }
        }
        else {
          t.style.height = 0;
          this.classList.remove('open');
          this.classList.add('closed');
          t.classList.remove('open');
          t.classList.add('closed');
          if (this.classList.contains('location-heading')) {
            this.parentElement.classList.remove('shadow');
          }
          if (this.parentElement.parentElement.parentElement.parentElement.classList.contains('suite')) {
            this.parentElement.parentElement.parentElement.classList.remove('shadow');
          }
        }
      });
    }
  })();

  // Receive the width from iframes so that they're responsive
  function receiveMessage(event) {
    if (event.origin !== 'https://go.mattersight.com') {
      return;
    }
    if (event.data.url && event.data.height) {
      document.querySelector('iframe[src="' + event.data.url + '"]').style.height = event.data.height + 'px';
    }
  }
  window.addEventListener('message', receiveMessage, false);
  // Only works if you place the following code into the iframe:
  // function resizeParentFrame() {
  //   var body = document.body;
  //   var html = document.documentElement;
  //   var docHeight = Math.max(document.body.scrollHeight, document.body.offsetHeight, document.documentElement.offsetHeight);
  //   if (window.location.href) {
  //     window.parent.postMessage({"url": window.location.href, "height": docHeight}, "*");
  //   }
  // }
  // setInterval(resizeParentFrame, 500);
})();

// Display google maps (this counts toward api call count, so use sparingly or opt for the iframe instead)
function initMap() {
  var maps = document.querySelectorAll('.google-map');
  for (var i = 0; i < maps.length; i++) {
    var mapObj = maps[i];
    if (mapObj.querySelector('.map-canvas > *')) {
      google.maps.event.trigger(mapObj.map, 'resize');
      mapObj.map.fitBounds(mapObj.bounds);
      var zoom = mapObj.querySelector('.map-canvas').getAttribute('zoom');
      if (zoom) {
        mapObj.map.setZoom(mapObj.map.getZoom() - parseInt(zoom));
      }
      else {
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
      zoom: 4,
      styles: [
        {
          "stylers": [
            {
              "visibility": "simplified"
            }
          ]
        },
        {
          "elementType": "labels.text",
          "stylers": [
            {
              "color": "#5f969e"
            }
          ]
        },
        {
          "featureType": "landscape.man_made",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "poi",
          "elementType": "labels",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "poi.business",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "poi.park",
          "elementType": "labels.text",
          "stylers": [
            {
              "visibility": "off"
            }
          ]
        },
        {
          "featureType": "road.highway",
          "elementType": "geometry",
          "stylers": [
            {
              "color": "#ffccaf"
            }
          ]
        },
        {
          "featureType": "water",
          "stylers": [
            {
              "color": "#c9eced"
            }
          ]
        }
      ]
    });
    var markers = [];
    var locs = mapObj.querySelectorAll('.location');
    for (var ii = 0; ii < locs.length; ii++) {
      var coords = locs[ii].value.split('~%~%~');
      // var directions = 'https://www.google.com/maps/dir/?api=1&destination=' + encodeURI(coords[4].replace('<br>', ', '));

      markers[ii] = new google.maps.Marker({
        position: {
          lat: parseFloat(coords[0]),
          lng: parseFloat(coords[1])
        },
        map: map
      });
      markers[ii].infoWindow = new google.maps.InfoWindow({
        content: '<div class="map-info-window">' + coords[2] + '</div>'
      });
      markers[ii].addListener('click', function() {
        this.infoWindow.open(map, this);
      });
    }
    var bounds = new google.maps.LatLngBounds();
    for (var ii = 0; ii < markers.length; ii++) {
      bounds.extend(markers[ii].getPosition());
    }
    map.fitBounds(bounds);
    map.set('mapObj', mapObj);
    mapObj.map = map;
    mapObj.bounds = bounds;
    google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
      var zoom = this.mapObj.querySelector('.map-canvas').getAttribute('zoom');
      if (zoom) {
        this.setZoom(this.getZoom() - parseInt(zoom));
      }
      else {
        this.setZoom(this.getZoom() - 1);
      }
    });
  }
}

// Accessible card clicking
document.addEventListener('click', function(e) {
  var card = checkParents(e.target, '.link-container');
  if (card) {
    var l = card.querySelector('a');
    if (l) {
      if (l.hasAttribute('target')) {
        var temp = document.createElement('a');
        temp.href = l.href;
        temp.target = l.target;
        e.preventDefault();
        temp.click();
        temp = null;
      }
      else {
        location.href = l.href;
      }
    }
  }
});

//=include _custom.js

if (typeof svg4everybody == 'function') {
  svg4everybody();
}
// console.timeEnd('JS Runtime');
