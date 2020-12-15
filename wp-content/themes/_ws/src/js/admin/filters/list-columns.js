/* globals React, wp */
const { InspectorControls } = wp.blockEditor
const { PanelBody, RangeControl } = wp.components
const { createHigherOrderComponent } = wp.compose
const { __ } = wp.i18n

export const listColumnsControls = {
  hook: 'editor.BlockEdit',
  name: 'ws/with-list-columns-controls',
  func: createHigherOrderComponent(BlockEdit => {
    return props => {
      if (props.name !== 'core/list' && props.name !== 'ws/icon-list') {
        return (
          <BlockEdit { ...props } />
        )
      }
      const { setAttributes } = props
      const { className = '' } = props.attributes
      const classes = className ? className.split(' ') : []
      const classIndex = classes.findIndex(c => c.substring(0, 13) === 'column-count-')
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('List Options', '_ws') }
            >
              <RangeControl
                label={ __('Columns', '_ws') }
                min={ 1 }
                max={ 3 }
                onChange={ newValue => {
                  if (classIndex === -1) {
                    classes.push(`column-count-${newValue}`)
                    setAttributes({ className: classes.join(' ') })
                  }
                  else {
                    classes[classIndex] = `column-count-${newValue}`
                    setAttributes({ className: classes.join(' ') })
                  }
                } }
                value={ classIndex === -1 ? 1 : Number(classes[classIndex].substr(-1)) }
              />
            </PanelBody>
          </InspectorControls>
          <BlockEdit { ...props } />
        </>
      )
    }
  }, 'withListColumnsControls')
}
