/* global wp */
const { unregisterBlockStyle } = wp.blocks
const { __ } = wp.i18n

export function removeStyles() {
  unregisterBlockStyle('core/quote', 'default')
  unregisterBlockStyle('core/quote', 'large')

  unregisterBlockStyle('core/pullquote', 'default')
  unregisterBlockStyle('core/pullquote', 'solid-color')

  unregisterBlockStyle('core/separator', 'default')
  unregisterBlockStyle('core/separator', 'wide')
  unregisterBlockStyle('core/separator', 'dots')

  unregisterBlockStyle('core/table', 'stripes')
}

export const styles = [
  {
    name: 'core/button',
    args: {
      name: 'underline',
      label: __('Underline', '_ws')
    }
  },
  {
    name: 'core/button',
    args: {
      name: 'arrow',
      label: __('Arrow', '_ws')
    }
  },
  {
    name: 'core/paragraph',
    args: {
      name: 'label',
      label: __('Label', '_ws')
    }
  }
]
