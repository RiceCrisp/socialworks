const shareButtons = document.querySelectorAll('.ws-block-social-share a')
for (let i = 0; i < shareButtons.length; i++) {
  const shareButton = shareButtons[i]
  shareButton.addEventListener(e => {
    e.preventDefault()
    window.open(shareButton.getAttribute('href'), shareButton.getAttribute('target'), 'resizeable,width=550,height=520')
  })
}
