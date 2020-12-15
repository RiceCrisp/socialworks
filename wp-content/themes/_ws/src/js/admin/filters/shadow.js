/* globals React, wp */
const { InspectorControls } = wp.blockEditor
const { PanelBody, ToggleControl } = wp.components
const { createHigherOrderComponent } = wp.compose
const { __ } = wp.i18n

export const shadowControls = {
  hook: 'editor.BlockEdit',
  name: 'ws/with-shadow-controls',
  func: createHigherOrderComponent(BlockEdit => {
    return props => {
      if (props.name !== 'core/image') {
        return (
          <BlockEdit { ...props } />
        )
      }
      const { setAttributes } = props
      const { className } = props.attributes
      const classes = className ? className.split(' ') : []
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Shadow', '_ws') }
            >
              <ToggleControl
                label={ __('Drop Shadow', '_ws') }
                onChange={ newValue => {
                  const classIndex = classes.indexOf('has-drop-shadow')
                  if (newValue && classIndex === -1) {
                    classes.push('has-drop-shadow')
                    setAttributes({ className: classes.join(' ') })
                  }
                  else if (!newValue && classIndex !== -1) {
                    classes.splice(classIndex, 1)
                    setAttributes({ className: classes.join(' ') })
                  }
                } }
                checked={ classes.indexOf('has-drop-shadow') !== -1 }
              />
            </PanelBody>
          </InspectorControls>
          <BlockEdit { ...props } />
        </>
      )
    }
  }, 'withShadowControls')
}
