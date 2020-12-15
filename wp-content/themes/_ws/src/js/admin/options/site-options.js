/* globals React, ReactDOM, wp, locals */
const { Button, CheckboxControl, TextareaControl, TextControl } = wp.components
// const { useSelect } = wp.data
const { Component } = wp.element

// MediaUploadCheck doesn't work outside of posts, so we have to do our own permissions check
// function CanUpload(props) {
//   const canUpload = useSelect(select => {
//     return select('core').canUser('create', 'media')
//   })
//   return canUpload ? props.children : <p>Insuffiencent Permissions</p>
// }

class SiteOptions extends Component {

  constructor(props) {
    super(props)
    this.state = {
      mediaObject: null,
      logo: locals.logo,
      clientLogo: locals.clientLogo,
      disableComments: locals.disableComments,
      googleMapsKey: locals.googleMapsKey,
      googleMapsStyles: locals.googleMapsStyles,
      seoMetaTitle: locals.seoMetaTitle,
      sitePhone: locals.sitePhone,
      siteEmail: locals.siteEmail,
      siteLocationStreet: locals.siteLocationStreet,
      siteLocationCity: locals.siteLocationCity,
      siteLocationState: locals.siteLocationState,
      siteLocationZip: locals.siteLocationZip,
      socialLinks: locals.socialLinks
    }
  }

  render() {
    const {
      logo,
      disableComments,
      googleMapsKey,
      googleMapsStyles,
      seoMetaTitle,
      sitePhone,
      siteEmail,
      siteLocationStreet,
      siteLocationCity,
      siteLocationState,
      siteLocationZip,
      socialLinks
    } = this.state
    return (
      <>
        <h1>Site Options</h1>
        <section>
          <h2>General</h2>
          <div className="logo">
            <label>Logo</label>
            <small>Due to svg security exploits, svg&apos;s cannot be uploaded to media manager. To change the logo, contact super admin, or replace the <b>logo.svg</b> file in the root of this theme directory.</small>
            { logo ? (
              <img src={ logo } alt="Logo" />
            ) : (
              <p><i>No Logo</i></p>
            ) }
          </div>
          <CheckboxControl
            label="Disable Comments"
            onChange={ newValue => this.setState({ disableComments: newValue }) }
            checked={ disableComments }
            name="disable_comments"
          />
        </section>
        <section>
          <h2>Google Maps</h2>
          <TextControl
            label="API Key"
            onChange={ newValue => this.setState({ googleMapsKey: newValue }) }
            value={ googleMapsKey }
            name="google_maps_key"
          />
          <TextareaControl
            label="JSON Styles"
            onChange={ newValue => this.setState({ googleMapsStyles: newValue }) }
            value={ googleMapsStyles }
            name="google_maps_styles"
            help={ <a href="https://mapstyle.withgoogle.com/" target="_blank" rel="noopener noreferrer">Google Map Styler</a> }
          />
        </section>
        <section>
          <h2>SEO</h2>
          <TextControl
            label="Text to append to meta titles"
            onChange={ newValue => this.setState({ seoMetaTitle: newValue }) }
            value={ seoMetaTitle }
            name="seo_meta_title"
          />
        </section>
        <section>
          <h2>Contact</h2>
          <TextControl
            label="Telephone"
            onChange={ newValue => this.setState({ sitePhone: newValue }) }
            value={ sitePhone }
            name="site_phone"
          />
          <TextControl
            label="Email"
            type="email"
            onChange={ newValue => this.setState({ siteEmail: newValue }) }
            value={ siteEmail }
            name="site_email"
          />
          <TextControl
            label="Address"
            placeholder="Street Address"
            onChange={ newValue => this.setState({ siteLocationStreet: newValue }) }
            value={ siteLocationStreet }
            name="site_location_street"
          />
          <TextControl
            placeholder="City"
            onChange={ newValue => this.setState({ siteLocationCity: newValue }) }
            value={ siteLocationCity }
            name="site_location_city"
          />
          <TextControl
            placeholder="State"
            onChange={ newValue => this.setState({ siteLocationState: newValue }) }
            value={ siteLocationState }
            name="site_location_state"
            maxlength="2"
          />
          <TextControl
            placeholder="Zip Code"
            onChange={ newValue => this.setState({ siteLocationZip: newValue }) }
            value={ siteLocationZip }
            name="site_location_zip"
            maxlength="5"
          />
        </section>
        <section>
          <h2>Social</h2>
          <TextControl
            label="Facebook"
            onChange={ newValue => this.setState({ socialLinks: { ...socialLinks, facebook: newValue } }) }
            value={ socialLinks.facebook }
            name="social_links[facebook]"
          />
          <TextControl
            label="Twitter"
            onChange={ newValue => this.setState({ socialLinks: { ...socialLinks, twitter: newValue } }) }
            value={ socialLinks.twitter }
            name="social_links[twitter]"
          />
          <TextControl
            label="Instagram"
            onChange={ newValue => this.setState({ socialLinks: { ...socialLinks, instagram: newValue } }) }
            value={ socialLinks.instagram }
            name="social_links[instagram]"
          />
          <TextControl
            label="Youtube"
            onChange={ newValue => this.setState({ socialLinks: { ...socialLinks, youtube: newValue } }) }
            value={ socialLinks.youtube }
            name="social_links[youtube]"
          />
          <TextControl
            label="LinkedIn"
            onChange={ newValue => this.setState({ socialLinks: { ...socialLinks, linkedin: newValue } }) }
            value={ socialLinks.linkedin }
            name="social_links[linkedin]"
          />
        </section>
        <Button
          isPrimary
          type="submit"
        >
          Save Changes
        </Button>
      </>
    )
  }

}

function init() {
  const siteOptions = document.querySelector('.site-options')
  if (siteOptions) {
    ReactDOM.render(
      <SiteOptions />,
      siteOptions
    )
  }
}

export { init }
