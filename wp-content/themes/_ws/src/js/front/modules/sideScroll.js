/* global locals */
import * as utilities from 'Utilities'

function init() {
  document.querySelectorAll('.horizontal-scroll').forEach(container => {
    createControls(container)
  })
}

function createControls(container) {
  const prevIcon = locals['svgs'].filter(svg => svg.id === 'caret-left')[0]
  const nextIcon = locals['svgs'].filter(svg => svg.id === 'caret-right')[0]
  const controls = document.createElement('div')
  controls.classList.add('horizontal-scroll-controls')
  controls.innerHTML = `<button class="prev-button" disabled aria-label="Previous">${prevIcon ? utilities.svgFromObject(prevIcon) : '<'}</button><button class="next-button" aria-label="Next">${nextIcon ? utilities.svgFromObject(nextIcon) : '>'}</button>`
  // container.insertAdjacentElement('beforebegin', controls)
  container.insertAdjacentElement('afterend', controls)
  addSliderRowListeners(container, controls)
}

function addSliderRowListeners(container, controls) {
  const prevButton = controls.querySelector('.prev-button')
  const nextButton = controls.querySelector('.next-button')
  prevButton.addEventListener('click', e => {
    e.preventDefault()
    const width = container.children[0].offsetWidth
    container.scrollLeft -= width
  })
  nextButton.addEventListener('click', e => {
    e.preventDefault()
    const width = container.children[0].offsetWidth
    container.scrollLeft += width
  })
  container.addEventListener('scroll', function(e) {
    const left = this.scrollLeft === 0
    const right = Math.abs(this.scrollWidth - this.offsetWidth - this.scrollLeft) < 2
    if (left) {
      prevButton.setAttribute('disabled', true)
    }
    else {
      prevButton.removeAttribute('disabled')
    }
    if (right) {
      nextButton.setAttribute('disabled', true)
    }
    else {
      nextButton.removeAttribute('disabled')
    }
    if (left && right) {
      container.classList.add('no-scroll')
    }
  })
  function checkWidth() {
    const left = container.scrollLeft === 0
    const right = Math.abs(container.scrollWidth - container.offsetWidth - container.scrollLeft) < 2
    if (left && right) {
      controls.classList.add('no-controls')
    }
    else {
      controls.classList.remove('no-controls')
    }
  }
  window.addEventListener('resize', checkWidth)
  checkWidth()
}

export { init }
