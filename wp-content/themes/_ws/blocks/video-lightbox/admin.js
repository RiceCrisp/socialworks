/* global React, wp */
import MediaPreview from 'Components/media-preview.js'
import MediaSelect from 'Components/media-select.js'
const { InspectorControls } = wp.blockEditor
const { Icon, PanelBody, TextControl } = wp.components
const { __ } = wp.i18n

export const videoLightbox = {
  name: 'ws/video-lightbox',
  args: {
    title: __('Video Lightbox', '_ws'),
    description: __('Play button with optional background image that opens a lightbox playing a video.', '_ws'),
    icon: 'video-alt3',
    category: 'ws-bit',
    supports: {
      anchor: true
    },
    attributes: {
      preview: {
        type: 'number'
      },
      video: {
        type: 'string'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { preview, video } = props.attributes
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Video Lightbox Options', '_ws') }
            >
              <TextControl
                label={ __('Video URL', '_ws') }
                onChange={ newValue => setAttributes({ video: newValue }) }
                checked={ video }
              />
              <MediaSelect
                label={ __('Preview Image', '_ws') }
                onChange={ ({ id }) => {
                  setAttributes({
                    preview: id
                  })
                } }
                id={ preview }
              />
            </PanelBody>
          </InspectorControls>
          <div className="video-lightbox">
            <MediaPreview
              id={ preview }
              size="full"
            />
            <Icon icon="controls-play" />
          </div>
        </>
      )
    },
    save: props => {
      return null
    }
  }
}
