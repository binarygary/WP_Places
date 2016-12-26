<?php
/**
 */

function places_get_coordinates( $posts = '' ) {

	//print_r($posts);


	if ( count( $posts ) < 2 ) {
		return;
	}

	foreach ( $posts as $post ) {

		//$lat=get_post_meta($post, _WP_Places_lat, TRUE);
		//$lng=get_post_meta($post, _WP_Places_lng, TRUE);
		//$name=get_post_meta($post, _WP_Places_name, TRUE);

		//$apikey=get_option('WP_Places_Google_Id_Setting');

		if ( $old_meta = get_post_meta( $post->ID, '_WP_Places_meta_Google_response', true ) ) {
			update_post_meta( $post->ID, '_wp_places', $old_meta );
			delete_post_meta( $post->ID, '_WP_Places_meta_Google_response' );
		}

		$locationPlace = get_post_meta( $post, '_wp_places', true );

		$place = WP_Places::get_instance();

		$placeArray = $place->google_places_api->placeDetails( $locationPlace );

		$lat  = $placeArray['lat'];
		$lng  = $placeArray['lng'];
		$name = $placeArray['name'];
		//$address="1316 W Adams St. jacksonville FL 32204";
		setup_postdata( $post );
		$description = $placeArray['formattedAddress'];
		$title       = get_the_title( $post );
		$link        = get_the_permalink( $post );
		$size        = array( 150, 150 );
		$image       = get_the_post_thumbnail( $post, $size );
		$description = "<B><a href=$link>$title</a></B><BR>$description<BR><a href=$link>$image</a><BR><a href=$link><B style=color:red>Read More</B></a> or <a href=https://www.google.com/maps/dir/Current+Location/$lat,$lng><i>get directions</i></a>";

		if ( $lat != '' AND ! $placeArray['permanentlyClosed'] ) {
			$arr[ $title ]['lat']         = $lat;
			$arr[ $title ]['lng']         = $lng;
			$arr[ $title ]['name']        = $name;
			$arr[ $title ]['description'] = $description;
			$arr[ $title ]['address']     = $address;
		}
	}

	if ( count( $arr ) == 0 ) {
		return;
	}

	//print_r($arr);

	echo "<a href=#reviews>View Articles</a>";
	echo "<script src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyB0s2HTycYFSdIce8nkkXuWy5Is9IA3Q54&callback=initMap\" async defer></script>";
	echo "<script>
      function initMap() {
        var mapDiv = document.getElementById('map');
        var map = new google.maps.Map(mapDiv, { zoom: 8 });
		
      	
				var bounds = new google.maps.LatLngBounds();
				var infowindow = new google.maps.InfoWindow();
				
				
				var markers = ";
	echo json_encode( $arr );
	echo "
				
				for(var i in markers)
						{
							var m = markers[i];

							var marker = new google.maps.Marker({
								position: {lat: Number(m.lat) ,lng : Number(m.lng) },
								map: map,
								title: m.name,
								icon: m.pinImage,
							});

							 //extend the bounds to include each marker's position
							bounds.extend(marker.position);

						bindInfoWindow(marker, map, infowindow, m.description);
						
						function bindInfoWindow(marker, map, infowindow, description) {
								marker.addListener('click', function() {
										infowindow.setContent(description);
										infowindow.open(map, this);
								});
						}

						}

						//now fit the map to the newly inclusive bounds
					map.fitBounds(bounds);
			
			}
    </script>";

	echo "<style>
	  #map {
	    width: 100%;
	    height: 400px;
	    background-color: #CCC;
	  }
	</style>";

	echo "<div id=\"map\"></div>";
	echo "<a name='reviews' />";

}

add_shortcode( 'wpplacesmap', 'places_get_coordinates' );