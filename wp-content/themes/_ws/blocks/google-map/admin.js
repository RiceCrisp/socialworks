/* global React, wp */
import LocationPicker from 'Components/location-picker.js'
const { InspectorControls } = wp.blockEditor
const { PanelBody, TextareaControl } = wp.components
const { __ } = wp.i18n

export const googleMap = {
  name: 'ws/google-map',
  args: {
    title: __('Google Map', '_ws'),
    description: __('Iframe of Google Maps with editable location.', '_ws'),
    icon: 'location-alt',
    category: 'ws-bit',
    supports: {
      anchor: true
    },
    attributes: {
      location: {
        type: 'object'
      },
      styles: {
        type: 'string'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { location, styles } = props.attributes
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Google Map Options', '_ws') }
            >
              <TextareaControl
                label={ __('JSON Styles', '_ws') }
                onChange={ newValue => setAttributes({ styles: newValue }) }
                value={ styles }
                help={ <>Overrides <a href="/wp-admin/options-general.php?page=site_options" target="_blank">global styles</a></> }
              />
            </PanelBody>
          </InspectorControls>
          <LocationPicker
            onChange={ newValue => setAttributes({ location: newValue }) }
            location={ location }
          />
        </>
      )
    },
    save: props => {
      return null
    }
  }
}
