/* global React, wp */
import { splitHalf } from './split-half/admin.js'
const { InnerBlocks, InspectorControls } = wp.blockEditor
const { PanelBody, SelectControl, ToggleControl } = wp.components
const { useDispatch, useSelect } = wp.data
const { __ } = wp.i18n

export const split = {
  name: 'ws/split',
  args: {
    title: __('Split', '_ws'),
    description: __('Two columns of content with optional variations.', '_ws'),
    icon: 'image-flip-horizontal',
    category: 'ws-layout',
    supports: {
      anchor: true
    },
    attributes: {
      alignment: {
        type: 'string'
      },
      variant: {
        type: 'string'
      },
      padding: {
        type: 'boolean'
      },
      mobileReverse: {
        type: 'boolean'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { alignment, variant, padding, mobileReverse } = props.attributes
      const { moveBlockToPosition } = useDispatch('core/block-editor')
      const { splits } = useSelect(select => ({
        splits: select('core/block-editor').getBlocks(props.clientId)
      }))
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Split Options', '_ws') }
            >
              <SelectControl
                label={ __('Vertical Alignment', '_ws') }
                options={ [
                  { label: 'Top', value: 'start' },
                  { label: 'Center', value: 'center' },
                  { label: 'Bottom', value: 'end' }
                ] }
                onChange={ newValue => setAttributes({ alignment: newValue }) }
                value={ alignment }
              />
              <SelectControl
                label={ __('Layout', '_ws') }
                options={ [
                  { label: '50/50', value: '' },
                  { label: '40/60', value: 'large-right' },
                  { label: '60/40', value: 'large-left' }
                ] }
                onChange={ newValue => setAttributes({ variant: newValue }) }
                value={ variant }
              />
              <ToggleControl
                label={ __('Padding Between', '_ws') }
                onChange={ newValue => setAttributes({ padding: newValue }) }
                checked={ padding }
              />
              <ToggleControl
                label={ __('Reverse halves on mobile', '_ws') }
                onChange={ newValue => {
                  moveBlockToPosition(splits[0].clientId, props.clientId, props.clientId, 1)
                  moveBlockToPosition(splits[1].clientId, props.clientId, props.clientId, 0)
                  setAttributes({ mobileReverse: newValue })
                } }
                checked={ mobileReverse }
              />
            </PanelBody>
          </InspectorControls>
          <div className={ `block-row has-2-columns ${alignment ? `align-items-${alignment}` : ''} ${variant ? `variant-${variant}` : ''} ${padding ? 'split-padding' : ''} ${mobileReverse ? 'row-reverse' : ''}` }>
            <InnerBlocks
              allowedBlocks={ ['ws/split-half'] }
              templateLock='insert'
              template={ [
                ['ws/split-half'],
                ['ws/split-half']
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
  innerBlocks: [splitHalf]
}
