/* global React, wp */
import SVGPicker from 'Components/svg-picker.js'
import { panel } from './panel/admin.js'
const { InnerBlocks, RichText } = wp.blockEditor
const { createBlock } = wp.blocks
const { Button } = wp.components
const { useDispatch, useSelect } = wp.data
const { useEffect, useState } = wp.element
const { __ } = wp.i18n

export const tabbedPanels = {
  name: 'ws/tabbed-panels',
  args: {
    title: __('Tabbed Panels', '_ws'),
    description: __('Tabbable panels of content.', '_ws'),
    icon: 'table-row-after',
    category: 'ws-layout',
    supports: {
      anchor: true
    },
    example: {
      attributes: {}
    },
    edit: props => {
      const { className = '' } = props.attributes
      const [activeTabId, setActiveTabId] = useState('')
      const {
        insertBlock,
        moveBlocksDown,
        moveBlocksUp,
        removeBlock,
        selectBlock,
        updateBlockAttributes
      } = useDispatch('core/block-editor')
      const {
        blockCount,
        getNextBlockClientId,
        getPreviousBlockClientId,
        panels
      } = useSelect(select => ({
        blockCount: select('core/block-editor').getBlockCount(props.clientId),
        getNextBlockClientId: select('core/block-editor').getNextBlockClientId,
        getPreviousBlockClientId: select('core/block-editor').getPreviousBlockClientId,
        panels: select('core/block-editor').getBlocks(props.clientId)
      }))
      useEffect(() => {
        if (panels.length > 0 && (activeTabId === '' || panels.length === 1)) {
          setActiveTabId(panels[0].clientId)
        }
      }, [panels])
      useEffect(() => {
        panels.forEach(panel => {
          updateBlockAttributes(panel.clientId, { isActive: false })
        })
        if (activeTabId) {
          updateBlockAttributes(activeTabId, { isActive: true })
        }
      }, [activeTabId])
      return (
        <div className="row tabs-panels">
          <div className="col tabs">
            { panels.map((panel, index) => {
              return (
                <div
                  className="tab-container"
                  key={ panel.clientId }
                >
                  <div className="movers">
                    <Button
                      label={ className.includes('is-style-vertical') ? __('Move Up', '_ws') : __('Move Left', '_ws') }
                      onClick={ () => moveBlocksUp([panel.clientId], props.clientId) }
                      icon={ className.includes('is-style-vertical') ? 'arrow-up-alt2' : 'arrow-left-alt2' }
                    />
                    <Button
                      label={ className.includes('is-style-vertical') ? __('Move Down', '_ws') : __('Move Right', '_ws') }
                      onClick={ () => moveBlocksDown([panel.clientId], props.clientId) }
                      icon={ className.includes('is-style-vertical') ? 'arrow-down-alt2' : 'arrow-right-alt2' }
                    />
                  </div>
                  <div className="button-container">
                    <button
                      className={ `tab ${activeTabId === panel.clientId ? 'is-active' : ''}` }
                      onClick={ () => setActiveTabId(panel.clientId) }
                    >
                      <SVGPicker
                        onChange={ newValue => updateBlockAttributes(panel.clientId, { icon: newValue }) }
                        value={ panel.attributes.icon }
                      />
                      <RichText
                        placeholder={ __('Tab Name', '_ws') }
                        keepPlaceholderOnFocus={ true }
                        onChange={ newValue => updateBlockAttributes(panel.clientId, { heading: newValue }) }
                        value={ panel.attributes.heading }
                      />
                    </button>
                    <Button
                      className="remove-button"
                      label={ __('Remove Tab', '_ws') }
                      onClick={ () => {
                        const nextBlockId = getNextBlockClientId(panel.clientId)
                        const previousBlockId = getPreviousBlockClientId(panel.clientId)
                        removeBlock(panel.clientId)
                          .then(data => {
                            selectBlock(props.clientId)
                            if (activeTabId === panel.clientId) {
                              setActiveTabId(nextBlockId || (previousBlockId || ''))
                            }
                          })
                      } }
                      icon="no-alt"
                    />
                  </div>
                </div>
              )
            }) }
            <div className="add-tab">
              <Button
                isSecondary
                onClick={ () => {
                  // insertBlock(createBlock('ws/panel', {}, [createBlock('core/paragraph')]),
                  insertBlock(createBlock('ws/panel'), blockCount, props.clientId)
                    .then(data => {
                      selectBlock(props.clientId)
                      setActiveTabId(data.blocks[0].clientId)
                      document.querySelector(`#block-${props.clientId} .tab-container:nth-child(${data.index + 1}) .rich-text`).focus()
                    })
                } }
              >
                Add Tab
              </Button>
            </div>
          </div>
          <div className="col panels">
            <div className="panels-outline">
              <InnerBlocks
                allowedBlocks={ ['ws/panel'] }
                template={ [
                  ['ws/panel']
                ] }
                renderAppender={ false }
              />
            </div>
          </div>
        </div>
      )
    },
    save: props => {
      return (
        <InnerBlocks.Content />
      )
    }
  },
  innerBlocks: [panel],
  styles: [
    {
      name: 'horizontal',
      label: __('Horizontal', '_ws'),
      isDefault: true
    },
    {
      name: 'vertical',
      label: __('Vertical', '_ws')
    }
  ]
}
