/* global React, wp */
const { useSelect } = wp.data
const { InnerBlocks, InspectorControls } = wp.blockEditor
const { PanelBody, ToggleControl } = wp.components
const { __ } = wp.i18n

export const splitHalf = {
  name: 'ws/split-half',
  args: {
    title: __('Split Half', '_ws'),
    icon: 'info-outline',
    category: 'ws-layout',
    supports: {
      customClassName: false,
      html: false
    },
    parent: ['ws/split'],
    attributes: {
      extendTop: {
        type: 'boolean'
      },
      extendBottom: {
        type: 'boolean'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { extendTop, extendBottom } = props.attributes
      const { hasChildBlocks } = useSelect(select => ({
        hasChildBlocks: select('core/block-editor').getBlockCount(props.clientId) > 0
      }))
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Split Half Options', '_ws') }
            >
              <ToggleControl
                label={ __('Extend Top', '_ws') }
                onChange={ newValue => setAttributes({ extendTop: newValue }) }
                checked={ extendTop }
              />
              <ToggleControl
                label={ __('Extend Bottom', '_ws') }
                onChange={ newValue => setAttributes({ extendBottom: newValue }) }
                checked={ extendBottom }
              />
            </PanelBody>
          </InspectorControls>
          <InnerBlocks
            templateLock={ false }
            renderAppender={ hasChildBlocks ? undefined : () => <InnerBlocks.ButtonBlockAppender /> }
          />
        </>
      )
    },
    save: props => {
      return (
        <InnerBlocks.Content />
      )
    },
    deprecated: [
      {
        migrate: (attributes, innerBlocks) => {
          return [attributes, innerBlocks]
        },
        save: props => {
          return (
            <div className="col">
              <InnerBlocks.Content />
            </div>
          )
        }
      }
    ]
  }
}
