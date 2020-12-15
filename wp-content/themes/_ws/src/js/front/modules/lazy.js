/* global objectFitPolyfill */
import * as utilities from 'Utilities'
import { updateSubmenuHeights } from 'Modules/header.js'
import 'objectFitPolyfill/dist/objectFitPolyfill.min.js'

function init() {
  lazyEvent()
  window.addEventListener('load', () => {
    lazyEvent()
  })
  window.removeEventListener('throttleScroll', lazyEvent, false)
  window.addEventListener('throttleScroll', lazyEvent, false)
  window.removeEventListener('resize', lazyEvent, false)
  window.addEventListener('resize', lazyEvent, false)
  const menuItems = document.querySelectorAll('.site-header .menu > .menu-item')
  for (let i = 0; i < menuItems.length; i++) {
    const menuItem = menuItems[i]
    menuItem.addEventListener('mouseover', checkHeaderLazy, { once: true })
  }
}

function lazyEvent() {
  document.querySelectorAll('.lazy-load').forEach(el => {
    if (utilities.onScreen(el, '-250px')) {
      if (el.tagName === 'IMG') {
        if (el.hasAttribute('data-sizes')) {
          el.sizes = el.getAttribute('data-sizes')
        }
        if (el.hasAttribute('data-srcset')) {
          el.srcset = el.getAttribute('data-srcset')
        }
        if (el.hasAttribute('data-src')) {
          el.src = el.getAttribute('data-src')
        }
        if (el.hasAttribute('data-object-fit')) {
          objectFitPolyfill(el)
        }
      }
      else if (el.tagName === 'VIDEO') {
        const node = document.createElement('SOURCE')
        node.setAttribute('type', 'video/mp4')
        node.setAttribute('src', el.getAttribute('data-src'))
        el.appendChild(node)
        el.load()
      }
      else if (el.tagName === 'IFRAME') {
        el.src = el.getAttribute('data-src')
      }
      else {
        if (el.hasAttribute('data-src')) {
          el.style.backgroundImage = `url(${el.getAttribute('data-src')})`
        }
      }
      el.classList.remove('lazy-load')
    }
  })
}

function checkHeaderLazy() {
  document.querySelectorAll('.header-lazy-load').forEach(el => {
    el.addEventListener('load', e => {
      updateSubmenuHeights()
    }, { once: true })
    el.src = el.getAttribute('data-src')
    el.classList.remove('header-lazy-load')
  })
}

export { init, lazyEvent }
