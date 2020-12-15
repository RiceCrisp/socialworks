/* global React, wp */
import MediaSelect from 'Components/media-select.js'
const { InnerBlocks } = wp.blockEditor
const { __ } = wp.i18n

export const person = {
  name: 'ws/person',
  args: {
    title: __('Person', '_ws'),
    description: __('Profile picture with name and any other information.', '_ws'),
    icon: 'admin-users',
    category: 'ws-bit',
    supports: {
      anchor: true
    },
    attributes: {
      image: {
        type: 'number'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { image } = props.attributes
      return (
        <>
          <div className="row align-items-center">
            <div className="col person-image">
              <MediaSelect
                size="thumbnail"
                onChange={ ({ id }) => setAttributes({ image: id }) }
                id={ image }
              />
            </div>
            <div className="col-xs">
              <InnerBlocks />
            </div>
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
