import * as mods from './modules'

// Fix foreach in browsers
if (typeof NodeList !== 'undefined' && NodeList.prototype) {
  // forEach
  if (!NodeList.prototype.forEach) {
    NodeList.prototype.forEach = Array.prototype.forEach
  }
  // Iterability
  if (typeof Symbol !== 'undefined' && Symbol.iterator && !NodeList.prototype[Symbol.iterator]) {
    Object.defineProperty(NodeList.prototype, Symbol.iterator, {
      value: Array.prototype[Symbol.itereator],
      writable: true,
      configurable: true
    })
  }
}

// mods.customSelect()
mods.header()
mods.iframeComm()
mods.scrollListener()
mods.lazy()
// mods.animate()
mods.lightbox()
mods.parallax()
mods.sideScroll()

document.body.classList.remove('no-transitions') // Prevents CSS transitions on page load
