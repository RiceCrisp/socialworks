/* global React, wp */
import SVGPicker from 'Components/svg-picker.js'
const { ColorPalette, InnerBlocks, InspectorControls } = wp.blockEditor
const { BaseControl, ColorIndicator, PanelBody, SelectControl } = wp.components
const { __ } = wp.i18n

export const icon = {
  name: 'ws/icon',
  args: {
    title: __('Icon', '_ws'),
    description: __('SVG icon.', '_ws'),
    icon: 'marker',
    category: 'ws-bit',
    supports: {
      textAlign: true
    },
    attributes: {
      icon: {
        type: 'string'
      },
      size: {
        type: 'string',
        default: 'artboard'
      },
      iconColor: {
        type: 'string'
      },
      iconBackgroundColor: {
        type: 'string'
      },
      text: {
        type: 'string'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { className, size, icon, iconColor, iconBackgroundColor, text } = props.attributes
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Icon Options', '_ws') }
            >
              <SelectControl
                label={ __('Size', '_ws') }
                options={ [
                  { value: 'artboard', label: 'Artboard' },
                  { value: 'small', label: 'Small' },
                  { value: 'large', label: 'Large' }
                ] }
                onChange={ newValue => setAttributes({ size: newValue }) }
                value={ size }
              />
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
              <BaseControl
                label={
                  <>
                    { __('Background Color', '_ws') }
                    { iconBackgroundColor &&
                      <ColorIndicator
                        colorValue={ iconBackgroundColor }
                      />
                    }
                  </>
                }
              >
                <ColorPalette
                  className="block-editor-color-palette-control__color-palette"
                  onChange={ newValue => setAttributes({ iconBackgroundColor: newValue }) }
                  value={ iconBackgroundColor }
                />
              </BaseControl>
              <SelectControl
                label={ __('Text Location', '_ws') }
                options={ [
                  { value: '', label: 'None' },
                  { value: 'right', label: 'Right' },
                  { value: 'below', label: 'Below' },
                  { value: 'left', label: 'Left' }
                ] }
                onChange={ newValue => setAttributes({ text: newValue }) }
                value={ text }
              />
            </PanelBody>
          </InspectorControls>
          <div className={ `${className || ''} ${text === 'right' ? 'side-text' : ''}` }>
            <SVGPicker
              label={ false }
              style={ { color: iconColor, backgroundColor: iconBackgroundColor } }
              className={ size ? `size-${size}` : '' }
              onChange={ newValue => setAttributes({ icon: newValue }) }
              value={ icon }
            />
            { text && (
              <InnerBlocks
                templateLock={ false }
              />
            ) }
          </div>
        </>
      )
    },
    save: props => {
      return (
        <InnerBlocks.Content />
      )
    }
  }
}
