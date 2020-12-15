/* globals React, wp, locals */
const { BaseControl, Button, Dashicon, Dropdown, SVG } = wp.components
const { Component } = wp.element
const { __ } = wp.i18n

export default class SVGPicker extends Component {

  getSVG(id) {
    for (let i = 0; i < locals.svgs.length; i++) {
      if (locals.svgs[i].id === id) {
        return (
          <SVG
            viewBox={ locals.svgs[i].viewbox }
            width={ locals.svgs[i].viewbox.split(' ')[2] }
            height={ locals.svgs[i].viewbox.split(' ')[3] }
            dangerouslySetInnerHTML={ { __html: locals.svgs[i].path } }
          ></SVG>
        )
      }
    }
  }

  render() {
    const { className, label, help, style, value } = this.props
    return (
      <BaseControl
        label={ label }
        help={ help }
        className={ `components-svg-picker ${className || ''}` }
      >
        <Dropdown
          className="svg-dropdown"
          renderToggle={ ({ isOpen, onToggle }) => (
            <Button
              isSecondary
              style={ style }
              className={ `svg-button ${style && style.color ? 'has-color' : ''} ${style && style.backgroundColor ? 'has-background' : ''} ${this.getSVG(value) ? 'has-icon' : ''}` }
              onClick={ () => onToggle() }
              aria-expanded={ isOpen }
            >
              { this.getSVG(value) || __('Select Icon', '_ws') }
            </Button>
          ) }
          renderContent={ ({ onClose }) => {
            return (
              <div className="svg-select">
                <Button
                  className={ !value ? 'selected' : '' }
                  onClick={ () => {
                    if (this.props.onChange) {
                      this.props.onChange(null)
                    }
                    onClose()
                  } }
                >
                  <SVG
                    viewBox="0 0 24 24"
                    fill-rule="evenodd"
                  >
                    <text x="6" y="10" style={ { font: '10px sans-serif' } }>No</text>
                    <text x="3" y="21" style={ { font: '10px sans-serif' } }>Icon</text>
                  </SVG>
                </Button>
                { locals.svgs.map(v => {
                  return (
                    <Button
                      key={ v.id }
                      className={ value === v.id ? 'selected' : '' }
                      title={ v.id }
                      onClick={ () => {
                        if (this.props.onChange) {
                          this.props.onChange(v.id)
                        }
                        onClose()
                      } }
                    >
                      <SVG
                        viewBox={ v.viewbox }
                        fill-rule="evenodd"
                        dangerouslySetInnerHTML={ { __html: v.path } }
                      >
                      </SVG>
                    </Button>
                  )
                }) }
                <div className="svg-manager-link">
                  <a href="/wp-admin/options-general.php?page=svg">SVG Manager<Dashicon icon="external" /></a>
                </div>
              </div>
            )
          } }
        />
      </BaseControl>
    )
  }

}
