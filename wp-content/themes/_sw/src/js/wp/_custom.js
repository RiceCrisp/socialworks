/*
 * INCLUDE ANY SCRIPTS ONLY RELEVANT TO THIS PROJECT HERE.
 */

var navTabs = document.querySelectorAll('.tabs-nav li a');
var tabs = document.querySelectorAll('.tabs li');
function checkHash() {
  var hash = window.location.hash.substring(1);
  for (var i = 0; i < navTabs.length; i++) {
    navTabs[i].classList.remove('current');
    tabs[i].classList.remove('current');
  }
  if (hash) {
    document.querySelector('.tabs-nav [href="#' + hash + '"]').classList.add('current');
    document.querySelector('.tabs [idz=' + hash + ']').classList.add('current');
  }
  else {
    document.querySelector('.tabs-nav li:first-child a').classList.add('current');
    document.querySelector('.tabs li:first-child').classList.add('current');
  }
}
if (tabs.length) {
  window.addEventListener('hashchange', function(e) {
    checkHash();
  });
  checkHash();
}

var filter = document.querySelector('#gallery-filters select');
if (filter) {
  filter.addEventListener('change', function(e) {
    document.querySelector('#gallery-filters').submit();
  });
}
