var config = window.config;
var center = [config.centerlat, config.centerlng];
var zoom = config.zoom;
var all_markers = (typeof config.marker === 'string') ? JSON.parse(config.marker) : [];
var all_lines = (typeof config.polyline === 'string') ? JSON.parse(config.polyline) : [];

// Create the map
var map = L.map('map').setView(center, zoom);

L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
}).addTo(map);

// define custom marker
var EventMarker = L.Icon.extend({
  options: {
    shadowUrl: null,
    iconAnchor: new L.Point(12, 12),
    iconSize: new L.Point(24, 24),
    iconUrl: 'https://storage.googleapis.com/planet4-netherlands-stateless/2018/05/913c0158-cropped-5b45d6f2-p4_favicon-150x150.png'
  }
});
var ShipMarker = L.Icon.extend({
  options: {
    shadowUrl: null,
    iconAnchor: new L.Point(12, 12),
    iconSize: new L.Point(24, 24),
    iconUrl: 'https://storage.googleapis.com/planet4-netherlands-stateless/2019/03/3decbd94-sailboat-zwart.png'
  }
});

$.each(all_lines, function (index, value) {
  let polyline = L.polyline(value,
    {
      color: '#66CC00',
      weight: 5,
      opacity: 1,
    }).addTo(map);
  delete all_lines[index];
  all_lines[L.stamp(polyline)] = value;
});
$.each(all_markers, function(index, value) {
  if (index === 0) {
    var marker = L.marker(value, {icon: new EventMarker()}).addTo(map);
    delete all_markers[index];
    all_markers[L.stamp(marker)] = value;
    marker.bindPopup('<a href="https://www.greenpeace.org/nl/acties/plasticmonster/rave/">Doe mee met de Plastic Monster Rave!</a>', {'className': 'popupCustom'});
  }
  if (index === 1) {
    var marker = L.marker(value, {icon: new EventMarker()}).addTo(map);
    delete all_markers[index];
    all_markers[L.stamp(marker)] = value;
    marker.bindPopup('<a href="https://www.greenpeace.org/nl/acties/plasticmonster/plastival/">Kom ook naar het Plastival!</a>', {'className': 'popupCustom'}).openPopup();
  }
  if (index === 2) {
    var marker = L.marker(value, {icon: new ShipMarker()}).addTo(map);
    delete all_markers[index];
    all_markers[L.stamp(marker)] = value;
    marker.bindPopup('Hier is de Beluga nu!', {'className': 'popupCustom'});
  }
});
