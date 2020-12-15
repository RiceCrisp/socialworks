/* global React, wp */
const { RichTextToolbarButton } = wp.blockEditor
const { __ } = wp.i18n
const { toggleFormat } = wp.richText

export const formatSmall = {
  name: 'ws/format-small',
  args: {
    title: __('Small', '_ws'),
    tagName: 'small',
    className: null,
    edit: props => {
      return (
        <RichTextToolbarButton
          title={ __('Small', '_ws') }
          icon={ <svg viewBox="0 0 20 20"><path d="M11.3,5v1.8h-3V15H6.2V6.7h-3V5H11.3z"/><path d="M16.9,8.6v1.1h-1.9V15h-1.4V9.7h-1.9V8.6H16.9z"/></svg> }
          onClick={ () => props.onChange(toggleFormat(
            props.value,
            { type: 'ws/format-small' }
          )) }
          isActive={ props.isActive }
        />
      )
    }
  }
}
