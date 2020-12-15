/* globals React, wp, locals, google */
const { Dashicon, TextControl } = wp.components
const { useEffect, useRef, useState } = wp.element
const { __ } = wp.i18n

export default function LocationPicker(props) {
  const {
    className,
    styles,
    onChange,
    location = {
      name: '',
      street: '',
      city: '',
      state: '',
      zip: ''
    }
  } = props
  const [map, setMap] = useState(null)
  const [geocoder, setGeocoder] = useState(null)
  const [marker, setMarker] = useState([])
  const [timer, setTimer] = useState(null)
  const [statusText, setStatusText] = useState('Loading...')
  const googleMap = useRef(null)

  useEffect(() => {
    const mapLoad = () => {
      const mapInstance = new google.maps.Map(googleMap.current, {
        zoom: 3,
        center: { lat: 37, lng: 95 },
        styles: styles || locals.googleMapsStyles ? JSON.parse(styles || locals.googleMapsStyles) : []
      })
      const geocoderInstance = new google.maps.Geocoder()
      const markerInstance = new google.maps.Marker()
      setMap(mapInstance)
      setGeocoder(geocoderInstance)
      setMarker(markerInstance)
      updateMap(mapInstance, geocoderInstance, markerInstance)
    }
    const scriptElement = document.querySelector('[src*="https://maps.googleapis.com/maps/api/js?key="]')
    if (scriptElement) {
      scriptElement.addEventListener('load', mapLoad)
    }
    else {
      const googleMapScript = document.createElement('script')
      googleMapScript.src = `https://maps.googleapis.com/maps/api/js?key=${locals.googleMapsKey}`
      window.document.body.appendChild(googleMapScript)
      googleMapScript.addEventListener('load', mapLoad)
    }
  }, [])

  useEffect(() => {
    if (map && geocoder && marker) {
      setStatusText(__('Loading...', '_ws'))
      if (timer) {
        clearTimeout(timer)
      }
      setTimer(setTimeout(e => {
        updateMap(map, geocoder, marker)
      }, 2000))
    }
  }, [location.name, location.street, location.city, location.state, location.zip, styles])

  function updateMap(map, geocoder, marker) {
    const locationString = `${location.name} ${location.street} ${location.city} ${location.state} ${location.zip}`
    geocoder.geocode({ 'address': locationString }, (results, status) => {
      switch (status) {
        case 'OK': {
          const position = results[0].geometry.location
          onChange({ ...location, coordinates: `${position.lat()},${position.lng()}` })
          marker.setPosition(position)
          marker.setMap(map)
          map.setCenter(position)
          map.setZoom(12)
          setStatusText('OK')
          break
        }
        case 'ZERO_RESULTS': {
          setStatusText(__('Location Not Found', '_ws'))
          onChange({ ...props.location, coordinates: null })
          break
        }
        case 'REQUEST_DENIED': {
          setStatusText(__('Missing Google Maps API Key', '_ws'))
          onChange({ ...props.location, coordinates: null })
          break
        }
        default: {
          setStatusText(__('Google Maps API Error', '_ws'))
          onChange({ ...props.location, coordinates: null })
        }
      }
    })
  }

  return (
    <div className={ `components-location-picker ${className}` }>
      <TextControl
        placeholder={ __('Name (Optional)', '_ws') }
        onChange={ newValue => onChange({ ...location, name: newValue }) }
        value={ location.name }
      />
      <TextControl
        placeholder={ __('Street', '_ws') }
        onChange={ newValue => onChange({ ...location, street: newValue }) }
        value={ location.street }
      />
      <TextControl
        placeholder={ __('City', '_ws') }
        onChange={ newValue => onChange({ ...location, city: newValue }) }
        value={ location.city }
      />
      <TextControl
        placeholder={ __('State', '_ws') }
        onChange={ newValue => onChange({ ...location, state: newValue }) }
        value={ location.state }
      />
      <TextControl
        placeholder={ __('Zip Code', '_ws') }
        onChange={ newValue => onChange({ ...location, zip: newValue }) }
        value={ location.zip }
      />
      <div style={ { display: statusText === 'OK' ? 'block' : 'none' } } className="google-map" ref={ googleMap }></div>
      { statusText !== 'OK' && (
        <div className="map-placeholder">
          <Dashicon icon="location-alt" />
          <small>{ statusText }</small>
        </div>
      )}
    </div>
  )
}
