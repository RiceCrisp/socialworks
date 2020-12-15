/* global React, wp */
import { tile } from './tile/admin.js'
const { InnerBlocks, InspectorControls } = wp.blockEditor
const { PanelBody, ToggleControl } = wp.components
const { __ } = wp.i18n

export const tiles = {
  name: 'ws/tiles',
  args: {
    title: __('Tiles', '_ws'),
    description: __('Flexible grid of tiles.', '_ws'),
    icon: 'screenoptions',
    category: 'ws-layout',
    supports: {
      anchor: true
    },
    attributes: {
      gutters: {
        type: 'boolean'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { gutters } = props.attributes
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Tile Options', '_ws') }
            >
              <ToggleControl
                label={ __('Gutters', '_ws') }
                onChange={ newValue => setAttributes({ gutters: newValue }) }
                checked={ gutters }
              />
            </PanelBody>
          </InspectorControls>
          <div className={ `tile-grid ${gutters ? 'has-gutters' : ''}` }>
            <InnerBlocks
              allowedBlocks={ ['ws/tile'] }
              template={ [
                ['ws/tile']
              ] }
            />
          </div>
        </>
      )
    },
    save: props => {
      return (
        <InnerBlocks.Content />
      )
    }
  },
  innerBlocks: [tile]
}
