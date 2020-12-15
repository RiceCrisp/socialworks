/* global React, wp */
import MediaSelect from 'Components/media-select.js'
const { RichText } = wp.blockEditor
const { __ } = wp.i18n

export const testimonial = {
  name: 'ws/testimonial',
  args: {
    title: __('Testimonial', '_ws'),
    description: __('Quote with optional citation and image fields.', '_ws'),
    icon: 'format-quote',
    category: 'ws-layout',
    supports: {
      anchor: true
    },
    attributes: {
      image: {
        type: 'number'
      },
      quote: {
        type: 'string'
      },
      citation: {
        type: 'string'
      }
    },
    edit: props => {
      const { setAttributes } = props
      const { image, quote, citation } = props.attributes
      return (
        <>
          <MediaSelect
            label={ false }
            buttonText={ __('Logo', '_ws') }
            onChange={ ({ id }) => setAttributes({ image: id }) }
            id={ image }
          />
          <RichText
            placeholder={ __('Quote', '_ws') }
            keepPlaceholderOnFocus={ true }
            tagName="h5"
            onChange={ newValue => setAttributes({ quote: newValue }) }
            value={ quote }
          />
          <RichText
            placeholder={ __('Citation', '_ws') }
            keepPlaceholderOnFocus={ true }
            tagName="p"
            onChange={ newValue => setAttributes({ citation: newValue }) }
            value={ citation }
          />
        </>
      )
    },
    save: props => {
      return null
    }
  }
}
