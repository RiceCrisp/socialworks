/* global React, wp */
const { InnerBlocks, InspectorControls } = wp.blockEditor
const { PanelBody, SelectControl } = wp.components
const { __ } = wp.i18n

export const section = {
  name: 'ws/section',
  args: {
    title: __('Section', '_ws'),
    description: __('Generic wrapper for transforming any combination of blocks into a section with background and padding rules.', '_ws'),
    icon: <svg viewBox="-4 -4 32 32"><path d="M8 0v2h8v-2h-8zm0 24v-2h8v2h-8zm10-24h6v6h-2v-4h-4v-2zm-18 8h2v8h-2v-8zm0-2v-6h6v2h-4v4h-2zm24 10h-2v-8h2v8zm0 2v6h-6v-2h4v-4h2zm-18 6h-6v-6h2v4h4v2zm12-18h-12v12h12v-12z"/></svg>,
    category: 'ws-layout',
    supports: {
      anchor: true,
      backgroundMedia: true,
      color: {
        gradients: true
      }
    },
    attributes: {
      width: {
        type: 'string',
        default: 'default'
      },
      paddingTop: {
        type: 'string',
        default: '100'
      },
      paddingBottom: {
        type: 'string',
        default: '100'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { width, paddingTop, paddingBottom } = props.attributes
      return (
        <>
          <InspectorControls>
            <PanelBody
              title={ __('Section Options', '_ws') }
            >
              <SelectControl
                label={ __('Width', '_ws') }
                options={ [
                  { label: __('Wide', '_ws'), value: 'wide' },
                  { label: __('Default', '_ws'), value: 'default' },
                  { label: __('Narrow', '_ws'), value: 'narrow' },
                  { label: __('Narrower', '_ws'), value: 'narrower' },
                  { label: __('Narrowest', '_ws'), value: 'narrowest' }
                ] }
                onChange={ newValue => setAttributes({ width: newValue }) }
                value={ width }
              />
              <div className="padding">
                <SelectControl
                  label={ __('Top', '_ws') }
                  options={ [
                    { label: '-100%', value: '-100' },
                    { label: '-50%', value: '-50' },
                    { label: '0%', value: '0' },
                    { label: '50%', value: '50' },
                    { label: '100%', value: '100' },
                    { label: '150%', value: '150' },
                    { label: '200%', value: '200' }
                  ] }
                  onChange={ newValue => setAttributes({ paddingTop: newValue }) }
                  value={ paddingTop }
                />
                <SelectControl
                  label={ __('Bottom', '_ws') }
                  options={ [
                    { label: '200%', value: '200' },
                    { label: '150%', value: '150' },
                    { label: '100%', value: '100' },
                    { label: '50%', value: '50' },
                    { label: '0%', value: '0' },
                    { label: '-50%', value: '-50' },
                    { label: '-100%', value: '-100' }
                  ] }
                  onChange={ newValue => setAttributes({ paddingBottom: newValue }) }
                  value={ paddingBottom }
                />
              </div>
            </PanelBody>
          </InspectorControls>
          <div className={ `width-${width} padding-top-${paddingTop} padding-bottom-${paddingBottom}` }>
            <InnerBlocks />
          </div>
        </>
      )
    },
    save: props => {
      return (
        <InnerBlocks.Content />
      )
    }
  }
}
