<?php
/*
	Template Name: travel-maps

	Almost all of the following code was copied from \wp-content\plugins\moralab-co2\templates\forms\travel-form.php
	The two script src are from the function add_header_scripts() in \wp-content\plugins\moralab-co2\includes\functions.php
	Also the 'style="width: 350px;height:250px' added on lines 61, 141, and 212 are from the file below
	\wp-content\plugins\moralab-co2\includes\css\moralab.css 
*/

?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC7vypkjCVKS6DD_mAaRrMm0aljfF-EhQE&v=3.exp&libraries=places"></script>

<div class="form-group">
	<!-- Bus Form -->
	<span class="input-group-addon"><h3>Bus Routes</h3></span>
	<div class="row">
		<div class="medium-6 large-6 columns">
			<div class="input-group row">
				<div class="small-2 columns">
					<span class="input-group-addon">From</span>
				</div>
				<div class="small-10 columns">
					<input type="text" name="bfrom" id="bfrom" value="">
				</div>
			</div>
			<div class="input-group row">
				<div class="small-2 columns">
					<span class="input-group-addon">To</span>
				</div>
				<div class="small-10 columns">
					<input type="text" name="bto" id="bto" value="">
				</div>
			</div>
			<div class="input-group row">
				<div class="small-4 columns">I make this trip</div>
				<div class="small-3 columns"><input type="text" name="btrips" id="btrips" value="" length="4"></div>
				<div class="small-1 columns">per</div>
				<div class="small-4 columns">
					<select name="btrip_freq" id="btrip_freq" value="">
						<option value="week">week</option>
						<option value="month">month></option>
						<option value="year">year></option>
					</select>
				</div>
			</div>
			<div class="input-group row">
				<div class="small-4 columns">School Year Only</div>
				<div class="small-8 columns"><input type="checkbox" name="bschool" id="bschool" length="4" value="1"></div>
			</div>
			<div class="input-group row">
				<div class="button" id="add_bus_route" onclick="carbon_bus()">Add Route</div>
				<input type="hidden" name="bdist" id="bdist" value="">
				<input type="hidden" name="bcount" id="bcount"
				value="<?php echo !empty($carbon_data) ? sizeof($travel['busfrom']) : '0' ?>">
			</div>
		</div>
		<div class="medium-6 large-6 columns">
			<div id="busmap" style="width: 350px;height:250px"></div>
		</div>
	</div>
	
	<div class="row">
		<input type="hidden" name="total_bus_trip" id="total_bus_trip"
			   vale="<?php echo !empty($carbon_data) ? $travel['total_bus_trip'] : '0' ?>">
		<div class="routes" id="bus_routes">
			<?php if (!empty($travel['busfrom'])) {
				$max = sizeof($travel['busfrom']);
				for ($i = 0; $i < $max; $i++) { ?>
					<div id="bus<?php echo $i; ?>">
						From <?php echo $travel['busfrom'][$i]; ?> to <?php echo $travel['busto'][$i]; ?>,
						<?php echo $travel['bustrips'][$i]; ?> times per <?php echo $travel['busfreq'][$i]; ?>
						<?php echo ($travel['bussy'][$i] == '1') ? ' on a school year' : ''; ?>,
						<?php echo $travel['busmiles'][$i]; ?> miles.
						<span onclick="remove_bus_route(<?php echo $travel['busmiles'][$i] . ',' . $i; ?>)">[Remove]</span>
						<input type="hidden" name="busfrom[]" value="<?php echo $travel['busfrom'][$i]; ?>">
						<input type="hidden" name="busto[]" value="<?php echo $travel['busto'][$i]; ?>">
						<input type="hidden" name="bustrips[]" value="<?php echo $travel['bustrips'][$i]; ?>">
						<input type="hidden" name="busfreq[]" value="<?php echo $travel['busfreq'][$i]; ?>">
						<input type="hidden" name="bussy[]" value="<?php echo $travel['bussy'][$i]; ?>">
						<input type="hidden" name="busmiles[]" value="<?php echo $travel['busmiles'][$i]; ?>">
					</div>
				<?php }
			} ?>
		</div>
	</div>
	<!-- .Bus Form -->


	<br><br>


	<!-- Train Form -->
	<span class="input-group-addon"><h3>Train Routes</h3></span>
	<div class="row">
		<div class="medium-6 large-6 columns">
			<div class="input-group row">
				<div class="small-2 columns">
					<span class="input-group-addon">From</span>
				</div>
				<div class="small-10 columns">
					<input type="text" name="rfrom" id="rfrom" value="">
				</div>
			</div>
			<div class="input-group row">
				<div class="small-2 columns">
					<span class="input-group-addon">To</span>
				</div>
				<div class="small-10 columns">
					<input type="text" name="rto" id="rto" value="">
				</div>
			</div>
			<div class="input-group row">
				<div class="small-4 columns">I make this trip</div>
				<div class="small-3 columns"><input type="text" min="0" name="rtrips" id="rtrips" value=""length="4"></div>
				<div class="small-1 columns">per</div>
				<div class="small-4 columns">
					<select name="rfreq" id="rfreq">
						<option value="week">week</option>
						<option value="month">month></option>
						<option value="year">year></option>
					</select>
				</div>
			</div>
			<div class="input-group row">
				<div class="small-4 columns">School Year Only</div>
				<div class="small-8 columns"><input type="checkbox" name="train_sy" id="train_sy" length="4"></div>
			</div>
			<div class="input-group row">
				<div class="button" id="add_train_route" onclick="carbon_train()">Add Route</div>
				<input type="hidden" name="total_train_trip" id="total_train_trip"
					   value="<?php echo !empty($carbon_data) ? $travel['total_train_trip'] : '0' ?>">
				<input type="hidden" name="rcount" id="rcount"
					   value="<?php echo !empty($carbon_data) ? sizeof($travel['trainfrom']) : '0' ?>">
				<input type="hidden" name="rdist" id="rdist" value="">
			</div>
		</div>
		<div class="medium-6 large-6 columns">
			<div id="trainmap" style="width: 350px;height:250px"></div>
		</div>
	</div>

	<div class="row">
		<div class="routes" id="train_routes">
			<?php if (!empty($travel['trainfrom'])) {
				$max = sizeof($travel['trainfrom']);
				for ($i = 0; $i < $max; $i++) { ?>
					<div id="train<?php echo $i; ?>">
						From <?php echo $travel['trainfrom'][$i]; ?> to <?php echo $travel['trainto'][$i]; ?>,
						<?php echo $travel['traintrips'][$i]; ?> times
						per <?php echo $travel['trainfreq'][$i]; ?>
						<?php echo ($travel['trainsy'][$i] == '1') ? ' on a school year' : ''; ?>,
						<?php echo $travel['trainmiles'][$i]; ?> miles.
						<span onclick="remove_train_route(<?php echo $travel['trainmiles'][$i] . ',' . $i; ?>)">[Remove]</span>
						<input type="hidden" name="trainfrom[]" value="<?php echo $travel['trainfrom'][$i]; ?>">
						<input type="hidden" name="trianto[]" value="<?php echo $travel['trainto'][$i]; ?>">
						<input type="hidden" name="traintrips[]" value="<?php echo $travel['traintrips'][$i]; ?>">
						<input type="hidden" name="trainfreq[]" value="<?php echo $travel['trainfreq'][$i]; ?>">
						<input type="hidden" name="triansy[]" value="<?php echo $travel['trainsy'][$i]; ?>">
						<input type="hidden" name="trainmiles[]" value="<?php echo $travel['trainmiles'][$i]; ?>">
					</div>
				<?php }
			} ?>
		</div>
	</div>
	<!-- .Train Form -->
	
	
	<br><br>
	
	
	<!-- Plane Form -->
	<span class="input-group-addon"><h3>Flight Routes</h3></span>
	<div class="row">
		<div class="medium-6 large-6 columns">
			<div class="input-group row">
				<div class="small-2 columns">
					<span class="input-group-addon">From</span>
				</div>
				<div class="small-10 columns">
					<input type="text" name="pfrom" id="pfrom" value="">
				</div>
			</div>
			<div class="input-group row">
				<div class="small-2 columns">
					<span class="input-group-addon">To</span>
				</div>
				<div class="small-10 columns">
					<input type="text" name="pto" id="pto" value="">
				</div>
			</div>
			<div class="input-group row">
				<div class="small-9 columns">How often do you make this trip per year?</div>
				<div class="small-3 columns"><input type="text" name="ptrips" id="ptrips" value="" length="4"></div>
			</div>
			<div class="input-group row">
				<div class="small-3 columns">Roundtrip</div>
				<div class="small-9 columns"><input type="checkbox" name="plane_rt" id="plane_rt" length="4"></div>
			</div>
			<div class="input-group row">
				<div class="button" id="add_plane_route" onclick="carbon_plane()">Add Route</div>
				<input type="hidden" name="pdist" id="pdist">
				<input type="hidden" name="pcount" id="pcount"
					   value="<?php echo !empty($carbon_data) ? sizeof($travel['planefrom']) : '0' ?>">
				<input type="hidden" name="total_plane_trip" id="total_plane_trip"
					   value="<?php echo !empty($carbon_data) ? $travel['total_plane_trip'] : '0' ?>">
			</div>
		</div>
		<div class="medium-6 large-6 columns">
			<div id="planemap" style="width: 350px;height:250px"></div>
		</div>
	</div>
	
	<div class="row">
		<div class="routes" id="plane_routes">
			<?php if (!empty($travel['planefrom'])) {
				$max = sizeof($travel['planefrom']);
				for ($i = 0; $i < $max; $i++) { ?>
					<div id="plane<?php echo $i; ?>">
						From <?php echo $travel['planefrom'][$i]; ?> to <?php echo $travel['planeto'][$i]; ?>,
						<?php echo $travel['planetrips'][$i]; ?> times per year
						<?php echo ($travel['planert'][$i] == '1') ? ' round trip' : ''; ?>,
						<?php echo $travel['planemiles'][$i]; ?> miles.
						<span onclick="remove_plane_route(<?php echo $travel['planemiles'][$i] . ',' . $i; ?>)">[Remove]</span>
						<input type="hidden" name="planefrom[]" value="<?php echo $travel['planefrom'][$i]; ?>">
						<input type="hidden" name="planeto[]" value="<?php echo $travel['planeto'][$i]; ?>">
						<input type="hidden" name="planetrips[]"
							   value="<?php echo $travel['planetrips'][$i]; ?>">
						<input type="hidden" name="planert[]" value="<?php echo $travel['planert'][$i]; ?>">
						<input type="hidden" name="planemiles[]"
							   value="<?php echo $travel['planemiles'][$i]; ?>">
					</div>
				<?php }
			} ?>
		</div>
	</div>
	<!-- .Plane Form -->
	
</div>


<script type="text/javascript">
    var busmap, trainmap, planemap;
    var cityMarkers = [];
    var flightMarkers = [];
    var flightPath;
	
    function mapInit(map_canvas, from, to, dist) {
        var map = map_canvas;
        var origin_place_id = null;
        var destination_place_id = null;
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService;
        var geocoder;
        var autoOptions = {};
        var distanceInput = dist;
        if (map_canvas == 'planemap') {
            autoOptions = {
                types: ['(cities)']
            };
        }


        for (var i = 0; i < cityMarkers.length; i++) {
            cityMarkers[i].setMap(null);
        }
        cityMarkers = [];

        for (var i = 0; i < flightMarkers.length; i++) {
            flightMarkers[i].setMap(null);
        }
        flightMarkers = [];

        var inputFrom = document.getElementById(from);
        var from_autocomplete = new google.maps.places.Autocomplete(inputFrom, autoOptions);
        var inputTo = document.getElementById(to);
        var dest_autocomplete = new google.maps.places.Autocomplete(inputTo, autoOptions);
        geocoder = new google.maps.Geocoder();
        directionsDisplay = new google.maps.DirectionsRenderer;

        var center_map = new google.maps.LatLng(41.850033, -87.6500523);
        if (map_canvas == 'busmap') {
            busmap = new google.maps.Map(document.getElementById(map_canvas),
                {
                    zoom: 2,
                    zoomControl: true,
                    streetViewControl: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: center_map
                });
            directionsDisplay.setMap(busmap);
            from_autocomplete.bindTo('bounds', busmap);
            dest_autocomplete.bindTo('bounds', busmap);
        }
        else if (map_canvas == 'trainmap') {
            trainmap = new google.maps.Map(document.getElementById(map_canvas),
                {
                    zoom: 2,
                    zoomControl: true,
                    streetViewControl: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: center_map
                });
            directionsDisplay.setMap(trainmap);
            from_autocomplete.bindTo('bounds', trainmap);
            dest_autocomplete.bindTo('bounds', trainmap);
        }
        else {
            planemap = new google.maps.Map(document.getElementById(map_canvas),
                {
                    zoom: 2,
                    zoomControl: true,
                    streetViewControl: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: center_map
                });
            directionsDisplay.setMap(planemap);
            from_autocomplete.bindTo('bounds', planemap);
            dest_autocomplete.bindTo('bounds', planemap);
            //route(planemap, inputFrom.value, inputTo.value, directionsService, directionsDisplay, distanceInput);
        }

        function expandViewportToFitPlace(map, place) {
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            }
            else {

                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
        }

        from_autocomplete.addListener('place_changed', function () {
            var place = from_autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }
            var markers;

            switch (map_canvas) {
                case 'busmap':

                    var marker = new google.maps.Marker({
                        map: busmap,
                        position: place.geometry.location
                    });
                    cityMarkers.push(marker);
                    expandViewportToFitPlace(busmap, place);
                    break;
                case 'trainmap':
                    var marker = new google.maps.Marker({
                        map: trainmap,
                        position: place.geometry.location
                    });
                    cityMarkers.push(marker);
                    expandViewportToFitPlace(trainmap, place);
                    break;
                default:
                    var marker = new google.maps.Marker({
                        map: planemap,
                        position: place.geometry.location
                    });
                    flightMarkers.push(marker);
                    expandViewportToFitPlace(planemap, place);
                    break;
            }

            // If the place has a geometry, store its place ID and route if we have
            // the other place ID
            origin_place_id = place.place_id;

            if (!isNaN(inputTo.value)) {
                var request = {
                    location: center_map,
                    radius: '25',
                    query: inputTo.value
                };

                var service = new google.maps.places.PlacesService(map);
                service.textSearch(request, function (results, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        destination_place_id = results[0].place_id;
                    }
                });
            }

            route(map, origin_place_id, destination_place_id,
                directionsService, directionsDisplay, distanceInput);
        });

        dest_autocomplete.addListener('place_changed', function () {
            var place = dest_autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }
            var markers;
            switch (map_canvas) {
                case 'busmap':
                    var marker = new google.maps.Marker({
                        map: busmap,
                        position: place.geometry.location
                    });

                    cityMarkers.push(marker);
                    expandViewportToFitPlace(busmap, place);
                    break;
                case 'trainmap':
                    var marker = new google.maps.Marker({
                        map: trainmap,
                        position: place.geometry.location
                    });
                    cityMarkers.push(marker);
                    markers = cityMarkers;
                    expandViewportToFitPlace(trainmap, place);
                    break;
                default:
                    var marker = new google.maps.Marker({
                        map: planemap,
                        position: place.geometry.location
                    });
                    flightMarkers.push(marker);
                    expandViewportToFitPlace(planemap, place);
                    break;
            }

            // If the place has a geometry, store its place ID and route if we have
            // the other place ID
            destination_place_id = place.place_id;

            if (!isNaN(inputFrom.value)) {
                var request = {
                    location: center_map,
                    radius: '25',
                    query: inputTo.value
                };

                var service = new google.maps.places.PlacesService(map);
                service.textSearch(request, function (results, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        origin_place_id = results[0].place_id;
                    }
                });
            }

            route(map, origin_place_id, destination_place_id,
                directionsService, directionsDisplay, distanceInput);
        });

        function route(map, origin_place_id, destination_place_id, directionsService, directionsDisplay, distance) {
            if (!origin_place_id || !destination_place_id) {
                return;
            }
            var distanceInput = document.getElementById(distance);
            var travelMode = google.maps.DirectionsTravelMode.TRANSIT;

            if (map == 'busmap' || map == 'trainmap') {
                for (var i = 0; i < cityMarkers.length; i++) {
                    cityMarkers[i].setMap(null);
                }
                cityMarkers = [];

                var request = {
                    origin: {'placeId': origin_place_id},
                    destination: {'placeId': destination_place_id},
                    travelMode: travelMode
                };

                directionsService.route(request, function (response, status) {

                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                        distanceInput.value = response.routes[0].legs[0].distance.value / 1000;
                    } else {
                        request.travelMode = google.maps.TravelMode.DRIVING;
                        directionsService.route(request, function (response, status) {
                            directionsDisplay.setDirections(response);
                            distanceInput.value = response.routes[0].legs[0].distance.value / 1000;
                        });
                    }
                });
            }

            else {
                var pointA = [];
                var pointB = [];
                var bounds = new google.maps.LatLngBounds();

                if (flightMarkers.length > 1) {
                    for (var i = 0; i < flightMarkers.length; i++) {
                        flightMarkers[i].setMap(null);
                    }
                    flightMarkers = [];
                }

                geocoder.geocode({placeId: origin_place_id}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        pointA[0] = parseFloat(results[0].geometry.location.lat());
                        pointA[1] = parseFloat(results[0].geometry.location.lng());

                        planemap.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                            map: planemap,
                            position: results[0].geometry.location
                        });
                        flightMarkers.push(marker);

                        geocoder.geocode({placeId: destination_place_id}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                pointB[0] = parseFloat(results[0].geometry.location.lat());
                                pointB[1] = parseFloat(results[0].geometry.location.lng());
                                planemap.setCenter(results[0].geometry.location);
                                var marker = new google.maps.Marker({
                                    map: planemap,
                                    position: results[0].geometry.location
                                });
                                flightMarkers.push(marker);

                                var a = new google.maps.LatLng(pointA[0], pointA[1]);
                                var b = new google.maps.LatLng(pointB[0], pointB[1]);

                                // planemap.setCenter(google.maps.geometry.spherical.interpolate(a, b, 0.5));
                                // map.setZoom(2);
                                var dist_between_points = google.maps.geometry.spherical.computeDistanceBetween(a, b);
                                var dist_in_miles = 0.000621371 * dist_between_points;
                                distanceInput.value = dist_in_miles;
                                console.log(dist_in_miles);

                                if (flightPath != null) {
                                    flightPath.setMap(null);
                                }

                                flightPath = new google.maps.Polyline({
                                    path: [a, b],
                                    geodesic: false,
                                    strokeColor: '#FF0000',
                                    strokeOpacity: 1.0,
                                    strokeWeight: 2
                                });

                                bounds.extend(a);
                                bounds.extend(b);
                                planemap.fitBounds(bounds);
                                flightPath.setMap(planemap);
                            }
                        });
                    } else {
                        console.log("Error Point A: " + status);
                    }
                });
            }
        }
    }

    jQuery(function ($) {
        mapInit('busmap', 'bfrom', 'bto', 'bdist');
        mapInit('trainmap', 'rfrom', 'rto', 'rdist');
        mapInit('planemap', 'pfrom', 'pto', 'pdist');
    }); 
</script>