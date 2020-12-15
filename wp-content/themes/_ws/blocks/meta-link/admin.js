/* global React, wp */
const { RadioControl, TextControl } = wp.components
const { useDispatch } = wp.data
const { useEffect } = wp.element
const { __ } = wp.i18n

export const metaLink = {
  name: 'ws/meta-link',
  args: {
    title: __('Page Link Meta', '_ws'),
    description: __('Modify page behavior. Only the data is used, so this block\'s position does not matter.', '_ws'),
    icon: 'external',
    category: 'ws-meta',
    supports: {
      multiple: false,
      customClassName: false
    },
    attributes: {
      linkUrl: {
        type: 'string',
        source: 'meta',
        meta: '_link_url'
      },
      linkType: {
        type: 'string',
        source: 'meta',
        meta: '_link_type',
        default: 'new-tab'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { linkUrl, linkType } = props.attributes
      const { editPost } = useDispatch('core/editor')
      useEffect(() => {
        return () => {
          const metas = {}
          for (const attribute in metaLink.args.attributes) {
            const metaName = metaLink.args.attributes[attribute].meta
            const metaType = metaLink.args.attributes[attribute].type
            metas[metaName] = metaType === 'boolean' ? false : (metaType === 'number' ? 0 : '')
          }
          editPost({ meta: metas })
        }
      }, [])
      return (
        <div className="row">
          <div className="col-sm-6">
            <TextControl
              label={ __('Internal or external resource URL', '_ws') }
              onChange={ newValue => setAttributes({ linkUrl: newValue }) }
              value={ linkUrl }
            />
          </div>
          <div className="col-sm-6">
            { !!linkUrl && (
              <RadioControl
                label={ __('Link Behavior') }
                options={ [
                  { label: 'Open in new tab', value: 'new-tab' },
                  { label: 'Download file', value: 'download' },
                  { label: 'Open lightbox', value: 'lightbox' }
                ] }
                onChange={ newValue => setAttributes({ linkType: newValue }) }
                selected={ linkType || 'new-tab' }
              />
            ) }
          </div>
        </div>
      )
    },
    save: props => {
      return null
    }
  }
}
