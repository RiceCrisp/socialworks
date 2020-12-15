import lottie from 'lottie-web/build/player/lottie_light'
import * as utilities from 'Utilities'

initAnimations()

function initAnimations() {
  const animations = document.querySelectorAll('.svg-animation')
  for (let i = 0; i < animations.length; i++) {
    const animation = animations[i]
    lottie.loadAnimation({
      container: animation,
      renderer: 'svg',
      loop: animation.hasAttribute('data-loop'),
      autoplay: false,
      animationData: JSON.parse(decodeURIComponent(animation.getAttribute('data-json')).replace(/\+/g, ' ')),
      name: animation.getAttribute('data-name'),
      rendererSettings: {
        preserveAspectRatio: 'xMinYMid meet'
      }
    })
  }
  window.removeEventListener('throttleScroll', animateEvent, false)
  window.addEventListener('throttleScroll', animateEvent, false)
}

function animateEvent() {
  const animations = document.querySelectorAll('.svg-animation')
  for (let i = 0; i < animations.length; i++) {
    const animation = animations[i]
    if (utilities.onScreen(animation, '100%')) {
      lottie.play(animation.getAttribute('data-name'))
      animation.classList.remove('svg-animation')
    }
  }
}
