/* globals React, wp */
import Chip from 'Components/chip.js'
const apiFetch = wp.apiFetch
const { Component } = wp.element
const { __ } = wp.i18n

export default class PostPreview extends Component {

  constructor() {
    super(...arguments)
    this.state = {
      post: {}
    }
  }

  monthToString(n) {
    const m = [
      '',
      __('January', '_ws'),
      __('February', '_ws'),
      __('March', '_ws'),
      __('April', '_ws'),
      __('May', '_ws'),
      __('June', '_ws'),
      __('July', '_ws'),
      __('August', '_ws'),
      __('September', '_ws'),
      __('Novemeber', '_ws'),
      __('December', '_ws')
    ]
    return m[n]
  }

  getPost(id = this.props.id) {
    /* -1 means this is just an example post */
    if (id === -1) {
      this.setState({ post: {
        post_type: 'post',
        post_title: 'Post Title',
        thumbnail: '/wp-content/themes/_ws/assets/placeholder_image.jpg',
        post_date: Date.now()
      } })
    }
    else {
      apiFetch({ path: `/ws/all?include=${id}` })
        .then(res => {
          this.setState({ post: res[0] })
        })
        .catch(err => {
          this.setState({ post: {} })
          console.error('error', err)
        })
    }
  }

  componentDidUpdate(prevProps) {
    if (this.props.id !== prevProps.id) {
      this.getPost()
    }
  }

  componentDidMount() {
    if (this.props.id) {
      this.getPost()
    }
  }

  decodeEntities(html) {
    const txt = document.createElement('textarea')
    txt.innerHTML = html
    return txt.value
  }

  render() {
    const { post } = this.state
    const { onDelete } = this.props
    if (post) {
      const d = post.start_date ? new Date(post.start_date) : new Date(post.post_date)
      if (onDelete) {
        return (
          <div className="chips">
            <Chip value={ post.post_title } onDelete={ onDelete } />
          </div>
        )
      }
      return (
        <div className="components-post-preview">
          { post.thumbnail && (
            <div className="featured-image">
              <img src={ post.thumbnail } alt={ post.post_title } />
            </div>
          ) }
          <div>
            <p className="label">{ `${post.post_type} | ${this.monthToString(d.getMonth() - 1)} ${d.getDate()}, ${d.getFullYear()}` }</p>
            <h3 className="post-title">{ this.decodeEntities(post.post_title) }</h3>
          </div>
        </div>
      )
    }
    return (
      <>
        <h3>{ __('Post Removed or Corrupted', '_ws') }</h3>
      </>
    )
  }

}
