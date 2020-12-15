/* globals React, wp */
const { Component } = wp.element

export default class MediaPreview extends Component {

  constructor() {
    super(...arguments)
    this.state = {
      mediaObject: null
    }
  }

  componentDidMount() {
    if (this.props.id) {
      wp.media.attachment(this.props.id).fetch()
        .then(data => {
          this.setState({ mediaObject: data })
        })
        .fail(() => {
          this.setState({ mediaObject: null })
        })
    }
  }

  componentDidUpdate(oldProps) {
    if (oldProps.id !== this.props.id) {
      wp.media.attachment(this.props.id).fetch()
        .then(data => {
          this.setState({ mediaObject: data })
        })
        .fail(() => {
          this.setState({ mediaObject: null })
        })
    }
  }

  render() {
    const { className, size, id, x, y } = this.props
    const { mediaObject } = this.state
    if (id && mediaObject && mediaObject.url) {
      return (
        <div className={ `components-media-preview ${className}` }>
          { mediaObject.type === 'image' ? (
            <img
              src={ size && mediaObject.sizes[size] ? mediaObject.sizes[size].url : mediaObject.url }
              alt={ mediaObject.alt }
              style={ { objectPosition: x && y ? `${x * 100}% ${y * 100}%` : false } }
            />
          ) : (
            <span>{ mediaObject.url.replace(/^.*[/]/, '') }</span>
          ) }
        </div>
      )
    }
    return null
  }

}
