/* globals React, wp */
import MediaSelect from 'Components/media-select.js'
const { InspectorControls } = wp.blockEditor
const { hasBlockSupport } = wp.blocks
const { PanelBody, SelectControl, ToggleControl } = wp.components
const { createHigherOrderComponent } = wp.compose
const { useSelect } = wp.data
const { Component } = wp.element
const { __ } = wp.i18n

/* useEffect fires every time we update background image focal point, and useRef doesn't work for HOC's, so we use the class syntax's componentDidUpdate to check for actual media changes */
class Background extends Component {

  constructor() {
    super(...arguments)
    this.state = {
      mediaObject: null
    }
  }

  componentDidMount() {
    if (this.props.attributes.backgroundMedia) {
      wp.media.attachment(this.props.attributes.backgroundMedia).fetch()
        .then(data => {
          this.setState({ mediaObject: data })
        })
        .fail(() => {
          this.setState({ mediaObject: null })
        })
    }
  }

  componentDidUpdate(oldProps) {
    if (oldProps.attributes.backgroundMedia !== this.props.attributes.backgroundMedia) {
      wp.media.attachment(this.props.attributes.backgroundMedia).fetch()
        .then(data => {
          this.setState({ mediaObject: data })
        })
        .fail(() => {
          this.setState({ mediaObject: null })
        })
    }
  }

  render() {
    const {
      backgroundX,
      backgroundY,
      backgroundSize,
      backgroundOverlay,
      backgroundParallax
    } = this.props.attributes
    const {
      mediaObject
    } = this.state
    const classes = ['background-image']
    if (backgroundSize) {
      classes.push(`has-${backgroundSize}-background-size`)
    }
    if (backgroundOverlay) {
      classes.push('has-background-overlay')
    }
    if (backgroundParallax) {
      classes.push('parallax-bg')
    }
    return (
      <div
        className={ classes.join(' ') }
        style={ {
          backgroundImage: mediaObject && mediaObject.url ? `url(${mediaObject.url})` : '',
          backgroundPosition: backgroundX && backgroundY ? `${backgroundX * 100}% ${backgroundY * 100}%` : ''
        } }
      ></div>
    )
  }

}

export const backgroundControls = {
  hook: 'editor.BlockEdit',
  name: 'ws/with-background-controls',
  func: createHigherOrderComponent(BlockEdit => {
    return props => {
      if (!props.name.startsWith('ws/')) {
        return <BlockEdit { ...props } />
      }
      if (props.name.startsWith('ws/meta-')) {
        return (
          <div
            className={ `ws-block-container has-background` }
          >
            <div className="background-container"></div>
            <BlockEdit { ...props } />
          </div>
        )
      }
      const { setAttributes } = props
      const {
        backgroundMedia,
        backgroundVideoPoster,
        backgroundX,
        backgroundY,
        backgroundSize,
        backgroundOverlay,
        backgroundParallax
      } = props.attributes
      return (
        <>
          <div className="ws-block-container">
            <div className="background-container">
              <Background { ...props } />
            </div>
            <BlockEdit { ...props } />
          </div>
          { hasBlockSupport(props.name, 'backgroundMedia') && (
            <InspectorControls>
              <PanelBody
                title={ __('Background Media Settings', '_ws') }
              >
                <MediaSelect
                  label={ __('Background Image/Video', '_ws') }
                  buttonText={ __('Select Background Image/Video', '_ws') }
                  onChange={ ({ id, focalPoint, posterImage, newMedia }) => setAttributes({
                    backgroundMedia: id,
                    backgroundX: focalPoint.x,
                    backgroundY: focalPoint.y,
                    backgroundVideoPoster: posterImage
                  }) }
                  id={ backgroundMedia }
                  focalPoint={ {
                    x: backgroundX,
                    y: backgroundY
                  } }
                  posterImage={ backgroundVideoPoster }
                />
                { backgroundMedia && (
                  <>
                    <SelectControl
                      label={ __('Image/Video Position', '_ws') }
                      options={ [
                        { label: 'Cover', value: 'cover' },
                        { label: 'Left Half', value: 'left-half' },
                        { label: 'Right Half', value: 'right-half' }
                      ] }
                      onChange={ newValue => setAttributes({ backgroundSize: newValue }) }
                      value={ backgroundSize }
                    />
                    <ToggleControl
                      label={ __('Overlay', '_ws') }
                      onChange={ newValue => setAttributes({ backgroundOverlay: newValue }) }
                      checked={ backgroundOverlay }
                    />
                    <ToggleControl
                      label={ __('Parallax', '_ws') }
                      onChange={ newValue => setAttributes({ backgroundParallax: newValue }) }
                      checked={ backgroundParallax }
                    />
                  </>
                ) }
              </PanelBody>
            </InspectorControls>
          ) }
        </>
      )
    }
  }, 'withBackgroundControls')
}

export const backgroundAttributes = {
  hook: 'blocks.registerBlockType',
  name: 'ws/with-background-attributes',
  func: (props, name) => {
    if (props.supports && props.supports.backgroundMedia) {
      props.attributes = {
        ...props.attributes,
        backgroundMedia: {
          type: 'number'
        },
        backgroundVideoPoster: {
          type: 'number'
        },
        backgroundX: {
          type: 'string',
          default: '0.5'
        },
        backgroundY: {
          type: 'string',
          default: '0.5'
        },
        backgroundSize: {
          type: 'string'
        },
        backgroundOverlay: {
          type: 'boolean'
        },
        backgroundParallax: {
          type: 'boolean'
        }
      }
    }
    return props
  }
}
