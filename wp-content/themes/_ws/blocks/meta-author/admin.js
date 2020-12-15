/* global React, wp */
import MediaSelect from 'Components/media-select.js'
const { TextControl } = wp.components
const { useDispatch, useSelect } = wp.data
const { useEffect } = wp.element
const { __ } = wp.i18n

export const metaAuthor = {
  name: 'ws/meta-author',
  args: {
    title: __('Author Meta', '_ws'),
    description: __('Author and profile image override. Only the data is used, so this block\'s position does not matter.', '_ws'),
    icon: 'edit',
    category: 'ws-meta',
    supports: {
      multiple: false,
      customClassName: false
    },
    attributes: {
      authorName: {
        type: 'string',
        source: 'meta',
        meta: '_author_name'
      },
      authorImage: {
        type: 'number',
        source: 'meta',
        meta: '_author_image'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { authorName, authorImage } = props.attributes
      const { author } = useSelect(select => ({
        author: select('core').getAuthors().find(author => {
          return select('core/editor').getEditedPostAttribute('author') === author.id
        })
      }))
      const { editPost } = useDispatch('core/editor')
      useEffect(() => {
        return () => {
          const metas = {}
          for (const attribute in metaAuthor.args.attributes) {
            const metaName = metaAuthor.args.attributes[attribute].meta
            const metaType = metaAuthor.args.attributes[attribute].type
            metas[metaName] = metaType === 'boolean' ? false : (metaType === 'number' ? 0 : '')
          }
          editPost({ meta: metas })
        }
      }, [])
      return (
        <div className="row align-items-center">
          <div className="col">
            <div className="profile-pic">
              <MediaSelect
                label={ __('Profile Image', '_ws') }
                buttonText={ __('Replace Image', '_ws') }
                size="thumbnail"
                onChange={ ({ id }) => setAttributes({ authorImage: id }) }
                id={ authorImage }
              />
              { author && !authorImage && (
                <img src={author.avatar_urls['96']} alt="Profile Pic" />
              ) }
            </div>
          </div>
          <div className="col-xs">
            <TextControl
              label={ __('Name', '_ws') }
              placeholder={ author ? author.name : '' }
              onChange={ newValue => setAttributes({ authorName: newValue }) }
              value={ authorName }
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
