<?php
	/*
		Template Name: emissions-car

		Almost all of the following code was copied from \wp-content\plugins\moralab-co2\templates\forms\travel-form.php
		
		Except for:
		The script src at the top is from the function add_header_scripts() in the file below:
			\wp-content\plugins\moralab-co2\includes\functions.php
		The functions carbon_car() and jQuery("#show_calc_car").on("click", function() are from the file below:
			\wp-content\plugins\moralab-co2\includes\js\carbon.js
		The functions get_car_model() and get_car_year() are from the file below:
			\wp-content\plugins\moralab-co2\includes\functions.php
	*/

	/* 
		Notes: 
			A lot of this page requires $carbon_data which is the carbon emissions from a specific user,
			which uses wp-db to find that info.  
			See \wp-content\plugins\moralab-co2\includes\functions.php lines 460-477 for make, model, year
			See \wp-content\plugins\moralab-co2\templates\forms\travel-form.php lines 775-856 for functions of ^
	*/

	/*
		The following lines won't work because they use Wordpress specific functions:

		global $carbon_data;

		$travel = get_post_meta($carbon_data['ID'], 'travel_data', true);  
		$makes = get_car_make();  											

		if (!empty($travel['car_make']) && !empty($travel['car_model']) && !empty($travel['car_year'])):
			$models = get_car_model($travel['car_make']);
			$years = get_car_year($travel['car_make'], $travel['car_model']);
		endif;
	*/
	
	/* Establish the connection with SQL database */
	$hostname = "localhost";
	$database = "carbon_neutrality";
	$username = "carbon_challenge";
	$password = "changeme";
	$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
	mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());

	/* Declare formSubmit if document is just loading */
	if(empty($_POST['formSubmit'])){
		$formSubmit = '';
	} else {
		$formSubmit = $_POST['formSubmit'];
	}
	
	if($formSubmit == "Submit")
	{
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "";
		$id_user = 1; 
		$date = date("Y-m-d");

		//$id_car_model = $_POST["bfrom"];  //need to fix select menus first
		$id_car_model = 0;
		$last_milage= $_POST["car_last"];
		$date_last_milage = $_POST["car_last_date"];
		$current_milage = $_POST["car_current"];
		$date_current_milage = $_POST["car_current_date"];
		$car_fuel_type = $_POST["car_fuel"];
		$car_milage = $_POST["car_miles"];
		$car_emissions = $_POST["car"];  //fix so it doesn't use total route emissions, instead use single route emission

		if($last_milage == ""){
			$last_milage  = 0;
		}		
		if($current_milage == ""){
			$current_milage  = 0;
		}

		$sql = "INSERT INTO user_emissions_travel_car
			(id, id_user, id_car_model, date, last_milage, date_last_milage, current_milage, date_current_milage, car_fuel_type, car_milage, car_emissions) 
			VALUES ('0', $id_user, $id_car_model, '$date', $last_milage, '$date_last_milage', $current_milage, '$date_current_milage', '$car_fuel_type', $car_milage, $car_emissions)";
	
		/* Check if query processed correctly */
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}

?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div class="form-group">
		<!-- Car Form -->
		<div class="input-group">
			all cars
			<input name="all_cars" id="all_cars" value="<?php echo !empty($carbon_data) ? $travel['car'] : '' ?>">
			current car
			<input name="car" id="car" value="<?php echo !empty($carbon_data) ? $travel['car'] : '' ?>">
			<br>
			<span class="input-group-addon"> What is your total year's car mileage? </span>
			<div class="row">
				<div class="small-7 columns">
					<input type="text" name="car_miles" onchange="carbon_car()" id="car_miles" class="form-control"
						   value="<?php echo !empty($carbon_data) ? $travel['car_miles'] : '' ?>">
				</div>
				<div class="small-5 columns">
					<select name="car_unit" onchange="carbon_car()" id="car_unit"
							class="form-control" <?php echo !empty($carbon_data) ? $travel['car_unit'] : '' ?>>
						<option
							value="miles" <?php echo (!empty($carbon_data) && $travel['car_unit'] == 'miles') ? 'selected' : '' ?>>
							miles
						</option>
						<option
							value="km" <?php echo (!empty($carbon_data) && $travel['car_unit'] == 'km') ? 'selected' : '' ?>>
							km
						</option>
					</select>
				</div>
			</div>
		</div>

		<div class="input-group">
			<span class="input-group-addon"> Need Help Calculating Your Car Mileage? </span>
			<div class="button" id="show_calc_car" helper="off"><strong>Click Here</strong></div>
		</div>

		<div class="input-group" id="car_helper" style="display:none">
			<div class="row">
				<div class="small-6 columns">
					<span class="input-group-addon"> Vehicle mileage at last safety/oil check</span>
					<input type="text" min="0" name="car_last" onchange="carbon_car()" id="car_last"
						   value="<?php echo !empty($carbon_data) ? $travel['car_last'] : '' ?>">
				</div>
				<div class="small-6 columns">
					<span class="input-group-addon"> Enter the date of last check</span>
					<input type="text" name="car_last_date" onchange="carbon_car()" id="car_last_date"
						   value="<?php echo !empty($carbon_data) ? $travel['car_last_date'] : '' ?>"
						   class="datepick">
				</div>
			</div>
			<div class="row">
				<div class="small-6 columns">
					<span class="input-group-addon"> Current vehicle mileage </span>
					<input type="text" min="0" name="car_current" onchange="carbon_car()" id="car_current"
						   value="<?php echo !empty($carbon_data) ? $travel['car_current'] : '' ?>">
				</div>
				<div class="small-6 columns">
					<span class="input-group-addon"> Enter the date of current safety/oil check</span>
					<input type="text" name="car_current_date" onchange="carbon_car()" id="car_current_date"
						   value="<?php echo !empty($carbon_data) ? $travel['car_current_date'] : '' ?>"
						   class="datepick">
				</div>
			</div>
		</div>

		<div class="input-group">
			<span class="input-group-addon"> Select the fuel type for your car? </span>
			<select name="car_fuel" onchange="carbon_car()" id="car_fuel" class="form-control">
				<option
					value="gasoline" <?php echo (!empty($carbon_data) && $travel['car_fuel'] == 'gasoline') ? 'selected' : '' ?>>
					gasoline
				</option>
				<option
					value="other" <?php echo (!empty($carbon_data) && $travel['car_fuel'] == 'diesel') ? 'selected' : '' ?>>
					diesel
				</option>
			</select>
		</div>

		<div class="input-group">
			<span class="input-group-addon"> What is your car's fuel efficiency? </span>
			<input type="text" name="car_efficiency" onchange="carbon_car()" id="car_efficiency"
				   class="form-control" value="<?php echo !empty($carbon_data) ? $travel['car_efficiency'] : '' ?>">
			<div class="row">
				<div class="small-4 large-4 columns">
					<select id="car_make" name="car_make" onchange="get_car_models()">
						<option>Select a Make</option>
						<?php
							/* 
								Sy's Edit: These lines have been commented out and replaced below because it uses old db
							*/
							/*
							foreach ($makes as $make) {
								if ($travel['car_make'] == $make->make) {
									$selected = "selected";
								} else {
									$selected = "";
								}
								echo '<option value="' . $make->make . '" ' . $selected . '>' . $make->make . '</option>';
							}*/ 
							$makes = mysqli_query($conn, "SELECT DISTINCT make FROM library_car_models");
							
							while($row = mysqli_fetch_array($makes, MYSQLI_ASSOC)) {
								echo '<option value="' . $row["make"] . '" ' . '>' . $row["make"] . '</option>';
							}
						?>
					</select>
				</div>
				<div class="small-4 large-4 columns">
                make = <br />
                <?php 
				//if (!empty($_POST['car_make'])) { "make = ".$make."<br />"; }
							$make = $_POST['car_make'];
							if (!empty($make)) { echo "make = ".$make."<br />"; }
				?>
					<select id="car_model" name="car_model" onchange="get_car_years()" 
						<?php 
							/* 
								Sy's Edit: These lines have been commented out and replaced because it uses old db
							*/
							//echo (!empty($travel['car_model'])) ? '' : 'disabled';
							echo (!empty($make)) ? '' : 'disabled';
				    	?> >
						<option>Model</option>
						<?php
							/* 
								Sy's Edit: These lines have been commented out and replaced because it uses old db
							*/
							/*
							if (!empty($travel['car_model'])):
								foreach ($models as $model) {
									if ($travel['car_model'] == $model->model) {
										$selected = "selected";
									} else {
										$selected = "";
									}
									echo '<option value="' . $model->model . '"' . $selected . '>' . $model->model . '</option>';
								}
							endif;
							*/

							//$models = mysqli_query($conn, "SELECT DISTINCT model FROM library_car_models");
							echo '<option>1 ' . $selected_make . '</option>';
							echo '<option>2 ' . $selected_make2 . '</option>';
							echo '<option>3 ' . $car_make . '</option>';
							echo '<option>4 ' . $make . '</option>';
							echo '<option>1 ' . $_POST['selected_make'] . '</option>';
							echo '<option>2 ' . $_POST['selected_make2'] . '</option>';
							echo '<option>3 ' . $_POST['car_make'] . '</option>';
							echo '<option>4 ' . $_POST['make'] . '</option>';
							if (!empty($make)) {
								//$where_make = " WHERE make='". $make."' ";
								$where_make = " WHERE make='Yugo' ";
							} else {
								$where_make = "";
							}
							//$models = mysqli_query($conn, "SELECT DISTINCT model FROM library_car_models");
							//$make = $_POST['selected_make'];
							$models = mysqli_query($conn, "SELECT DISTINCT model FROM library_car_models".$where_make);
							//$models = mysqli_query($conn, "SELECT DISTINCT model FROM library_car_models WHERE make='". $make."'");
							//$years = mysqli_query($conn, "SELECT DISTINCT year FROM library_car_models WHERE make='". $make."' AND model='" . $model . "'");
		
							while($row = mysqli_fetch_array($models, MYSQLI_ASSOC)) {
								echo '<option value="' . $row["model"] . '" ' . '>' . $row["model"] . '</option>';
							}
							
						?>
					</select>
				</div>
				<div class="small-4 large-4 columns">
					<select id="car_year" name="car_year" onchange="get_car_efficiency()" 
						<?php 
							/* 
								Sy's Edit: These lines have been commented out and replaced because it uses old db
							*/
							//echo (!empty($travel['car_year'])) ? '' : 'disabled'; 
							$model = $_POST['car_model'];
							echo (!empty($model)) ? '' : 'disabled';
						?> >
						<option>Year</option>
						<?php
							/* 
								Sy's Edit: These lines have been commented out and replaced because it uses old db
							*/
							/*
							if (!empty($travel['car_year'])):
								foreach ($years as $year) {
									if ($travel['car_year'] == $year->year) {
										$selected = "selected";
									} else {
										$selected = "";
									}
									echo '<option value="' . $year->year . '"' . $selected . '>' . $year->year . '</option>';
								}
							endif;
							*/
							$years = mysqli_query($conn, "SELECT DISTINCT year FROM library_car_models");
							//$years = mysqli_query($conn, "SELECT DISTINCT year FROM library_car_models WHERE make='". $make."' AND model='" . $model . "'");
		
							while($row = mysqli_fetch_array($years, MYSQLI_ASSOC)) {
								echo '<option value="' . $row["year"] . '" ' . '>' . $row["year"] . '</option>';
							}
						?>
					</select>
				</div>
				
				<!-- Sy's Edits: Added a button to add a car to the view log -->
				<div class="input-group row">
					<br><br><br>
					<div class="button" id="add_car" onclick="add_car()">Add Car</div>
					<input type="hidden" name="cdist" id="cdist" value="">
					<input type="hidden" name="ccount" id="ccount"
						   value="<?php echo !empty($carbon_data) ? sizeof($travel['carfrom']) : '0' ?>">
				</div>
			</div>
		</div>
		
		<!-- Sy's Edits: Added a view log section to hold the users' multiple car records -->
		<div class="row">
			<div class="routes" id="car_list">
				<?php if (!empty($travel['carfrom'])) {
					$max = sizeof($travel['carfrom']);
					for ($i = 0; $i < $max; $i++) { ?>
						<div id="car<?php echo $i; ?>">
							From <?php echo $travel['carfrom'][$i]; ?> to <?php echo $travel['carto'][$i]; ?>,
							<?php echo $travel['cartrips'][$i]; ?> times per <?php echo $travel['carfreq'][$i]; ?>
							<?php echo ($travel['carsy'][$i] == '1') ? ' on a school year' : ''; ?>,
							<?php echo $travel['carmiles'][$i]; ?> miles.
							<span onclick="remove_car(<?php echo $travel['carmiles'][$i] . ',' . $i; ?>)">[Remove]</span>
							<input type="hidden" name="carfrom[]" value="<?php echo $travel['carfrom'][$i]; ?>">
							<input type="hidden" name="carto[]" value="<?php echo $travel['carto'][$i]; ?>">
							<input type="hidden" name="cartrips[]" value="<?php echo $travel['cartrips'][$i]; ?>">
							<input type="hidden" name="carfreq[]" value="<?php echo $travel['carfreq'][$i]; ?>">
							<input type="hidden" name="carsy[]" value="<?php echo $travel['carsy'][$i]; ?>">
							<input type="hidden" name="carmiles[]" value="<?php echo $travel['carmiles'][$i]; ?>">
						</div>
					<?php }
				} ?>
			</div>
		</div>
		<!-- .Car Form -->
		<input type="submit" id="formSubmit" name="formSubmit" value="Submit" />
	</div>
</form>


<script type="text/javascript">
	function carbon_car(){
		var total_car_carbon = 0.0;
		var miles = isNaN(parseFloat(document.getElementById('car_miles').value)) ? 0.0:parseFloat(document.getElementById('car_miles').value);
		var efficieny = isNaN(parseFloat(document.getElementById('car_efficiency').value)) ? 0.0:parseFloat(document.getElementById('car_efficiency').value);
		var unit = (document.getElementById('car_unit').value); // selectbox
		var fuel = (document.getElementById('car_fuel').value); // selectbox

		var last_check = isNaN(parseFloat(document.getElementById('car_last').value)) ? 0.0:parseFloat(document.getElementById('car_last').value);
		var current_check = isNaN(parseFloat(document.getElementById('car_current').value)) ? 0.0:parseFloat(document.getElementById('car_current').value);
		var last_date =  document.getElementById('car_last_date').value;
		var current_date = document.getElementById('car_current_date').value;

		if (last_check != '' && current_check != '') {
			var msecs = Date.parse(current_date) - Date.parse(last_date);
			num_days = msecs / 86400000;
			console.log(num_days);

			if (!isNaN(num_days)){
				miles = [(current_check - last_check) / num_days] * 365;
				jQuery('#car_miles').val(miles.toFixed(2));
			}
		}

		if (parseFloat(miles) >= 0.0 && parseFloat(efficieny) >= 0.0){
			if (parseFloat(efficieny) == 0.0 ){
				total_car_carbon = 0.00;
			}
			else {
				switch(fuel){
					case 'gasoline':
						total_car_carbon = (miles/efficieny) * (2307 + 8874);
						total_car_carbon += miles * 56;
						total_car_carbon = total_car_carbon * .000001;
						break;
					default:
						total_car_carbon = (miles/efficieny) * (2335 + 10153);
						total_car_carbon += miles * 56;
						total_car_carbon = total_car_carbon * .000001;
						break;
				}

				if (unit != 'miles') {
					total_car_carbon = total_car_carbon * 0.621371;
				}
			}

			jQuery('#car').val(total_car_carbon.toFixed(2));
			/* 
				Sy's Edit: This line/function has been commented out because it is used to update the box that shows
				how much co2 the user produces from their travel
			*/
			//total_carbon_transport();
		}
		else {
			jQuery('#car').val(0.00);
			/* 
				Sy's Edit: This line/function has been commented out because it is used to update the box that shows
				how much co2 the user produces from their travel
			*/
			//total_carbon_transport();
		}
	}
		
	/* 
		Sy's Edit: This function allows capability for a user to add multiple cars
	*/
	function add_car(){
		// Add up current car carbon data to all_car_carbon
		var total_car_carbon = isNaN(parseFloat(document.getElementById('car').value))? 0.0:parseFloat(document.getElementById('car').value);
		var all_car_carbon = isNaN(parseFloat(document.getElementById('all_cars').value))? 0.0:parseFloat(document.getElementById('all_cars').value);
		all_car_carbon = all_car_carbon + total_car_carbon;
		jQuery('#all_cars').val(all_car_carbon.toFixed(2));
		
		// Add data to view log and add input fields
		var miles = isNaN(parseFloat(document.getElementById('car_miles').value)) ? 0.0:parseFloat(document.getElementById('car_miles').value);
		var efficieny = isNaN(parseFloat(document.getElementById('car_efficiency').value)) ? 0.0:parseFloat(document.getElementById('car_efficiency').value);
		var unit = (document.getElementById('car_unit').value); // selectbox
		var fuel = (document.getElementById('car_fuel').value); // selectbox
		var make = document.getElementById('car_make').value;
		var model = document.getElementById('car_model').value;
		var year = document.getElementById('car_year').value;
		var count = isNaN(parseInt(document.getElementById('ccount').value))? 0: parseInt(document.getElementById('ccount').value);
		
		if(make == "Select a Make" || make == "" || model == "Model" || model == "" || year == "Year" || year == ""){
			var segment_display =  "Car, ";
		}
		else{
			var segment_display =  year + " " + make + " " + model + ", ";
		}

		segment_display += efficieny.toFixed(2) + "mpg: ";
		segment_display += miles.toFixed(2) + " " + unit + ", " + total_car_carbon + " tons of CO2" + " " + 
			'<span onclick="remove_car(' + total_car_carbon.toFixed(2) + ', ' + count + ')">[Remove]</span>';

		var segment_inputs = '<input type="hidden" name="carmake[]" value="' + make + '">';
		segment_inputs += '<input type="hidden" name="carmodel[]" value="' + model + '">';
		segment_inputs += '<input type="hidden" name="caryear[]" value="' + year + '">';
		segment_inputs += '<input type="hidden" name="carefficiency[]" value="' + efficieny.toFixed(2) + '">';
		segment_inputs += '<input type="hidden" name="carmiles[]" value="' + miles.toFixed(2) + '">';
		segment_inputs += '<input type="hidden" name="carunit[]" value="' + unit + '">';

		var segment = '<div id="car' + count + '"><hr/>' + segment_display + segment_inputs + '</div>';
		
		count += 1;

		// empty search fields;
		/*
		jQuery("#bfrom").val();
		jQuery("#bto").val();
		jQuery("#btrips").val();
		jQuery("#btripf").val('week');
		*/

		jQuery('#ccount').val(count);
		jQuery("#car_list").append(segment);

	}
	
	/* 
		Sy's Edit: Added function to remove a car from the car list
	*/
	function remove_car(carbon, id){

		var all_car_carbon = isNaN(parseFloat(document.getElementById('all_cars').value))?0.0:parseFloat(document.getElementById('all_cars').value);
		all_car_carbon = all_car_carbon - parseFloat(carbon);
		jQuery('#all_cars').val(all_car_carbon.toFixed(2));

		jQuery('#car'+id).remove();
	}
	
	jQuery("#show_calc_car").on("click", function(){
		if (jQuery(this).attr("helper") == "off"){
			jQuery(this).attr("helper", "on");
			jQuery("#car_helper").show();
		}
		else {
			jQuery(this).attr("helper", "off");
			jQuery("#car_helper").hide();
		}
	});

	function get_car_models(){
		var selected_make = document.getElementById('car_make').value;

		if(selected_make == "Select a Make"){
			document.getElementById("car_model").disabled = true;
			document.getElementById("car_year").disabled = true;
		}
		else{
			document.getElementById("car_model").disabled = false;
			document.getElementById("car_model").style.background = "red";
		}

		//simplified ajax, still doesn't work
		//$.post('emissions-car.php', {selected_make: selected_make});
		
		
		//fix this, might not be working because folder html is locked?
		
		jQuery.ajax({
		    //url: "/var/www/html/carbon-functions/Travel/emissions-car.php",
		    url: "emissions-car-2.php",
		    type: 'POST',
		    data: { make : selected_make },
		    dataType: "text",
		    success: function (data) {
		         alert("success! - " + selected_make + " - ");
		    },
		    error: function (e) {
		        alert("Server Error : " + e.state());
		    }
		});
		
	}

	function get_car_years(){
		var selected_model = document.getElementById('car_model').value;

		if(selected_model == "Model"){
			document.getElementById("car_year").disabled = true;
		}
		else{
			document.getElementById("car_year").disabled = false;
		}
	}
	
	


</script>
