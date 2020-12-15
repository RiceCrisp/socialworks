/* globals React, wp */
import { socialLink } from './social-link/admin.js'
const { InnerBlocks } = wp.blockEditor
const { useDispatch, useSelect } = wp.data
const { useEffect, useRef } = wp.element
const { __ } = wp.i18n

export const socialLinks = {
  name: 'ws/social-links',
  args: {
    title: __('Social Links', '_ws'),
    description: __('SVG links to social sites.', '_ws'),
    icon: 'share-alt2',
    category: 'ws-bit',
    supports: {
      anchor: true
    },
    edit: props => {
      const { removeBlock } = useDispatch('core/block-editor')
      const { blockCount } = useSelect(select => ({
        blockCount: select('core/block-editor').getBlockCount(props.clientId)
      }))
      const previousBlockCount = useRef(blockCount)
      useEffect(() => {
        if (previousBlockCount.current > 0 && blockCount === 0) {
          removeBlock(props.clientId)
        }
        previousBlockCount.current = blockCount
      }, [blockCount, props.clientId])
      return (
        <InnerBlocks
          allowedBlocks={ ['ws/social-link'] }
          template={ [
            ['ws/social-link']
          ] }
          templateLock={ false }
        />
      )
    },
    save: props => {
      return (
        <InnerBlocks.Content />
      )
    }
  },
  innerBlocks: [socialLink]
}
