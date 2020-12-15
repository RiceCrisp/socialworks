/* global React, wp */
import MediaSelect from 'Components/media-select.js'
const { TextControl } = wp.components
const { useDispatch } = wp.data
const { useEffect } = wp.element
const { __ } = wp.i18n

export const metaResource = {
  name: 'ws/meta-resource',
  args: {
    title: __('Resource Meta', '_ws'),
    description: __('Resource template data. Only the data is used, so this block\'s position does not matter.', '_ws'),
    icon: 'analytics',
    category: 'ws-meta',
    supports: {
      multiple: false,
      customClassName: false
    },
    attributes: {
      banner: {
        type: 'number',
        source: 'meta',
        meta: '_resource_banner_image'
      },
      formHeading: {
        type: 'string',
        source: 'meta',
        meta: '_resource_form_heading'
      },
      form: {
        type: 'string',
        source: 'meta',
        meta: '_resource_form'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { banner, formHeading, form } = props.attributes
      const { editPost } = useDispatch('core/editor')
      useEffect(() => {
        return () => {
          const metas = {}
          for (const attribute in metaResource.args.attributes) {
            const metaName = metaResource.args.attributes[attribute].meta
            const metaType = metaResource.args.attributes[attribute].type
            metas[metaName] = metaType === 'boolean' ? false : (metaType === 'number' ? 0 : '')
          }
          editPost({ meta: metas })
        }
      }, [])
      return (
        <div className="row">
          <div className="col-xs-12">
            <MediaSelect
              label={ __('Banner Image', '_ws') }
              onChange={ ({ id }) => setAttributes({ banner: id }) }
              id={ banner }
            />
          </div>
          <div className="col-sm-6">
            <TextControl
              label={ __('Form Heading', '_ws') }
              placeholder={ __('Download Now', '_ws') }
              onChange={ newValue => setAttributes({ formHeading: newValue }) }
              value={ formHeading }
            />
          </div>
          <div className="col-sm-6">
            <TextControl
              label={ __('Form URL', '_ws') }
              onChange={ newValue => setAttributes({ form: newValue }) }
              value={ form }
            />
          </div>
        </div>
      )
    },
    save: props => {
      return null
    }
  }
}
