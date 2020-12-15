/* global React, wp */
import lottie from 'lottie-web/build/player/lottie_light'
const { BlockControls, InspectorControls } = wp.blockEditor
const { Button, FormFileUpload, PanelBody, Placeholder, ToggleControl, ToolbarGroup } = wp.components
const { useEffect, useRef } = wp.element
const { __ } = wp.i18n

export const svgAnimation = {
  name: 'ws/svg-animation',
  args: {
    title: __('SVG Animation', '_ws'),
    description: __('SVG animation via the lottie.js library.', '_ws'),
    icon: 'marker',
    category: 'ws-bit',
    attributes: {
      json: {
        type: 'string'
      },
      loop: {
        type: 'boolean'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { json, loop } = props.attributes
      const animationElement = useRef(null)
      useEffect(() => {
        if (json && animationElement.current) {
          // useRef is null on first render and it's difficult to assign a callback so we just use setTimeout to wait for the second render
          setTimeout(() => {
            lottie.loadAnimation({
              container: animationElement.current,
              renderer: 'svg',
              loop: true,
              autoplay: true,
              animationData: JSON.parse(json)
            })
          }, 0)
        }
      }, [json])
      return (
        <>
          { json ? (
            <>
              <InspectorControls>
                <PanelBody
                  title={ __('SVG Animation Options', '_ws') }
                >
                  <ToggleControl
                    label={ __('Loop', '_ws') }
                    onChange={ newValue => setAttributes({ loop: newValue }) }
                    checked={ loop }
                  />
                </PanelBody>
              </InspectorControls>
              <BlockControls>
                <ToolbarGroup>
                  <Button
                    className="components-toolbar__control"
                    label={ __('Remove Animation', '_ws') }
                    icon="trash"
                    onClick={ () => setAttributes({ json: '' }) }
                  />
                </ToolbarGroup>
              </BlockControls>
              <div
                className="svg-animation"
                ref={ animationElement }
              ></div>
            </>
          ) : (
            <Placeholder
              icon="marker"
              label={ __('SVG Animation', '_ws') }
              instructions={ __('Import the JSON file from your lottie animation.', '_ws') }
            >
              <FormFileUpload
                isPrimary
                accept=".json"
                onChange={ e => {
                  const reader = new FileReader()
                  reader.onload = () => {
                    try {
                      const result = reader.result.replace(/[\n\t\r]+/g, '')
                      setAttributes({ json: result })
                    }
                    catch (err) {
                      console.error(err)
                    }
                  }
                  if (e.target.files.length) {
                    reader.readAsText(e.target.files[0])
                  }
                } }
              >
                Upload JSON
              </FormFileUpload>
            </Placeholder>
          ) }
        </>
      )
    },
    save: props => {
      return (
        null
      )
    }
  }
}
