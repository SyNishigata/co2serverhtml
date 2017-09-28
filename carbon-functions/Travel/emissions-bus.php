<?php
	/*
		Template Name: emissions-bus
		
		Majority of the following code was copied from \wp-content\plugins\moralab-co2\templates\forms\travel-form.php
	*/

	/* Establish the connection with SQL database */
	$hostname = "localhost";
	$database = "carbon_neutrality";
	$username = "carbon_challenge";
	$password = "changeme";
	$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
	mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());

	/* Variables */
	$id_user = 1; 
	if (isset($_POST['editing'])){
		$editing = $_POST["editing"];
	}
	if (isset($_POST['editing2'])){
		$editing2 = $_POST["editing2"];
	}
	
	/* Fetch all records of bus emissions for the user */
	$result = mysqli_query($conn, "SELECT * FROM user_emissions_travel_bus WHERE id_user='$id_user' ORDER BY date_data_entry ASC");
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$route_records[] = $row;
	}
	$result = mysqli_query($conn, "SELECT * FROM user_emissions WHERE id_user='$id_user' AND item_name='travel_bus' ORDER BY date_data_entry ASC");
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$route_records2[] = $row;
	}
	
	/* Calculate Yearly CO2 */
	include '../Calculations/functions.php';
	//fetch the most recent records of bus route emissions for the user
	$result = mysqli_query($conn, "SELECT * FROM user_emissions WHERE id_user='$id_user' AND item_name = 'travel_bus' ORDER BY date_data_entry DESC");			
	
	$yearly_co2 = 0.0;
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$yearly_co2 += calculate_yearly("travel_bus", $row)[0];
	}
	
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "";
		$current_date = date("Y-m-d");
		
		$item_name = "travel_bus";
		$emission_date = $_POST["emission_date"];
		$route_from = $_POST["bfrom"];
		$route_to = $_POST["bto"];
		$route_times = $_POST["btrips"];
		$period = $_POST["btrip_freq"];
		$school_year = $_POST["bschool"];
		$route_miles = $_POST["bdist"];
		$route_emissions = $_POST["bus"];  

		if($editing == 0){
			$sql = "INSERT INTO user_emissions_travel_bus
				(id, id_user, date_data_entry, date_modification, route_from, route_to, route_times, period, school_year, route_miles, emissions_this_month, emissions_this_year) 
				VALUES ('0', $id_user, '$emission_date', '$current_date', '$route_from', '$route_to', $route_times, '$period', $school_year, $route_miles, $route_emissions, '0')";
			$sql2 = "INSERT INTO user_emissions (id, id_user, date_data_entry, date_modification, item_name, monthly_co2_emissions) 
				VALUES ('0', $id_user, '$emission_date', '$current_date', 'travel_bus', $route_emissions)";
		}
		else{
			$sql = "UPDATE user_emissions_travel_bus
				SET date_data_entry='$emission_date', date_modification='$current_date', route_from='$route_from', route_to='$route_to', route_times='$route_times', period='$period', school_year='$school_year',
				route_miles='$route_miles', emissions_this_month='$route_emissions' WHERE id_user=$id_user AND id=$editing";
			$sql2 = "UPDATE user_emissions 
				SET date_data_entry='$emission_date', date_modification='$current_date', monthly_co2_emissions='$route_emissions' 
				WHERE id_user=$id_user AND id=$editing2";
		}		
		/* Check if query processed correctly */
		if (($conn->query($sql) === TRUE) && ($conn->query($sql2) === TRUE)) {
			/* Start storage into user_deficits table */
			$total_emissions = carbon_ranking()[0];
			$total_tree_sequestration = carbon_ranking()[1];
			$carbon_deficit = $total_emissions - $total_tree_sequestration;
			
			$result = mysqli_query($conn, "SELECT * FROM user_deficits WHERE id_user='$id_user'");
			$rows = $result->num_rows;
			if($rows == 0){
				$sql = "INSERT INTO user_deficits (id, id_user, date, total_emissions, total_tree_sequestration, carbon_deficit) 
					VALUES ('0', $id_user, '$current_date', $total_emissions, $total_tree_sequestration, $carbon_deficit)";
			}
			else{
				$sql = "UPDATE user_deficits
				SET date='$current_date',  total_emissions='$total_emissions', total_tree_sequestration='$total_tree_sequestration', 
				carbon_deficit='$carbon_deficit' WHERE id_user=$id_user";
			}
			if ($conn->query($sql) === TRUE){
				header("Location: /carbon-functions");
			}
			else{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC7vypkjCVKS6DD_mAaRrMm0aljfF-EhQE&v=3.exp&libraries=places"></script>

<form id="bus_form" action="http://128.171.9.198/carbon-functions/Travel/emissions-bus.php" method="post">
	<div class="form-group">
		<!-- Bus Form -->
		<span class="input-group-addon"><h3>Bus Routes</h3></span>
		<div class="row">
			<div id="route_form_holder">
				<input type="hidden" name="editing" id="editing" value="0">
				<input type="hidden" name="editing2" id="editing2" value="0">
				<input type="hidden" name="bus" id="bus" value="<?php echo !empty($carbon_data) ? $travel['bus'] : '' ?>">
				<br>
				
				Your yearly bus route emissions amount to  
				<div style="display:inline"> <?php echo $yearly_co2; ?></div>
				tons of carbon
				<br><br>
				
				This bus route produces
				<div style="display:inline" name="bus2" id="bus2" value="0">0.0</div>
				tons of carbon a month
				<br><br>
				
				<span> Enter the date for this emission </span>
				<input type="text" name="emission_date" id="emission_date" value="<?php echo date("Y-m-d")?>">
				<br><br>
			</div>
			
			<div class="medium-6 large-6 columns">
				<div class="input-group row">
					<div class="small-2 columns">
						<span class="input-group-addon">From</span>
					</div>
					<div class="small-10 columns">
						<input type="text" name="bfrom" id="bfrom" onchange="carbon_bus()" value="">
					</div>
				</div>
				<div class="input-group row">
					<div class="small-2 columns">
						<span class="input-group-addon">To</span>
					</div>
					<div class="small-10 columns">
						<input type="text" name="bto" id="bto" onchange="carbon_bus()" value="">
					</div>
				</div>
				<div class="input-group row">
					<div class="small-4 columns">I make this trip</div>
					<div class="small-3 columns"><input type="text" name="btrips" id="btrips" onchange="carbon_bus()" value="" length="4">
					</div>
					<div class="small-1 columns">per</div>
					<div class="small-4 columns"><select name="btrip_freq" id="btrip_freq" onchange="carbon_bus()" value="0">
							<option value="week">week</option>
							<option value="month">month></option>
							<option value="year">year></option>
						</select></div>
				</div>
				<div class="input-group row">
					<div class="small-4 columns">School Year Only</div>
					<div class="small-8 columns">
						<input type="checkbox" name="bschool" id="bschool" onchange="carbon_bus()" value="">
					</div>
				</div>
				<div class="input-group row">
					<input type="button" id="add_route" value="Add Route" onclick="add_routes()"></input>
					<a style="display:inline" href="/carbon-functions" class="button"><strong>Cancel</strong></a> 
					<input type="button" value="Edit" name="edit_route" id="edit_route"></input>
					
					<input type="hidden" name="total_bus_trip" id="total_bus_trip">
					<input type="hidden" name="bdist" id="bdist" value="">
					<input type="hidden" name="bcount" id="bcount"
						   value="<?php echo !empty($carbon_data) ? sizeof($travel['busfrom']) : '0' ?>">
				</div>
			</div>
			<div class="row" name="route_list" id="route_list"> </div>
			<div class="medium-6 large-6 columns">
				<div id="busmap" style="width: 350px;height:250px"></div>
			</div>
		</div>
		<!-- .Bus Form -->
	</div>
</form>


<script type="text/javascript">

	/* Function containing the calculations of carbon */
	function carbon_bus(){
		var route_miles = 0.0;
		var total_distance = isNaN(parseFloat(document.getElementById('total_bus_trip').value))? 0.0:parseFloat(document.getElementById('total_bus_trip').value);
		var from = document.getElementById('bfrom').value;
		var to = document.getElementById('bto').value;
		var trips = isNaN(parseInt(document.getElementById('btrips').value))? 0: parseInt(document.getElementById('btrips').value);
		var tripf = (document.getElementById('btrip_freq').value);
		var rt = (document.getElementById('bschool').checked);
		var dist = isNaN(parseFloat(document.getElementById('bdist').value))? 0.0:parseFloat(document.getElementById('bdist').value);
		var count = isNaN(parseInt(document.getElementById('bcount').value))? 0: parseInt(document.getElementById('bcount').value);
		switch(tripf){
			case 'week':
				if (!rt){ route_miles = dist * trips * 52; }
				else{ route_miles = dist * trips * 40; }
				break;
			case 'month':
				if (!rt){ route_miles = dist * trips * 12; }
				else{ route_miles = dist * trips * 8; }
				break;
			default:
				route_miles = dist * trips;     
		}
		
		total_distance = route_miles;
		carbon_bus_total = total_distance * 1.26 * 300 * 0.000001;
		carbon_bus_total = carbon_bus_total / 12;
		
		if(rt){
			jQuery('#bschool').val(1);
		}
		else{
			jQuery('#bschool').val(0);
		}
		jQuery('#total_bus_trip').val(total_distance.toFixed(4));
		jQuery('#bus').val(carbon_bus_total.toFixed(4));
		document.getElementById("bus2").innerHTML = carbon_bus_total.toFixed(4);
	}
	
	/* Function that creates the edit buttons for the user */
	jQuery("#edit_route").on("click", function(){
		var route_records = <?php if(!empty($route_records)){echo json_encode($route_records);}else{echo "''";} ?>;
		var route_records2 = <?php if(!empty($route_records2)){echo json_encode($route_records2);}else{echo "''";} ?>;
		var size = <?php if(!empty($route_records)){echo sizeof($route_records);}else{echo "0";} ?>;
		   
		var route_list = '<hr><span> Click on a route to edit from this list of your recorded route emissions: </span><br>';
		route_list += '<div class="row">';
		if(size != 0){
			for(i = 0; i < size; i++){
				route_list += '<input type="button" id="route_record' + i + '" value="' + "From: " + route_records[i].route_from + "  To: "  + route_records[i].route_to + '" ';
				route_list += 'onclick="edit_route_record(' + route_records[i].id + ',' + route_records2[i].id + ')"></input>';
			}
		}
		route_list += '</div>';

		$("#route_list").html(route_list);
	});

	/* Function that calls edit-route.php when an edit button is clicked */
	function edit_route_record(row_id, row_id2){
		var row_id = row_id;
		var row_id2 = row_id2;
		
		$.when(ajax1(row_id, row_id2)).done(function(a1){
			var travel_record = JSON.parse(document.getElementById('travel_record').textContent);
			
			document.getElementById("total_bus_trip").value = travel_record.route_miles;
			document.getElementById("bdist").value = travel_record.route_miles;
			document.getElementById("bfrom").value = travel_record.route_from;
			document.getElementById("bto").value = travel_record.route_to;
			document.getElementById("btrips").value = travel_record.route_times;
			document.getElementById("btrip_freq").value = travel_record.period;
			if(travel_record.school_year == "0"){
				document.getElementById("bschool").checked = false;
			}
			else{
				document.getElementById("bschool").checked = true;
			}
			document.getElementById("bschool").value = travel_record.school_year;
			document.getElementById("add_route").value = "Submit";
			
			carbon_bus();
		});
	}
	
	/* Function containing the ajax necessary for the function edit_route_record */
	function ajax1(row_id, row_id2) {
		var travel_type = "bus";
		var data = 'row_id=' + row_id + '&row_id2=' + row_id2 + '&travel_type=' + travel_type;
		return $.ajax({
			type: "POST",
			url: "edit-route.php",
			data: data,
			success: function(html){
				$("#route_form_holder").html(html);
			},
			error: function (e) {
				 alert("Server Error : " + e.state());
			}
		});
	}
	
	/* Function that submits the form */
	function add_routes(){
		document.getElementById("bus_form").submit();
	}

    /* General map variables */
	var busmap;    
    var cityMarkers = [];
    var flightMarkers = [];
    var flightPath;
	
	/* Function that initiliazes the map */
    jQuery(function ($) {
        mapInit('busmap', 'bfrom', 'bto', 'bdist');
    }); 

	/* Function that creates the map */
	function mapInit(map_canvas, from, to, dist) {
        var map = map_canvas;
        var origin_place_id = null;
        var destination_place_id = null;
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService;
        var geocoder;
        var autoOptions = {};
        var distanceInput = dist;

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

			var marker = new google.maps.Marker({
				map: busmap,
				position: place.geometry.location
			});
			cityMarkers.push(marker);
			expandViewportToFitPlace(busmap, place);

			
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


			var marker = new google.maps.Marker({
				map: busmap,
				position: place.geometry.location
			});

			cityMarkers.push(marker);
			expandViewportToFitPlace(busmap, place);


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
    }

	// Not used anymore
	/* Function that removes a route from the list */
	/*
	function remove_bus_route(miles, id){

		var total_distance = isNaN(parseFloat(document.getElementById('total_bus_trip').value))?0.0:parseFloat(document.getElementById('total_bus_trip').value);

		total_distance = total_distance - parseFloat(miles);
		carbon_bus_total = total_distance * 1.26 * 300 * 0.000001;
		jQuery('#total_bus_trip').val(total_distance.toFixed(2));
		jQuery('#bus').val(carbon_bus_total.toFixed(2));

		jQuery('#bus'+id).remove();
	}
	*/
</script>
