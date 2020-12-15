import { CountUp } from 'countup.js'
import * as utilities from 'Utilities'

initCounters()
counterEvent()
window.addEventListener('load', () => {
  counterEvent()
})
window.removeEventListener('throttleScroll', counterEvent, false)
window.addEventListener('throttleScroll', counterEvent, false)

function initCounters() {
  const counts = document.querySelectorAll('.count-up')
  for (let i = 0; i < counts.length; i++) {
    const count = counts[i]
    const number = count.innerHTML.match(/[0-9,.]+/g)
    if (number) {
      count.setAttribute('data-count', count.innerHTML)
      const strings = count.getAttribute('data-count').split(number[0])
      count.innerHTML = `${strings[0]}0${strings[1]}`
    }
  }
}

function counterEvent() {
  const counts = document.querySelectorAll('.count[data-count]')
  for (let i = 0; i < counts.length; i++) {
    const count = counts[i]
    if (utilities.onScreen(count, '50%')) {
      count.classList.remove('count')
      let number = count.getAttribute('data-count').match(/[0-9,.]+/g)
      if (number) {
        number = number[0]
        const parts = count.getAttribute('data-count').split(number)
        const decimalPlaces = number.indexOf('.') !== -1 ? number.substring(number.indexOf('.') + 1).length : 0
        const countUp = new CountUp(count, number, {
          duration: 2.5,
          decimalPlaces: decimalPlaces,
          prefix: parts[0],
          suffix: parts[1]
        })
        countUp.start()
      }
    }
  }
}
