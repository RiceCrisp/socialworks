/* globals React, wp */
import CheckboxGroupControl from 'Components/checkbox-group-control.js'
const { SelectControl } = wp.components
const { useSelect } = wp.data

export default function PostTypeControl(props) {
  const { postTypes } = useSelect(select => {
    const postTypes = select('core').getPostTypes({ per_page: -1 }) || []
    return {
      postTypes: postTypes.filter(postType => postType.slug !== 'attachment' && postType.slug !== 'wp_block')
    }
  })
  if (props.multiple) {
    return (
      <CheckboxGroupControl
        { ...props }
        options={ [...postTypes.map(postType => {
          return { label: postType.name, value: postType.slug }
        })] }
      />
    )
  }
  else {
    return (
      <SelectControl
        { ...props }
        options={ [...postTypes.map(postType => {
          return { label: postType.name, value: postType.slug }
        })] }
      />
    )
  }
}
