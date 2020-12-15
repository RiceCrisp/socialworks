/* globals wp */
import * as utilities from 'Utilities'

function init() {
  const headingDiv = document.querySelector('#headingdiv')
  const searchDiv = document.querySelector('#searchdiv')
  const columnDiv = document.querySelector('#columndiv')
  if (headingDiv) {
    addHeadingMenuListeners(headingDiv)
  }
  if (searchDiv) {
    addSearchMenuListeners(searchDiv)
  }
  if (columnDiv) {
    addColumnMenuListeners(columnDiv)
  }
  document.addEventListener('click', e => {
    const addImageButton = utilities.checkParents(e.target, '.add-menu-image')
    const removeImageButton = utilities.checkParents(e.target, '.remove-menu-image')
    const addIconMenu = utilities.checkParents(e.target, '.svg-select')
    const addIconButton = utilities.checkParents(e.target, '.add-menu-icon')
    const addIconMenuButton = utilities.checkParents(e.target, '.svg-select button')
    if (addImageButton) {
      e.preventDefault()
      addMenuImage(addImageButton)
    }
    if (removeImageButton) {
      e.preventDefault()
      removeMenuImage(removeImageButton)
    }
    if (!addIconMenu) {
      hideIconMenu()
    }
    if (addIconButton) {
      e.preventDefault()
      const menu = document.querySelector(`#${addIconButton.getAttribute('aria-controls')}`)
      menu.classList.add('show')
    }
    if (addIconMenuButton) {
      e.preventDefault()
      hideIconMenu()
      addMenuIcon(addIconMenuButton)
    }
  })
}

function addMenuImage(addImageButton) {
  const frame = wp.media({ multiple: false })
  frame.on('select', function() {
    const imageContainer = addImageButton.parentElement.querySelector('.menu-image-container')
    const imageIdInput = addImageButton.parentElement.querySelector('input[type=hidden]')
    const removeImageButton = addImageButton.parentElement.querySelector('.remove-menu-image')
    const attachment = frame.state().get('selection').first().toJSON()
    imageContainer.innerHTML = `<img src="${attachment.sizes.small.url || attachment.url}" alt="${attachment.alt}" />`
    imageIdInput.value = attachment.id
    addImageButton.classList.add('hidden')
    removeImageButton.classList.remove('hidden')
    frame.remove()
  })
  frame.open()
}

function removeMenuImage(removeImageButton) {
  const imageContainer = removeImageButton.parentElement.querySelector('.menu-image-container')
  const imageIdInput = removeImageButton.parentElement.querySelector('input[type=hidden]')
  const addImageButton = removeImageButton.parentElement.querySelector('.add-menu-image')
  imageContainer.innerHTML = ''
  imageIdInput.value = ''
  addImageButton.classList.remove('hidden')
  removeImageButton.classList.add('hidden')
}

function addMenuIcon(addIconMenuButton) {
  const menu = addIconMenuButton.parentElement
  const button = document.querySelector(`#${menu.getAttribute('aria-labelledby')}`)
  const value = addIconMenuButton.getAttribute('data-value')
  button.nextElementSibling.value = value
  button.innerHTML = value ? addIconMenuButton.innerHTML : 'Select Icon'
  menu.classList.remove('show')
}

function hideIconMenu() {
  document.querySelectorAll('.svg-select').forEach(menu => menu.classList.remove('show'))
}

function addHeadingMenuListeners(headingDiv) {
  const input = headingDiv.querySelector('#heading-menu-item-name')
  const submit = headingDiv.querySelector('#add-heading-menu-item')
  input.addEventListener('keypress', e => {
    headingDiv.classList.remove('form-invalid')
    if (e.keyCode === 13) {
      e.preventDefault()
      submit.click()
    }
  })
  submit.addEventListener('click', e => {
    const url = '#custom_heading'
    if (input.value === '') {
      headingDiv.classList.add('form-invalid')
      return false
    }
    headingDiv.querySelector('.spinner').classList.add('is-active')
    window.wpNavMenu.addLinkToMenu(url, input.value, window.wpNavMenu.addMenuItemToBottom, function() {
      headingDiv.querySelector('.spinner').classList.remove('is-active')
      input.value = ''
      input.blur()
    })
  })
}

function addSearchMenuListeners(searchDiv) {
  const submit = searchDiv.querySelector('#add-search-menu-item')
  submit.addEventListener('click', e => {
    const url = '#custom_search'
    searchDiv.querySelector('.spinner').classList.add('is-active')
    window.wpNavMenu.addLinkToMenu(url, 'Search', window.wpNavMenu.addMenuItemToBottom, function() {
      searchDiv.querySelector('.spinner').classList.remove('is-active')
    })
  })
}

function addColumnMenuListeners(columnDiv) {
  const submit = columnDiv.querySelector('#add-column-menu-item')
  submit.addEventListener('click', e => {
    const url = '#custom_column'
    columnDiv.querySelector('.spinner').classList.add('is-active')
    window.wpNavMenu.addLinkToMenu(url, 'Column', window.wpNavMenu.addMenuItemToBottom, function() {
      columnDiv.querySelector('.spinner').classList.remove('is-active')
    })
  })
}

export { init }
