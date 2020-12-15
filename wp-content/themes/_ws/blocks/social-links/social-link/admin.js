/* global React, wp */
import SVGPicker from 'Components/svg-picker.js'
import URLControl from 'Components/url-control.js'
const { InspectorControls } = wp.blockEditor
const { PanelBody } = wp.components
const { __ } = wp.i18n

export const socialLink = {
  name: 'ws/social-link',
  args: {
    title: __('Social Link', '_ws'),
    icon: 'format-aside',
    category: 'ws-bit',
    supports: {
      customClassName: false,
      html: false
    },
    parent: ['ws/social-links'],
    attributes: {
      icon: {
        type: 'string'
      },
      link: {
        type: 'object',
        default: {
          url: '',
          opensInNewTab: false
        }
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { icon, link } = props.attributes
      return (
        <div className="row">
          <SVGPicker
            label={ false }
            className="col"
            onChange={ newValue => setAttributes({ icon: newValue }) }
            value={ icon }
          />
          <InspectorControls>
            <PanelBody
              title={ __('Social Link Options', '_ws') }
            >
              <URLControl
                label={ __('URL', '_ws') }
                onChange={ newValue => setAttributes({ link: newValue }) }
                value={ link }
              />
            </PanelBody>
          </InspectorControls>
        </div>
      )
    },
    save: props => {
      return null
    }
  }
}
