import * as utilities from 'Utilities'
import * as lazy from 'Modules/lazy.js'

const forms = document.querySelectorAll('.archive-filters')
for (let i = 0; i < forms.length; i++) {
  const form = forms[i]
  form.addEventListener('submit', e => {
    e.preventDefault()
    submitFormAjax(form)
  })
  submitFormAjax(form)
  const formInputs = form.querySelectorAll('.archive-filters select, .archive-filters .listbox input')
  for (let i = 0; i < formInputs.length; i++) {
    const formInput = formInputs[i]
    formInput.addEventListener('change', e => {
      submitFormAjax(form)
    })
  }
}

function submitFormAjax(form) {
  const loopQuery = {
    post_type: 'any',
    post_status: 'publish'
  }
  // Convert form values to query object
  const serialized = utilities.serializeForm(form)
  serialized.split('&').forEach(pair => {
    const temp = pair.split('=')
    const key = temp[0]
    const value = temp[1]
    if (value) {
      if (key.substring(0, 7) === 'filter-') {
        if (loopQuery.tax_query === undefined) {
          loopQuery.tax_query = []
        }
        loopQuery.tax_query.push({
          'taxonomy': key.substring(7),
          'field': 'slug',
          'terms': value.split(',')
        })
      }
      else if (key === 'search') {
        loopQuery.s = value
      }
      else {
        loopQuery[temp[0]] = temp[1]
      }
    }
  })
  const loopString = encodeURIComponent(JSON.stringify(loopQuery))
  // Clear results and set loop-var so that inifinite load will work
  document.querySelector('.archive-results').innerHTML = `<input class="loop-var" type="hidden" value="${loopString}" /><div class="loading"></div>`
  // We have to run the first request ourselves
  const loopContainer = document.querySelector('.archive-results')
  const type = loopContainer.hasAttribute('data-type') ? `&type=${loopContainer.getAttribute('data-type')}` : ''
  const url = `/wp-admin/admin-ajax.php?action=_ws_get_more_posts&paged=1&loop=${encodeURIComponent(loopString)}${type}`
  utilities.handleAjax(url, filterSuccess, filterFail)
}

function filterSuccess(data) {
  const loopContainer = document.querySelector('.archive-results')
  const loopVar = document.querySelector('.loop-var')
  // Remove loading animation
  const loading = document.querySelector('.loading')
  loading.parentNode.removeChild(loading)
  // Remove paged variable
  loopContainer.removeAttribute('data-paged')
  // Add load more button if necessary
  if (data.more) {
    loopVar.insertAdjacentHTML('afterend', '<div class="col-xs-12 load-more"><button>Load More</button></div>')
    loopContainer.setAttribute('data-paged', 2)
  }
  if (!data.output) {
    loopVar.insertAdjacentHTML('afterend', '<div class="col-xs-12"><p class="no-results">No results found.</p></div>')
  }
  loopVar.insertAdjacentHTML('afterend', data.output)
  lazy.lazyEvent()
}

function filterFail() {
  const loopContainer = document.querySelector('.archive-results')
  const loopVar = document.querySelector('.loop-var')
  // Remove loading animation
  const loading = document.querySelector('.loading')
  loading.parentNode.removeChild(loading)
  // Remove paged variable
  loopContainer.parentElement.removeAttribute('data-paged')
  loopVar.insertAdjacentHTML('afterend', '<div class="col-xs-12 text-center"><p class="no-margin">Something has gone wrong. Please try again.</p></div>')
}

document.removeEventListener('click', loadMoreButtonEvent, false)
document.addEventListener('click', loadMoreButtonEvent, false)

function loadMoreButtonEvent(e) {
  const button = utilities.checkParents(e.target, '.load-more button')
  if (button) {
    e.preventDefault()
    const loopContainer = utilities.checkParents(button, '.archive-results')
    const loop = loopContainer.querySelector('.loop-var') ? loopContainer.querySelector('.loop-var').value : ''
    const type = loopContainer.hasAttribute('data-type') ? `&type=${loopContainer.getAttribute('data-type')}` : ''
    const url = `/wp-admin/admin-ajax.php?action=_ws_get_more_posts&paged=${(loopContainer.getAttribute('data-paged') || '2')}&loop=${encodeURIComponent(loop)}${type}`
    button.innerHTML = 'Loading...'
    button.disabled = true
    utilities.handleAjax(url, buttonSuccess, buttonFail, button)
  }
}

function buttonSuccess(data, button) {
  const loopContainer = utilities.checkParents(button, '.archive-results')
  const paged = loopContainer.getAttribute('data-paged')
  button.parentNode.insertAdjacentHTML('beforebegin', data.output)
  button.innerHTML = 'Load More'
  button.disabled = false
  loopContainer.setAttribute('data-paged', paged ? parseInt(paged) + 1 : 3)
  if (!data.more) {
    button.parentNode.removeChild(button)
  }
  // loopContainer.classList.add('animate-group')
  // animate.animateEvent()
  lazy.lazyEvent()
}

function buttonFail(button) {
  button.outerHTML = 'We are sorry, but it appears that something has gone wrong. Please try again at a later time.'
}
