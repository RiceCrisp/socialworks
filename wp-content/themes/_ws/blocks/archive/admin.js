/* global React, wp, locals */
import CheckboxGroupControl from 'Components/checkbox-group-control.js'
import PostPreview from 'Components/post-preview.js'
import PostTypeControl from 'Components/post-type-control.js'
const apiFetch = wp.apiFetch
const { InspectorControls } = wp.blockEditor
const { PanelBody, TextControl, ToggleControl } = wp.components
const { useSelect } = wp.data
const { useEffect, useState } = wp.element
const { __ } = wp.i18n

export const archive = {
  name: 'ws/archive',
  args: {
    title: __('Archive', '_ws'),
    description: __('List all posts of a chosen type with optional filters. Display for each post type is determined by the theme.', '_ws'),
    icon: 'schedule',
    category: 'ws-dynamic',
    supports: {
      anchor: true,
      multiple: false
    },
    attributes: {
      postTypes: {
        type: 'array',
        default: []
      },
      allPosts: {
        type: 'boolean'
      },
      numPosts: {
        type: 'string',
        default: locals.postsPerPage
      },
      filters: {
        type: 'array',
        default: []
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { postTypes, allPosts, numPosts, filters } = props.attributes
      const [posts, setPosts] = useState([])
      const [years, setYears] = useState([])
      useEffect(() => {
        if (postTypes.length > 0) {
          apiFetch({ path: `/ws/all?post_type=${postTypes.join(',')}` })
            .then(res => {
              setPosts(res)
              const years = []
              res.forEach(post => {
                const year = post.post_date.substring(0, 4)
                if (!years.includes(year)) {
                  years.push(year)
                }
              })
              setYears(years.sort())
            })
            .catch(err => {
              setPosts([])
              console.error('error', err)
            })
        }
        else {
          setPosts([])
        }
      }, [postTypes])
      const { taxonomies, filterList } = useSelect(select => {
        let taxonomies = select('core').getTaxonomies({ per_page: -1 })
        let filterList = [
          { label: 'Year', value: 'year' },
          { label: 'Search', value: 'search' }
        ]
        if (taxonomies && postTypes.length > 0) {
          taxonomies = taxonomies.filter(taxonomy => {
            return postTypes.every(postType => {
              return taxonomy.types.indexOf(postType) !== -1
            })
          })
          taxonomies = taxonomies.map(taxonomy => {
            return {
              ...taxonomy,
              terms: select('core').getEntityRecords('taxonomy', taxonomy.slug)
            }
          })
          if (taxonomies.some(taxonomy => taxonomy.terms && taxonomy.terms.length)) {
            filterList = taxonomies.map(tax => {
              return { label: tax.name, value: tax.slug }
            }).concat(filterList)
          }
        }
        return {
          taxonomies: taxonomies,
          filterList: filterList
        }
      }, [postTypes])
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Archive Options', '_ws') }
            >
              <PostTypeControl
                label={ __('Post Type', '_ws') }
                onChange={ newValue => setAttributes({ postTypes: newValue, filters: [] }) }
                value={ postTypes }
                multiple
              />
              <ToggleControl
                label={ __('Show All Posts', '_ws') }
                onChange={ newValue => setAttributes({ allPosts: newValue }) }
                checked={ allPosts }
              />
              { !allPosts && (
                <TextControl
                  label={ __('Posts Per Page', '_ws') }
                  help={ `Default: ${locals.postsPerPage}` }
                  min="1"
                  max="99"
                  type="number"
                  onChange={ newValue => setAttributes({ numPosts: newValue }) }
                  value={ numPosts }
                />
              ) }
              { filterList.length > 0 && (
                <CheckboxGroupControl
                  legend={ __('Filters', '_ws') }
                  options={ filterList }
                  onChange={ newValue => setAttributes({ filters: newValue }) }
                  value={ filters }
                />
              ) }
            </PanelBody>
          </InspectorControls>
          <div className="archive-filters">
            { filters && filters.length > 0 && (
              filters.map(filter => {
                if (filter === 'year') {
                  return (
                    <div className="archive-filter">
                      <span>{ __('Year', '_ws') }</span>
                      <ul>
                        { years.map(v => {
                          return (
                            <li key={ v }>{ v }</li>
                          )
                        }) }
                      </ul>
                    </div>
                  )
                }
                if (filter === 'timeline') {
                  return (
                    <div className="archive-filter">
                      <span>{ __('Past & Upcoming', '_ws') }</span>
                      <ul>
                        <li>{ __('Upcoming', '_ws') }</li>
                        <li>{ __('Past', '_ws') }</li>
                      </ul>
                    </div>
                  )
                }
                if (filter === 'search') {
                  return (
                    <div className="archive-filter search">Search</div>
                  )
                }
                if (taxonomies) {
                  return taxonomies.map(tax => {
                    if (tax.slug === filter) {
                      return (
                        <div className="archive-filter">
                          <span>{ tax.name }</span>
                          <ul>
                            { tax.terms && tax.terms.map(term => {
                              return (
                                <li key={ term.id }>{ term.name }</li>
                              )
                            }) }
                          </ul>
                        </div>
                      )
                    }
                  })
                }
              })
            ) }
          </div>
          <div className="preview-row">
            { posts && posts.map((v, i) => {
              if (i < 4) {
                return (
                  <div key={ v.ID } className="col">
                    <PostPreview id={ v.ID } />
                  </div>
                )
              }
            }) }
          </div>
          { posts && posts.length > 4 ? (<p className="no-posts">And { posts.length - 4 } others</p>) : '' }
          { posts && posts.length === 0 ? (<small className="no-posts">{ __('No posts found with current filters.', '_ws') }</small>) : '' }
        </>
      )
    },
    save: props => {
      return null
    }
  }
}
