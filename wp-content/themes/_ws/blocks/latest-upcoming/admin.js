/* global React, wp */
import CheckboxGroupControl from 'Components/checkbox-group-control'
import PostPreview from 'Components/post-preview.js'
import PostTypeControl from 'Components/post-type-control.js'
const apiFetch = wp.apiFetch
const { InspectorControls } = wp.blockEditor
const { PanelBody, RangeControl, ToggleControl } = wp.components
const { useSelect } = wp.data
const { useEffect, useState } = wp.element
const { __ } = wp.i18n

export const latestUpcoming = {
  name: 'ws/latest-upcoming',
  args: {
    title: __('Latest & Upcoming', '_ws'),
    description: __('List latest posts of a chosen type or list upcoming post types with a start date.', '_ws'),
    icon: 'warning',
    category: 'ws-dynamic',
    supports: {
      anchor: true
    },
    example: {
      attributes: {
        example: true
      }
    },
    attributes: {
      example: {
        type: 'boolean',
        default: false
      },
      postTypes: {
        type: 'array',
        default: []
      },
      taxTerms: {
        type: 'object',
        default: {}
      },
      allPosts: {
        type: 'boolean'
      },
      numPosts: {
        type: 'number',
        default: 3
      },
      horizontalScroll: {
        type: 'boolean'
      }
    },
    edit: props => {
      if (props.attributes.example) {
        return (
          <div className={ `preview-row ${props.attributes.className || ''}` }>
            <div className="col">
              <PostPreview id={ -1 } />
            </div>
            <div className="col">
              <PostPreview id={ -1 } />
            </div>
          </div>
        )
      }
      const { setAttributes } = props
      const { className, postTypes, taxTerms, allPosts, numPosts, horizontalScroll } = props.attributes
      const [posts, setPosts] = useState([])
      useEffect(() => {
        if (postTypes.length > 0) {
          let url = `/ws/all?post_type=${postTypes.join(',')}`
          if (Object.keys(taxTerms).length > 0) {
            url += '&terms='
            for (const taxonomy in taxTerms) {
              const terms = taxTerms[taxonomy]
              url += `${taxonomy}~~${terms.join('~')},`
            }
            url = url.slice(0, -1)
          }
          apiFetch({ path: url })
            .then(res => {
              setPosts(res)
            })
            .catch(err => {
              setPosts([])
              console.error('error', err)
            })
        }
        else {
          setPosts([])
        }
      }, [postTypes, taxTerms])
      const { taxonomies } = useSelect(select => {
        let taxonomies = select('core').getTaxonomies({ per_page: -1 })
        if (taxonomies && postTypes.length > 0) {
          taxonomies = taxonomies.filter(taxonomy => {
            return postTypes.every(postType => {
              return taxonomy.types.indexOf(postType) !== -1
            })
          })
          taxonomies = taxonomies.map(taxonomy => {
            const terms = select('core').getEntityRecords('taxonomy', taxonomy.slug, { per_page: -1 }) || []
            const childTerms = []
            const parentTerms = terms.filter(term => {
              if (term.parent === 0) {
                term.children = []
                return true
              }
              childTerms.push(term)
              return false
            })
            childTerms.forEach(child => {
              const parentIndex = parentTerms.findIndex(parentTerm => parentTerm.id === child.parent)
              if (parentIndex >= 0) {
                parentTerms[parentIndex].children.push(child)
              }
            })
            return {
              ...taxonomy,
              terms: parentTerms
            }
          })
        }
        return {
          taxonomies: taxonomies
        }
      }, [postTypes])
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Latest & Upcoming Options', '_ws') }
            >
              <PostTypeControl
                label={ __('Post Types', '_ws') }
                help={ __('Post types with start dates will ordered by "upcoming" instead of "latest"', '_ws') }
                onChange={ newValue => setAttributes({ postTypes: newValue, taxTerms: [] }) }
                value={ postTypes }
                multiple
              />
              { !!taxonomies && taxonomies.length > 0 && (
                taxonomies.map(tax => {
                  if (tax.terms && tax.terms.length > 0) {
                    return (
                      <CheckboxGroupControl
                        legend={ tax.name }
                        options={ tax.terms.map(term => ({
                          label: term.name,
                          value: term.id,
                          children: term.children.map(t => ({
                            label: t.name, value: t.id
                          }))
                        })) }
                        onChange={ newValue => setAttributes({ taxTerms: { ...taxTerms, [tax.slug]: newValue } }) }
                        value={ taxTerms[tax.slug] || [] }
                      />
                    )
                  }
                })
              ) }
              <ToggleControl
                label={ __('Show All Posts', '_ws') }
                onChange={ newValue => setAttributes({ allPosts: newValue }) }
                checked={ allPosts }
              />
              { !allPosts && (
                <RangeControl
                  label={ __('Number of Posts', '_ws') }
                  min="1"
                  max="8"
                  onChange={ newValue => setAttributes({ numPosts: newValue }) }
                  value={ numPosts }
                />
              ) }
              <ToggleControl
                label={ __('Horizontal scroll', '_ws') }
                onChange={ newValue => setAttributes({ horizontalScroll: newValue }) }
                checked={ horizontalScroll }
              />
            </PanelBody>
          </InspectorControls>
          <div className={ `preview-row ${className || ''}` }>
            { posts && posts.map((v, i) => {
              if ((allPosts && i < 4) || (!allPosts && i < numPosts)) {
                return (
                  <div key={ v.ID } className="col">
                    <PostPreview id={ v.ID } />
                  </div>
                )
              }
            }) }
          </div>
          { allPosts && posts && posts.length > 4 ? (<p className="no-posts">And { posts.length - 4 } others</p>) : '' }
          { posts && posts.length === 0 ? (<small className="no-posts">{ __('No posts found with current filters.', '_ws') }</small>) : '' }
        </>
      )
    },
    save: props => {
      return null
    }
  },
  styles: [
    {
      name: 'default',
      label: __('Default', '_ws'),
      isDefault: true
    },
    {
      name: 'featured',
      label: __('Featured', '_ws')
    },
    {
      name: 'cards',
      label: __('Cards', '_ws')
    },
    {
      name: 'tiles',
      label: __('Tiles', '_ws')
    },
    {
      name: 'list',
      label: __('List', '_ws')
    }
  ]
}
