/* global React, wp */
import SVGPicker from 'Components/svg-picker.js'
const { ColorPalette, InspectorControls, RichText } = wp.blockEditor
const { createBlock } = wp.blocks
const { BaseControl, ColorIndicator, PanelBody } = wp.components
const { useDispatch } = wp.data
const { __ } = wp.i18n

export const iconListItem = {
  name: 'ws/icon-list-item',
  args: {
    title: __('Icon List Item', '_ws'),
    icon: 'saved',
    category: 'ws-bit',
    supports: {
      customClassName: false,
      html: false
    },
    parent: ['ws/icon-list'],
    attributes: {
      icon: {
        type: 'string'
      },
      iconColor: {
        type: 'string'
      },
      text: {
        type: 'string'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { icon, iconColor, text } = props.attributes
      const { removeBlocks, replaceBlocks } = useDispatch('core/block-editor')
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Icon Options', '_ws') }
            >
              <BaseControl
                label={
                  <>
                    { __('Icon Color', '_ws') }
                    { iconColor &&
                      <ColorIndicator
                        colorValue={ iconColor }
                      />
                    }
                  </>
                }
              >
                <ColorPalette
                  className="block-editor-color-palette-control__color-palette"
                  onChange={ newValue => setAttributes({ iconColor: newValue }) }
                  value={ iconColor }
                />
              </BaseControl>
            </PanelBody>
          </InspectorControls>
          <div className="icon-list-item">
            <SVGPicker
              label={ false }
              style={ { color: iconColor } }
              onChange={ newValue => setAttributes({ icon: newValue }) }
              value={ icon }
            />
            <RichText
              placeholder={ __('List Item', '_ws') }
              tagName="p"
              keepPlaceholderOnFocus={ true }
              onChange={ newValue => setAttributes({ text: newValue }) }
              onSplit={ value => {
                if (!value) {
                  return createBlock('ws/icon-list-item', {
                    ...props.attributes,
                    text: ''
                  })
                }
                return createBlock('ws/icon-list-item', {
                  ...props.attributes,
                  text: value
                })
              } }
              onReplace={ (blocks, indexToSelect) => {
                replaceBlocks([props.clientId], blocks, indexToSelect)
              } }
              onRemove={ forward => {
                removeBlocks([props.clientId], !forward)
              } }
              value={ text }
            />
          </div>
        </>
      )
    },
    save: props => {
      return null
    }
  }
}
