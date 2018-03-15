function venueMap() {
	var mapProp = {
    center:new google.maps.LatLng(51.509865,-0.118092),
    zoom:8,
	};
	var map = new google.maps.Map(document.getElementById("venue_map"),mapProp);
	var infowindow = new google.maps.InfoWindow();
  var latlngbounds = new google.maps.LatLngBounds();
	var marker;
	$.map(markers, function(value, index) {
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(value.latitude, value.longitude),
      map: map
    });
    google.maps.event.addListener(marker, 'click', (function(marker, index) {
      return function() {
        infowindow.setContent(value.information);
        infowindow.open(map, marker);
      }
    })(marker, index));
    latlngbounds.extend(marker.position);
	});
  var bounds = new google.maps.LatLngBounds();
  map.setCenter(latlngbounds.getCenter());
  map.fitBounds(latlngbounds);
}