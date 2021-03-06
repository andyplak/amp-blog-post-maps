var map;
var markers = [];

/**
 * Initialise the properties map if map div present on page
 * Center and zoom on Cornwall
 */
function initAmpMap() {

  if( document.getElementById('amp-map') ) {
    var mapOptions = {
      center: { lat: 50, lng: 0 },
      zoom: 5,
      scrollwheel: false
    }

    map = new google.maps.Map(document.getElementById('amp-map'), mapOptions);

    addPostMarkers();
  }

}

/**
 * Search for map data embeded in the page via data attributes, and plot onto the map
 */
function addPostMarkers() {

  var bounds = new google.maps.LatLngBounds();
  var infowindow = new google.maps.InfoWindow();
  var baseUrl = jQuery( "#amp-map").data('url');

  jQuery( ".map-marker" ).each(function( index ) {
    if( jQuery( this ).data( 'lat' ) && jQuery( this ).data( 'lng' ) ) {

      var pos = new google.maps.LatLng( jQuery( this ).data( 'lat' ), jQuery( this ).data( 'lng' ) );
      bounds.extend( pos );

      var marker = new google.maps.Marker({
        position: pos,
        map: map,
        title: jQuery( this ).data( 'title' ),
      });

      var url = baseUrl + jQuery( this ).data( 'post-id' );
      var content = '<strong>'+jQuery( this ).data( 'title' )+'</strong><br />';
      content += jQuery( this ).data( 'date' )+'<br />';
      content += '<a href="'+url+'" target="_new">view post</a>';
      makeInfoWindowEvent(map, infowindow, marker, content );

      markers.push(marker);
    }
  });

  map.fitBounds(bounds);
}

function removePostMarkers() {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(null);
  }
  markers = [];
}

function refreshPostMarkers() {
  removePostMarkers();
  addPostMarkers();
}

function makeInfoWindowEvent(map, infowindow, marker, contentString) {
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(contentString);
    infowindow.open(map, marker);
  });
}

jQuery(function() {
  initAmpMap();
});
