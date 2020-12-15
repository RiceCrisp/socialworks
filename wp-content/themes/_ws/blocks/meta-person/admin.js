/* global React, wp */
const { TextControl } = wp.components
const { useDispatch } = wp.data
const { useEffect } = wp.element
const { __ } = wp.i18n

export const metaPerson = {
  name: 'ws/meta-person',
  args: {
    title: __('Person Meta', '_ws'),
    description: __('Person template data. Only the data is used, so this block\'s position does not matter.', '_ws'),
    icon: 'groups',
    category: 'ws-meta',
    supports: {
      multiple: false,
      customClassName: false
    },
    attributes: {
      position: {
        type: 'string',
        source: 'meta',
        meta: '_person_position'
      },
      linkedin: {
        type: 'string',
        source: 'meta',
        meta: '_person_linkedin'
      },
      instagram: {
        type: 'string',
        source: 'meta',
        meta: '_person_instagram'
      },
      twitter: {
        type: 'string',
        source: 'meta',
        meta: '_person_twitter'
      },
      facebook: {
        type: 'string',
        source: 'meta',
        meta: '_person_facebook'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { position, linkedin, instagram, twitter, facebook } = props.attributes
      const { editPost } = useDispatch('core/editor')
      useEffect(() => {
        return () => {
          const metas = {}
          for (const attribute in metaPerson.args.attributes) {
            const metaName = metaPerson.args.attributes[attribute].meta
            const metaType = metaPerson.args.attributes[attribute].type
            metas[metaName] = metaType === 'boolean' ? false : (metaType === 'number' ? 0 : '')
          }
          editPost({ meta: metas })
        }
      }, [])
      return (
        <div className="row">
          <div className="col-xs-12">
            <TextControl
              label={ __('Position/Title', '_ws') }
              onChange={ newValue => setAttributes({ position: newValue }) }
              value={ position }
            />
          </div>
          <div className="col-sm-6">
            <TextControl
              label={ __('LinkedIn', '_ws') }
              onChange={ newValue => setAttributes({ linkedin: newValue }) }
              value={ linkedin }
            />
          </div>
          <div className="col-sm-6">
            <TextControl
              label={ __('Instagram', '_ws') }
              onChange={ newValue => setAttributes({ instagram: newValue }) }
              value={ instagram }
            />
          </div>
          <div className="col-sm-6">
            <TextControl
              label={ __('Twitter', '_ws') }
              onChange={ newValue => setAttributes({ twitter: newValue }) }
              value={ twitter }
            />
          </div>
          <div className="col-sm-6">
            <TextControl
              label={ __('Facebook', '_ws') }
              onChange={ newValue => setAttributes({ facebook: newValue }) }
              value={ facebook }
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
