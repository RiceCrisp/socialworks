import * as utilities from 'Utilities'

const accordions = document.querySelectorAll('.accordion-button')
for (let i = 0; i < accordions.length; i++) {
  const accordion = accordions[i]
  const target = document.querySelector(`#${accordion.getAttribute('aria-controls')}`)
  accordion.addEventListener('click', function(e) {
    e.preventDefault()
    if (this.classList.contains('animating')) {
      return
    }
    if (this.classList.contains('closed')) {
      openAccordion(this, target)
    }
    else {
      closeAccordion(this, target)
    }
  })
}

function openAccordion(button, target) {
  const transitionEvent = utilities.getTransitionEvent()
  button.classList.add('animating')
  target.style.height = '0px'
  target.classList.remove('closed')
  target.classList.add('open')
  target.removeAttribute('hidden')
  button.classList.remove('closed')
  button.classList.add('open')
  target.style.height = `${target.scrollHeight}px`
  target.addEventListener(transitionEvent, function autoHeight1(e) {
    button.setAttribute('aria-expanded', true)
    this.removeEventListener(e.type, autoHeight1)
    button.classList.remove('animating')
  })
}

function closeAccordion(button, target) {
  const transitionEvent = utilities.getTransitionEvent()
  button.classList.add('animating')
  target.style.height = '0px'
  target.classList.remove('open')
  target.classList.add('closed')
  button.classList.remove('open')
  button.classList.add('closed')
  target.addEventListener(transitionEvent, function autoHeight2(e) {
    button.setAttribute('aria-expanded', false)
    target.setAttribute('hidden', 'hidden')
    this.removeEventListener(e.type, autoHeight2)
    button.classList.remove('animating')
  })
}
