import * as utilities from 'Utilities'
import { lazyEvent } from 'Modules/lazy.js'

document.querySelectorAll('.wp-block-ws-tabbed-panels').forEach(tp => {
  initTabPanel(tp)
  addTabbedPanelsListener(tp)
  selectTab(tp.querySelector('.tab:first-child'), tp, false)
})

function initTabPanel(tp) {
  const panels = tp.querySelectorAll('.panel')
  const tabContainer = tp.querySelector('.tabs')
  tabContainer.setAttribute('role', 'tablist')
  const uniqid = utilities.uniqid()
  let tabs = ''
  let tabsMobile = `<label class="screen-reader-text" for="tabs-${uniqid}">Tabs</label><select id="tabs-${uniqid}">`
  panels.forEach((panel, i) => {
    const uid = panel.getAttribute('id').slice(6)
    const icon = JSON.parse(decodeURIComponent(panel.getAttribute('data-icon').replace(/\+/g, '%20')))
    const heading = panel.getAttribute('data-heading')
    // Setup tabs
    tabs += `<button
      id="tab-${uid}"
      class="tab"
      role="tab"
      aria-selected="false"
      aria-controls="panel-${uid}"
      tabindex="-1"
    >${icon ? utilities.svgFromObject(icon) : ''}${heading}</button>`
    tabsMobile += `<option value="tab-${uid}">${heading}</option>`
    // Setup panel
    panel.setAttribute('role', 'tabpanel')
    panel.setAttribute('aria-labelledby', `tab-${uid}`)
    panel.setAttribute('tabindex', '0')
  })
  tabsMobile += '</select>'
  tabContainer.innerHTML = tabs
  const mobileSelect = document.createElement('div')
  mobileSelect.classList.add('col-xs-12')
  mobileSelect.classList.add('tabs-mobile')
  mobileSelect.innerHTML = tabsMobile
  tabContainer.insertAdjacentElement('afterend', mobileSelect)
}

function addTabbedPanelsListener(tp) {
  const tabs = tp.querySelectorAll('.tab')
  tabs.forEach((tab, i) => {
    tab.addEventListener('click', e => {
      selectTab(tab, tp)
    })
    tab.addEventListener('keydown', e => {
      if (e.keyCode === utilities.keyCodes.ARROW_UP || e.keyCode === utilities.keyCodes.ARROW_DOWN) {
        e.preventDefault()
      }
    })
    tab.addEventListener('keyup', e => {
      // Desktop keyboard controls
      if (window.innerWidth > 768 && !tp.classList.contains('is-style-vertical')) {
        if (e.keyCode === utilities.keyCodes.ARROW_LEFT) {
          if (i === 0) {
            selectTab(tabs[tabs.length - 1], tp)
          }
          else {
            selectTab(tabs[i - 1], tp)
          }
        }
        if (e.keyCode === utilities.keyCodes.ARROW_RIGHT) {
          if (i === tabs.length - 1) {
            selectTab(tabs[0], tp)
          }
          else {
            selectTab(tabs[i + 1], tp)
          }
        }
      }
      // Mobile keybaord controls
      else {
        if (e.keyCode === utilities.keyCodes.ARROW_UP) {
          if (i === 0) {
            selectTab(tabs[tabs.length - 1], tp)
          }
          else {
            selectTab(tabs[i - 1], tp)
          }
        }
        if (e.keyCode === utilities.keyCodes.ARROW_DOWN) {
          if (i === tabs.length - 1) {
            selectTab(tabs[0], tp)
          }
          else {
            selectTab(tabs[i + 1], tp)
          }
        }
      }
    })
  })
  tp.querySelector('.tabs-mobile select').addEventListener('change', function(e) {
    selectTab(document.querySelector(`#${this.value}`), tp)
  })
}

function selectTab(tab, tp, focus = true) {
  const tabs = tp.querySelectorAll('.tab')
  const panels = tp.querySelectorAll('.panel')
  // Clear tabs
  tabs.forEach(tab => {
    tab.classList.remove('current')
    tab.setAttribute('aria-selected', false)
    tab.setAttribute('tabindex', -1)
  })
  // Clear panels
  panels.forEach(panel => {
    panel.classList.remove('current')
  })
  // Select tab
  const panel = tp.querySelector(`#${tab.getAttribute('aria-controls')}`)
  tab.classList.add('current')
  tab.setAttribute('aria-selected', true)
  tab.removeAttribute('tabindex', true)
  if (focus) { // Prevents the page from scrolling to element when run in init()
    tab.focus()
  }
  panel.classList.add('current')
  lazyEvent()
}
